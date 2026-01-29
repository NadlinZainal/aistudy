<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $firestore = app('firebase.firestore')->database();
    $collection = $firestore->collection('test_connection');
    $doc = $collection->add(['timestamp' => time(), 'message' => 'Hello from Laravel']);
    echo "Success: Connected to Firestore! Document ID: " . $doc->id() . PHP_EOL;
    // Cleanup
    $doc->delete();
} catch (\Throwable $e) {
    echo "Error: " . $e->getMessage() . PHP_EOL;
}
