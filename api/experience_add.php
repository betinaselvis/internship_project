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

  $title = $data['title'] ?? '';
  $organization = $data['organization'] ?? '';
  $startDate = $data['start_date'] ?? null;
  $endDate = $data['end_date'] ?? null;
  $description = $data['description'] ?? '';
  $techStack = $data['tech_stack'] ?? '';

  if (empty($title) || empty($organization) || empty($startDate) || empty($description)) {
    $response['message'] = 'Missing required fields';
    echo json_encode($response);
    exit;
  }

  $stmt = $pdo->prepare('
    INSERT INTO experience (user_id, title, organization, start_date, end_date, description, tech_stack, created_at)
    VALUES (?, ?, ?, ?, ?, ?, ?, NOW())
  ');

  if ($stmt->execute([$user['id'], $title, $organization, $startDate, $endDate, $description, $techStack])) {
    $response['success'] = true;
    $response['message'] = 'Experience added successfully';
    $response['id'] = $pdo->lastInsertId();
  } else {
    $response['message'] = 'Failed to add experience';
  }

} catch (Exception $e) {
  $response['message'] = 'Server error: ' . $e->getMessage();
}

echo json_encode($response);
?>
