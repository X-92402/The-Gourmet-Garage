<?php
// Start the session
session_start();

// Database connection file
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

// Check if admin is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: admin_login.html');
    exit;
}

// Fetch reservations from the database
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
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
    exit;
}
?>
