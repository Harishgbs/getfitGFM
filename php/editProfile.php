<?php
session_start();
include './dbcon.php'; // Include your database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $phno = $_POST['phno'];
    $dob = $_POST['dob'];
    $currentweight = $_POST['currentweight'];
    $height = $_POST['height'];
    $goal_weight = $_POST['goal_weight'];

    // Handle file upload
    if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] == 0) {
        $target_dir = "../images/"; // Directory to save uploaded files
        $target_file = $target_dir . basename($_FILES["profile_pic"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if the file is an actual image
        $check = getimagesize($_FILES["profile_pic"]["tmp_name"]);
        if ($check !== false) {
            // Move the uploaded file to the target directory
            if (move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $target_file)){
                // Update the database with the new profile picture path
                $update_query = "UPDATE addition_info SET firstname=?, lastname=?, phno=?, dob=?, cur_weight=?, height=?, goal_weight=?, profile_path=? WHERE id=?";
                $stmt = $con->prepare($update_query);
                $picpath="./images/".$_FILES['profile_pic']['name'];
                $stmt->bind_param("ssssiissi", $firstname, $lastname, $phno, $dob, $currentweight, $height, $goal_weight, $picpath, $id);
                $stmt->execute();
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        } else {
            echo "File is not an image.";
        }
    } else {
        // If no file is uploaded, just update the other fields
        $update_query = "UPDATE addition_info SET firstname=?, lastname=?, phno=?, dob=?, cur_weight=?, height=?, goal_weight=? WHERE id=?";
        $stmt = $con->prepare($update_query);
        $stmt->bind_param("ssssiisi", $firstname, $lastname, $phno, $dob, $currentweight, $height, $goal_weight, $id);
        $stmt->execute();
    }

    // Redirect back to the dashboard or show a success message
    header("Location: ../dashboard.php");
    exit();
}
?>