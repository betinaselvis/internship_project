<?php
// =========================
// MySQL Configuration
// =========================
define('DB_HOST', '127.0.0.1');        // MySQL host
define('DB_NAME', 'guvi_intern');      // Database name
define('DB_USER', 'root');             // MySQL user
define('DB_PASS', '');                 // Empty password

// =========================
// Redis Configuration (Optional)
// =========================
define('REDIS_HOST', '127.0.0.1');
define('REDIS_PORT', 6379);
define('SESSION_TTL', 86400); // 1 day
