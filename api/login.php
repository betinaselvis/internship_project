<?php
require_once __DIR__ . '/helpers.php';

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

$pdo = getPDO();
$stmt = $pdo->prepare(
    "SELECT id,password_hash FROM users WHERE email=?"
);
$stmt->execute([$email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$user || !password_verify($password, $user['password_hash'])){
    jsonResponse(['success'=>false,'message'=>'Invalid login']);
}

$token = generateToken();

// Store token in database with 24-hour expiration
$pdo = getPDO();
$stmt = $pdo->prepare(
    "INSERT INTO sessions (token, user_id, expires_at) 
     VALUES (?, ?, DATE_ADD(NOW(), INTERVAL 24 HOUR))"
);
$stmt->execute([$token, $user['id']]);

jsonResponse(['success'=>true,'token'=>$token]);
