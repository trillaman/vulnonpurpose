<?php

function userHasAccessToFile($userId, $fileId) {
    // Fetch file details from the database based on $fileId
    $file = getFileById($fileId);  // This function is not provided here

    // Check if the file is public or belongs to the user with $userId
    return ($file['is_public'] == 1 || $file['user_id'] == $userId);
}


function fetchFilesByUser($userId) {
    // Connect to the database and execute SQL query to fetch files owned by $userId
    $conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

    $stmt = $conn->prepare("SELECT id, filename FROM files WHERE user_id = ?");
    $stmt->bind_param('i', $userId);
    $stmt->execute();

    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}


function getFileById($fileId) {
    // Connect to the database and execute SQL query to fetch file details based on $fileId
    $conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

    $stmt = $conn->prepare("SELECT * FROM files WHERE id = ?");
    $stmt->bind_param('i', $fileId);
    $stmt->execute();

    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

function getFilePathById($fileId) {
    // Fetch file details and return the file path
    $file = getFileById($fileId);  // This function is not provided here
    return 'uploads/' . $file['filename'];
}

?>