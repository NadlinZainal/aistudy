<?php
// list_dbs.php
try {
    $dsn = "mysql:host=localhost;port=3306";
    $pdo = new PDO($dsn, "root", "");
    $stmt = $pdo->query("SHOW DATABASES");
    $dbs = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo "DATABASES FOUND:\n";
    foreach ($dbs as $db) {
        echo " - '" . $db . "'\n";
    }
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
