<?php
// Helper function to get clean environment variables (remove quotes)
function getEnvClean($name, $default = '') {
    $value = getenv($name);
    if ($value === false) {
        return $default;
    }
    // Remove surrounding quotes if present
    return trim($value, '\'"');
}

// Railway environment variables for MySQL
define('DB_HOST', getEnvClean('MYSQL_HOST', 'localhost'));
define('DB_PORT', getEnvClean('MYSQL_PORT', 3306));
define('DB_NAME', getEnvClean('MYSQL_DATABASE', 'guvi_intern'));
define('DB_USER', getEnvClean('MYSQL_USER', 'root'));
define('DB_PASS', getEnvClean('MYSQL_PASSWORD', ''));

// Session settings
define('SESSION_TTL', 86400); // 24 hours in seconds

// Enable CORS for deployed version
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}
