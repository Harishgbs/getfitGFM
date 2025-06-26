<?php
include 'dbcon.php';
session_start();

if (!$con) {
    echo("Error connecting to the database.");
    exit; // Exit if the connection fails
}

if (isset($_POST['submit'])) {
    $id = $_SESSION['id'];
    $currentDate = date('Y-m-d'); // Get today's date

    // Check if progress for today already exists
    $sqlCheck = "SELECT * FROM progress WHERE id='$id' AND Date LIKE '$currentDate%'";
    $resCheck = mysqli_query($con, $sqlCheck);

    if (mysqli_num_rows($resCheck) > 0) {
        // Progress for today already exists
        header("Location: ../progress.php?PAE=1"); // Redirect back to progress page
        exit; // Exit to prevent further execution
    }

    // Proceed to insert the new progress
    $date = new DateTime();
    $dateString = $date->format('Y-m-d H:i:s'); 
    $weight = $_POST['weight'];
    $WorkoutDetails = $_POST['workouts'];
    $CaloriesBurned = $_POST['calories'];
    $steps = $_POST['cardio_step'] ?? 0; // Use null coalescing operator to default to 0

    $sql = "INSERT INTO progress (id, Date, weight, WorkoutDetails, CaloriesBurned, steps) VALUES ('$id', '$dateString', '$weight', '$WorkoutDetails', '$CaloriesBurned', $steps)";
    $res = mysqli_query($con, $sql);

    if (!$res) {
        echo("Error inserting progress: " . mysqli_error($con));
    } else {
        echo("Inserted successfully");
        header("Location: ../progress.php"); // Redirect after successful insertion
        exit; // Exit to prevent further execution
    }
} else {
    echo("Form submission error.");
}
?>