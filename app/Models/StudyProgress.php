<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudyProgress extends Model
{
    use HasFactory;

    protected $table = 'study_progress';

    protected $fillable = [
        'user_id',
        'flashcard_id',
        'total_cards',
        'studied_cards',
        'is_completed',
        'seconds_spent',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function flashcard()
    {
        return $this->belongsTo(Flashcard::class);
    }
}
