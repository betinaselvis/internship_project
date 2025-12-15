<?php
/**
 * Fix Users Table Script
 * Drops and recreates the users table with all required columns
 * Visit: https://your-railway-url/fix_users_table.php
 */

$host = getenv('MYSQL_HOST') ?: 'localhost';
$port = getenv('MYSQL_PORT') ?: 3306;
$user = getenv('MYSQL_USER') ?: 'root';
$pass = getenv('MYSQL_PASSWORD') ?: '';

echo "<h2>Fix Users Table</h2>";

try {
    $pdo = new PDO(
        "mysql:host=$host;port=$port;dbname=guvi_intern;charset=utf8mb4",
        $user,
        $pass,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    
    echo "<p>✅ Connected to database</p>";
    
    try {
        // Disable foreign key checks
        echo "<p>Disabling foreign key constraints...</p>";
        $pdo->exec("SET FOREIGN_KEY_CHECKS = 0");
        echo "<p>✅ Foreign key checks disabled</p>";
        
        // Drop existing users table
        echo "<p>Dropping old users table...</p>";
        $pdo->exec("DROP TABLE IF EXISTS users");
        echo "<p>✅ Old users table dropped</p>";
        
        // Create new users table with ALL columns
        echo "<p>Creating new users table with all columns...</p>";
        $pdo->exec("
            CREATE TABLE users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                username VARCHAR(100) UNIQUE,
                email VARCHAR(255) NOT NULL UNIQUE,
                password_hash VARCHAR(255) NOT NULL,
                first_name VARCHAR(100),
                last_name VARCHAR(100),
                profile_picture VARCHAR(255),
                headline VARCHAR(255),
                about TEXT,
                location VARCHAR(255),
                phone VARCHAR(20),
                alternate_email VARCHAR(255),
                gender ENUM('Male', 'Female', 'Other', 'Prefer not to say'),
                dob DATE,
                address TEXT,
                website VARCHAR(255),
                github_url VARCHAR(255),
                linkedin_url VARCHAR(255),
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
        ");
        echo "<p>✅ New users table created with all columns</p>";
        
        // Re-enable foreign key checks
        echo "<p>Re-enabling foreign key constraints...</p>";
        $pdo->exec("SET FOREIGN_KEY_CHECKS = 1");
        echo "<p>✅ Foreign key checks re-enabled</p>";
        
        // Show table columns to verify
        echo "<h3>Verifying table structure:</h3>";
        $columns = $pdo->query("DESCRIBE users")->fetchAll(PDO::FETCH_ASSOC);
        echo "<table border='1'><tr><th>Field</th><th>Type</th></tr>";
        foreach ($columns as $col) {
            echo "<tr><td>" . htmlspecialchars($col['Field']) . "</td><td>" . htmlspecialchars($col['Type']) . "</td></tr>";
        }
        echo "</table>";
        echo "<p><strong>✅ SUCCESS: Users table has " . count($columns) . " columns</strong></p>";
        
    } catch (Exception $e) {
        echo "<p><strong>❌ Error: " . htmlspecialchars($e->getMessage()) . "</strong></p>";
        // Try to re-enable foreign key checks even on error
        try {
            $pdo->exec("SET FOREIGN_KEY_CHECKS = 1");
        } catch (Exception $e2) {}
    }
    
    // Verify columns
    echo "<p>Verifying columns...</p>";
    $stmt = $pdo->query("DESCRIBE users");
    $columns = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $columns[] = $row['Field'];
    }
    
    echo "<p>Table columns:</p>";
    echo "<ul>";
    foreach ($columns as $col) {
        echo "<li>$col</li>";
    }
    echo "</ul>";
    
    echo "<hr>";
    echo "<h3 style='color: green;'>✅ Users table fixed!</h3>";
    echo "<p><a href='signup.html'>Go to Signup and Try Again</a></p>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'><strong>❌ Error:</strong></p>";
    echo "<pre>" . htmlspecialchars($e->getMessage()) . "</pre>";
}
?>