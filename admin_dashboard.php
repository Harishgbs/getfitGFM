<?php
session_start();
include './php/dbcon.php'; // Include your database connection

// Check if the user is logged in as admin
if (!isset($_SESSION['admin'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}

// Fetch total sessions and registered users from the database
$total_sessions = 0; // Replace with actual logic to fetch total sessions
$total_users = 0; // Replace with actual logic to fetch total registered users

// Example queries (you need to replace these with your actual queries)
$sessions_query = "SELECT COUNT(*) as total FROM sessions"; // Example query for total sessions
$users_query = "SELECT COUNT(*) as total FROM addition_info"; // Example query for total registered users

// Execute the queries
$sessions_result = mysqli_query($con, $sessions_query);
$users_result = mysqli_query($con, $users_query);

// Fetch the results
if ($sessions_result) {
    $total_sessions = mysqli_fetch_assoc($sessions_result)['total'];
}

if ($users_result) {
    $total_users = mysqli_fetch_assoc($users_result)['total'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background-image: url('./images/admin-bg.png'); /* Add your background image */
            background-size: cover;
            background-repeat: no-repeat;
            height: 100vh;
            color: white;
        }
        .logout-btn {
            position: absolute;
            top: 20px;
            right: 20px;
        }
        .card {
            background-color: rgba(0, 0, 0, 0.7);
        }
        .main-heading {
            margin-bottom: 30px;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <div class="logout-btn">
            <a href="index.html" class="btn btn-danger">Logout</a>
        </div>

        <h2 class="text-center main-heading">Admin Dashboard</h2>

        <div class="row justify-content-center">
            <div class="col-md-4 mt-5">
                <div class="card text-center text-light p-3">
                    <h4>Total Sessions</h4>
                    <p><?php echo $total_sessions; ?></p>
                    <a href="manage_sessions.php" class="btn btn-primary">Manage Sessions</a>
                </div>
            </div>

            <div class="col-md-4 mt-5">
                <div class="card text-center text-light p-3">
                    <h4>Registered Users</h4>
                    <p><?php echo $total_users; ?></p>
                    <a href="manage_users.php" class="btn btn-primary">Manage Users</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>