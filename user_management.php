<?php
include 'config.php'; // Include your mysqli configuration file

// Fetch user data from the database, only for regular users
$sql = "SELECT ui.id, CONCAT(ui.first_name, ' ', ui.last_name) AS name, ui.email, ui.role, rr.pickup_time, sd.status
        FROM user_information AS ui
        LEFT JOIN recycle_requests AS rr ON ui.id = rr.id
        LEFT JOIN submissions AS sd ON ui.id = sd.user_id
        WHERE ui.role = 'regular'"; // Only fetch regular users
$result = $conn->query($sql);

if (!$result) {
    die("Error fetching user data: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management - Admin Dashboard</title>
    <link rel="stylesheet" href="../css_files/user_management.css">
    <style>
        /* General button styling */
        button {
            background-color: #4CAF50; /* Green */
            color: white;
            border: none;
            padding: 8px 12px;
            cursor: pointer;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #45a049; /* Slightly darker green */
        }
    </style>
</head>
<body>
    <header class="admin-header">
        <h1>User Management</h1>
        <img src="../image_files/userlogo.img" alt="userlogo" class="admin-logo"> 
    </header>

    <main>
        <section id="users">
            <h2>User Submissions</h2>
            <table id="userTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Pickup Time</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($user = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($user['id']) ?></td>
                            <td><?= htmlspecialchars($user['name']) ?></td>
                            <td><?= htmlspecialchars($user['email']) ?></td>
                            <td><?= htmlspecialchars($user['role']) ?></td>
                            <td><?= htmlspecialchars($user['pickup_time']) ?></td>
                            <td id="status-<?= htmlspecialchars($user['id']) ?>">
                                <?= htmlspecialchars($user['status']) ?>
                            </td>
                            <td>
                            <button class="approve-btn" id="approve-btn" data-user-id="<?= htmlspecialchars($user['id']) ?>">Approve</button>
                            <button class="reject-btn"id="reject-btn" data-user-id="<?= htmlspecialchars($user['id']) ?>">Reject</button>
                            <button class="pending-btn" id="pending-btn" data-user-id="<?= htmlspecialchars($user['id']) ?>">Pending</button>

                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </section>
    </main>
    <script type="text/javascript" src="../javascript_files/user_management.js" ></script>
</body>
</html>

<?php
$conn->close(); // Close the database connection
?>
