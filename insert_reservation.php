<?php
// Database connection parameters
$servername = "localhost"; // or "127.0.0.1"
$username = "root"; // Default username for XAMPP
$password = ""; // Default password for XAMPP (empty)
$dbname = "gourmet_garage"; // Database name

// Create a new MySQLi connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Only proceed if this is a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $persons = $_POST['persons'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $message = $_POST['message'];

    // Validate required fields
    if (empty($name) || empty($phone) || empty($persons) || empty($date) || empty($time)) {
        die("All fields except message are required.");
    }

    // Prepare and execute the SQL statement to insert the reservation
    $stmt = $conn->prepare("INSERT INTO reservations (name, phone, persons, date, time, message, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");
    $stmt->bind_param("ssisss", $name, $phone, $persons, $date, $time, $message);

    if ($stmt->execute()) {
        // Success: redirect to success page or display a message
        echo "Reservation added successfully!";
        // Redirect to a thank you page (optional)
        // header("Location: thank_you.html");
    } else {
        // Error: failed to insert
        echo "Error inserting reservation: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
