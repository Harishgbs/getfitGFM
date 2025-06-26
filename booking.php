<?php
// Database Connection
$conn = new mysqli("localhost", "root", "", "new");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Booking logic
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $plan = $_POST['plan'];

    // Calculate the start and end date
    $start_date = date("Y-m-d");
    if ($plan == "weekly") {
        $end_date = date("Y-m-d", strtotime("+7 days"));
    } elseif ($plan == "monthly") {
        $end_date = date("Y-m-d", strtotime("+1 month"));
    } else {
        $end_date = date("Y-m-d", strtotime("+1 year"));
    }

    // Insert user data
    $stmt = $conn->prepare("INSERT INTO users (name, email, phone) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $phone);
    $stmt->execute();
    $user_id = $stmt->insert_id;
    $stmt->close();

    // Insert booking data
    $stmt = $conn->prepare("INSERT INTO bookings (user_id, start_date, end_date, plan_type) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $user_id, $start_date, $end_date, $plan);
    $stmt->execute();
    $stmt->close();

    echo "<script>alert('Booking Successful! Sessions scheduled daily until $end_date.');</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gym Booking</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            text-align: center;
        }
        .container {
            width: 40%;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px 0px gray;
            margin-top: 50px;
        }
        select, input, button {
            width: 90%;
            padding: 10px;
            margin-top: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            background: green;
            color: white;
            cursor: pointer;
        }
        button:hover {
            background: darkgreen;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Gym Booking</h2>
    <form method="POST">
        <input type="text" name="name" placeholder="Full Name" required><br>
        <input type="email" name="email" placeholder="Email" required><br>
        <input type="text" name="phone" placeholder="Phone Number" required><br>
        <select name="plan" required>
            <option value="">Select Plan</option>
            <option value="weekly">Weekly - $10</option>
            <option value="monthly">Monthly - $30</option>
            <option value="yearly">Yearly - $300</option>
        </select><br>
        <button type="submit">Book & Pay</button>
    </form>
</div>

</body>
</html>
