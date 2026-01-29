<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Checking migration status...\n";

// 1. Check if column exists
$columns = Schema::getColumnListing('messages');
if (in_array('flashcard_id', $columns)) {
    echo "Column 'flashcard_id' ALREADY EXISTS in 'messages' table.\n";
} else {
    echo "Column 'flashcard_id' MISSING in 'messages' table.\n";
    
    // 2. Check if migration entry exists
    $migrationName = '2026_01_24_130000_add_flashcard_id_to_messages_table';
    $exists = DB::table('migrations')->where('migration', $migrationName)->exists();
    
    if ($exists) {
        echo "Migration entry '$migrationName' found in DB. Deleting it to allow re-run...\n";
        DB::table('migrations')->where('migration', $migrationName)->delete();
        echo "Deleted. Now run 'php artisan migrate'.\n";
    } else {
        echo "Migration entry not found. 'php artisan migrate' should have worked. Checking for other issues.\n";
    }
}
