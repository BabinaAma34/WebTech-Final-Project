<?php
// Include the configuration file to connect to the database
include 'config.php';

// Start the session to store user information once logged in
session_start();

// Check if the form was submitted using the POST method
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve and trim form data
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Check if email or password fields are empty
    if (empty($email) || empty($password)) {
        die('Please fill in all required fields.'); // Stop execution if any field is empty
    }

    // Prepare a statement to retrieve user data from the database based on email
    $stmt = $conn->prepare('SELECT id, email, password_hash, role FROM user_information WHERE email = ?');
    $stmt->bind_param('s', $email); // Bind the email parameter to the query
    $stmt->execute(); // Execute the query
    $results = $stmt->get_result(); // Get the result of the query

    // Check if a matching user is found
    if ($results->num_rows > 0) {
        // Fetch the user data from the result set
        $row = $results->fetch_assoc();
        $user_id = $row['id'];
        $user_email = $row['email'];
        $user_password = $row['password_hash']; // Note: the column in the table is 'password_hash', not 'password'
        $user_role = $row['role'];

        // Verify the password entered by the user matches the hashed password in the database
        if (password_verify($password, $user_password)) {
            // Store user information in session variables for later access
            $_SESSION['id'] = $user_id;
            $_SESSION['role'] = $user_role;
            $_SESSION['username'] = $user_email;

            // Redirect the user based on their role
            if ($user_role == 'admin') {
                header('Location: user_management.php');
            } elseif ($user_role == 'superuser') {
                header('Location: user_management.php');
            } elseif ($user_role == 'regular') {
                header('Location: ../');
            } else {
                header('Location: login.php'); // Redirect back to login if role is unknown
            }
            exit(); // End script after redirect
        } else {
            echo '<script>alert("Incorrect password.");</script>';
        }
    } else {
        // Show an alert if the user is not registered
        echo '<script>alert("User not registered.");</script>';
    }

    // Close the statement after execution
    $stmt->close();
}

// Close the database connection at the end
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../css_files/Login.css">
</head>
<body>
    <div class="login-container">
        <form action="login.php" method="POST" class="login-form">
            <label for="email"><b>Email: </b></label><br>
            <input type="text" id="email" name="email" placeholder="Enter email" required><br>

            <label for="pwd"><b>Password:</b></label><br>
            <input type="password" id="pwd" name="password" placeholder="Password" required><br><br>

            <input type="checkbox" id="remember" name="remember" value="Remember me">
            <label for="remember"><b>Remember me</b></label><br><br>
            <p>Forgot password? <a href="forgot_password.php">Reset Password</a></p>
            <input type="submit" value="Login"><br><br>

            <p>Don't have an account? <a href="signup.php">Sign Up</a></p>
           
        </form>
    </div>
    <script src="../javaScript_files/login.js"></script>
</body>
</html>