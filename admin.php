<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('HTTP/1.1 403 Forbidden');
    echo json_encode(['error' => 'Access Denied']);
    exit;
}

require 'dp.php'; // Use the database connection file

// Fetch reservations from the database
$sql = "SELECT name, phone, persons, date, time, message FROM reservations";
$result = $conn->query($sql);

$reservations = [];

if ($result) {
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $reservations[] = $row;
        }
    }
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to fetch reservations']);
    exit;
}

echo json_encode($reservations);
$conn->close();
exit;
?>
