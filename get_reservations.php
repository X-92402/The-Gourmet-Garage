<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection parameters
$servername = "localhost";
$username = "root"; // Default XAMPP username
$password = ""; // Default XAMPP password
$dbname = "gourmet_garage"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(['error' => 'Connection failed: ' . $conn->connect_error]));
}

// Check if an ID is provided in the GET request
if (isset($_GET['id']) && !empty($_GET['id'])) {
    // Fetch a specific reservation by ID
    $reservationId = $_GET['id'];

    // Prepare and bind the query to fetch a specific reservation
    $stmt = $conn->prepare("SELECT id, name, phone, persons, date, time, message, created_at FROM reservations WHERE id = ?");
    if (!$stmt) {
        die(json_encode(['error' => 'SQL Error: ' . $conn->error]));
    }

    $stmt->bind_param("i", $reservationId); // "i" means the parameter is an integer
    $stmt->execute();
    $result = $stmt->get_result();

    $reservation = $result->fetch_assoc();

    if ($reservation) {
        // Return the reservation data in JSON format
        echo json_encode($reservation);
    } else {
        echo json_encode(['error' => 'Reservation not found.']);
    }

    $stmt->close();
} else {
    // Fetch all reservations from the table if no specific ID is provided
    $sql = "SELECT id, name, phone, persons, date, time, message, created_at FROM reservations ORDER BY created_at DESC";
    $result = $conn->query($sql);

    $reservations = [];

    if ($result->num_rows > 0) {
        // Output each row of the table
        while ($row = $result->fetch_assoc()) {
            $reservations[] = $row;
        }
    }

    // Return all reservations in JSON format
    echo json_encode($reservations);
}

$conn->close();
?>
