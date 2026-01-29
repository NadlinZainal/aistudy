<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FlashcardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Flashcard::create([
            'user_id' => 1, 
            'title' => 'Sample Flashcard Set',
            'description' => 'A test flashcard set for seeding',
            'document_path' => 'flashcard_documents/sample.pdf',
            'source_type' => 'pdf',
            'status' => 'completed',
            'openai_response' => null,
            'cards' => json_encode([
                ['question' => 'What is AI?', 'answer' => 'Artificial Intelligence'],
                ['question' => 'What is PHP?', 'answer' => 'A server-side scripting language'],
            ]),
        ]);
    }
}