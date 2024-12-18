<?php
// Start the session to access user data
session_start();

// Include database connection file
include 'config.php';

// Fetch the logged-in user's ID from the session
$userId = $_SESSION['id'] ?? 1; // Replace 1 with the default or test user ID

// Fetch the user's complete details from the database
$queryUser = "SELECT first_name, last_name, email, phone FROM user_information WHERE id = ?";
$stmt = $conn->prepare($queryUser);
$stmt->bind_param("i", $userId);
$stmt->execute();
$resultUser = $stmt->get_result();

// Fetch user details or set default values
if ($resultUser && $resultUser->num_rows > 0) {
    $userData = $resultUser->fetch_assoc();
    $userName = $userData['first_name'] . ' ' . $userData['last_name'];
} else {
    $userData = [
        'first_name' => 'User',
        'last_name' => '',
        'email' => 'N/A',
        'phone' => 'N/A'
    ];
    $userName = "User";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="../css_files/user_profile.css">
</head>
<body>
    <div class="container">
        <!-- Sidebar -->
        <nav class="sidebar">
            <div class="profile-logo">
                <div class="circle-logo">
                    <img src="../image_files/userlogo.img" alt="User Logo">
                </div>
                <h3 class="username"><?php echo htmlspecialchars($userName); ?></h3>
            </div>
            <ul>
                <li><a href="landing_page1.php">Home</a></li>
                <li><a href="recycle.php">Recycle</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>

        <!-- Profile Content -->
        <main class="profile-content">
            <header class="profile-header">
                <h1>Welcome, <?php echo htmlspecialchars($userName); ?>!</h1>
            </header>

            <!-- User Details Section -->
            <section class="user-details">
                <h2>My Profile</h2>
                <div class="details">
                    <p><strong>First Name:</strong> <?php echo htmlspecialchars($userData['first_name']); ?></p>
                    <p><strong>Last Name:</strong> <?php echo htmlspecialchars($userData['last_name']); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($userData['email']); ?></p>
                    <p><strong>Phone Number:</strong> <?php echo htmlspecialchars($userData['phone']); ?></p>
                </div>
            </section>
        </main>
    </div>
</body>
</html>
