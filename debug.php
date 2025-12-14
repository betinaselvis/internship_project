<?php
require_once __DIR__ . '/api/config.php';

echo "<h2>Database Debug Info</h2>";

try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    
    echo "<p>✅ Database connection successful</p>";
    
    // Check tables
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    echo "<h3>Tables in database:</h3>";
    echo "<ul>";
    foreach ($tables as $table) {
        echo "<li>$table</li>";
    }
    echo "</ul>";
    
    // Check if sessions table exists
    if (in_array('sessions', $tables)) {
        echo "<p>✅ Sessions table exists</p>";
        
        $stmt = $pdo->query("DESCRIBE sessions");
        echo "<h3>Sessions table structure:</h3>";
        echo "<pre>";
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            print_r($row);
        }
        echo "</pre>";
    } else {
        echo "<p>❌ Sessions table NOT found - need to create it</p>";
    }
    
    // Check profiles table
    if (in_array('profiles', $tables)) {
        echo "<p>✅ Profiles table exists</p>";
        
        $stmt = $pdo->query("DESCRIBE profiles");
        echo "<h3>Profiles table structure:</h3>";
        echo "<pre>";
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            print_r($row);
        }
        echo "</pre>";
    } else {
        echo "<p>❌ Profiles table NOT found</p>";
    }
    
} catch (Exception $e) {
    echo "<p>❌ Error: " . $e->getMessage() . "</p>";
}
?>
