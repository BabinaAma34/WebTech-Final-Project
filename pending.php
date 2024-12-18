<?php
// Include database configuration
include 'config.php';

// Check if the 'pending' request is sent
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submission_id'])) {
    $submission_id = $_POST['submission_id'];
    $status = 'pending';

    // Update the status to 'pending' in the database
    $stmt = $conn->prepare("UPDATE recycle_requests SET status = ? WHERE id = ?");
    $stmt->bind_param('si', $status, $submission_id);

    if ($stmt->execute()) {
        echo '<script>alert("Submission marked as pending successfully!");</script>';
    } else {
        echo '<script>alert("Failed to mark submission as pending. Please try again.");</script>';
    }

    $stmt->close();
}

$conn->close();
?>
