<?php
// modify_reservation.php

// Database connection
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


// Get the JSON input
$input = json_decode(file_get_contents('php://input'), true);

// Extract fields
$id = $input['id'] ?? null;
$name = $input['name'] ?? null;
$phone = $input['phone'] ?? null;
$persons = $input['persons'] ?? null;
$date = $input['date'] ?? null;
$time = $input['time'] ?? null;
$message = $input['message'] ?? null;

// Validate input
if ($id && $name && $phone && $persons && $date && $time) {
    try {
        // Prepare and execute the UPDATE query
        $stmt = $pdo->prepare("UPDATE reservations SET 
            name = :name, 
            phone = :phone, 
            persons = :persons, 
            date = :date, 
            time = :time, 
            message = :message
            WHERE id = :id");

        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':phone', $phone, PDO::PARAM_STR);
        $stmt->bindParam(':persons', $persons, PDO::PARAM_INT);
        $stmt->bindParam(':date', $date, PDO::PARAM_STR);
        $stmt->bindParam(':time', $time, PDO::PARAM_STR);
        $stmt->bindParam(':message', $message, PDO::PARAM_STR);

        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            echo "Reservation updated successfully.";
        } else {
            echo "No changes were made to the reservation.";
        }
    } catch (PDOException $e) {
        echo "Error updating reservation: " . $e->getMessage();
    }
} else {
    echo "Invalid input. All fields are required.";
}
?>
