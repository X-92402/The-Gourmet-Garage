<?php
$host = "localhost";
$username = "root";
$password = "";
$database = "gourmet_garage";

// Sanitize inputs
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id']) && is_numeric($_POST['id'])) {
    $reservationId = $_POST['id'];
    $name = htmlspecialchars($_POST['name']);
    $phone = htmlspecialchars($_POST['phone']);
    $persons = $_POST['persons'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $message = htmlspecialchars($_POST['message']);

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $pdo->prepare("UPDATE reservations SET name = ?, phone = ?, persons = ?, date = ?, time = ?, message = ? WHERE id = ?");
        $stmt->execute([$name, $phone, $persons, $date, $time, $message, $reservationId]);
        echo "Reservation updated successfully.";
    } catch (PDOException $e) {
        echo "Failed to update reservation: " . $e->getMessage();
    }
} else {
    echo "Invalid input.";
}
