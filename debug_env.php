<?php
// DEBUG: Show environment variables (REMOVE BEFORE PRODUCTION)
// This helps troubleshoot Railway deployment issues

require_once __DIR__ . '/api/config.php';

echo "<h2>Environment Variables Debug</h2>";
echo "<pre>";
echo "MYSQL_HOST: " . htmlspecialchars(getenv('MYSQL_HOST') ?? 'NOT SET') . "\n";
echo "MYSQL_PORT: " . htmlspecialchars(getenv('MYSQL_PORT') ?? 'NOT SET') . "\n";
echo "MYSQL_DATABASE: " . htmlspecialchars(getenv('MYSQL_DATABASE') ?? 'NOT SET') . "\n";
echo "MYSQL_USER: " . htmlspecialchars(getenv('MYSQL_USER') ?? 'NOT SET') . "\n";
echo "MYSQL_PASSWORD: " . (getenv('MYSQL_PASSWORD') ? '***SET***' : 'NOT SET') . "\n";
echo "</pre>";

echo "<h2>Cleaned Constants</h2>";
echo "<pre>";
echo "DB_HOST: " . htmlspecialchars(DB_HOST) . "\n";
echo "DB_PORT: " . htmlspecialchars(DB_PORT) . "\n";
echo "DB_NAME: " . htmlspecialchars(DB_NAME) . "\n";
echo "DB_USER: " . htmlspecialchars(DB_USER) . "\n";
echo "DB_PASS: " . (DB_PASS ? '***SET***' : 'NOT SET') . "\n";
echo "</pre>";

echo "<h2>Database Connection Test</h2>";
try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
    echo "<p style='color: green;'><strong>✅ Connected to database successfully!</strong></p>";
} catch (Exception $e) {
    echo "<p style='color: red;'><strong>❌ Connection failed:</strong></p>";
    echo "<pre>" . htmlspecialchars($e->getMessage()) . "</pre>";
}
?>