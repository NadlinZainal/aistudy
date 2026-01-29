<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Flashcard extends Model
{
    use HasFactory;

    protected $table = 'flashcard';

    protected $fillable = [
        'user_id',           // Owner of the flashcard set
        'document_path',     // Path to uploaded document
        'source_type',       // Type of source (e.g., pdf, docx, txt)
        'status',            // Status of processing (pending, processing, completed, failed)
        'openai_response',   // Raw OpenAI response (optional, for debugging)
        'title',             // Title of the flashcard set
        'description',       // Description or summary
        'summary',           // AI-generated 1-page executive summary
        'cards',             // JSON: array of generated flashcards (question/answer pairs)
        'is_favorite',       // Indicates if the flashcard set is marked as a favorite
    ];

    protected $casts = [
        'cards' => 'array',
    ];

    public function studyProgress()
    {
        return $this->hasMany(StudyProgress::class);
    }
}
