<?php
// Step 1: Database connection details
$host = "localhost";      // Update this with your host (e.g., '127.0.0.1' or your server's IP)
$username = "root";       // Update with your MySQL username
$password = "";           // Update with your MySQL password
$database = "gourmet_garage"; // Update with your database name

$conn = new mysqli($host, $username, $password, $database);

// Check for connection errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Step 2: Sanitize and validate POST data
$name = isset($_POST['name']) ? trim($_POST['name']) : '';
$phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
$persons = isset($_POST['persons']) ? intval($_POST['persons']) : 0;
$date = isset($_POST['date']) ? $_POST['date'] : '';
$time = isset($_POST['time']) ? $_POST['time'] : '';
$message = isset($_POST['message']) ? trim($_POST['message']) : null;

// Validate required fields
if (empty($name) || empty($phone) || $persons <= 0 || empty($date) || empty($time)) {
    echo "Please fill in all required fields.";
    exit;
}

// Step 3: Prepare and execute the SQL query
$sql = "INSERT INTO reservations (name, phone, persons, date, time, message) 
        VALUES (?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
if ($stmt === false) {
    die("Error preparing statement: " . $conn->error);
}

// Bind parameters to prevent SQL injection
$stmt->bind_param("ssisss", $name, $phone, $persons, $date, $time, $message);

// Execute the query and handle success or failure
if ($stmt->execute()) {
    echo "Reservation successfully created!";
} else {
    echo "Error: " . $stmt->error;
}

// Close the connection
$stmt->close();
$conn->close();
?>
