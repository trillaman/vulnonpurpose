<?php
include 'db_connection.php';
session_start();

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['commentText'], $_POST['file_id'])) {
    $commentText = trim($_POST['commentText']);
    $file_id = (int) $_POST['file_id']; // Ensure file_id is an integer
    $commenter = $_SESSION['username']; // Default to 'guest' if not logged in

    // Validate inputs
    if (!empty($commentText) && $file_id > 0) {
        // Prepare an SQL statement to insert the comment
        $stmt = $conn->prepare("INSERT INTO comments (file_id, commentText, commenter) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $file_id, $commentText, $commenter);
        
        if ($stmt->execute()) {
            // Redirect back to the file's page with success message
            header("Location: index.php");
        } else {
            // Redirect back with an error message
            header("Location: index.php");
        }

        $stmt->close();
    } else {
        // Redirect back with an error message if input is invalid
        header("Location: index.php");
    }
} else {
    // Redirect back if the request method is not POST
    header("Location: index.php");
}
exit();
?>