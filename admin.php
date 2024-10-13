<?php
// admin.php: Handle form submission from the reservation form (index.html)

// Initialize an error variable to store any errors
$error = '';

try {
    // Check if the form has been submitted via POST
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Get the form data from the POST request
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

        // Validate phone number (adjust as necessary based on your needs, e.g., for international numbers)
        if (!preg_match("/^[0-9]{10}$/", $phone)) {
            throw new Exception("Invalid phone number format. It should be a 10-digit number.");
        }

        // Validate the 'persons' field to ensure it's a positive integer
        if (!filter_var($persons, FILTER_VALIDATE_INT, ["options" => ["min_range" => 1]])) {
            throw new Exception("Invalid number of persons. Please select a valid number.");
        }

        // You can optionally add date and time validation here, if needed
        // For example, check if the date is in the future and the time is valid

        // Connect to the database (ensure dp.php contains a proper connection)
        require_once 'dp.php'; // dp.php should contain your $conn (mysqli connection object)

        // Check if the connection to the database is successful
        if (!$conn) {
            throw new Exception("Database connection failed: " . mysqli_connect_error());
        }

        // Prepare the SQL query to insert the form data into the database
        $stmt = $conn->prepare("INSERT INTO reservations (name, phone, persons, date, time, message) VALUES (?, ?, ?, ?, ?, ?)");

        // Check if the statement was prepared successfully
        if (!$stmt) {
            throw new Exception("Preparation of SQL query failed: " . $conn->error);
        }

        // Bind parameters to the SQL query (name: string, phone: string, persons: integer, date: string, time: string, message: string)
        $stmt->bind_param("ssisss", $name, $phone, $persons, $date, $time, $message);

        // Execute the query and check if it was successful
        if ($stmt->execute()) {
            // If the form data was inserted successfully, redirect to index.html with a success message
            header("Location: index.html?success=1");
            exit; // Always exit after a header redirect to stop further script execution
        } else {
            // If the query failed, throw an exception with the error
            throw new Exception("Error inserting data: " . $stmt->error);
        }
    }
} catch (Exception $e) {
    // Catch any exceptions that occur during the process
    $error = $e->getMessage(); // Store the error message
    error_log($error); // Log the error for debugging purposes (optional)
    
    // Redirect back to the form (index.html) with the error message as a URL parameter
    header("Location: index.html?error=" . urlencode($error));
    exit;
} finally {
    // Close the prepared statement and database connection if they were used
    if (isset($stmt) && $stmt) {
        $stmt->close();
    }
    if (isset($conn) && $conn) {
        $conn->close();
    }
}
?>
