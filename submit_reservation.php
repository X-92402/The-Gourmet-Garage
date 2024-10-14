<?php
// submit_reservation.php

require 'dp.php'; // Use the database connection file

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize form data
    $name = htmlspecialchars(trim($_POST['name']));
    $phone = htmlspecialchars(trim($_POST['phone']));
    $persons = (int)$_POST['persons'];
    $date = htmlspecialchars(trim($_POST['date']));
    $time = htmlspecialchars(trim($_POST['time']));
    $message = htmlspecialchars(trim($_POST['message']));

    // Prepare an SQL statement to insert the reservation data
    $stmt = $conn->prepare("INSERT INTO reservations (name, phone, persons, date, time, message) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssisss", $name, $phone, $persons, $date, $time, $message);

    // Execute the statement and check if it was successful
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to submit reservation']);
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
} else {
    // If the request method is not POST, return a 405 Method Not Allowed response
    header('HTTP/1.1 405 Method Not Allowed');
    echo json_encode(['error' => 'Method Not Allowed']);
}
?>