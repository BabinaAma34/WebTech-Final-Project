
<?php
ob_start();
// Include database configuration
include 'config.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $new_password = trim($_POST['new_password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo '<script>alert("Invalid email format.");</script>';
    } elseif ($new_password !== $confirm_password) {
        echo '<script>alert("Passwords do not match.");</script>';
    } elseif (strlen($new_password) < 6) {
        echo '<script>alert("Password must be at least 6 characters long.");</script>';
    } else {
        // Check if the email exists in the database
        $stmt = $conn->prepare('SELECT id FROM user_information WHERE email = ?');
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            // Email not found
            echo '<script>alert("No user found with that email.");</script>';
        } else {
            // Get the user ID
            $user = $result->fetch_assoc();
            $user_id = $user['id'];

            // Hash the new password
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

            // Update the password in the database
            $stmt = $conn->prepare('UPDATE user_information SET password_hash = ? WHERE id = ?');

            $stmt->bind_param('si', $hashed_password, $user_id);

            if ($stmt->execute()) {
                echo '<script>alert("Your password has been updated successfully.");</script>';

            } else {
                echo '<script>alert("An error occurred while updating your password. Please try again.");</script>';
            }
            header("Location:login.php");
            exit();
        }
        $stmt->close();

    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="../css_files/forgot_password.css">
</head>
<body>
    <div class="reset-password-container">
        <form action="forgot_password.php" method="POST" class="reset-password-form">
            <h2>Reset Password</h2>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" placeholder="Enter your email" required>

            <label for="new_password">New Password:</label>
            <input type="password" id="new_password" name="new_password" placeholder="Enter new password" required>

            <label for="confirm_password">Confirm Password:</label>
            <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm new password" required>

            <input type="submit" value="Reset Password">
        </form>
    </div>
</body>
</html>
