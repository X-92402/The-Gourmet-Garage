<?php
// delete_reservation.php

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gourmet_garage"; 

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Database connection failed: " . $e->getMessage();
    exit();
}

// Get the JSON input
$input = json_decode(file_get_contents('php://input'), true);
$id = $input['id'] ?? null;

if ($id) {
    try {
        // Prepare and execute the DELETE query
        $stmt = $pdo->prepare("DELETE FROM reservations WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            echo "Reservation deleted successfully.";
        } else {
            echo "Reservation not found or already deleted.";
        }
    } catch (PDOException $e) {
        echo "Error deleting reservation: " . $e->getMessage();
    }
} else {
    echo "Invalid reservation ID.";
}
?>
