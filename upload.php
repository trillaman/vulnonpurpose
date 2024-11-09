<?php

include 'db_connection.php';
session_start();
$target_dir = "uploads/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;

// Check the file extension strictly based on the last characters
$allowed_extensions = ["jpg", "png", "gif"];
$file_extension = strtolower(substr($target_file, -3)); // Get last 3 characters

if (!in_array($file_extension, $allowed_extensions)) {
    $message = "<div class='message error'>Sorry, only JPG, PNG & GIF files are allowed.</div>";
    $uploadOk = 0;
    echo $message;
}

// Attempt file upload if checks passed
if ($uploadOk == 1) {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], rtrim($target_file, '\s'))) {
        
        $fileName = basename($_FILES["fileToUpload"]["name"]);
        $fileSize = $_FILES["fileToUpload"]["size"];
        $username = $_SESSION['username'];
        $stmt = $conn->prepare("INSERT INTO files (fileName, fileSize, uploadedBy) VALUES (?, ?, ?)");
        $stmt->bind_param("sis", $fileName, $fileSize, $username);
        if ($stmt->execute()) {
            $message = "<div class='message success'>The file " . htmlspecialchars(basename($_FILES["fileToUpload"]["name"])) . " has been uploaded successfully.</div>";
        } else {
            $message = "<div class='message error'>Sorry, there was an error uploading your file.</div>";
        }
    } 
    echo $message;
}
?>
