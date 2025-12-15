<?php
// Must require files BEFORE any output
require_once __DIR__ . '/api/config.php';
require_once __DIR__ . '/api/helpers.php';

session_start();

echo "<!DOCTYPE html><html><head><title>Check Users Table</title><style>
body { font-family: Arial; margin: 20px; }
table { border-collapse: collapse; margin: 20px 0; }
td, th { border: 1px solid #ddd; padding: 8px; text-align: left; }
th { background-color: #4CAF50; color: white; }
.error { color: red; }
.success { color: green; }
</style></head><body>";

echo "<h1>Users Table Structure Check</h1>";

try {
    $pdo = getPDO();
    echo "<p class='success'>✅ Connected to database</p>";
    
    // Check if table exists
    $result = $pdo->query("SELECT COUNT(*) FROM information_schema.TABLES WHERE TABLE_SCHEMA='" . DB_NAME . "' AND TABLE_NAME='users'")->fetch();
    
    if ($result[0] == 0) {
        echo "<p class='error'>❌ Users table does NOT exist!</p>";
        echo "<p>Please visit <a href='fix_users_table.php'>fix_users_table.php</a> to create it.</p>";
    } else {
        echo "<p class='success'>✅ Users table EXISTS</p>";
        
        // Show columns
        echo "<h3>Current Columns in Users Table:</h3>";
        $columns = $pdo->query("DESCRIBE users")->fetchAll(PDO::FETCH_ASSOC);
        echo "<table><tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th></tr>";
        foreach ($columns as $col) {
            echo "<tr><td>" . htmlspecialchars($col['Field']) . "</td><td>" . htmlspecialchars($col['Type']) . "</td><td>" . $col['Null'] . "</td><td>" . $col['Key'] . "</td></tr>";
        }
        echo "</table>";
        
        // Check for required columns
        $requiredColumns = ['first_name', 'last_name', 'email', 'password_hash'];
        echo "<h3>Required Columns Check:</h3>";
        foreach ($requiredColumns as $colName) {
            $found = false;
            foreach ($columns as $col) {
                if ($col['Field'] === $colName) {
                    $found = true;
                    break;
                }
            }
            if ($found) {
                echo "<p class='success'>✅ $colName - OK</p>";
            } else {
                echo "<p class='error'>❌ $colName - MISSING!</p>";
            }
        }
        
        // Show row count
        $count = $pdo->query("SELECT COUNT(*) FROM users")->fetch()[0];
        echo "<h3>Row Count: <strong>$count</strong> user(s)</h3>";
    }
    
} catch (Exception $e) {
    echo "<p class='error'>❌ Error: " . htmlspecialchars($e->getMessage()) . "</p>";
}

echo "</body></html>";
?>
