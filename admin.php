<?php
// Start the session
session_start();

// Database connection
$servername = "localhost";
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

    // If fetching all reservations
    if (isset($_GET['all'])) {
        $sql = "SELECT id, name, phone, persons, date, time, message, created_at FROM reservations ORDER BY date, time";
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

    // If fetching a specific reservation by ID
    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $reservationId = $_GET['id'];
        $stmt = $conn->prepare("SELECT id, name, phone, persons, date, time, message FROM reservations WHERE id = ?");
        if (!$stmt) {
            die(json_encode(['error' => 'SQL Error: ' . $conn->error]));
        }

        $stmt->bind_param("i", $reservationId);
        $stmt->execute();
        $result = $stmt->get_result();
        $reservation = $result->fetch_assoc();

        if ($reservation) {
            echo json_encode($reservation);
        } else {
            echo json_encode(['error' => 'Reservation not found.']);
        }

        $stmt->close();
        exit;
    }
}

// Handle POST request to submit a new reservation (no login required)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if required data is provided
    if (empty($_POST)) {
        die("No form data received.");
    }

    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $persons = $_POST['persons'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $message = $_POST['message'];

    $stmt = $conn->prepare("INSERT INTO reservations (name, phone, persons, date, time, message, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");
    $stmt->bind_param("ssisss", $name, $phone, $persons, $date, $time, $message);

    if ($stmt->execute()) {
        echo "Reservation added successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }

    header('Content-Type: application/json');
    echo json_encode(['status' => 'success']);
    exit;
}
?>
