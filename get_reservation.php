<?php
// get_reservations.php

require 'dp.php'; // Use the database connection file

// Fetch all reservations from the 'reservations' table, including the message
$sql = "SELECT name, phone, persons, date, time, message FROM reservations";
$result = $conn->query($sql);

// Prepare an array to hold the fetched reservation data
$reservations = [];

if ($result) {
    if ($result->num_rows > 0) {
        // Fetch each row and add it to the reservations array
        while ($row = $result->fetch_assoc()) {
            $reservations[] = $row;
        }
    }
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to fetch reservations']);
    exit;
}

// Return the reservations data as JSON
header('Content-Type: application/json');
echo json_encode($reservations);

// Close the database connection
$conn->close();
?>
