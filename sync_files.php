<?php
// Sync files from Windows to WSL server
$files = [
    '/mnt/c/xampp/htdocs/internship_project/api/education_add.php' => '/var/www/html/internship_project/api/education_add.php',
    '/mnt/c/xampp/htdocs/internship_project/api/education_delete.php' => '/var/www/html/internship_project/api/education_delete.php',
    '/mnt/c/xampp/htdocs/internship_project/api/skills_add.php' => '/var/www/html/internship_project/api/skills_add.php',
    '/mnt/c/xampp/htdocs/internship_project/api/skills_delete.php' => '/var/www/html/internship_project/api/skills_delete.php',
    '/mnt/c/xampp/htdocs/internship_project/api/experience_add.php' => '/var/www/html/internship_project/api/experience_add.php',
    '/mnt/c/xampp/htdocs/internship_project/api/experience_delete.php' => '/var/www/html/internship_project/api/experience_delete.php',
];

foreach ($files as $src => $dst) {
    if (file_exists($src)) {
        $content = file_get_contents($src);
        file_put_contents($dst, $content);
        echo "Copied " . basename($src) . "\n";
    } else {
        echo "File not found: $src\n";
    }
}

echo "All files synced successfully!\n";
?>
