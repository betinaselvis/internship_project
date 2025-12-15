<?php
require_once __DIR__ . '/helpers.php';

$token = $_POST['token'] ?? null;
if (!$token) {
    jsonResponse(['success' => false, 'message' => 'Token required']);
}

// Delete token from database (sessions table)
$pdo = getPDO();
$stmt = $pdo->prepare('DELETE FROM sessions WHERE token = ?');
$stmt->execute([$token]);

jsonResponse(['success' => true, 'message' => 'Logged out successfully']);
?>