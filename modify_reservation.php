<?php
$host = "localhost";
$username = "root";
$password = "";
$database = "gourmet_garage";

// Sanitize inputs
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id']) && is_numeric($_POST['id'])) {
    $reservationId = $_POST['id'];
    $name = htmlspecialchars(trim($_POST['name']));
    $phone = htmlspecialchars(trim($_POST['phone']));
    $persons = intval($_POST['persons']);
    $date = $_POST['date'];
    $time = $_POST['time'];
    $message = htmlspecialchars(trim($_POST['message']));

    // Validate required fields
    if (empty($name) || empty($phone) || $persons <= 0 || empty($date) || empty($time)) {
        echo "Please fill in all required fields.";
        exit;
    }

    // Validate reservation time against working hours
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
        echo "Reservation time is outside of working hours.";
        exit;
    }

    // Check for existing reservations with the same date and time, excluding the current reservation
    try {
        $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "SELECT COUNT(*) FROM reservations WHERE date = ? AND time = ? AND id != ?";
        $stmt = $pdo->prepare($sql);
        $formattedTime = date('H:i:s', $time);
        $stmt->execute([$date, $formattedTime, $reservationId]);
        $count = $stmt->fetchColumn();

        if ($count > 0) {
            echo "A reservation already exists for the selected date and time.";
            exit;
        }

        // Update the reservation
        $sql = "UPDATE reservations SET name = ?, phone = ?, persons = ?, date = ?, time = ?, message = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$name, $phone, $persons, $date, $formattedTime, $message, $reservationId]);
        echo "Reservation updated successfully.";
    } catch (PDOException $e) {
        echo "Failed to update reservation: " . $e->getMessage();
    }
} else {
    echo "Invalid input.";
}
?>
