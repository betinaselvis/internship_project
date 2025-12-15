<?php
/**
 * Database Initialization API
 * Call this endpoint to set up all database tables
 */

require_once __DIR__ . '/config.php';

header('Content-Type: application/json');

$response = [
    'success' => false,
    'message' => '',
    'steps' => []
];

try {
    // Create connection without database selection
    $host = DB_HOST;
    $port = DB_PORT;
    $user = DB_USER;
    $pass = DB_PASS;
    
    $pdo = new PDO(
        "mysql:host=$host;port=$port;charset=utf8mb4",
        $user,
        $pass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
    
    $response['steps'][] = "✅ Connected to MySQL server at $host:$port";
    
    // Create database
    $pdo->exec("CREATE DATABASE IF NOT EXISTS " . DB_NAME);
    $response['steps'][] = "✅ Database " . DB_NAME . " ready";
    
    // Select database
    $pdo->exec("USE " . DB_NAME);
    
    // Disable foreign keys
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 0");
    $response['steps'][] = "✅ Foreign key checks disabled";
    
    // Drop existing tables
    $tables = ['sessions', 'education', 'experience', 'skills', 'certifications', 'profiles', 'users'];
    foreach ($tables as $table) {
        $pdo->exec("DROP TABLE IF EXISTS $table");
    }
    $response['steps'][] = "✅ Old tables removed";
    
    // Create users table
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
    $response['steps'][] = "✅ Users table created";
    
    // Create sessions table
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
    $response['steps'][] = "✅ Sessions table created";
    
    // Create education table
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
    $response['steps'][] = "✅ Education table created";
    
    // Create experience table
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
    $response['steps'][] = "✅ Experience table created";
    
    // Create skills table
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
    $response['steps'][] = "✅ Skills table created";
    
    // Create certifications table
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
    $response['steps'][] = "✅ Certifications table created";
    
    // Create profiles table
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
    $response['steps'][] = "✅ Profiles table created";
    
    // Re-enable foreign keys
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 1");
    $response['steps'][] = "✅ Foreign key checks re-enabled";
    
    // Verify tables exist
    $result = $pdo->query("SHOW TABLES IN " . DB_NAME)->fetchAll();
    
    $response['success'] = true;
    $response['message'] = 'Database initialized successfully! ' . count($result) . ' tables created.';
    $response['tables_created'] = count($result);
    
} catch (Exception $e) {
    $response['success'] = false;
    $response['message'] = 'Error: ' . $e->getMessage();
    $response['error_code'] = $e->getCode();
}

echo json_encode($response, JSON_PRETTY_PRINT);
?>
