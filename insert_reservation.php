<?php
// Step 1: Database connection details
$host = "localhost";      
$username = "root";       
$password = "";           
$database = "gourmet_garage"; 

$conn = new mysqli($host, $username, $password, $database);

// Check for connection errors
if ($conn->connect_error) {
    die(json_encode('Connection failed: ' . $conn->connect_error));
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
    echo json_encode('Please fill in all required fields.');
    exit;
}

// Step 3: Validate reservation time against working hours
$dayOfWeek = date('w', strtotime($date)); // 0 (for Sunday) through 6 (for Saturday)
$time = strtotime($time);
$lunchStart = strtotime('10:00');
$lunchEnd = strtotime('14:30');
$dinnerStart = strtotime('17:00');
$dinnerEnd = strtotime('22:00');

// Adjust end times to be one hour before closing
$lunchEndAdjusted = strtotime('-1 hour', $lunchEnd);
$dinnerEndAdjusted = strtotime('-1 hour', $dinnerEnd);

if (($dayOfWeek >= 0 && $dayOfWeek <= 5 && ($time < $lunchStart || $time > $lunchEndAdjusted) && ($time < $dinnerStart || $time > $dinnerEndAdjusted)) ||
    ($dayOfWeek == 6 && ($time < $dinnerStart || $time > $dinnerEndAdjusted))) {
    echo json_encode('Reservation time is outside of working hours.');
    exit;
}

// Step 4: Check for existing reservations with the same date and time
$sql = "SELECT COUNT(*) FROM reservations WHERE date = ? AND time = ?";
$stmt = $conn->prepare($sql);
if ($stmt === false) {
    die(json_encode('Error preparing statement: ' . $conn->error));
}
$formattedTime = date('H:i:s', $time);
$stmt->bind_param("ss", $date, $formattedTime);
$stmt->execute();
$stmt->bind_result($count);
$stmt->fetch();
$stmt->close();

if ($count > 0) {
    echo json_encode('A reservation already exists for the selected date and time.');
    exit;
}

// Step 5: Prepare and execute the SQL query
$sql = "INSERT INTO reservations (name, phone, persons, date, time, message) 
        VALUES (?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
if ($stmt === false) {
    die(json_encode('Error preparing statement: ' . $conn->error));
}

// Bind parameters to prevent SQL injection
$formattedTime = date('H:i:s', $time);
$stmt->bind_param("ssisss", $name, $phone, $persons, $date, $formattedTime, $message);

// Execute the query and handle success or failure
if ($stmt->execute()) {
    echo json_encode('Reservation successfully created!');
} else {
    echo json_encode('Error: ' . $stmt->error);
}

// Close the connection
$stmt->close();
$conn->close();
?>
