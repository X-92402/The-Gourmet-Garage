<?php
// admin.php: Handle form submission from index.html

// Initialize error variable
$error = '';

try {
    // Check if the form has been submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Get the form data
        $name = $_POST['name'];
        $phone = $_POST['phone'];
        $persons = $_POST['persons'];
        $date = $_POST['date'];
        $time = $_POST['time'];
        $message = $_POST['message'];

        // Validate the form data (basic example)
        if (empty($name) || empty($phone) || empty($persons) || empty($date) || empty($time) || empty($message)) {
            throw new Exception("Please fill in all fields.");
        }

        // Validate phone number and persons as needed
        if (!preg_match("/^[0-9]{10}$/", $phone)) { // Example validation for phone number
            throw new Exception("Invalid phone number format.");
        }

        // Connect to the database (using the dp.php file)
        require_once 'dp.php';

        // Prepare the SQL query to insert the form data into the database
        $stmt = $conn->prepare("INSERT INTO reservations (name, phone, persons, date, time, message) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssisss", $name, $phone, $persons, $date, $time, $message); // Assuming persons is an integer

        // Execute the query
        if ($stmt->execute()) {
            // Form data inserted successfully
            header("Location: index.html?success=1");
            exit; // Ensure this exit follows any logic that needs to run
        } else {
            throw new Exception("Error inserting data: " . $stmt->error);
        }
    }
} catch (Exception $e) {
    // Handle exceptions and set the error message
    $error = $e->getMessage();
    error_log($error); // Log the error
    header("Location: index.html?error=" . urlencode($error));
    exit;
} finally {
    // Close the database connection
    if (isset($stmt) && $stmt) {
        $stmt->close();
    }
    if (isset($conn) && $conn) {
        $conn->close();
    }
}
?>
