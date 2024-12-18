<?php
// Include database configuration
include 'php_files/config.php';

// Check if the 'approve' request is sent
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submission_id'])) {
    $submission_id = $_POST['submission_id'];
    $status = 'approved';

    // Update the status to 'approved' in the database
    $stmt = $conn->prepare("UPDATE recycle_requests SET status = ? WHERE id = ?");
    $stmt->bind_param('si', $status, $submission_id);

    if ($stmt->execute()) {
        echo '<script>alert("Submission approved successfully!");</script>';
    } else {
        echo '<script>alert("Failed to approve submission. Please try again.");</script>';
    }

    $stmt->close();
}

$conn->close();
?>
