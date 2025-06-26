<?php
session_start();
include './php/dbcon.php'; // Include your database connection

if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: ./login.php");
    exit();
}

if (!isset($_SESSION['username'])) {
    header("Location: ./login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Gym Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        .profile-pic {
            width: 70px;
            height: 70px;
            cursor: pointer;
            border: 2px solid white;
        }

        .card-custom {
            background-color: rgba(0, 0, 0, 0.6);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            color: white;
        }

        body {
            background-image: url('./images/dashboardbg.jpeg');
            background-size: cover;
            background-repeat: no-repeat;
            height: 100vh;
        }

        .modal-backdrop {
            display: none;
        }
    </style>
</head>

<?php
$id = $_SESSION['id'];
$username = $_SESSION['username'];
$user = $_SESSION['user'];
$email = $_SESSION['email'];

// Fetch additional information
$sql = "SELECT * FROM addition_info WHERE id = '$id'";
$res = mysqli_query($con, $sql);
if(mysqli_num_rows($res)<=0){
    echo '<div class="container1 position-absolute top-0 start-0 z-3 w-100 bg-light border rounded p-2">
                <div class="container">
                    <h2 class="text-center">Personal Details</h2>
                </div>
                <form action="./php/additionalInfo.php" method="post" class="was-validated" id="additionalInfoForm">
                    <h2 class="text-white d-flex justify-content-center rounded-bottom">Additiona Information</h2>
                    <div class="mb-3">
                        <label for="firstname" class="form-label">First name</label>
                        <input type="text" class="form-control" name="firstname" id="firstname"
                            placeholder="Enter First name" required>
                    </div>
                    <div class="mb-3">
                        <label for="lastname" class="form-label">Last name</label>
                        <input type="text" class="form-control" name="lastname" id="lastname" placeholder="Enter Last name"
                            required>
                    </div>
                    <div class="mb-3">
                        <label for="phno" class="form-label">Phone number</label>
                        <input type="number" class="form-control" name="phno" id="phno" placeholder="Enter phone number"
                            required>
                    </div>
                    <div class="mb-3">
                        <label for="dob" class="form-label">Date of birth</label>
                        <input type="date" class="form-control" name="dob" id="dob" placeholder="Enter your Date of Birth"
                            required>
                    </div>
                    <div class="mb-3">
                        <label for="currentweight" class="form-label">Current weight</label>
                        <input type="number" class="form-control" name="currentweight" id="currentweight"
                            placeholder="Enter your current weight" required>
                    </div>
                    <div class="mb-3">
                        <label for="height" class="form-label">Height :</label>
                        <input type="number" class="form-control" name="height" id="height"
                            placeholder="Enter your height" required>
                    </div>
                    <div class="mb-3">
                        <label for="GoalWeight" class="form-label">Goal Weight :</label>
                        <input type="number" class="form-control" name="GoalWeight" id="GoalWeight"
                            placeholder="Enter your goal weight " required>
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">Free Time</label>
                        <select class="form-select form-select-lg" name="free_time" id="free_time" required>
                            <option selected>Select one</option>
                            <option value="1">Morning</option>
                            <option value="2">Evening</option>
                        </select>
                    </div>
                    <div class="d-flex justify-content-center">
                        <button class="btn btn-success" name="submit">Submit</button>
                    </div>
                </form>
            </div>';
}
else{
$row = mysqli_fetch_assoc($res);
// Check if the user has already booked a session today
$last_booking_date = $row['last_booking_date'];
$current_date = date('Y-m-d');

if ($last_booking_date == $current_date) {
    $session_message = "You have already booked a session today. Please try again tomorrow.";
} else {
    // Proceed with booking logic
    // Check if payment is completed (you can implement your payment logic here)
    $payment_status = true; // Replace with actual payment check

    if ($payment_status) {
        // Fetch user's free time preference
        $free_time = $row['free_time'];

        // Define time slots
        $morning_time_slot = '06:00:00'; // Example morning time
        $evening_time_slot = '17:30:00'; // Example evening time

        // Fetch sessions based on free time
        if ($free_time == 1) {
            // User prefers morning sessions
            $session_query = "SELECT * FROM sessions WHERE time >= '$morning_time_slot' AND alloted_participants < max_participants ORDER BY time ASC";
        } else {
            // User prefers evening sessions
            $session_query = "SELECT * FROM sessions WHERE time >= '$evening_time_slot' AND alloted_participants < max_participants ORDER BY time ASC";
        }

        $session_result = mysqli_query($con, $session_query);
        // Allocate the first available session to the user if any
        if (mysqli_num_rows($session_result) > 0) {
            $session = mysqli_fetch_assoc($session_result);

            // Update the alloted participants
            $new_alloted_count = $session['alloted_participants'] + 1;
            $session_id = $session['id'];

            // Update the session in the database
            $update_query = "UPDATE sessions SET alloted_participants = ? WHERE id = ?";
            $stmt = $con->prepare($update_query);
            if ($stmt) {
                $stmt->bind_param("is", $new_alloted_count, $session_id);
                $stmt->execute();
            } else {
                echo "Error preparing statement: " . mysqli_error($con);
            }

            // Store the allocated session in the user's profile
            $user_session_query = "INSERT INTO user_sessions (user_id, user_type, session_id) VALUES (?, ?, ?)";
            $user_stmt = $con->prepare($user_session_query);
            if ($user_stmt) {
                $user_type = $_SESSION['user']; // 'student' or 'faculty'
                $user_stmt->bind_param("ssi", $id, $user_type, $session_id);
                $user_stmt->execute();

                // Update the last booking date
                $update_booking_date_query = "UPDATE addition_info SET last_booking_date = ? WHERE id = ?";
                $update_stmt = $con->prepare($update_booking_date_query);
                if ($update_stmt) {
                    $update_stmt->bind_param("ss", $current_date, $id);
                    $update_stmt->execute();
                } else {
                    echo "Error updating booking date: " . mysqli_error($con);
                }
            } else {
                echo "Error preparing user session statement: " . mysqli_error($con);
            }
        } else {
            $session_message = "No available sessions for your preferred time.";
        }
    } else {
        $session_message = "Please complete the payment to book a session.";
    }
}

// Fetch user's allocated sessions
$user_session_query = "select * from user_sessions where user_id=?";
$user_stmt = $con->prepare($user_session_query);
if ($user_stmt) {
    $user_stmt->bind_param("s", $id);
    $user_stmt->execute();
    $user_session_result = $user_stmt->get_result();
} else {
    echo "Error preparing user session fetch statement: " . mysqli_error($con);
}
?>


<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark position-sticky top-0 z-3">
        <div class="container main-content">
            <img src="<?php echo $row['profile_path']; ?>" class="img-fluid rounded-circle profile-pic ms-3 p-1 mx-3" alt="Profile" data-bs-toggle="modal" data-bs-target="#profileModal">
            <a class="navbar-brand fs-2" href="#">GetFit</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="workout.php">Workouts</a></li>
                    <li class="nav-item"><a class="nav-link" href="progress.php">Progress</a></li>
                    <li class="nav-item"><a class="nav-link" href="bookings.php">Bookings</a></li>
                    <li class="nav-item"><a class="nav-link" href="diet.php">Diet plans</a></li>
                    <li class="nav-item"><a class="nav-link" href="about1.php">About</a></li>
                </ul>
            </div>
        </div>
    </nav>
        <?php
        $phno=$_SESSION['phno']=$row['phno'];
        $date_of_birth=$_SESSION['dob']=$row['dob'];
        $cur_weight=$_SESSION['cur_weight']=$row['cur_weight'];
        $height=$_SESSION['height']=$row['height'];
        $GWieght=$_SESSION['goal_weight']=$row['goal_weight'];
        $dob = new DateTime($date_of_birth);
        $today = new DateTime(); // Get the current date
        $age = $_SESSION['age']=$today->diff($dob)->y;
        $profilPicPath=$_SESSION['profile_path']=$row['profile_path'];
        ?>
    <div class="container mt-5 h-75">
        <h2 class="text-center text-light">Welcome, <span id="userName"><?php echo $username; ?></span>!</h2>
        <div class="row mt-4">
            <div class="col-md-4">
                <div class="card card-custom p-3">
                    <h5 class="text-light">Personal Details</h5>
                    <p><strong>Email:</strong> <span id="userEmail"><?php echo $email; ?></span></p>
                    <p><strong>Last Booking:</strong> <span id="lastBooking"><?php echo $last_booking_date ? $last_booking_date : "No bookings yet"; ?></span></p>
                    <p><strong>Session Message:</strong> <span id="sessionMessage"><?php echo isset($session_message) ? $session_message : ""; ?></span></p>
                    <button class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#editProfileModal">Edit Profile</button>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card card-custom p-3">
                    <h5 class="text-light">Fitness Statistics</h5>
                    <p><strong>BMI:</strong> <span id="userBMI"><?php echo number_format($row['cur_weight'] / (($row['height'] * 0.3048) ** 2), 2); ?></span></p>
                    <?php
                    $progress_query="select * from progress where id = ? and date = ? ";
                    $progress_stmt=$con->prepare($progress_query);
                    if($progress_stmt){
                    $cd=date("y-m-d");
                    $progress_stmt->bind_param("ss",$id,$cd);
                    $progress_stmt->execute();
                    $progress_res=$progress_stmt->get_result();
                    if($progress_res->num_rows > 0){
                        $progress_row=$progress_res->fetch_assoc();
                       echo "<p><strong>Calories Burned:".$progress_row['CaloriesBurned']."</strong> </p>";
                        echo "<p><strong>Steps Taken:".$progress_row['steps']."</strong> steps</p>";
                    }
                    else{
                        echo "Update todays progress";
                    }
                }else{
                    echo mysqli_error($con);

                }
                    ?>

                </div>
            </div>

            <div class="col-md-4">
                <div class="card card-custom p-3">
                    <h5 class="text-light">Upcoming Workouts</h5>
                    <?php
                    // Check if the user has any allocated sessions
                    if ($user_session_result->num_rows > 0) {
                        $session_row = $user_session_result->fetch_assoc();
                        $sstimequery = "select * from sessions where id = '$session_row[id]'";
                        $sstime_stmt = $con->prepare($sstimequery);
                        if ($sstime_stmt) {
                            $sstime_stmt->execute();
                            $sstimeresult = $sstime_stmt->get_result();
                            if ($sstimeresult->num_rows>0){
                                $sstimerow=$sstimeresult->fetch_assoc();
                        echo "<p><strong>Session Time:</strong> " . $sstimerow['time'] . " <br>Allocated on: " . $session_row['created_at'] . "</p>";
                            }
                            else{
                            echo "Error preparing  session time fetch statement: " . mysqli_error($con);

                            }
                        } else {
                            echo "Error preparing user session fetch statement: " . mysqli_error($con);
                        }
                    } else {
                        echo "<p>No upcoming sessions allocated.</p>";
                    }
                }
                    ?>
                </div>
            </div>
        </div>

    </div>

    <!-- Profile Modal -->

    <div class="modal fade" id="profileModal" tabindex="-1" aria-labelledby="profileModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="profileModalLabel">Profile Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img src="<?php echo $row['profile_path']; ?>" class="img-fluid mb-3" width="100" height="100" alt="Profile">
                    <h5 id="modalUser Name"><?php echo $username; ?></h5>
                    <p class="text-muted" id="modalUser Email"><?php echo $email; ?></p>
                    <div class="text-start px-4">
                        <p><strong>Username:</strong> <span id="modalUser Username"><?php echo $username; ?></span></p>
                        <p><strong>Date of Birth:</strong> <span id="modalUser DOB"><?php echo $row['dob']; ?></span></p>
                        <p><strong>Phone Number:</strong> <span id="modalUser Phone"><?php echo $row['phno']; ?></span></p>
                        <p><strong>Height:</strong> <span id="modalUser Height"><?php echo $row['height']; ?></span></p>
                        <p><strong>Weight:</strong> <span id="modalUser Weight"><?php echo $row['cur_weight']; ?> kg</span></p>
                    </div>
                    <form action="" method="post">
                        <button class="btn btn-outline-danger mt-3 w-100" name="logout">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

   <!-- Edit Profile Modal -->
<div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editProfileModalLabel">Edit Profile Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editProfileForm" action="./php/editProfile.php" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                    <div class="mb-3">
                        <label for="editFirstname" class="form-label">First Name</label>
                        <input type="text" class="form-control" name="firstname" id="editFirstname" value="<?php echo $row['firstname']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="editLastname" class="form-label">Last Name</label>
                        <input type="text" class="form-control" name="lastname" id="editLastname" value="<?php echo $row['lastname']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="editPhno" class="form-label">Phone Number</label>
                        <input type="text" class="form-control" name="phno" id="editPhno" value="<?php echo $row['phno']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="editDob" class="form-label">Date of Birth</label>
                        <input type="date" class="form-control" name="dob" id="editDob" value="<?php echo $row['dob']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="editCurrentWeight" class="form-label">Current Weight</label>
                        <input type="number" class="form-control" name="currentweight" id="editCurrentWeight" value="<?php echo $row['cur_weight']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="editHeight" class="form-label">Height</label>
                        <input type="number" class="form-control" name="height" id="editHeight" value="<?php echo $row['height']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="editGoalWeight" class="form-label">Goal Weight</label>
                        <input type="number" class="form-control" name="goal_weight" id="editGoalWeight" value="<?php echo $row['goal_weight']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="editProfilePic" class="form-label">Profile Picture</label>
                        <input type="file" class="form-control" name="profile_pic" id="editProfilePic" accept="image/*">
                    </div>
                    <div class="d-flex justify-content-center">
                        <button type="submit" class="btn btn-success">Update Profile</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

    <footer class="bg-dark text-white text-center p-3 w-100 position-relative bottom-0">
        <p>&copy; 2025 GETFIT Gym. All Rights Reserved</p>
        <p>Contact us: Email✉: contact@getfitgym.com | Phone📞: +91 9876543210 | Address: JNTUA College of Engineering, Pulivendula</p>
    </footer>
</body>

</html>