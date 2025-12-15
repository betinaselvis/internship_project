<?php

// MySQL (from Railway environment variables)
$MYSQL_HOST = getenv('MYSQL_HOST');
$MYSQL_PORT = getenv('MYSQL_PORT');
$MYSQL_DB   = getenv('MYSQL_DATABASE');
$MYSQL_USER = getenv('MYSQL_USER');
$MYSQL_PASS = getenv('MYSQL_PASSWORD');

$pdo = new PDO(
    "mysql:host=$MYSQL_HOST;port=$MYSQL_PORT;dbname=$MYSQL_DB;charset=utf8mb4",
    $MYSQL_USER,
    $MYSQL_PASS,
    [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]
);

// Redis (from Railway environment variables)
$redis = new Redis();
$redis->connect(
    getenv('REDIS_HOST'),
    getenv('REDIS_PORT')
);

if (getenv('REDIS_PASSWORD')) {
    $redis->auth(getenv('REDIS_PASSWORD'));
}

// Session TTL
$SESSION_TTL = 3600;
