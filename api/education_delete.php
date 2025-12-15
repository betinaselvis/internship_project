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

  $id = $data['id'] ?? null;
  
  if (empty($id)) {
    $response['message'] = 'Missing education ID';
    echo json_encode($response);
    exit;
  }

  $pdo = getPDO();
  $stmt = $pdo->prepare('DELETE FROM education WHERE id = ? AND user_id = ?');
  
  if ($stmt->execute([$id, $user_id])) {
    $response['success'] = true;
    $response['message'] = 'Education deleted successfully';
  } else {
    $response['message'] = 'Failed to delete education';
  }

} catch (Exception $e) {
  $response['message'] = 'Server error: ' . $e->getMessage();
}

echo json_encode($response);
?>
