<?php
// Define server details needed to connect to the database
$servername = 'localhost'; // The server where the database is hosted
$username = 'babina.abban';  //    // The username to access the database
$password = 'Trick20$';      //pass      
$dbname = 'webtech_fall2024_babina_abban'; 

// Attempt to connect to the database using the provided details
$conn = mysqli_connect($servername, $username, $password, $dbname) 
    or die('Unable to connect'); // If the connection fails, display an error message and stop execution

// Check if the connection has an error and handle it
if ($conn->connect_error) {
    die('Connection failed'); // If there’s an error, stop execution and show 'Connection failed'
} else {
    // Connection was successful, so no further action is needed';
    // echo 'success';
}
?>