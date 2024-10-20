<?php
// submit_reservation.php

// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gourmet_garage";

// Establish connection
$conn = new mysqli($servername, $username, $password, $dbname, $port);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize form data
    $name = htmlspecialchars(trim($_POST['name']));
    $phone = htmlspecialchars(trim($_POST['phone']));
    $persons = filter_var($_POST['persons'], FILTER_VALIDATE_INT);
    $date = htmlspecialchars(trim($_POST['date']));
    $time = htmlspecialchars(trim($_POST['time']));
    $message = htmlspecialchars(trim($_POST['message']));

    // Validate required fields
    if ($name && $phone && $persons && $date && $time) {
        // Prepare an SQL statement to insert the reservation data
        $stmt = $conn->prepare("INSERT INTO reservations (name, phone, persons, date, time, message) VALUES (?, ?, ?, ?, ?, ?)");
        if ($stmt === false) {
            http_response_code(500); // Internal Server Error
            echo json_encode(['success' => false, 'error' => 'Prepare statement failed: ' . $conn->error]);
            exit;
        }

        $stmt->bind_param("ssisss", $name, $phone, $persons, $date, $time, $message);

        // Execute the statement and check for errors
        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            http_response_code(500); // Internal Server Error
            echo json_encode(['success' => false, 'error' => 'Execute failed: ' . $stmt->error]);
        }

        // Close the statement and connection
        $stmt->close();
    } else {
        http_response_code(400); // Bad Request
        echo json_encode(['success' => false, 'error' => 'Invalid input data']);
    }

    $conn->close();
} else {
    http_response_code(405); // Method Not Allowed
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
}
?>