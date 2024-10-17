<?php
// fetch_reservations.php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gourmet_garage";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch reservations from the database
$sql = "SELECT * FROM view_reservations";
$result = $conn->query($sql);

$reservations = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $reservations[] = $row;
    }
}

// Close the connection
$conn->close();

header('Content-Type: application/json');
echo json_encode($reservations);
?>
