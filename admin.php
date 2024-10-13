<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('HTTP/1.1 403 Forbidden');
    echo json_encode(['error' => 'Access Denied']);
    exit;
}

// Sample data for reservations (normally from a database)
$reservations = [
    ['name' => 'John Doe', 'date' => '2024-10-13', 'time' => '18:00', 'persons' => 4],
    ['name' => 'Jane Smith', 'date' => '2024-10-14', 'time' => '19:30', 'persons' => 2],
    // Add more reservations here or fetch from DB
];

echo json_encode($reservations);
exit;
