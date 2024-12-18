<?php
// Include database configuration
include 'config.php';

// Check if the request method is POST and the necessary data is provided
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id']) && isset($_POST['status'])) {
    $user_id = $_POST['id'];
    $status = $_POST['status'];

    // Update the status in the database
    $stmt = $conn->prepare("UPDATE recycle_requests SET status = ? WHERE id = ?");
    $stmt->bind_param('si', $status, $user_id);

    if ($stmt->execute()) {
        echo 'success';
    } else {
        echo 'error';
    }

    $stmt->close();
} else {
    echo 'Invalid request';
}

$conn->close();
?>
