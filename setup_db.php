<?php
/**
 * Database Setup Script
 * Run this once to create all required tables
 * Visit: https://your-railway-url/setup_db.php
 */

// Get connection details from environment
$mysqlUrl = getenv('MYSQL_URL');
$host = getenv('MYSQL_HOST');
$port = getenv('MYSQL_PORT');
$user = getenv('MYSQL_USER');
$pass = getenv('MYSQL_PASSWORD');

// If MYSQL_URL is set, parse it
if ($mysqlUrl) {
    $parsed = parse_url($mysqlUrl);
    $host = $parsed['host'] ?? 'localhost';
    $port = $parsed['port'] ?? 3306;
    $user = $parsed['user'] ?? 'root';
    $pass = $parsed['pass'] ?? '';
}

// Set defaults if not available
$host = $host ?: 'localhost';
$port = $port ?: 3306;
$user = $user ?: 'root';
$pass = $pass ?: '';

echo "<h2>Database Setup</h2>";
echo "<p>Connection details:</p>";
echo "<pre>";
echo "Host: " . htmlspecialchars($host) . "\n";
echo "Port: " . htmlspecialchars($port) . "\n";
echo "User: " . htmlspecialchars($user) . "\n";
echo "</pre>";

echo "<p>Connecting to MySQL...</p>";

try {
    // Connect without specifying database
    $pdo = new PDO(
        "mysql:host=$host;port=$port;charset=utf8mb4",
        $user,
        $pass,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    
    echo "<p>✅ Connected to MySQL server</p>";
    
    // Create database
    echo "<p>Creating database 'guvi_intern'...</p>";
    $pdo->exec("CREATE DATABASE IF NOT EXISTS guvi_intern");
    echo "<p>✅ Database created</p>";
    
    // Now connect to the specific database
    $pdo->exec("USE guvi_intern");
    
    // Create users table
    echo "<p>Creating 'users' table...</p>";
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS users (
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
    echo "<p>✅ Users table created</p>";
    
    // Create sessions table
    echo "<p>Creating 'sessions' table...</p>";
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS sessions (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            token VARCHAR(255) NOT NULL UNIQUE,
            expires_at TIMESTAMP NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
    ");
    echo "<p>✅ Sessions table created</p>";
    
    // Create education table
    echo "<p>Creating 'education' table...</p>";
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS education (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            institution_name VARCHAR(255),
            degree VARCHAR(100),
            field_of_study VARCHAR(100),
            start_year INT,
            end_year INT,
            cgpa DECIMAL(3,2),
            description TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
    ");
    echo "<p>✅ Education table created</p>";
    
    // Create experience table
    echo "<p>Creating 'experience' table...</p>";
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS experience (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            title VARCHAR(255),
            organization VARCHAR(255),
            start_date DATE,
            end_date DATE,
            description TEXT,
            tech_stack VARCHAR(255),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
    ");
    echo "<p>✅ Experience table created</p>";
    
    // Create skills table
    echo "<p>Creating 'skills' table...</p>";
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS skills (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            skill_name VARCHAR(100),
            skill_level ENUM('Beginner', 'Intermediate', 'Advanced'),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
    ");
    echo "<p>✅ Skills table created</p>";
    
    // Create certifications table
    echo "<p>Creating 'certifications' table...</p>";
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS certifications (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            title VARCHAR(255),
            issuer VARCHAR(255),
            issue_date DATE,
            expiry_date DATE,
            credential_url VARCHAR(255),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
    ");
    echo "<p>✅ Certifications table created</p>";
    
    echo "<hr>";
    echo "<h3 style='color: green;'>✅ All tables created successfully!</h3>";
    echo "<p><a href='debug_env.php'>Test Database Connection</a></p>";
    echo "<p><a href='signup.html'>Go to Signup</a></p>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'><strong>❌ Error:</strong></p>";
    echo "<pre>" . htmlspecialchars($e->getMessage()) . "</pre>";
    echo "<p><strong>Debugging Info:</strong></p>";
    echo "<pre>";
    echo "MYSQL_HOST: " . htmlspecialchars(getenv('MYSQL_HOST') ?: 'NOT SET') . "\n";
    echo "MYSQL_PORT: " . htmlspecialchars(getenv('MYSQL_PORT') ?: 'NOT SET') . "\n";
    echo "MYSQL_USER: " . htmlspecialchars(getenv('MYSQL_USER') ?: 'NOT SET') . "\n";
    echo "MYSQL_PASSWORD: " . (getenv('MYSQL_PASSWORD') ? '***SET***' : 'NOT SET') . "\n";
    echo "MYSQL_URL: " . htmlspecialchars(getenv('MYSQL_URL') ?: 'NOT SET') . "\n";
    echo "</pre>";
}
?>
