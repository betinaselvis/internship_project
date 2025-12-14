<?php
require_once __DIR__ . '/helpers.php';

$user_id = requireTokenOrJson();

$pdo = getPDO();

$data = [
    $_POST['first_name'] ?? null,
    $_POST['last_name'] ?? null,
    $_POST['headline'] ?? null,
    $_POST['about'] ?? null,
    $_POST['location'] ?? null,
    $_POST['gender'] ?? null,
    $_POST['dob'] ?? null,
    $_POST['phone'] ?? null,
    $_POST['alternate_email'] ?? null,
    $_POST['address'] ?? null,
    $_POST['website'] ?? null,
    $_POST['github_url'] ?? null,
    $_POST['linkedin_url'] ?? null,
    $user_id
];

$stmt = $pdo->prepare("
    UPDATE users 
    SET first_name=?, last_name=?, headline=?, about=?, location=?, 
        gender=?, dob=?, phone=?, alternate_email=?, address=?, 
        website=?, github_url=?, linkedin_url=?
    WHERE id=?
");
$stmt->execute($data);

jsonResponse(['success'=>true]);
?>
