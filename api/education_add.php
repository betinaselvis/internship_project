<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/helpers.php';

header('Content-Type: application/json');

$response = ['success' => false, 'message' => ''];

try {
  $data = json_decode(file_get_contents('php://input'), true) ?? $_POST;
  
  // Verify token
  $user_id = verifyToken($data['token'] ?? '');
  if (!$user_id) {
    $response['message'] = 'Unauthorized';
    echo json_encode($response);
    exit;
  }

  $institution = $data['institution_name'] ?? '';
  $degree = $data['degree'] ?? '';
  $field = $data['field_of_study'] ?? '';
  $startYear = $data['start_year'] ?? null;
  $endYear = $data['end_year'] ?? null;
  $cgpa = $data['cgpa'] ?? null;
  $description = $data['description'] ?? '';

  if (empty($institution) || empty($degree) || empty($field) || empty($startYear)) {
    $response['message'] = 'Missing required fields';
    echo json_encode($response);
    exit;
  }

  $pdo = getPDO();
  $stmt = $pdo->prepare('
    INSERT INTO education (user_id, institution_name, degree, field_of_study, start_year, end_year, cgpa, description, created_at)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())
  ');

  if ($stmt->execute([$user_id, $institution, $degree, $field, $startYear, $endYear, $cgpa, $description])) {
    $response['success'] = true;
    $response['message'] = 'Education added successfully';
    $response['id'] = $pdo->lastInsertId();
  } else {
    $response['message'] = 'Failed to add education';
  }

} catch (Exception $e) {
  $response['message'] = 'Server error: ' . $e->getMessage();
}

echo json_encode($response);
?>
