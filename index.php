<?php
// TEMP: Test MySQL connection
try {
    $pdo = new PDO(
        "mysql:host=" . getenv('MYSQL_HOST') . ";port=" . getenv('MYSQL_PORT') . ";dbname=" . getenv('MYSQL_DATABASE'),
        getenv('MYSQL_USER'),
        getenv('MYSQL_PASSWORD')
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Connected to MySQL successfully!";
    exit; // stop further execution
} catch (Exception $e) {
    echo "❌ Connection failed: " . $e->getMessage();
    exit;
}
?>
<?php
header("Location: signup.html");
exit;
