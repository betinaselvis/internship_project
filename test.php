<?php
require_once __DIR__ . '/api/config.php';

echo "<h2>Comprehensive System Diagnostic</h2>";

try {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    $pdo = new PDO($dsn, DB_USER, DB_PASS, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    
    echo "<h3>1. Database Connection</h3>";
    echo "<p>✅ Connected to database: " . DB_NAME . "</p>";
    
    // Check tables
    echo "<h3>2. Database Tables</h3>";
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    $required_tables = ['users', 'profiles', 'sessions'];
    foreach ($required_tables as $table) {
        if (in_array($table, $tables)) {
            echo "<p>✅ Table '$table' exists</p>";
        } else {
            echo "<p>❌ Table '$table' MISSING - need to create</p>";
        }
    }
    
    // Check existing data
    echo "<h3>3. Existing Users</h3>";
    $stmt = $pdo->query("SELECT id, username, email FROM users");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (count($users) > 0) {
        echo "<table border='1' cellpadding='5'>";
        echo "<tr><th>ID</th><th>Username</th><th>Email</th></tr>";
        foreach ($users as $user) {
            echo "<tr>";
            echo "<td>" . $user['id'] . "</td>";
            echo "<td>" . $user['username'] . "</td>";
            echo "<td>" . $user['email'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No users registered yet</p>";
    }
    
    // Check profiles
    echo "<h3>4. User Profiles</h3>";
    $stmt = $pdo->query("SELECT * FROM profiles");
    $profiles = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (count($profiles) > 0) {
        echo "<table border='1' cellpadding='5'>";
        echo "<tr><th>ID</th><th>User ID</th><th>Full Name</th><th>Age</th><th>Contact</th></tr>";
        foreach ($profiles as $profile) {
            echo "<tr>";
            echo "<td>" . $profile['id'] . "</td>";
            echo "<td>" . $profile['user_id'] . "</td>";
            echo "<td>" . $profile['full_name'] . "</td>";
            echo "<td>" . $profile['age'] . "</td>";
            echo "<td>" . $profile['contact'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No profiles created yet</p>";
    }
    
    // Check sessions
    echo "<h3>5. Active Sessions</h3>";
    $stmt = $pdo->query("SELECT id, user_id, token, expires_at FROM sessions ORDER BY created_at DESC LIMIT 5");
    $sessions = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (count($sessions) > 0) {
        echo "<table border='1' cellpadding='5'>";
        echo "<tr><th>Token</th><th>User ID</th><th>Expires At</th></tr>";
        foreach ($sessions as $session) {
            echo "<tr>";
            echo "<td>" . substr($session['token'], 0, 10) . "...</td>";
            echo "<td>" . $session['user_id'] . "</td>";
            echo "<td>" . $session['expires_at'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No sessions created yet</p>";
    }
    
    // Test API endpoints
    echo "<h3>6. API Test</h3>";
    echo "<p>Try registering a test user via the signup form, then check back here</p>";
    echo "<p><a href='http://localhost/internship_project/signup.html' target='_blank'>Go to Registration</a></p>";
    
} catch (Exception $e) {
    echo "<p style='color:red;'>❌ Error: " . $e->getMessage() . "</p>";
}
?>
