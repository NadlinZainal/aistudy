<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Flashcard;
use App\Models\StudyProgress;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $userId = auth()->id();
        
        $totalFlashcards = Flashcard::where('user_id', $userId)->count();
        
        // Calculate average study time in minutes for decks that have been studied
        $totalSeconds = StudyProgress::where('user_id', $userId)->sum('seconds_spent') ?? 0;
        $studiedDecksCount = StudyProgress::where('user_id', $userId)->where('seconds_spent', '>', 0)->count();
        
        $avgSeconds = $studiedDecksCount > 0 ? ($totalSeconds / $studiedDecksCount) : 0;
        $averageStudyTime = round($avgSeconds / 60, 1); // convert to minutes

        // AI Study Plan: Get 3 decks that need attention (lowest progress or never studied)
        $studyPlan = Flashcard::where('user_id', $userId)
            ->where('status', 'completed')
            ->with(['studyProgress' => function($q) use ($userId) {
                $q->where('user_id', $userId);
            }])
            ->get()
            ->sortBy(function($deck) {
                $progress = $deck->studyProgress->first();
                if (!$progress) return 0; // High priority (never studied)
                return ($progress->studied_cards / max($progress->total_cards, 1));
            })
            ->take(3);
        
        return view('home', compact('totalFlashcards', 'averageStudyTime', 'studyPlan'));
    }
}
