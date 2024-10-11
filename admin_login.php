<?php
// admin_login.php: Admin login page
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Dummy check for credentials (replace with actual database check)
    if ($username === 'admin' && $password === 'password') {
        $_SESSION['loggedin'] = true;
        header("Location: admin_dashboard.php");
        exit;
    } else {
        $error = "Invalid Credentials";
    }
}