<?php
require './config/config.php';

try {
    //Query to get image URLs from the database
    $sql = "SELECT image_url FROM room_images";
    $stmt = $connect->prepare($sql);
    $stmt->execute();

    // Fetch image URLs from the database
    $dbImageUrls = $stmt->fetchAll(PDO::FETCH_COLUMN);
    $dbImageUrls = array_map(function ($url) {
        return str_replace('uploads/rooms/', '', $url);
    }, $dbImageUrls);
    // print_r($dbImageUrls);

    // Directory containing images
    $directory = './uploads/rooms';

    // Get list of files in the directory
    $directoryContents = array_diff(scandir($directory), array('..', '.'));
    $normalizedDbImageUrls = array_map('trim', array_map('strtolower', $dbImageUrls));
    $normalizedDirectoryContents = array_map('trim', array_map('strtolower', $directoryContents));

    //print_r($directoryContents);



    $filesToDelete = array_diff($normalizedDirectoryContents, $normalizedDbImageUrls);
    // print_r($filesToDelete);
    // Iterate through database image URLs
    foreach ($filesToDelete as $fileToDelete) {
        $filePath = $directory . '/' . $fileToDelete;
        unlink($filePath);
        echo "Deleted: " . $filePath . PHP_EOL;
    }
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
