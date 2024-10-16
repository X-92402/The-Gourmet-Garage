<?php
session_start(); // Start a session

// Dummy credentials for example purposes
$valid_username = 'techblitz';
$valid_password_hash = password_hash('techblitz', PASSWORD_DEFAULT); // Hash the password

// Process the login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Validate credentials
    if ($username === $valid_username && password_verify($password, $valid_password_hash)) {
        $_SESSION['logged_in'] = true;
        echo json_encode(['success' => true]);
    } else {
        http_response_code(401); // Unauthorized
        echo json_encode(['success' => false, 'error' => 'Invalid username or password']);
    }
} else {
    http_response_code(405); // Method Not Allowed
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
}
?>
