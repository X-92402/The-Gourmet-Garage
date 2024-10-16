<?php
require_once 'dp.php';

if ($conn->ping()) {
    echo "Connection is successful!";
} else {
    echo "Error: " . $conn->error;
}

$conn->close();
?>