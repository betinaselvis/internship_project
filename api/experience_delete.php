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

  $id = $data['id'] ?? null;
  
  if (empty($id)) {
    $response['message'] = 'Missing experience ID';
    echo json_encode($response);
    exit;
  }

  $stmt = $pdo->prepare('DELETE FROM experience WHERE id = ? AND user_id = ?');
  
  if ($stmt->execute([$id, $user['id']])) {
    $response['success'] = true;
    $response['message'] = 'Experience deleted successfully';
  } else {
    $response['message'] = 'Failed to delete experience';
  }

} catch (Exception $e) {
  $response['message'] = 'Server error: ' . $e->getMessage();
}

echo json_encode($response);
?>
