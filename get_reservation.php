<?php
// get_reservations.php

require 'dp.php'; // Use the database connection file

// Fetch reservations from the database
$sql = "SELECT * FROM reservations ORDER BY date, time";
$result = $conn->query($sql);

$reservations = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $reservations[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode($reservations);

// Close the connection
$conn->close();
?>
