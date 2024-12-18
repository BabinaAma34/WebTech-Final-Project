<?php
session_start();
include 'config.php'; // Include your database connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $token = $_POST['token'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Check if passwords match
    if ($new_password !== $confirm_password) {
        echo "<script>alert('Passwords do not match.'); window.history.back();</script>";
        exit();
    }

    // Check if token is valid (you can also add expiration logic here)
    $stmt = $conn->prepare("SELECT email FROM password_resets WHERE token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        $email = $row['email'];

        // Hash the new password
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        // Update the password in the users table
        $update_stmt = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
        $update_stmt->bind_param("ss", $hashed_password, $email);

        if ($update_stmt->execute()) {
            // Delete the token from the password_resets table
            $delete_stmt = $conn->prepare("DELETE FROM password_resets WHERE token = ?");
            $delete_stmt->bind_param("s", $token);
            $delete_stmt->execute();

            // Redirect to login with a success message
            $_SESSION['success_message'] = "Password changed successfully. You can now log in.";
            header("Location:./login.php");
            //exit();
        } else {
            echo "<script>alert('Error updating password. Please try again later.'); window.history.back();</script>";
            exit();
        }
    } else {
        echo "<script>alert('Invalid or expired token.'); window.history.back();</script>";
        exit();
    }
} else {
    // If GET request, show the reset password form
    $token = htmlspecialchars($_GET['token'] ?? '');
}
?>

