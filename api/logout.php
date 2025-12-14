<?php
require_once __DIR__ . '/helpers.php';

$token = $_POST['token'] ?? null;
if(!$token){ jsonResponse(['success'=>false, 'message'=>'Token required']); }
$redis = getRedis();
$key = 'session:'.$token;
$redis->del($key);
jsonResponse(['success'=>true]);

?>