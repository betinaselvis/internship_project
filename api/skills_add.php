<?php
require 'config.php';
require 'helpers.php';

header('Content-Type: application/json');

$response = ['success' => false, 'message' => ''];

try {
  $data = json_decode(file_get_contents('php://input'), true) ?? $_POST;
  
  // Verify token
  $user = verifyToken($data['token'] ?? '');
  if (!$user) {
    $response['message'] = 'Unauthorized';
    echo json_encode($response);
    exit;
  }

  $skillName = $data['skill_name'] ?? '';
  $skillLevel = $data['skill_level'] ?? '';

  if (empty($skillName) || empty($skillLevel)) {
    $response['message'] = 'Missing required fields';
    echo json_encode($response);
    exit;
  }

  // Validate skill level
  $validLevels = ['Beginner', 'Intermediate', 'Advanced'];
  if (!in_array($skillLevel, $validLevels)) {
    $response['message'] = 'Invalid skill level';
    echo json_encode($response);
    exit;
  }

  $stmt = $pdo->prepare('
    INSERT INTO skills (user_id, skill_name, skill_level, created_at)
    VALUES (?, ?, ?, NOW())
  ');

  if ($stmt->execute([$user['id'], $skillName, $skillLevel])) {
    $response['success'] = true;
    $response['message'] = 'Skill added successfully';
    $response['id'] = $pdo->lastInsertId();
  } else {
    $response['message'] = 'Failed to add skill';
  }

} catch (Exception $e) {
  $response['message'] = 'Server error: ' . $e->getMessage();
}

echo json_encode($response);
?>
