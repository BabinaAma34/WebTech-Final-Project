<?php
// Database connection configuration
include 'config.php';

// Check connection
if ($conn->connect_error) {
    $response = [
        'status' => 'error',
        'message' => 'Database connection failed'
    ];
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

// Check if it's a POST request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate input
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $company = filter_input(INPUT_POST, 'company', FILTER_SANITIZE_STRING);
    $phone = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $pickup_location = filter_input(INPUT_POST, 'pickup_location', FILTER_SANITIZE_STRING);
    $pickup_time = $_POST['pickup_time'];  // Retrieve pickup time from the form
    $message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING);

    // Validate required fields
    if (empty($name) || empty($phone) || empty($email) || empty($pickup_location) || empty($pickup_time)) {
        $response = [
            'status' => 'error',
            'message' => 'Please fill in all required fields'
        ];
        header('Content-Type: application/json');
        echo json_encode($response);
        $conn->close();
        exit;
    }

    // Prepare SQL statement
    $sql = "INSERT INTO recycle_requests (name, company, phone, email, pickup_location, pickup_time, message) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";

    // Prepare and bind
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssss", $name, $company, $phone, $email, $pickup_location, $pickup_time, $message);

    // Execute the statement
    if ($stmt->execute()) {
        $response = [
            'status' => 'success',
            'message' => 'Request submitted successfully'
        ];
    } else {
        $response = [
            'status' => 'error',
            'message' => 'Failed to submit request'
        ];
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();

    // Send JSON response
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Waste Pickup Form</title>
  <link rel="stylesheet" href="../css_files/recycle.css">
</head>
<body>
  <div class="navbar">
    <h1>POLY</h1>
  </div>

  <div class="form-container">
    <h2>Schedule your waste pickup with Poly</h2>
    <form id="waste-pickup-form" action="" method="POST">
      <input type="text" placeholder="Name" name="name" required>
      <input type="text" placeholder="Company" name="company">
      <input type="tel" placeholder="Phone" name="phone" required>
      <input type="email" placeholder="Email" name="email" required>
      <input type="text" placeholder="Pick Up Location" name="pickup_location" required>
      
      <label for="pickup-time">Available Time for Pickup:</label>
      <input type="datetime-local" id="pickup-time" name="pickup_time" required>
      
      <textarea placeholder="Message" name="message"></textarea>
      
      <button type="submit">Schedule Now</button>
    </form>
  </div>

  <script src="../javaScript_files/recycle.js"></script>
</body>
</html>
