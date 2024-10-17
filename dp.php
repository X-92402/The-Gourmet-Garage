<?php
// dp.php: Database connection file
$servername = "localhost";
$username = "root";
$password = "your_password";
$dbname = "gourmet_garage";
$port = 3306;  // Default MySQL port

// Establish connection
$conn = new mysqli($servername, $username, $password, $dbname, $port);

// Check connection
if ($conn->connect_error) {
    error_log("Connection failed: " . $conn->connect_error);
    die("Connection failed: " . $conn->connect_error);
}

// Set the charset to utf8mb4 for better security and compatibility
$conn->set_charset("utf8mb4");
?>
