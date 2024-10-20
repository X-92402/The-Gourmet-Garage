<?php
// Database connection parameters
$servername = "localhost";
$username = "root"; // Default XAMPP username
$password = ""; // Default XAMPP password
$dbname = "gourmet_garage"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch reservations from the 'reservations' table
$sql = "SELECT name, phone, persons, date, time, message, created_at FROM reservations";
$result = $conn->query($sql);

$reservations = array();

if ($result->num_rows > 0) {
    // Output each row of the table
    while ($row = $result->fetch_assoc()) {
        $reservations[] = $row;
    }
} 

// Return the result in JSON format
header('Content-Type: application/json');
echo json_encode($reservations);

$conn->close();
?>
