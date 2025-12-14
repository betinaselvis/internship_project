<?php
require_once __DIR__ . '/helpers.php';

$user_id = requireTokenOrJson();
$pdo = getPDO();

// Get user details
$stmt = $pdo->prepare("
    SELECT id, first_name, last_name, email, profile_picture, headline, about, 
           location, phone, alternate_email, gender, dob, address, website, 
           github_url, linkedin_url
    FROM users
    WHERE id = ?
");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    jsonResponse(['success' => false, 'message' => 'User not found']);
}

// Get education
$stmt = $pdo->prepare("SELECT * FROM education WHERE user_id = ? ORDER BY start_year DESC");
$stmt->execute([$user_id]);
$education = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get skills
$stmt = $pdo->prepare("SELECT * FROM skills WHERE user_id = ?");
$stmt->execute([$user_id]);
$skills = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get experience
$stmt = $pdo->prepare("SELECT * FROM experience WHERE user_id = ? ORDER BY start_date DESC");
$stmt->execute([$user_id]);
$experience = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get certifications
$stmt = $pdo->prepare("SELECT * FROM certifications WHERE user_id = ? ORDER BY issue_date DESC");
$stmt->execute([$user_id]);
$certifications = $stmt->fetchAll(PDO::FETCH_ASSOC);

jsonResponse([
    'success' => true,
    'user' => $user,
    'education' => $education,
    'skills' => $skills,
    'experience' => $experience,
    'certifications' => $certifications
]);
?>
