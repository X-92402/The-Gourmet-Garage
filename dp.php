<?php
// dp.php: Database connection file
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gourmet_garage";
$port = 3306;  // Default MySQL port

// Establish connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
