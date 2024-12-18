<?php
// Include the database configuration file to connect to the database
include 'config.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect and trim form data
    $fname = trim($_POST['fname']);
    $lname = trim($_POST['lname']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    $userrole = 'regular'; // Default role for new users

    // Check for empty fields
    if (empty($fname) || empty($lname) || empty($email) || empty($phone) || empty($password) || empty($confirm_password)) {
        die('Please fill in all required fields.');
    }

    // Validate phone number (basic validation)
    if (!preg_match('/^[0-9]{10,15}$/', $phone)) {
        die('Please enter a valid phone number (10-15 digits).');
    }

    // Check if passwords match
    if ($password !== $confirm_password) {
        die('Passwords do not match.');
    }

    // Check if email is already registered
    $stmt = $conn->prepare('SELECT email FROM user_information WHERE email = ?');
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $results = $stmt->get_result();

    if ($results->num_rows > 0) {
        echo '<script>alert("User already registered.");</script>';
        exit();
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Insert the user into the database
    $query = 'INSERT INTO user_information (first_name, last_name, email, phone, password_hash, role) VALUES (?, ?, ?, ?, ?, ?)';
    $stmt = $conn->prepare($query);

    if (!$stmt) {
        die('Error preparing statement: ' . $conn->error);
    }

    $stmt->bind_param('ssssss', $fname, $lname, $email, $phone, $hashed_password, $userrole);

    if ($stmt->execute()) {
        header('Location: login.php');
        exit();
    } else {
        die('Error inserting user: ' . $stmt->error);
    }

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
    <title>Sign Up</title>
    <link rel="stylesheet" href="../css_files/Signup.css">
</head>
<body>
    <div class="signup-container">
        <form action="signup.php" method="post" class="signup-form">
            <h4>Sign Up</h4>
            
            <label for="fname">First name:</label>
            <input type="text" id="fname" name="fname" placeholder="First name" required>
            
            <label for="lname">Last name:</label>
            <input type="text" id="lname" name="lname" placeholder="Last name" required>
            
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" placeholder="Email" required>
            
            <label for="phone">Phone Number:</label>
            <input type="tel" id="phone" name="phone" placeholder="1234567890" pattern="[0-9]{10}" required>
            <small>Format: 1234567890</small>
            
            <label for="pwd">Password:</label>
            <input type="password" id="pwd" name="password" placeholder="Password" required>
            
            <label for="Cpwd">Confirm Password:</label>
            <input type="password" id="Cpwd" name="confirm_password" placeholder="Confirm Password" required>
            
            <input type="submit" value="Create account">
        </form>
    </div>
</body>
</html>
