<?php
include './dbcon.php'; // Include your database connection file
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['profileImage'])) {
    $userId = $_SESSION['id']; // Assuming you have the user ID in the session
    $targetDir = "../images/"; // Directory where images will be uploaded
    $targetFile = $targetDir . basename($_FILES["profileImage"]["name"]);
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Check if the uploaded file is an image
    $check = getimagesize($_FILES["profileImage"]["tmp_name"]);
    if ($check === false) {
        die("File is not an image.");
    }

    // Move the uploaded file to the target directory
    if (move_uploaded_file($_FILES["profileImage"]["tmp_name"], $targetFile)) {
        // Update the database with the new image path
        $targetFile="./images/".$_FILES["profileImage"]["name"];
        $sql = "UPDATE addition_info SET profile_path = '$targetFile' WHERE id = '$userId'";
        if (mysqli_query($con, $sql)) {
            $_SESSION['profile_path'] = $targetFile; // Update session variable
            header("Location: ../dashboard.php"); // Redirect back to the dashboard
            exit();
        } else {
            die("Error updating record: " . mysqli_error($con));
        }
    } else {
        die("Sorry, there was an error uploading your file.");
    }
}
?>