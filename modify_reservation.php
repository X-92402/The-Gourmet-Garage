<?php
// Database connection (adjust your credentials accordingly)
$host = "localhost";      
$username = "root";       
$password = "";           
$database = "gourmet_garage"; 

// Check if form data is posted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reservationId = $_POST['id'];
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $persons = $_POST['persons'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $message = $_POST['message'];

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Query to update the reservation
        $stmt = $pdo->prepare("UPDATE reservations SET name = ?, phone = ?, persons = ?, date = ?, time = ?, message = ? WHERE id = ?");
        $stmt->execute([$name, $phone, $persons, $date, $time, $message, $reservationId]);

        echo "Reservation updated successfully.";
    } catch (PDOException $e) {
        echo "Failed to update reservation: " . $e->getMessage();
    }
} else {
    echo "Invalid request method.";
}
?>
