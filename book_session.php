<?php
// book_session.php
header('Content-Type: application/json');

// Database connection
$servername = "localhost"; // Change as needed
$username = "root"; // Change as needed
$password = ""; // Change as needed
$dbname = "gym"; // Change as needed

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(['status' => 'error', 'message' => 'Database connection failed: ' . $conn->connect_error]));
}

// Get the JSON input
$data = json_decode(file_get_contents("php://input"), true);

$email = $conn->real_escape_string($data['email']);
$plan = $conn->real_escape_string($data['plan']);
$amount = $conn->real_escape_string($data['amount']);
$date = $conn->real_escape_string($data['date']);

// Insert booking into the database
$sql = "INSERT INTO bookings (user_email, plan, date, created_at) VALUES ('$email', '$plan', '$date', NOW())";

if ($conn->query($sql) === TRUE) {
    echo json_encode(['status' => 'success', 'message' => 'Booking successful!']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Error: ' . $conn->error]);
}

$conn->close();
?>