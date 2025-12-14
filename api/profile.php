<?php
require_once __DIR__ . '/helpers.php';

$user_id = requireTokenOrJson();

$pdo = getPDO();

$stmt = $pdo->prepare("
    SELECT u.username, u.email,
           p.full_name, p.age, p.dob, p.contact, p.address
    FROM users u
    LEFT JOIN profiles p ON u.id = p.user_id
    WHERE u.id = ?
");
$stmt->execute([$user_id]);
$profile = $stmt->fetch(PDO::FETCH_ASSOC);

jsonResponse([
    'success' => true,
    'profile' => $profile
]);
