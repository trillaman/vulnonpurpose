<?php
// Database configuration
$servername = "localhost"; // Database server, typically "localhost"
$username = "vulnuser"; // Your database username
$password = "password"; // Your database password
$dbname = "vulnerable_login"; // Your database name

// Create a new MySQLi connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>