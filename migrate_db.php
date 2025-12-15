<?php
/**
 * Database Migration Script
 * Adds missing columns to users table
 * Visit: https://your-railway-url/migrate_db.php
 */

// Get connection details from environment
$host = getenv('MYSQL_HOST') ?: 'localhost';
$port = getenv('MYSQL_PORT') ?: 3306;
$user = getenv('MYSQL_USER') ?: 'root';
$pass = getenv('MYSQL_PASSWORD') ?: '';

echo "<h2>Database Migration</h2>";
echo "<p>Connecting to MySQL...</p>";

try {
    // Connect to database
    $pdo = new PDO(
        "mysql:host=$host;port=$port;dbname=guvi_intern;charset=utf8mb4",
        $user,
        $pass,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    
    echo "<p>✅ Connected to database</p>";
    
    // Check if columns exist and add them if they don't
    echo "<p>Checking users table structure...</p>";
    
    $columnQueries = [
        "first_name" => "ALTER TABLE users ADD COLUMN first_name VARCHAR(100) AFTER email",
        "last_name" => "ALTER TABLE users ADD COLUMN last_name VARCHAR(100) AFTER first_name",
        "profile_picture" => "ALTER TABLE users ADD COLUMN profile_picture VARCHAR(255) AFTER last_name",
        "headline" => "ALTER TABLE users ADD COLUMN headline VARCHAR(255) AFTER profile_picture",
        "about" => "ALTER TABLE users ADD COLUMN about TEXT AFTER headline",
        "location" => "ALTER TABLE users ADD COLUMN location VARCHAR(255) AFTER about",
        "phone" => "ALTER TABLE users ADD COLUMN phone VARCHAR(20) AFTER location",
        "alternate_email" => "ALTER TABLE users ADD COLUMN alternate_email VARCHAR(255) AFTER phone",
        "gender" => "ALTER TABLE users ADD COLUMN gender ENUM('Male', 'Female', 'Other', 'Prefer not to say') AFTER alternate_email",
        "dob" => "ALTER TABLE users ADD COLUMN dob DATE AFTER gender",
        "address" => "ALTER TABLE users ADD COLUMN address TEXT AFTER dob",
        "website" => "ALTER TABLE users ADD COLUMN website VARCHAR(255) AFTER address",
        "github_url" => "ALTER TABLE users ADD COLUMN github_url VARCHAR(255) AFTER website",
        "linkedin_url" => "ALTER TABLE users ADD COLUMN linkedin_url VARCHAR(255) AFTER github_url",
    ];
    
    // Get current columns
    $stmt = $pdo->query("DESCRIBE users");
    $currentColumns = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $currentColumns[] = $row['Field'];
    }
    
    echo "<p>Current columns: " . implode(", ", $currentColumns) . "</p>";
    
    // Add missing columns
    foreach ($columnQueries as $colName => $query) {
        if (!in_array($colName, $currentColumns)) {
            echo "<p>Adding column '$colName'...</p>";
            try {
                $pdo->exec($query);
                echo "<p>✅ Column '$colName' added</p>";
            } catch (Exception $e) {
                echo "<p style='color: orange;'>⚠️ Could not add '$colName': " . $e->getMessage() . "</p>";
            }
        } else {
            echo "<p>✅ Column '$colName' already exists</p>";
        }
    }
    
    echo "<hr>";
    echo "<h3 style='color: green;'>✅ Database migration completed!</h3>";
    echo "<p><a href='debug_env.php'>Test Database Connection</a></p>";
    echo "<p><a href='signup.html'>Go to Signup</a></p>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'><strong>❌ Error:</strong></p>";
    echo "<pre>" . htmlspecialchars($e->getMessage()) . "</pre>";
}
?>