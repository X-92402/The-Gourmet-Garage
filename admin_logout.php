<?php
// admin_logout.php: Admin logout script
session_start();
session_destroy();
header("Location: admin_login.php");
exit;
?>
