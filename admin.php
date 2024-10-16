<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: admin_login.html');
    exit;
}

// Include database connection
require 'dp.php';

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
