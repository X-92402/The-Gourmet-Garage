<?php
// admin.php
require_once 'db.php';

if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header("Location: admin_login.php");
    exit;
}

$stmt = $conn->prepare("SELECT * FROM reservations");
$stmt->execute();
$result = $stmt->get_result();
$reservations = array();

while ($row = $result->fetch_assoc()) {
    $reservations[] = $row;
}

echo json_encode($reservations);
?>