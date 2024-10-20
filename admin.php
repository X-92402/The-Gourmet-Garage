<?php
// Start the session
session_start();

// Database connection
$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "gourmet_garage"; 

// Establish connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle GET request to fetch reservations (requires admin login)
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Check if admin is logged in
    if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
        header('Location: admin_login.html');
        exit;
    }

    $sql = "SELECT name, phone, persons, date, time, message, created_at FROM reservations ORDER BY date, time";
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

// Handle POST request to submit a new reservation (no login required)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $persons = $_POST['persons'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $message = $_POST['message'];

    $stmt = $conn->prepare("INSERT INTO reservations (name, phone, persons, date, time, message, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");
    $stmt->bind_param("ssisss", $name, $phone, $persons, $date, $time, $message);

    $response = [];
    if ($stmt->execute()) {
        $response['success'] = true;
    } else {
        $response['success'] = false;
        $response['error'] = $stmt->error;
    }

    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}
?>
