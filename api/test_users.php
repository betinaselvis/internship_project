<?php
header('Content-Type: application/json');

require_once __DIR__ . '/config.php';

try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    
    // Get all users
    $stmt = $pdo->query("SELECT u.id, u.username, u.email, p.full_name, p.age, p.contact FROM users u LEFT JOIN profiles p ON u.id = p.user_id");
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'success' => true,
        'users' => $data
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>
