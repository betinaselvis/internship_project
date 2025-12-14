<?php
require_once __DIR__ . '/helpers.php';

$first_name = trim($_POST['first_name'] ?? '');
$last_name = trim($_POST['last_name'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if(!$first_name || !$last_name || !$email || !$password){
    jsonResponse(['success'=>false,'message'=>'All fields are required']);
}

// Validate password strength
if(strlen($password) < 8){
    jsonResponse(['success'=>false,'message'=>'Password must be at least 8 characters long']);
}
if(!preg_match('/[A-Z]/', $password)){
    jsonResponse(['success'=>false,'message'=>'Password must contain at least one uppercase letter']);
}
if(!preg_match('/[a-z]/', $password)){
    jsonResponse(['success'=>false,'message'=>'Password must contain at least one lowercase letter']);
}
if(!preg_match('/[0-9]/', $password)){
    jsonResponse(['success'=>false,'message'=>'Password must contain at least one number']);
}
if(!preg_match('/[!@#$%^&*()_+\-=\[\]{};:\'"\\|,.<>\/?]/', $password)){
    jsonResponse(['success'=>false,'message'=>'Password must contain at least one special character']);
}

$pdo = getPDO();

try {
    $stmt = $pdo->prepare(
        "INSERT INTO users(first_name, last_name, email, password_hash)
         VALUES (?, ?, ?, ?)"
    );
    $stmt->execute([
        $first_name,
        $last_name,
        $email,
        password_hash($password, PASSWORD_DEFAULT)
    ]);

    jsonResponse(['success'=>true]);

} catch(Exception $e){
    $msg = 'Registration failed';
    if ($e instanceof PDOException) {
        $err = $e->errorInfo[1] ?? null;
        // MySQL duplicate entry error code is 1062
        if ($err == 1062) {
            $msg = 'Email already exists';
        }
    }
    jsonResponse(['success'=>false,'message'=>$msg]);
}
?>
