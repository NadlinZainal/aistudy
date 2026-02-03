<?php
// debug_script.php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Flashcard;
use Illuminate\Support\Facades\Storage;

$output = "DEBUG DATA\n";
$output .= "==========\n\n";
$output .= "Disk Root: " . Storage::disk('public')->path('') . "\n\n";

$flashcards = Flashcard::whereNotNull('document_path')->latest()->take(10)->get();
foreach ($flashcards as $f) {
    $exists = Storage::disk('public')->exists($f->document_path) ? "YES" : "NO";
    $output .= sprintf("ID: %d | Path: %s | Exists: %s\n", $f->id, $f->document_path, $exists);
    $output .= "   Full Path: " . Storage::disk('public')->path($f->document_path) . "\n\n";
}

$output .= "\nFiles in flashcard_documents:\n";
$files = Storage::disk('public')->files('flashcard_documents');
foreach ($files as $file) {
    $output .= " - " . $file . "\n";
}

file_put_contents('debug_output.txt', $output);
echo "Debug data written to debug_output.txt\n";
