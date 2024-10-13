<?php
session_start(); // Start a session

// Dummy credentials for example purposes
$valid_username = 'techblitz';
$valid_password = 'techblitz';

// Process the login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($username === $valid_username && $password === $valid_password) {
        // Set session variables
        $_SESSION['logged_in'] = true;
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
    exit;
}
