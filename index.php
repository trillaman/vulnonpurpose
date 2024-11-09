<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SecureShare - Upload Your Files</title>
    <style>
        /* General Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f4f7f6;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #333;
        }

        /* Container Styling */
        .container {
            width: 1200px;
            padding: 2rem;
            margin-top: 4rem;
            background: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            text-align: center;
        }

        /* Header Styling */
        .header {
            font-size: 24px;
            margin-bottom: 2rem;
            margin-top: 2rem;
            color: #4CAF50;
            font-weight: bold;
        }

        /* Upload Form Styling */
        .upload-form {
            margin-top: 20px;
        }

        .file-input {
            margin-top: 1rem;
            display: block;
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }

        .upload-btn {
            margin-top: 1.5rem;
            background-color: #4CAF50;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        .upload-btn:hover {
            background-color: #45a049;
        }

        /* Footer Styling */
        .footer {
            margin-top: 2rem;
            font-size: 14px;
            color: #777;
        }

        .footer a {
            color: #4CAF50;
            text-decoration: none;
        }
        /* Container for file gallery */
    .file-gallery {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        justify-content: center;
        padding: 20px;
    }

    /* Styling each file card */
    .file-card {
        width: 600px;
        padding: 15px;
        background-color: #f9f9f9;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
        text-align: center;
    }

    /* Header styling for file name */
    .file-card h3 {
        font-size: 18px;
        color: #333;
        margin-bottom: 10px;
    }

    /* Text styling for file details */
    .file-card p {
        font-size: 14px;
        color: #555;
    }

    /* Optional: Hover effect */
    .file-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
    }

    .comments-section {
        margin-top: 20px;
        padding: 15px;
        background-color: #f9f9f9;
        border-radius: 8px;
    }

    /* Styling each comment */
    .comment {
        margin-bottom: 15px;
        padding: 10px;
        border-bottom: 1px solid #ddd;
    }

    /* Commenter's name and comment text */
    .comment p {
        margin: 0;
        color: #333;
    }

    /* Date styling for comments */
    .comment-date {
        font-size: 12px;
        color: #777;
    }

    /* Styling add-comment form */
    .add-comment {
        margin-top: 20px;
    }

    .add-comment textarea {
        width: 100%;
        padding: 10px;
        border-radius: 4px;
        border: 1px solid #ccc;
        resize: vertical;
    }

    .add-comment button {
        margin-top: 10px;
        padding: 10px 20px;
        background-color: #007bff;
        color: #fff;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    .add-comment button:hover {
        background-color: #0056b3;
    }
    </style>
</head>

<body>
    <?php 
    session_start(); 
    include('db_connection.php');
    ?>



    <div class="container">
        <div class="header">SecureShare</div>
        <p>Share your files easily and securely.</p>
        
        <br/>
        <?php if(isset($_SESSION['username'])): ?>
        Welcome,
        <?= $_SESSION['username'] ?>!
        
        <a href="my_files.php">My Files</a> |
        <a href="logout.php">Logout</a>
        <form action="upload.php" method="post" enctype="multipart/form-data" class="upload-form">
            <label for="fileToUpload" class="file-label">Select file to upload:</label>
            <input type="file" name="fileToUpload" id="fileToUpload" class="file-input">
            <button type="submit" name="submit" class="upload-btn">Upload File</button>
        </form>
        <?php else: ?>
        Please log in to view and upload your files.<br/>
        <a href="login.php">Login</a> |
        <a href="register.php">Register</a>
        
        <?php endif; ?>
        <?php if (isset($_SESSION['isAdmin']) == "true"): ?>
            <?php
                $query = "SELECT id, fileName, fileSize, uploadDate, uploadedBy FROM files";
                $result = $conn->query($query);

                if ($result->num_rows > 0) {
                    echo '<div class="file-gallery">';
                    while ($row = $result->fetch_assoc()) {
                            echo '<div class="file-card">';
                            echo '<h3>' . htmlspecialchars($row['fileName']) . '</h3>';
                            echo '<p><strong>Size:</strong> ' . number_format($row['fileSize'] / 1024, 2) . ' KB</p>';
                            echo '<p><strong>Uploaded on:</strong> ' . date("F j, Y", strtotime($row['uploadDate'])) . '</p>';
                            echo '<p><strong>Uploaded by:</strong> ' . htmlspecialchars($row['uploadedBy']) . '</p>';
                            echo "<p><strong>Link to:</strong><a href='/uploads/" . $row['fileName'] . "'>Download</a>";

                            
                            echo '<div class="comments-section">';
                            echo '<h2>Comments</h2>';
                            $query_comments = "SELECT commentText, commenter, commentDate FROM comments WHERE file_id = " . ($row['id']) . " ORDER BY commentDate DESC";
                            $comments = $conn->query($query_comments);
                            if ($comments->num_rows > 0) {
                                while ($comment = $comments->fetch_assoc()) {
                                    echo '<div class="comment">';
                                    echo '<p><strong>' . htmlspecialchars($comment['commenter']) . '</strong><br>' . $comment['commentText'] . '</p>';
                                    echo '<p class="comment-date">' . date("F j, Y, g:i a", strtotime($comment['commentDate'])) . '</p>';
                                    echo '</div>';
                                }
                            } else {
                                echo '<p>No comments yet. Be the first to comment!</p>';
                            }
                            echo "</div>";
                            echo '<div class="add-comment">';
                            
                            echo '<h3>Leave a Comment</h3>';
                            echo '<form method="POST" action="add_comment.php">';
                            echo '<textarea name="commentText" required placeholder="Write your comment here..."></textarea>';
                            echo '<input type="hidden" name="file_id" value="' . ($row['id']) . '">'; // Pass the file ID for the comment
                            echo '<button type="submit">Submit</button>';
                            echo '</form>';
                            echo '</div>';
                            echo "</div>";
                    }
                    echo '</div>';
                }
                else {
                    echo "<br/>No files found.";
                }
                ?>

        <?php else: ?>
            
        <?php
            //$conn = new mysqli($servername, $username, $password, $dbname);
            $query = "SELECT * FROM files WHERE uploadedBy = '" . $_SESSION['username']. "'";
            $result = $conn->query($query);

            if ($result->num_rows > 0) {
                echo '<div class="file-gallery">';
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="file-card">';
                    echo '<h3>' . htmlspecialchars($row['fileName']) . '</h3>';
                    echo '<p><strong>Size:</strong> ' . number_format($row['fileSize'] / 1024, 2) . ' KB</p>';
                    echo '<p><strong>Uploaded on:</strong> ' . date("F j, Y", strtotime($row['uploadDate'])) . '</p>';
                    echo '<p><strong>Uploaded by:</strong> ' . htmlspecialchars($row['uploadedBy']) . '</p>';
                    echo "<p><strong>Link to:</strong><a href='/uploads/" . $row['fileName'] . "'>Download</a>";
                    echo '<div class="comments-section">';
                    echo '<h2>Comments</h2>';
                    $query_comments = "SELECT commentText, commenter, commentDate FROM comments WHERE file_id = " . ($row['id']) . " ORDER BY commentDate DESC";
                    $comments = $conn->query($query_comments);
                    if ($comments->num_rows > 0) {
                        while ($comment = $comments->fetch_assoc()) {
                            echo '<div class="comment">';
                            echo '<p><strong>' . htmlspecialchars($comment['commenter']) . '</strong><br>' . $comment['commentText'] . '</p>';
                            echo '<p class="comment-date">' . date("F j, Y, g:i a", strtotime($comment['commentDate'])) . '</p>';
                            echo '</div>';
                        }
                    } else {
                        echo '<p>No comments yet. Be the first to comment!</p>';
                    }
                    echo "</div>";
                    echo '<div class="add-comment">';
                    
                    echo '<h3>Leave a Comment</h3>';
                    echo '<form method="POST" action="add_comment.php">';
                    echo '<textarea name="commentText" required placeholder="Write your comment here..."></textarea>';
                    echo '<input type="hidden" name="file_id" value="' . ($row['id']) . '">'; // Pass the file ID for the comment
                    echo '<button type="submit">Submit</button>';
                    echo '</form>';
                    echo '</div>';
                    echo "</div>";
            }
            echo '</div>';
            } else {
                if(isset($_SESSION['username'])){
                echo "<br/>No files found.";
                }
            }
            ?>
        <?php endif; ?>
        <div class="footer">
            <p>Need help? <a href="#">Contact Support</a></p>
        </div>
    </div>
</body>

</html>