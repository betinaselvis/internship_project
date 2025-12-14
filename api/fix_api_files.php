<?php
// Fix script to update all API files with correct $user_id variable

$files = [
    'education_add.php',
    'education_delete.php',
    'skills_add.php',
    'skills_delete.php',
    'experience_add.php',
    'experience_delete.php'
];

$dir = __DIR__;

foreach ($files as $file) {
    $filePath = $dir . '/' . $file;
    if (!file_exists($filePath)) {
        echo "File not found: $file\n";
        continue;
    }
    
    $content = file_get_contents($filePath);
    
    // Replace $user with $user_id in verifyToken assignment
    $content = preg_replace('/\$user = verifyToken/', '$user_id = verifyToken', $content);
    
    // Replace $user['id'] with $user_id
    $content = str_replace("\$user['id']", '$user_id', $content);
    
    if (file_put_contents($filePath, $content)) {
        echo "Fixed: $file\n";
    } else {
        echo "Error fixing: $file\n";
    }
}

echo "All files processed!\n";
?>
