<?php

namespace App\Services;

use OpenAI;
use Illuminate\Support\Facades\Storage;
use Smalot\PdfParser\Parser as PdfParser;
use Illuminate\Support\Facades\Log;

class FlashcardGeneratorService
{
    public function extractTextFromFile($filePath)
    {
        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        $text = '';

        if ($extension === 'pdf') {
            $text = $this->extractTextFromPdf($filePath);
        } elseif ($extension === 'txt') {
            $text = file_get_contents($filePath);
        } elseif (in_array($extension, ['jpg', 'jpeg', 'png'])) {
            $text = $this->extractTextViaOcr($filePath);
        }

        if (empty(trim($text)) && in_array($extension, ['pdf', 'jpg', 'jpeg', 'png'])) {
            $text = $this->extractTextViaOcr($filePath);
        }

        return $text;
    }

    public function generateFromText($text, $flashcard)
    {
        try {
            $apiKey = config('services.openai.key');
            if (!$apiKey) {
                throw new \Exception('OPENAI_API_KEY is not set in environment variables (Config Check).');
            }

            // Limit text length to prevent token limits
            $text = substr($text, 0, 25000); // Increased for multi-doc

            // Generate flashcards using OpenAI
            $cards = $this->generateFlashcardsWithOpenAI($text, $apiKey);
            return $cards;

        } catch (\Throwable $e) {
            Log::error('Error in generateFromText: ' . $e->getMessage());
            throw $e;
        }
    }

    public function generateFromDocument($flashcard)
    {
        try {
            $apiKey = config('services.openai.key');
            if (!$apiKey) {
                throw new \Exception('OPENAI_API_KEY is not set in environment variables (Config Check).');
            }

            $filePath = storage_path('app/public/' . $flashcard->document_path);
            
            if (!file_exists($filePath)) {
                throw new \Exception("File not found at path: {$filePath}");
            }

            $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
            $text = '';

            // Extract text based on file type
            if ($extension === 'pdf') {
                $text = $this->extractTextFromPdf($filePath);
            } elseif ($extension === 'txt') {
                $text = file_get_contents($filePath);
            } elseif (in_array($extension, ['jpg', 'jpeg', 'png'])) {
                Log::info("Image detected, using OCR for: {$filePath}");
                $text = $this->extractTextViaOcr($filePath);
            } else {
                Log::warning("Unsupported file extension: {$extension}");
                return [];
            }

            if (empty(trim($text))) {
                Log::info("Text extraction empty, attempting OCR for file: {$filePath}");
                $text = $this->extractTextViaOcr($filePath);
            }

            if (empty(trim($text))) {
                Log::warning("OCR extraction also empty or failed for file: {$filePath}");
                return [];
            }

            // Limit text length to prevent token limits
            $text = substr($text, 0, 15000); 

            // Generate flashcards using OpenAI
            $cards = $this->generateFlashcardsWithOpenAI($text, $apiKey);
            return $cards;

        } catch (\Throwable $e) {
            Log::error('Error in FlashcardGeneratorService: ' . $e->getMessage(), [
                'exception' => $e,
                'flashcard_id' => $flashcard->id
            ]);
            // Re-throw so the controller catches it and marks status as failed
            throw $e;
        }
    }

    protected function extractTextFromPdf($filePath)
    {
        if (!class_exists(PdfParser::class)) {
            throw new \Exception('Smalot\PdfParser is not installed.');
        }
        $parser = new PdfParser();
        $pdf = $parser->parseFile($filePath);
        return $pdf->getText();
    }

    protected function extractTextViaOcr($filePath)
    {
        try {
            $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
            $client = new \GuzzleHttp\Client(['verify' => false]);
            $response = $client->post('https://api.ocr.space/parse/image', [
                'headers' => [
                    'apikey' => 'K87561817388957', // Free API Key for OCR.space
                ],
                'multipart' => [
                    [
                        'name'     => 'file',
                        'contents' => fopen($filePath, 'r'),
                    ],
                    [
                        'name'     => 'language',
                        'contents' => 'eng',
                    ],
                    [
                        'name'     => 'isOverlayRequired',
                        'contents' => 'false',
                    ],
                    [
                        'name'     => 'filetype',
                        'contents' => strtoupper($extension),
                    ],
                    [
                        'name'     => 'isTable',
                        'contents' => 'true',
                    ],
                ]
            ]);

            $result = json_decode($response->getBody()->getContents(), true);

            if (isset($result['ParsedResults'][0]['ParsedText'])) {
                return $result['ParsedResults'][0]['ParsedText'];
            }

            Log::error("OCR Gateway Error: " . ($result['ErrorMessage'][0] ?? 'Unknown error'));
            return '';
        } catch (\Exception $e) {
            Log::error("OCR Request Failed: " . $e->getMessage());
            return '';
        }
    }

    protected function generateFlashcardsWithOpenAI($text, $apiKey)
    {
        // Bypass SSL certificate verification for local development (fixes cURL error 77)
        $client = OpenAI::factory()
            ->withApiKey($apiKey)
            ->withHttpClient(new \GuzzleHttp\Client(['verify' => false]))
            ->make();
        
        $systemPrompt = "You are a helpful study assistant. Your task is to generate flashcards from the provided text. Return ONLY a valid JSON array of objects, where each object has strictly two keys: 'question' and 'answer'. Do not wrap the JSON in markdown code blocks. Do not add any explanation.";
        
        $response = $client->chat()->create([
            'model' => 'gpt-4o-mini',
            'messages' => [
                ['role' => 'system', 'content' => $systemPrompt],
                ['role' => 'user', 'content' => "Generate flashcards from this text:\n\n" . $text],
            ],
            'temperature' => 0.5,
            'max_tokens' => 2000,
        ]);

        $content = $response->choices[0]->message->content ?? '';
        
        // Log raw response for debugging
        Log::info("OpenAI Raw Response: " . $content);

        // Strip markdown code blocks if present (e.g. ```json ... ```)
        $cleanContent = preg_replace('/^```json\s*|\s*```$/', '', trim($content));
        $cleanContent = preg_replace('/^```\s*|\s*```$/', '', $cleanContent);

        $cards = json_decode($cleanContent, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            Log::error("JSON Decode Error: " . json_last_error_msg());
            throw new \Exception("Failed to decode OpenAI response as JSON.");
        }

        if (!is_array($cards)) {
            throw new \Exception("OpenAI response is not an array.");
        }

        return $cards;
    }

    public function chatWithAi($message, $context = '')
    {
        try {
            $apiKey = config('services.openai.key');
            if (!$apiKey) {
                throw new \Exception('OPENAI_API_KEY is not set (Config).');
            }

            // Bypass SSL certificate verification for local development
            $client = OpenAI::factory()
                ->withApiKey($apiKey)
                ->withHttpClient(new \GuzzleHttp\Client(['verify' => false]))
                ->make();

            $systemPrompt = "You are a friendly and helpful tutor assisting a student with their flashcards. " . 
                            "Answer their questions concisely and clearly. " . 
                            "If they ask about a specific flashcard, use the context provided to help them understand.";

            $messages = [
                ['role' => 'system', 'content' => $systemPrompt],
            ];

            if ($context) {
                $messages[] = ['role' => 'system', 'content' => "Current Flashcard Context: " . $context];
            }

            $messages[] = ['role' => 'user', 'content' => $message];

            $response = $client->chat()->create([
                'model' => 'gpt-4o-mini',
                'messages' => $messages,
                'max_tokens' => 500,
            ]);

            return $response->choices[0]->message->content ?? 'Sorry, I could not generate a response.';
        } catch (\Throwable $e) {
            Log::error("AI Chat Error: " . $e->getMessage());
            return "Error: Unable to connect to AI tutor. Please check logs.";
        }
    }

    public function generateSummary($text, $apiKey)
    {
        // Bypass SSL certificate verification for local development
        $client = OpenAI::factory()
            ->withApiKey($apiKey)
            ->withHttpClient(new \GuzzleHttp\Client(['verify' => false]))
            ->make();

        $systemPrompt = "You are an expert academic assistant. Your task is to generate a comprehensive, 1-page executive summary (or 'Cheat Sheet') of the provided text. Focus on key concepts, definitions, and main takeaways. Use clean Markdown formatting with clear headings and bullet points. Keep it professional and easy to read.";

        $response = $client->chat()->create([
            'model' => 'gpt-4o-mini',
            'messages' => [
                ['role' => 'system', 'content' => $systemPrompt],
                ['role' => 'user', 'content' => "Generate a 1-page summary/cheat-sheet for this text:\n\n" . $text],
            ],
            'temperature' => 0.5,
            'max_tokens' => 2000,
        ]);

        return $response->choices[0]->message->content ?? 'Failed to generate summary.';
    }

    /**
     * Extract text from a URL.
     */
    public function extractTextFromUrl($url)
    {
        try {
            $client = new \GuzzleHttp\Client([
                'verify' => false, // Consistent with other OpenAI requests
                'timeout' => 30,
            ]);

            $response = $client->get($url);
            $html = (string) $response->getBody();

            // Basic cleanup: remove script and style elements
            $html = preg_replace('/<(script|style)\b[^>]*>(.*?)<\/\1>/is', '', $html);
            $text = strip_tags($html);
            $text = html_entity_decode($text);
            
            // Remove excessive whitespace
            $text = preg_replace('/\s+/', ' ', $text);
            $text = trim($text);

            return $text;
        } catch (\Exception $e) {
            Log::error("URL Extraction Error: " . $e->getMessage());
            return null;
        }
    }
    /**
     * Generate MCQs from existing flashcards.
     */
    public function generateQuiz($flashcard)
    {
        try {
            $apiKey = config('services.openai.key');
            if (!$apiKey) {
                throw new \Exception('OPENAI_API_KEY is not set (Config).');
            }

            $client = OpenAI::factory()
                ->withApiKey($apiKey)
                ->withHttpClient(new \GuzzleHttp\Client(['verify' => false]))
                ->make();

            $cardsJson = json_encode($flashcard->cards);
            
            $systemPrompt = "You are a quiz master. Your task is to transform flashcards into a Multiple Choice Question (MCQ) quiz. " .
                            "For each flashcard, keep the original question and the correct answer. " .
                            "Generate 3 highly plausible but incorrect 'distractors' for each question. " .
                            "Return ONLY a valid JSON array of objects. Each object must have: 'question', 'correct_answer', and 'options' (an array containing the correct answer and the 3 distractors). " .
                            "IMPORTANT: DO NOT always put the correct answer at the same position. Randomize the order of options in your response. " .
                            "Do not add markdown formatting or explanations.";

            $response = $client->chat()->create([
                'model' => 'gpt-4o-mini',
                'messages' => [
                    ['role' => 'system', 'content' => $systemPrompt],
                    ['role' => 'user', 'content' => "Generate an MCQ quiz from these flashcards:\n\n" . $cardsJson],
                ],
                'temperature' => 0.8,
            ]);

            $content = $response->choices[0]->message->content ?? '';
            $cleanContent = preg_replace('/^```json\s*|\s*```$/', '', trim($content));
            $quiz = json_decode($cleanContent, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception("Failed to decode Quiz JSON.");
            }

            // Explicitly shuffle options for each question to guarantee randomization even if AI fails to randomize
            if (is_array($quiz)) {
                foreach ($quiz as &$question) {
                    if (isset($question['options']) && is_array($question['options'])) {
                        $options = array_values($question['options']);
                        shuffle($options);
                        $question['options'] = $options;
                    }
                }
            }

            return $quiz;
        } catch (\Throwable $e) {
            Log::error("Quiz Generation Failed: " . $e->getMessage());
            throw $e;
        }
    }
}
