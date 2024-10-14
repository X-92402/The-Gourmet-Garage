<?php
// get_reservations.php

// Database connection details
$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$dbname = "gourmet_garage";

// Create a connection to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check for connection errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all reservations from the 'reservations' table, including the message
$sql = "SELECT name, phone, persons, date, time, message FROM reservations";
$result = $conn->query($sql);

// Prepare an array to hold the fetched reservation data
$reservations = [];

if ($result->num_rows > 0) {
    // Fetch each row and add it to the reservations array
    while ($row = $result->fetch_assoc()) {
        $reservations[] = $row;
    }
}

// Return the reservations data as JSON
header('Content-Type: application/json');
echo json_encode($reservations);

// Close the database connection
$conn->close();
?>
