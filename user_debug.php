<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;

$output = "USER TABLE DEBUG\n";
$output .= "================\n\n";

try {
    $count = User::count();
    $output .= "Total Users: " . $count . "\n\n";
    
    $users = User::latest()->take(5)->get();
    foreach ($users as $u) {
        $output .= sprintf("ID: %d | Name: %s | Email: %s | Created: %s\n", 
            $u->id, $u->name, $u->email, $u->created_at);
    }
} catch (\Exception $e) {
    $output .= "Error: " . $e->getMessage() . "\n";
}

file_put_contents('user_debug_output.txt', $output);
echo "User debug data written to user_debug_output.txt\n";
