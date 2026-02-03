<?php

use Illuminate\Support\Facades\Storage;
use App\Models\Flashcard;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$flashcards = Flashcard::whereNotNull('document_path')->latest()->take(20)->get();

echo "Checking " . count($flashcards) . " flashcards...\n";
echo str_repeat("-", 80) . "\n";
echo sprintf("%-5s | %-60s | %-10s\n", "ID", "Path", "Exists?");
echo str_repeat("-", 80) . "\n";

foreach ($flashcards as $f) {
    $exists = Storage::disk('public')->exists($f->document_path) ? "✅ YES" : "❌ NO";
    echo sprintf("%-5d | %-60s | %-10s\n", $f->id, $f->document_path, $exists);
    if (!Storage::disk('public')->exists($f->document_path)) {
        echo "   -> Full Path checked: " . Storage::disk('public')->path($f->document_path) . "\n";
    }
}

echo str_repeat("-", 80) . "\n";
echo "Disk 'public' root: " . Storage::disk('public')->path('') . "\n";
