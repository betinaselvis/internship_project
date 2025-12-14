<?php
require_once __DIR__ . '/config.php';

function getPDO(){
    static $pdo = null;
    if ($pdo) return $pdo;

    $dsn = "mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=utf8mb4";
    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    return $pdo;
}

function jsonResponse($arr){
    header('Content-Type: application/json');
    echo json_encode($arr);
    exit;
}

/* ---------- TOKEN ---------- */

function generateToken(){
    return bin2hex(random_bytes(24));
}

function verifyToken($token){
    $pdo = getPDO();
    $stmt = $pdo->prepare("SELECT user_id FROM sessions WHERE token=? AND (expires_at IS NULL OR expires_at > NOW())");
    $stmt->execute([$token]);
    $row = $stmt->fetch();
    return $row ? (int)$row['user_id'] : false;
}

function requireTokenOrJson(){
    $token = $_POST['token'] ?? '';
    if (!$token) jsonResponse(['success'=>false,'message'=>'Token missing']);

    $uid = verifyToken($token);
    if (!$uid) jsonResponse(['success'=>false,'message'=>'Invalid token']);

    return $uid;
}
