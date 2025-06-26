<?php
include "./php/dbcon.php";
session_start();
$id = $_SESSION['id'];
$sql = "SELECT * FROM progress WHERE id='$id'";
$res = mysqli_query($con, $sql);
echo(mysqli_error($con));
$username = $_SESSION['username'];
$phno = $_SESSION['phno'];
$date_of_birth = $_SESSION['dob'];
$cur_weight = $_SESSION['cur_weight'];
$height = $_SESSION['height'];
$email = $_SESSION['email'];
$profilPicPath = $_SESSION['profile_path'] ?? './images/default-profile.png'; // Default profile picture
if(isset($_GET['PAE'])){
    echo "<script>alert('You have already submitted your progress for today.');</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GetFit - Fitness Progress</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <style>
        .profile-pic {
            width: 70px;
            height: 70px;
            cursor: pointer;
            border: 2px solid white;
        }
        #updateprogress {
            display: none;
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
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark position-sticky top-0">
    <div class="container z-3">
        <div class="modal fade" id="profileModal" tabindex="-1" aria-labelledby="profileModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-dark" id="profileModalLabel">Profile Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center">
                        <div class="position-relative d-inline-block">
                            <img src="<?=$profilPicPath?>" id="modalProfilePic" class="img-fluid mb-3" width="100" height="100" alt="Profile">
                            <span class="edit-icon position-absolute top-50 start-50 translate-middle bg-light rounded-circle p-2 d-none">
                                <i class="fas fa-pencil-alt text-dark"></i>
                            </span>
                        </div>
                        <h5 id="modalUser Name"><?=$username?></h5>
                        <p class="text-muted" id="modalUser Email"><?=$email ?></p>
                        <div class="text-start px-4 text-dark">
                            <p><strong>Username:</strong> <span id="modalUser Username"><?=$username ?></span></p>
                            <p><strong>Date of Birth:</strong> <span id="modalUser DOB"><?=$date_of_birth?></span></p>
                            <p><strong>Phone Number:</strong> <span id="modalUser Phone"><?=$phno ?></span></p>
                            <p><strong>Height:</strong> <span id="modalUser Height"><?=$height ?></span></p>
                            <p><strong>Weight:</strong> <span id="modalUser Weight"><?=$cur_weight ?> kg</span></p>
                        </div> 
                        <form action="" method="post">
                            <button class="btn btn-outline-danger mt-3 w-100" name="logout">Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <img src="<?=$profilPicPath?>" class="img-fluid rounded-circle profile-pic ms-3 p-1 mx-3" alt="Profile" data-bs-toggle="modal" data-bs-target="#profileModal">
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

<input name="" id="update-progress-but" class="btn btn-primary m-2 position-fixed end-0" type="button" value="Update progress" onclick="document.getElementById('updateprogress').classList.toggle('d-block');let a = document.getElementById('update-progress-but'); a.value= a.value=='Update progress'?'close':'Update progress';"/>

<div class="container mt-4 bg-dark text-light p-2">
    <div class="container mt-2 me-5" id="updateprogress">
        <h4>Update Your Fitness Progress</h4>
        <form id="fitnessForm" method="post" action="./php/progressInsert.php">
            <div class="mb-3">
                <label class="form-label">Workout Details</label>
                <div id="workout-list">
                    <div class="form-check">
                        <input class="form-check-input workout-checkbox" name="Abs_c" type="checkbox" id="Abs" value="5">
                        <label class="form-check-label" for="Abs">Abs </label>
                        <input type="number" id="AbsCount" name="Abs" class="form-control workout-input" placeholder="Sets/Reps" min="0" style="display: none;">
                    </div>
                    <div class="form-check">
                        <input class="form-check-input workout-checkbox" name="Triceps_c" type="checkbox" id="Triceps" value="6">
                        <label class="form-check-label" for="Triceps">Triceps </label>
                        <input type="number" id="TricepsCount" name="Triceps" class="form-control workout-input" placeholder="Sets/Reps" min="0" style="display: none;">
                    </div>
                    <div class="form-check">
                        <input class="form-check-input workout-checkbox" name="Leg_c" type="checkbox" id="Leg" value="8">
                        <label class="form-check-label" for="Leg">Leg </label>
                        <input type="number" id="LegCount" name="Leg" class="form-control workout-input" placeholder="Sets/Reps" min="0" style="display: none;">
                    </div>
                    <div class="form-check">
                        <input class="form-check-input workout-checkbox" name="Biceps_c" type="checkbox" id="Biceps" value="7">
                        <label class="form-check-label" for="Biceps">Biceps </label>
                        <input type="number" id="BicepsCount" name="Biceps" class="form-control workout-input" placeholder="Sets/Reps" min="0" style="display: none;">
                    </div> 
                    <div class="form-check">
                        <input class="form-check-input workout-checkbox" name="push_ups_c" type="checkbox" id="push-ups" value="9">
                        <label class="form-check-label" for="push-ups ">Pushups</label>
                        <input type="number" id="push-upsCount" name="push_ups" class="form-control workout-input" placeholder="Sets/Reps" min="0" style="display: none;">
                    </div> 
                    <div class="form-check">
                        <input class="form-check-input workout-checkbox" name="planks_c" type="checkbox" id="planks" value="10">
                        <label class="form-check-label" for="planks">Planks </label>
                        <input type="number" id="planksCount" name="planks" class="form-control workout-input" placeholder="Sets/Reps" min="0" style="display: none;">
                    </div> 
                    <div class="form-check">
                        <input class="form-check-input workout-checkbox" name="squats_c" type="checkbox" id="squats" value="10">
                        <label class="form-check-label" for="squats">Squats </label>
                        <input type="number" id="squatsCount" name="squats" class="form-control workout-input" placeholder="Sets/Reps" min="0" style="display: none;">
                    </div> 
                    <div class="form-check">
                        <input class="form-check-input workout-checkbox" name="Deadlifts_c" type="checkbox" id="Deadlifts" value="10">
                        <label class="form-check-label" for="Deadlifts">Deadlifts </label>
                        <input type="number" id="DeadliftsCount" name="Deadlifts" class="form-control workout-input" placeholder="Sets/Reps" min="0" style="display: none;">
                    </div> 
                    <div class="form-check">
                        <input class="form-check-input workout-checkbox" name="Benchpress_c" type="checkbox" id="Benchpress" value="15">
                        <label class="form-check-label" for="Benchpress">Benchpress </label>
                        <input type="number" id="BenchpressCount" name="Benchpress" class="form-control workout-input" placeholder="Sets/Reps" min="0" style="display: none;">
                    </div> 
                    <div class="form-check">
                        <input class="form-check-input workout-checkbox" name="Jumpsquats_c" type="checkbox" id="Jumpsquats" value="11">
                        <label class="form-check-label" for="Jumpsquats">Jumpsquats </label>
                        <input type="number" id="JumpsquatsCount" name="Jumpsquats" class="form-control workout-input" placeholder="Sets/Reps" min="0" style="display: none;">
                    </div> 
                    <div class="form-check">
                        <input class="form-check-input workout-checkbox" name="Sidesquats_c" type="checkbox" id="Sidesquats" value="10">
                        <label class="form-check-label" for="Sidesquats">Sidesquats</label>
                        <input type="number" id="SidesquatsCount" name="Sidesquats" class="form-control workout-input" placeholder="Sets/Reps" min="0" style="display: none;">
                    </div> 
                    <div class="form-check">
                        <input class="form-check-input workout-checkbox" name="Burpees_c" type="checkbox" id="Burpees" value="17">
                        <label class="form-check-label" for="Burpees">Burpees </label>
                        <input type="number" id="BurpeesCount" name="Burpees" class="form-control workout-input" placeholder="Sets/Reps" min="0" style="display: none;">
                    </div> 
                    <div class="form-check">
                        <input class="form-check-input workout-checkbox" name="cardio_step_c" type="checkbox" id="cardio_step"value="0.04">
                        <label class="form-check-label" for="cardio_step">Cardio</label>
                        <input type="number" id="cardio_step_input" name="cardio_step" class="form-control workout-input" placeholder="Enter no.of steps" min="0" style="display: none;">
                    </div> 
                </div>
            </div>
            <div class="mb-3">
                <label for="weight" class="form-label">Enter today weight</label>
                <input type="text" class="form-control" name="weight" id="weight" placeholder="Weight (In kgs)">
            </div>
            <div class="mb- 3 d-none">
                <input type="text" class="form-control" name="workouts" id="workouts" readonly>
            </div>
            <div class="mb-3">
                <label for="calories" class="form-label">Total Calories Burned</label>
                <input type="text" name="calories" class="form-control" id="calories" readonly>
            </div>
            <button type="submit" name="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>

    <h4 class="mt-4">Your Fitness Progress</h4>
        <div class="container table-responsive m-1 w-100">
        <table class="table table-bordered">
        <thead>
            <tr>
                <th>Date</th>
                <th>Weight</th>
                <th>BMI</th>
                <th>Workout Details</th>
                <th>Steps</th>
                <th>Calories Burned</th>
            </tr>
        </thead>
        <tbody id="history">
            <?php 
                $heightInMeters = $height * 0.3048;
                while ($row = mysqli_fetch_assoc($res)) {
            ?>
            <tr>
                <td><?=$row['Date'] ?></td>
                <td><?=$row['weight'] ?></td>
                <td><?= round($cur_weight / ($heightInMeters * $heightInMeters), 2) ?></td>
                <td><?=$row['WorkoutDetails'] ?></td>
                <td><?=$row['steps'] ?></td>
                <td><?=$row['CaloriesBurned'] ?></td>
            </tr>
            <?php
                }
            ?>
        </tbody>
    </table>
        </div>
</div>
<!-- <footer class="bg-dark text-white text-center p-3 mt-3 w-100 position-absolute bottom-0 z-0">
    <p>&copy; 2025 GETFIT Gym. All Rights Reserved</p>
    <p>Contact us: Email✉: contact@getfitgym.com | Phone📞: +91 9876543210 | Address: JNTUA College of Engineering, Pulivendula</p>
</footer> -->
<script>
document.addEventListener("DOMContentLoaded", function () {
    function toggleInputVisibility() {
        document.querySelectorAll('.workout-checkbox').forEach(checkbox => {
            let relatedInput = document.getElementById(checkbox.id + "Count") || document.getElementById("cardio_step_input");
            if (checkbox.checked) {
                relatedInput.style.display = "block";
                relatedInput.required = true;
            } else {
                relatedInput.style.display = "none";
                relatedInput.value = "";
                relatedInput.required = false;
            }
            calculateCalories(); // Update calories automatically
        });
    }

    function calculateCalories() {
        let totalCalories = 0;
        let workoutDetails = []; // Array to hold workout details

        document.querySelectorAll('.workout-checkbox:checked').forEach(checkbox => {
            let relatedInput = document.getElementById(checkbox.id + "Count") || document.getElementById("cardio_step_input");
            let setsReps = parseInt(relatedInput.value) || 0; // Get the value of the input field
            totalCalories += setsReps * parseInt(checkbox.value);

            // Add workout detail to the array
            if (setsReps > 0) {
                workoutDetails.push(`${checkbox.nextElementSibling.innerText }`);
                console.log(workoutDetails);
            }
        });

        document.getElementById("calories").value = totalCalories + " kcal";
        document.getElementById("workouts").value = workoutDetails.join(", "); // Update the workouts input
    }

    // Event delegation for checkboxes
    document.getElementById("workout-list").addEventListener("input", function (event) {
        if (event.target.classList.contains("workout-checkbox")) {
            toggleInputVisibility();
        }
    });

    // Initialize input visibility on page load
    toggleInputVisibility();
});
    
</script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const profilePic = document.getElementById("modalProfilePic");
        const editIcon = document.querySelector(".edit-icon");

        profilePic.addEventListener("mouseenter", function() {
            editIcon.classList.remove("d-none");
        });
        editIcon.addEventListener("mouseenter", function() {
            editIcon.classList.remove("d-none");
        });

        profilePic.addEventListener("mouseleave", function() {
            editIcon.classList.add("d-none");
        });
    });
</script>
</body>
</html>