<?php
/**
 * Complete Database Setup and Fix
 * Run this script to initialize or reset the database completely
 */

// Must NOT output anything before this
require_once __DIR__ . '/api/config.php';

// Now we can output HTML
echo "<!DOCTYPE html><html><head><title>Database Setup</title><style>
body { font-family: Arial; margin: 20px; background: #f5f5f5; }
.container { max-width: 800px; margin: 0 auto; background: white; padding: 20px; border-radius: 5px; }
.success { color: #4CAF50; font-weight: bold; }
.error { color: #f44336; font-weight: bold; }
.warning { color: #ff9800; font-weight: bold; }
h2 { color: #333; border-bottom: 2px solid #4CAF50; padding-bottom: 10px; }
.step { margin: 15px 0; padding: 10px; background: #f9f9f9; border-left: 4px solid #4CAF50; }
</style></head><body>";

echo "<div class='container'>";
echo "<h1>🔧 Complete Database Setup</h1>";

try {
    // Get direct MySQL connection (without specifying database)
    $host = DB_HOST;
    $port = DB_PORT;
    $user = DB_USER;
    $pass = DB_PASS;
    
    echo "<div class='step'>";
    echo "<p>Connecting to MySQL server at <strong>$host:$port</strong>...</p>";
    
    $pdo = new PDO(
        "mysql:host=$host;port=$port;charset=utf8mb4",
        $user,
        $pass,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    
    echo "<p class='success'>✅ Connected to MySQL server</p>";
    echo "</div>";
    
    // Create database
    echo "<div class='step'>";
    echo "<p>Creating database <strong>guvi_intern</strong>...</p>";
    $pdo->exec("CREATE DATABASE IF NOT EXISTS guvi_intern");
    echo "<p class='success'>✅ Database ready</p>";
    
    // Switch to the database
    $pdo->exec("USE guvi_intern");
    echo "<p class='success'>✅ Using database guvi_intern</p>";
    echo "</div>";
    
    // Disable foreign keys to allow clean table drops
    echo "<div class='step'>";
    echo "<p>Preparing tables (disabling foreign key checks)...</p>";
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 0");
    echo "<p class='success'>✅ Foreign key checks disabled</p>";
    echo "</div>";
    
    // Drop tables in reverse order of dependencies
    echo "<div class='step'>";
    echo "<p>Removing old tables...</p>";
    $tables = ['sessions', 'education', 'experience', 'skills', 'certifications', 'profiles', 'users'];
    foreach ($tables as $table) {
        $pdo->exec("DROP TABLE IF EXISTS $table");
        echo "<p class='success'>✅ Dropped table: $table</p>";
    }
    echo "</div>";
    
    // Create users table
    echo "<div class='step'>";
    echo "<p>Creating <strong>users</strong> table...</p>";
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
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_email (email)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
    ");
    echo "<p class='success'>✅ Users table created (21 columns)</p>";
    echo "</div>";
    
    // Create sessions table
    echo "<div class='step'>";
    echo "<p>Creating <strong>sessions</strong> table...</p>";
    $pdo->exec("
        CREATE TABLE sessions (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            token VARCHAR(255) NOT NULL UNIQUE,
            expires_at TIMESTAMP NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
            INDEX idx_token (token)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
    ");
    echo "<p class='success'>✅ Sessions table created with foreign key</p>";
    echo "</div>";
    
    // Create education table
    echo "<div class='step'>";
    echo "<p>Creating <strong>education</strong> table...</p>";
    $pdo->exec("
        CREATE TABLE education (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            school VARCHAR(255) NOT NULL,
            field_of_study VARCHAR(255),
            start_date DATE,
            end_date DATE,
            grade VARCHAR(10),
            activities TEXT,
            description TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
            INDEX idx_user_id (user_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
    ");
    echo "<p class='success'>✅ Education table created</p>";
    echo "</div>";
    
    // Create experience table
    echo "<div class='step'>";
    echo "<p>Creating <strong>experience</strong> table...</p>";
    $pdo->exec("
        CREATE TABLE experience (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            job_title VARCHAR(255) NOT NULL,
            company_name VARCHAR(255) NOT NULL,
            employment_type VARCHAR(100),
            location VARCHAR(255),
            start_date DATE,
            end_date DATE,
            is_current BOOLEAN DEFAULT FALSE,
            description TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
            INDEX idx_user_id (user_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
    ");
    echo "<p class='success'>✅ Experience table created</p>";
    echo "</div>";
    
    // Create skills table
    echo "<div class='step'>";
    echo "<p>Creating <strong>skills</strong> table...</p>";
    $pdo->exec("
        CREATE TABLE skills (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            skill_name VARCHAR(255) NOT NULL,
            endorsement_count INT DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
            INDEX idx_user_id (user_id),
            UNIQUE KEY unique_skill_per_user (user_id, skill_name)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
    ");
    echo "<p class='success'>✅ Skills table created</p>";
    echo "</div>";
    
    // Create certifications table
    echo "<div class='step'>";
    echo "<p>Creating <strong>certifications</strong> table...</p>";
    $pdo->exec("
        CREATE TABLE certifications (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            name VARCHAR(255) NOT NULL,
            issuing_organization VARCHAR(255),
            issue_date DATE,
            expiration_date DATE,
            credential_id VARCHAR(255),
            credential_url VARCHAR(255),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
            INDEX idx_user_id (user_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
    ");
    echo "<p class='success'>✅ Certifications table created</p>";
    echo "</div>";
    
    // Create profiles table (if used)
    echo "<div class='step'>";
    echo "<p>Creating <strong>profiles</strong> table...</p>";
    $pdo->exec("
        CREATE TABLE profiles (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL UNIQUE,
            bio TEXT,
            avatar_url VARCHAR(255),
            cover_image_url VARCHAR(255),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
    ");
    echo "<p class='success'>✅ Profiles table created</p>";
    echo "</div>";
    
    // Re-enable foreign keys
    echo "<div class='step'>";
    echo "<p>Re-enabling foreign key checks...</p>";
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 1");
    echo "<p class='success'>✅ Foreign key checks re-enabled</p>";
    echo "</div>";
    
    // Show summary
    echo "<div class='step'>";
    echo "<h2>✅ Database Setup Complete!</h2>";
    echo "<p>All 7 tables created successfully:</p>";
    echo "<ul>";
    echo "<li><strong>users</strong> - User accounts (21 columns)</li>";
    echo "<li><strong>sessions</strong> - Authentication tokens</li>";
    echo "<li><strong>education</strong> - User education history</li>";
    echo "<li><strong>experience</strong> - User work experience</li>";
    echo "<li><strong>skills</strong> - User skills</li>";
    echo "<li><strong>certifications</strong> - User certifications</li>";
    echo "<li><strong>profiles</strong> - Profile metadata</li>";
    echo "</ul>";
    echo "<p>You can now <a href='signup.html'><strong>try signing up</strong></a> 🎉</p>";
    echo "</div>";
    
} catch (Exception $e) {
    echo "<div class='step'>";
    echo "<p class='error'>❌ Error: " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p>Details:</p>";
    echo "<pre style='background: #f0f0f0; padding: 10px; border-radius: 3px;'>";
    echo htmlspecialchars($e->getTraceAsString());
    echo "</pre>";
    echo "</div>";
}

echo "</div></body></html>";
?>
