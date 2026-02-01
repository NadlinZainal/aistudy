<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Flashcard;
use Illuminate\Support\Facades\Storage;
use App\Services\FlashcardGeneratorService;

use App\Models\StudyProgress;
use App\Models\QuizResult;

class FlashcardController extends Controller
{
    // List all flashcard sets for the authenticated user
    public function index(Request $request)
    {
        $query = Flashcard::where('user_id', auth()->id());

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $flashcards = $query->with(['studyProgress' => function($q) {
            $q->where('user_id', auth()->id());
        }])->get();

        // Get friends list safely
        $users = auth()->user()->friends(); 
        if ($users instanceof \Illuminate\Database\Eloquent\Builder) {
            $users = $users->get();
        }

        return view('Flashcard.index', compact('flashcards', 'users'));
    }

    public function create()
    {
        return view('Flashcard.create');
    }

    // Store a new flashcard set with document upload or URL import
    public function store(Request $request)
    {
        // Increase limits for batch processing
        ini_set('max_execution_time', 600);
        ini_set('memory_limit', '512M');

        $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'document' => 'required_without:url',
            'document.*' => 'file|mimes:pdf,png,jpg,jpeg,txt|max:10240', // Max 10MB per file
            'url' => 'required_without:document|nullable|url',
        ]);

        $generator = new FlashcardGeneratorService();
        $files = $request->file('document');

        // Handle URL import (remains single for now as per structure)
        if ($request->url && !$files) {
            $title = $request->title ?: 'Web Import - ' . date('Y-m-d H:i');
            return $this->processUrlImport($request, $generator, $title);
        }

        // Handle File uploads (single or multiple)
        if ($files) {
            $uploadedFiles = is_array($files) ? $files : [$files];
            $combinedText = "";
            $filePaths = [];
            
            // First pass: Save files and extract text
            foreach ($uploadedFiles as $file) {
                $path = $file->store('flashcard_documents', 'public');
                $filePaths[] = $path;
                
                $fullPath = storage_path('app/public/' . $path);
                try {
                    $text = $generator->extractTextFromFile($fullPath);
                    $combinedText .= "\n\n--- Source: " . $file->getClientOriginalName() . " ---\n\n" . $text;
                } catch (\Exception $e) {
                    \Log::error("Failed to extract text from " . $file->getClientOriginalName() . ": " . $e->getMessage());
                }
            }

            if (empty(trim($combinedText))) {
                return back()->with('error', 'Could not extract any content from the uploaded files.');
            }

            // Create ONE flashcard entry for all combined files
            $title = $request->title ?: pathinfo($uploadedFiles[0]->getClientOriginalName(), PATHINFO_FILENAME);
            if (count($uploadedFiles) > 1 && !$request->title) {
                $title .= " (Combined)";
            }

            $flashcard = Flashcard::create([
                'user_id' => $request->user()->id,
                'title' => $title,
                'description' => $request->description ?: "Created from " . count($uploadedFiles) . " documents.",
                'document_path' => $filePaths[0], // Reference the first file as primary
                'source_type' => count($uploadedFiles) > 1 ? 'multi' : $uploadedFiles[0]->getClientOriginalExtension(),
                'status' => 'processing',
            ]);

            try {
                // Generate from the combined text
                $cards = $generator->generateFromText($combinedText, $flashcard);
                $flashcard->cards = $cards;
                $flashcard->status = $cards ? 'completed' : 'failed';
                $flashcard->save();
                
                return redirect()->route('flashcard.show', $flashcard->id)->with('success', 'Flashcard created successfully.');
            } catch (\Throwable $e) {
                $flashcard->status = 'failed';
                $flashcard->save();
                \Log::error('Flashcard generation failed: ' . $e->getMessage());
                return redirect()->route('flashcard.index')->with('error', 'Connected successfully but failed to generate cards.');
            }
        }

        return redirect()->route('flashcard.index')->with('error', 'No valid input provided.');
    }

    protected function processUrlImport(Request $request, FlashcardGeneratorService $generator, $title = null)
    {
        $text = $generator->extractTextFromUrl($request->url);
        if (!$text) {
            return back()->withInput()->with('error', 'Failed to extract content from the provided URL.');
        }

        $fileName = 'url_' . time() . '.txt';
        $path = 'flashcard_documents/' . $fileName;
        \Illuminate\Support\Facades\Storage::disk('public')->put($path, $text);

        $flashcard = Flashcard::create([
            'user_id' => $request->user()->id,
            'title' => $title ?: $request->title,
            'description' => $request->description,
            'document_path' => $path,
            'source_type' => 'url',
            'status' => 'processing',
        ]);

        try {
            $cards = $generator->generateFromDocument($flashcard);
            $flashcard->cards = $cards;
            $flashcard->status = $cards ? 'completed' : 'failed';
            $flashcard->save();
            return redirect()->route('flashcard.show', $flashcard->id)->with('success', 'Flashcard created successfully.');
        } catch (\Throwable $e) {
            $flashcard->status = 'failed';
            $flashcard->save();
            return redirect()->route('flashcard.index')->with('error', 'Failed to generate flashcards from URL.');
        }
    }

    // Show a single flashcard set
    public function show($id)
    {
        $flashcard = Flashcard::findOrFail($id);
        return view('Flashcard.show', compact('flashcard'));
    }

    // Show the form to edit a flashcard
    public function edit($id)
    {
        $flashcard = Flashcard::findOrFail($id);
        return view('Flashcard.edit', compact('flashcard'));
    }

    // Update a flashcard set (title/description/document)
    public function update(Request $request, $id)
    {
        $flashcard = Flashcard::findOrFail($id);
        
        $data = $request->only(['title', 'description']);
        
        if ($request->hasFile('document')) {
            // Delete old document if it exists
            if ($flashcard->document_path && \Storage::disk('public')->exists($flashcard->document_path)) {
                \Storage::disk('public')->delete($flashcard->document_path);
            }
            
            $path = $request->file('document')->store('flashcards', 'public');
            $data['document_path'] = $path;
        }
        
        $flashcard->update($data);
        
        return redirect()->route('flashcard.show', $flashcard->id)->with('success', 'Deck updated successfully.');
    }

    // Delete a flashcard set
    public function destroy($id)
    {
        $flashcard = Flashcard::findOrFail($id);

        // Optionally delete the document file
        if ($flashcard->document_path) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($flashcard->document_path);
        }
        $flashcard->delete();

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Flashcard deck deleted successfully.']);
        }

        return redirect()->route('flashcard.index')->with('success', 'Flashcard deleted successfully.');
    }

    // Study mode for a flashcard set
    public function study($id)
    {
        $flashcard = Flashcard::findOrFail($id);
        
        // Ensure flashcards are generated
        if ($flashcard->status !== 'completed' || empty($flashcard->cards)) {
            return redirect()->route('flashcard.index')->with('error', 'This deck is not ready for studying yet.');
        }

        return view('Flashcard.study', compact('flashcard'));
    }

    public function updateProgress(Request $request)
    {
        $request->validate([
            'flashcard_id' => 'required|exists:flashcard,id',
            'current_index' => 'required|integer',
            'total_cards' => 'required|integer',
            'seconds_spent' => 'nullable|integer',
        ]);

        $user = $request->user();
        if (!$user) return response()->json(['error' => 'Unauthorized'], 401);

        $progress = StudyProgress::firstOrNew([
            'user_id' => $user->id,
            'flashcard_id' => $request->flashcard_id,
        ]);

        $progress->total_cards = $request->total_cards;
        $progress->studied_cards = $request->current_index + 1;
        $progress->is_completed = ($request->current_index + 1) >= $request->total_cards;
        
        // Increment seconds_spent
        $progress->seconds_spent = ($progress->seconds_spent ?? 0) + ($request->seconds_spent ?? 0);
        
        $progress->save();

        return response()->json(['success' => true, 'progress' => $progress]);
    }

    // AI Chat Handler
    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
            'context' => 'nullable|string',
        ]);

        $service = new FlashcardGeneratorService();
        $response = $service->chatWithAi($request->message, $request->context);

        return response()->json(['reply' => $response]);
    }

    // Generate/Retrieve Executive Summary
    public function summarize($id)
    {
        $flashcard = Flashcard::findOrFail($id);

        if ($flashcard->summary) {
            return response()->json(['summary' => $flashcard->summary]);
        }

        try {
            $generator = new FlashcardGeneratorService();
            
            // Extract text again from the document
            $filePath = storage_path('app/public/' . $flashcard->document_path);
            $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
            
            // Re-using extraction logic (ideally this should be refactored, but kept simple for now)
            $text = '';
            if ($extension === 'pdf') {
                $parser = new \Smalot\PdfParser\Parser();
                $pdf = $parser->parseFile($filePath);
                $text = $pdf->getText();
            } elseif ($extension === 'txt') {
                $text = file_get_contents($filePath);
            }

            if (empty(trim($text))) {
                // Try OCR fallback
                $text = $generator->chatWithAi("Can you read this document? (Wait, I need better text)", "No text extracted");
                // Actually, the generator already has OCR logic in generateFromDocument but it's internal.
                // Let's just call a specific method if we had one, or re-run the whole thing.
                // For now, let's assume the standard extraction works or show error.
                return response()->json(['error' => 'Could not extract text for summary.'], 422);
            }

            $summary = $generator->generateSummary($text, env('OPENAI_API_KEY'));
            $flashcard->summary = $summary;
            $flashcard->save();

            return response()->json(['summary' => $summary]);
        } catch (\Throwable $e) {
            \Log::error('Summary generation failed: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to generate summary.'], 500);
        }
    }

    // Export Flashcards as JSON
    public function export($id)
    {
        $flashcard = Flashcard::findOrFail($id);
        
        $data = [
            'title' => $flashcard->title,
            'description' => $flashcard->description,
            'cards' => $flashcard->cards,
            'summary' => $flashcard->summary,
            'exported_at' => now()->toDateTimeString(),
        ];

        $fileName = \Illuminate\Support\Str::slug($flashcard->title) . '_export.json';

        return response()->streamDownload(function () use ($data) {
            echo json_encode($data, JSON_PRETTY_PRINT);
        }, $fileName, ['Content-Type' => 'application/json']);
    }
    // Export Flashcards as PDF
    public function exportPdf($id)
    {
        $flashcard = Flashcard::findOrFail($id);
        
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('Flashcard.pdf', compact('flashcard'));
        
        $fileName = \Illuminate\Support\Str::slug($flashcard->title) . '.pdf';
        
        return $pdf->download($fileName);
    }

    // List only favorited decks
    public function favorites(Request $request)
    {
        $query = Flashcard::where('user_id', auth()->id())
            ->where('is_favorite', true);

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $flashcards = $query->with(['studyProgress' => function($q) {
                $q->where('user_id', auth()->id());
            }])->get();
            
        return view('Flashcard.index', [
            'flashcards' => $flashcards,
            'is_favorites_page' => true
        ]);
    }

    // Toggle Favorite Status
    public function toggleFavorite($id)
    {
        $flashcard = Flashcard::where('user_id', auth()->id())->findOrFail($id);
        $flashcard->is_favorite = !$flashcard->is_favorite;
        $flashcard->save();

        return response()->json([
            'success' => true,
            'is_favorite' => $flashcard->is_favorite
        ]);
    }

    // Show Quiz for a flashcard set
    public function quiz($id)
    {
        $flashcard = Flashcard::where('user_id', auth()->id())->findOrFail($id);
        
        if ($flashcard->status !== 'completed' || empty($flashcard->cards)) {
            return redirect()->route('flashcard.index')->with('error', 'This deck is not ready for a quiz yet.');
        }

        try {
            $service = new FlashcardGeneratorService();
            $quiz = $service->generateQuiz($flashcard);
            
            return view('Flashcard.quiz', compact('flashcard', 'quiz'));
        } catch (\Throwable $e) {
            \Log::error('Quiz generation failed: ' . $e->getMessage());
            return redirect()->route('flashcard.index')->with('error', 'Failed to generate quiz. Please try again later.');
        }
    }

    // Save Quiz Result
    public function saveQuizResult(Request $request)
    {
        $request->validate([
            'flashcard_id' => 'required|exists:flashcard,id',
            'score' => 'required|integer',
            'correct_count' => 'required|integer',
            'total_questions' => 'required|integer',
        ]);

        $result = QuizResult::create([
            'user_id' => auth()->id(),
            'flashcard_id' => $request->flashcard_id,
            'score' => $request->score,
            'correct_count' => $request->correct_count,
            'total_questions' => $request->total_questions,
        ]);

        return response()->json(['success' => true, 'result' => $result]);
    }

    // Show Quiz History
    public function quizHistory()
    {
        $results = QuizResult::where('user_id', auth()->id())
            ->with('flashcard')
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('Flashcard.history', compact('results'));
    }

    /**
     * Update an individual card in a deck.
     */
    public function updateCard(Request $request, $id, $index)
    {
        $flashcard = Flashcard::where('user_id', auth()->id())->findOrFail($id);
        
        $request->validate([
            'question' => 'required|string',
            'answer' => 'required|string',
        ]);

        $cards = $flashcard->cards;
        if (!isset($cards[$index])) {
            return response()->json(['error' => 'Card not found'], 404);
        }

        $cards[$index]['question'] = $request->question;
        $cards[$index]['answer'] = $request->answer;
        
        $flashcard->cards = $cards;
        $flashcard->save();

        return response()->json(['success' => true]);
    }

    /**
     * Delete an individual card from a deck.
     */
    public function destroyCard($id, $index)
    {
        $flashcard = Flashcard::where('user_id', auth()->id())->findOrFail($id);
        
        $cards = $flashcard->cards;
        if (!isset($cards[$index])) {
            return response()->json(['error' => 'Card not found'], 404);
        }

        array_splice($cards, $index, 1);
        
        $flashcard->cards = $cards;
        $flashcard->save();

        return response()->json(['success' => true]);
    }

    /**
     * Clone a flashcard deck for the current user.
     */
    public function clone($id)
    {
        $original = Flashcard::findOrFail($id);
        
        // Don't clone if the user already owns it (or maybe allow it? Let's allow it but check)
        
        $clone = $original->replicate();
        $clone->user_id = auth()->id();
        $clone->is_favorite = false;
        $clone->save();
        
        if (request()->ajax()) {
            return response()->json([
                'success' => true, 
                'message' => 'Flashcard deck added to your library!',
                'redirect' => route('flashcard.index')
            ]);
        }
        
        return redirect()->route('flashcard.index')->with('success', 'Flashcard deck added to your library!');
    }
}
