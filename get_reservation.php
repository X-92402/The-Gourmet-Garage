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

// Handle GET requests (fetch reservation data)
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
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
}

// Handle POST requests (submit reservation)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Assuming form data is submitted via POST
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $persons = $_POST['persons'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $message = $_POST['message'];

    if (empty($name) || empty($phone) || empty($persons) || empty($date) || empty($time)) {
        echo json_encode(['error' => 'All fields are required.']);
    } else {
        // Insert reservation into database
        $stmt = $conn->prepare("INSERT INTO reservations (name, phone, persons, date, time, message) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssisss", $name, $phone, $persons, $date, $time, $message);
        if ($stmt->execute()) {
            echo json_encode(['success' => 'Reservation successfully submitted.']);
        } else {
            echo json_encode(['error' => 'Failed to submit reservation.']);
        }
        $stmt->close();
    }
}

$conn->close();
?>
