<?php
// admin_logout.php
session_start();

// Destroy all sessions
session_unset();
session_destroy();

// Redirect to login page
header("Location: admin_login.php");
exit;
?>