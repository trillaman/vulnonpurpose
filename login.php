<?php
// Database connection
$servername = "localhost";
$username = "vulnuser"; // replace with your database username
$password = "password"; // replace with your database password
$dbname = "vulnerable_login";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}



// Handle login form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Vulnerable SQL query (no parameterization)
    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo "Login successful! Welcome, " . htmlspecialchars($username) . ".";
        session_start();
        $_SESSION['username'] = $username;
        if ($row['isAdmin'] == 1) {
            $_SESSION['isAdmin'] = "true";
        }
        header("Location: index.php");
        exit();
    } else {
        $message = "Invalid username or password.<br>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7f6;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #333;
        }
        .container {
            width: 400px;
            padding: 2rem;
            background: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            text-align: center;
        }
        h2 {
            color: #4CAF50;
        }
        .form-group {
            margin: 1rem 0;
        }
        .form-control {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
        .btn {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 1rem;
            transition: background-color 0.3s;
        }
        .btn:hover {
            background-color: #45a049;
        }
        .message {
            margin-top: 1rem;
            padding: 10px;
            border-radius: 5px;
        }
        .success {
            color: #4CAF50;
            background-color: #e9f6ed;
        }
        .error {
            color: #e74c3c;
            background-color: #fce4e4;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Login</h2>
        
        <form method="post" action="">
            <div class="form-group">
                <input type="text" name="username" placeholder="Username" class="form-control" required>
            </div>
            <div class="form-group">
                <input type="password" name="password" placeholder="Password" class="form-control" required>
            </div>
            <?= $message; ?>
            <button type="submit" class="btn">Login</button>
        </form>
        
    </div>
</body>
</html>