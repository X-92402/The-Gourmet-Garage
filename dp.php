<?php
// dp.php: Database connection file
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gourmet_garage";

// Establish connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Example of a secure query using prepared statements
$stmt = $conn->prepare("SELECT * FROM reservations WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

// Close the connection
$stmt->close();
$conn->close();
?>
