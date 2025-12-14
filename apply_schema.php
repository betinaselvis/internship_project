<?php
require_once __DIR__ . '/api/config.php';

try {
    $dsn = "mysql:host=" . DB_HOST . ";charset=utf8mb4";
    $pdo = new PDO($dsn, DB_USER, DB_PASS);
    
    // Read the schema file
    $sql = file_get_contents(__DIR__ . '/db/schema.sql');
    
    // Split by semicolons and execute each statement
    $statements = array_filter(array_map('trim', explode(';', $sql)));
    
    foreach ($statements as $stmt) {
        if (!empty($stmt)) {
            try {
                $pdo->exec($stmt);
            } catch (PDOException $e) {
                // Some statements might fail if they already exist, that's ok
                if (strpos($e->getMessage(), 'already exists') === false) {
                    echo "Error: " . $e->getMessage() . "\n";
                }
            }
        }
    }
    
    echo "Schema applied successfully!";
} catch (Exception $e) {
    echo "Failed: " . $e->getMessage();
}
?>
