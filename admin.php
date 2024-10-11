<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header("Location: admin_login.php");
    exit;
}
?>
<h2>Admin Dashboard</h2>
<p>Welcome, Admin! You are logged in.</p>
<a href="admin_logout.php">Logout</a>
