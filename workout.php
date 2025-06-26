<?php
session_start();
$username=$_SESSION['username'];
$phno=$_SESSION['phno'];
$date_of_birth=$_SESSION['dob'];
$cur_weight=$_SESSION['cur_weight'];
$height=$_SESSION['height'];
$GWieght=$_SESSION['goal_weight'];
$dob = new DateTime($date_of_birth);
$today = new DateTime(); // Get the current date
$age = $_SESSION['age'];
$email=$_SESSION['email'];
$profilPicPath=$_SESSION['profile_path'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Workout Tracker</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #121212;
            color: #fff;
            text-align: center;
        }
        .profile-pic {
            width: 70px;
            height: 70px;
            cursor: pointer;
            border: 2px solid white;
        }
        header {
            background: linear-gradient(90deg, #ff416c, #ff4b2b);
            padding: 20px;
            font-size: 28px;
            font-weight: bold;
            text-transform: uppercase;
            box-shadow: 0px 4px 10px rgba(255, 75, 43, 0.5);
        }

        .workout-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            padding: 30px;
            gap: 20px;
        }

        .workout {
            background: #1e1e1e;
            border-radius: 15px;
            overflow: hidden;
            width: 280px;
            text-align: center;
            padding: 20px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            box-shadow: 0 4px 10px rgba(255, 75, 43, 0.2);
        }

        .workout:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 20px rgba(255, 75, 43, 0.5);
        }

        .workout img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 10px;
            transition: opacity 0.3s ease;
        }

        .workout:hover img {
            opacity: 0.8;
        }

        .workout h2 {
            margin-top: 15px;
            font-size: 22px;
            font-weight: bold;
            color: white;
        }

        .container1 {
            background: #1e1e1e;
            border-radius: 15px;
            padding: 30px;
            margin: 0 auto;
            box-shadow: 0 4px 10px rgba(255, 75, 43, 0.5);
        }

        label {
            font-size: 18px;
            margin: 10px 0;
            display: block;
        }

        input, select {
            padding: 10px;
            width: 100%;
            margin: 10px 0;
            border-radius: 5px;
            border: none;
        }

        button {
            padding: 10px 20px;
            background-color: #ff4b2b;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #ff416c;
        }

        footer {
            background: #222;
            padding: 20px;
            font-size: 18px;
            margin-top: 30px;
            box-shadow: 0 -4px 10px rgba(255, 75, 43, 0.5);
        }

        .result {
            margin-top: 20px;
        }

        #workout-selector, #workout-about {
            display: block;
        }

        #workout-about {
            display: none;
        }

        .back-button {
            background-color: #444;
            margin-top: 20px;
            padding: 10px 20px;
            cursor: pointer;
            color: #fff;
            border: none;
            border-radius: 5px;
        }

        .back-button:hover {
            background-color: #555;
        }

        h3 {
            margin-top: 20px;
            font-size: 20px;
            color: #ff4b2b;
        }

        ul {
            list-style-type: none;
            padding-left: 0;
        }

        li {
            font-size: 16px;
        }
        .modal-backdrop {
            display: none;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark position-sticky top-0 z-3 ">
        <div class="modal fade" id="profileModal" tabindex="-1" aria-labelledby="profileModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-dark" id="profileModalLabel">Profile Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center">
                        <div class="position-relative d-inline-block">
                            <img src=<?=$profilPicPath?> id="modalProfilePic" class="img-fluid mb-3" width="100" height="100" alt="Profile">

                            <span class="edit-icon position-absolute top-50 start-50 translate-middle bg-light rounded-circle p-2 d-none">
                                <i class="fas fa-pencil-alt text-dark"></i>
                            </span>
                        </div>
        
                        <h5 id="modalUserName"><?=$username?></h5>
                        <p class="text-muted" id="modalUserEmail"><?=$email ?></p>
        
                        <div class="text-start px-4 text-dark">
                            <p><strong>Username:</strong> <span id="modalUserUsername"><?=$username ?></span></p>
                            <p><strong>Date of Birth:</strong> <span id="modalUserDOB"><?=$dob->format('Y-m-d')?></span></p>
                            <p><strong>Phone Number:</strong> <span id="modalUserPhone"><?= $phno ?></span></p>
                            <p><strong>Height:</strong> <span id="modalUserHeight"><?=$height ?></span></p>
                            <p><strong>Weight:</strong> <span id="modalUserWeight"><?=$cur_weight ?> kg</span></p>
                        </div> 
                        <button class="btn btn-outline-danger mt-3 w-100" >Logout</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="container main-content">
            <img src=<?=$profilPicPath?> class="img-fluid rounded-circle profile-pic ms-3 p-1 mx-3" alt="Profile" data-bs-toggle="modal" data-bs-target="#profileModal">

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

<div class="container1 mt-5">
    <header>🔥 Choose your workout routine 💪</header>

<section id="workout-selector">
    <div class="workout-container">
        <div class="workout" onclick="showAboutWorkout('pushups')">
            <img src="./images/pushup.jpg" alt="Push-ups">
            <h2>Push-ups</h2>
        </div>
        <div class="workout" onclick="showAboutWorkout('pullups')">
            <img src="./images/pullup.jpg" alt="Pull-ups">
            <h2>Pull-ups</h2>
        </div>
        <div class="workout" onclick="showAboutWorkout('cardio')">
            <img src="./images/cardio.jpg" alt="Cardio">
            <h2>Cardio</h2>
        </div>
        <div class="workout" onclick="showAboutWorkout('biceps')">
            <img src="./images/biceps.jpg" alt="Biceps">
            <h2>Biceps</h2>
        </div>
        <div class="workout" onclick="showAboutWorkout('triceps')">
            <img src="./images/triceps.jpg" alt="Triceps">
            <h2>Triceps</h2>
        </div>
        <div class="workout" onclick="showAboutWorkout('legs')">
            <img src="./images/legs.jpg" alt="Legs">
            <h2>Legs</h2>
        </div>
        <div class="workout" onclick="showAboutWorkout('shoulders')">
            <img src="./images/shoulders.jpg" alt="Shoulders">
            <h2>Shoulders</h2>
        </div>
        <div class="workout" onclick="showAboutWorkout('abs')">
            <img src="./images/abs.jpg" alt="Abs">
            <h2>Abs</h2>
        </div>
        <div class="workout" onclick="showAboutWorkout('back')">
            <img src="./images/back.jpg" alt="Back">
            <h2>Back</h2>
        </div>
    </div>
</section>

<!-- About Workout Section -->
<section id="workout-about">
    <div class="container">
        <h1>About the Workout</h1>
        <p id="workout-description"></p>

        <h3>Types of Exercises:</h3>
        <ul id="workout-types"></ul>

        <!-- Back Button -->
        <button class="back-button" onclick="goBack()">Back to Workout Selection</button>
    </div>
</section>


</div>
<footer class="bg-dark text-white text-center p-3 mt-2 w-100 position-relative bottom-0">
        <p>&copy; 2025 GETFIT Gym. All Rights Reserved</p>
        <p>Contact us: Email✉: contact@getfitgym.com | Phone📞: +91 9876543210 | Address: JNTUA College of Engineering, Pulivendula</p>
    </footer>
<script>
    // Show the about section for the selected workout
    function showAboutWorkout(workout) {
        // Hide the workout selection and show the about section
        document.getElementById("workout-selector").style.display = "none";
        document.getElementById("workout-about").style.display = "block";
    
        // Set the description and types based on the selected workout
        let description = "";
        let types = [];
    
        switch (workout) {
            case 'pushups':
                description = "Push-ups are a classic exercise that primarily targets the chest, shoulders, and triceps. They help build upper body strength and endurance.";
                types = [
                    "Standard Push-up",
                    "Incline Push-up",
                    "Decline Push-up",
                    "Diamond Push-up"
                ];
                break;
            case 'pullups':
                description = "Pull-ups are excellent for building back, shoulder, and arm strength. They are performed by pulling your body up to a bar using your arms and back muscles.";
                types = [
                    "Wide-Grip Pull-up",
                    "Chin-up",
                    "Neutral-Grip Pull-up",
                    "Kipping Pull-up"
                ];
                break;
            case 'cardio':
                description = "Cardio exercises improve your heart and lung health. They include a range of activities that increase your heart rate, like running, cycling, or swimming.";
                types = [
                    "Running",
                    "Cycling",
                    "Jump Rope",
                    "Swimming"
                ];
                break;
            case 'biceps':
                description = "Bicep exercises target the upper arm muscles, improving arm strength and definition. Common exercises focus on curling motions.";
                types = [
                    "Bicep Curls",
                    "Hammer Curls",
                    "Concentration Curls",
                    "Barbell Curls"
                ];
                break;
            case 'triceps':
                description = "Triceps exercises build the muscles in the back of your arms, improving strength and tone.";
                types = [
                    "Tricep Dips",
                    "Close-Grip Push-ups",
                    "Overhead Tricep Extension",
                    "Skull Crushers"
                ];
                break;
            case 'legs':
                description = "Leg exercises strengthen the lower body muscles, including quadriceps, hamstrings, glutes, and calves.";
                types = [
                    "Squats",
                    "Lunges",
                    "Deadlifts",
                    "Leg Press"
                ];
                break;
            case 'shoulders':
                description = "Shoulder exercises help develop the deltoid muscles, improving shoulder strength and stability.";
                types = [
                    "Shoulder Press",
                    "Lateral Raises",
                    "Front Raises",
                    "Arnold Press"
                ];
                break;
            case 'abs':
                description = "Ab exercises target the muscles in your core, helping to improve posture, balance, and stability.";
                types = [
                    "Crunches",
                    "Planks",
                    "Leg Raises",
                    "Russian Twists"
                ];
                break;
            case 'back':
                description = "Back exercises help strengthen your back muscles, improving posture and overall strength.";
                types = [
                    "Deadlifts",
                    "Rows",
                    "Lat Pulldowns",
                    "Hyperextensions"
                ];
                break;
        }
    
        // Display the description and types
        document.getElementById("workout-description").textContent = description;
    
        // Clear previous types before adding new ones
        const workoutTypesList = document.getElementById("workout-types");
        workoutTypesList.innerHTML = "";
    
        // Add the types dynamically to the list
        types.forEach(type => {
            const li = document.createElement("li");
            li.textContent = type;
            workoutTypesList.appendChild(li);
        });
    }
    
    // Go back to the workout selection page
    function goBack() {
        // Show the workout selection and hide the about section
        document.getElementById("workout-selector").style.display = "block";
        document.getElementById("workout-about").style.display = "none";
    }
    </script>

</body>
</html>
