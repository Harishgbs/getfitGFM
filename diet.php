
<?php
include "./php/dbcon.php"; // Include your database connection
session_start();
$username = $_SESSION['username'];
$phno = $_SESSION['phno'];
$date_of_birth = $_SESSION['dob'];
$cur_weight = $_SESSION['cur_weight'];
$height = $_SESSION['height'];
$GWieght = $_SESSION['goal_weight'];
$email = $_SESSION['email'];
$profilPicPath = $_SESSION['profile_path'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diet Plan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('./images/fooddiet.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            color: white;
            font-family: Arial, sans-serif;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            text-align: center;
            transition: background 0.5s ease-in-out;
            margin: 0; /* Ensure no margin on body */
        }
        .profile-pic {
            width: 70px;
            height: 70px;
            cursor: pointer;
            border: 2px solid white;
        }
        .welcome {
            font-size: 5rem;
            font-weight: bold;
            color: #0d63a5;
            text-shadow: 5px 5px 15px rgba(255, 204, 0, 1);
            animation: glow 1.5s infinite alternate;
        }
        @keyframes glow {
            from {
                text-shadow: 5px 5px 15px rgba(255, 204, 0, 1);
            }
            to {
                text-shadow: 10px 10px 20px rgba(255, 204, 0, 0.8);
            }
        }
        .btn {
            font-size: 1rem;
            padding: 20px 40px;
            margin: 15px;
        }
        .modal-backdrop{
            display: none;
                }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark w-100 position-sticky top-0 ">
    <div class="container text-dark">
        <div class="modal fade" id="profileModal" tabindex="-1" aria-labelledby="profileModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="profileModalLabel">Profile Details</h5>
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
                        <div class="text-start px-4">
                            <p><strong>Username:</strong> <span id="modalUser Username"><?=$username?></span></p>
                            <p><strong>Date of Birth:</strong> <span id="modalUser DOB"><?=$date_of_birth?></span></p>
                            <p><strong>Phone Number:</strong> <span id="modalUser Phone"><?=$phno?></span></p>
                            <p><strong>Height:</strong> <span id="modalUser Height"><?=$height?></span></p>
                            <p><strong>Weight:</strong> <span id="modalUser Weight"><?=$cur_weight?> kg</span></p>
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

<div class="container main" style="margin-top: 100px; height:100vh;"> <!-- Add margin to avoid overlap with fixed navbar -->
    <div id="home">
        <h1 class="welcome">Welcome to Diet Plan</h1>
        <a href="#" onclick="showSection('weightGain')" class="btn btn-success">Weight Gain</a>
        <a href="#" onclick="showSection('weightLoss')" class="btn btn-danger">Weight Loss</a>
    </div>

    <div id="weightGain" style="display: none;">
        <h2>Weight Gain Plan</h2>
        <a href="#" onclick="showSection('nonVegGain')" class="btn btn-warning">Non-Veg Diet</a>
        <a href="#" onclick="showSection('vegGain')" class="btn btn-success">Veg Diet</a>
        <br><br>
        <a href="#" onclick="showSection('home')" class="btn btn-primary">Back to Home</a>
    </div>

    <div id="nonVegGain" style="display: none;">
        <h2>Non-Veg Gym Diet Plan (Weight Gain)</h2>
        <h3>Morning</h3>
        <p class="h4">Scrambled eggs, toast, grilled chicken, milk</p>
        <h3>Lunch</h3>
        <p class="h4">Grilled salmon, brown rice, vegetables</p>
        <h3>Dinner</h3>
        <p class="h4">Chicken steak, sweet potatoes, greens</p>
        <a href="#" onclick="showSection('weightGain')" class="btn btn-primary">Back</a>
    </div>

    <div id="vegGain" style="display: none;">
        <h2>Veg Gym Diet Plan (Weight Gain)</h2>
        <h3>Morning</h3>
        <p class="h4">Oats with fruits, almond milk</p>
        <h3>Lunch</h3>
        <p class="h4">Quinoa, lentil stew, roasted vegetables</p>
        <h3>Dinner</h3>
        <p class="h4">Tofu stir-fry, brown rice, leafy greens</p>
        <a href="#" onclick="showSection('weightGain')" class="btn btn-primary">Back</a>
    </div>

    <div id="weightLoss" style="display: none;">
        <h2>Weight Loss Plan</h2>
        <a href="#" onclick="showSection('nonVegLoss')" class="btn btn-warning">Non-Veg Diet</a>
        <a href="#" onclick="showSection('vegLoss')" class="btn btn-success">Veg Diet</a>
        <br><br>
        <a href="#" onclick="showSection('home')" class="btn btn-primary">Back to Home</a>
    </div>

    <div id="nonVegLoss" style="display: none;">
        <h2>Non-Veg Gym Diet Plan (Weight Loss)</h2>
        <h3>Morning</h3>
        <p class="h4">Boiled eggs, small portion of fruit</p>
        <h3>Lunch</h3>
        <p class="h4">Grilled chicken breast, quinoa, steamed broccoli</p>
        <h3>Dinner</h3>
        <p class="h4">Gr illed fish, salad with olive oil dressing</p>
        <a href="#" onclick="showSection('weightLoss')" class="btn btn-primary">Back</a>
    </div>

    <div id="vegLoss" style="display: none;">
        <h2>Veg Gym Diet Plan (Weight Loss)</h2>
        <h3>Morning</h3>
        <p class="h4">Oats with almond milk, a few almonds</p>
        <h3>Lunch</h3>
        <p class="h4">Lentil soup, steamed vegetables</p>
        <h3>Dinner</h3>
        <p class="h4">Tofu stir-fry, spinach, mushrooms, brown rice</p>
        <a href="#" onclick="showSection('weightLoss')" class="btn btn-primary">Back</a>
    </div>
</div>
<footer class="bg-dark text-white text-center p-3 w-100 position-relative bottom-0 ">
        <p>&copy; 2025 GETFIT Gym. All Rights Reserved</p>
        <p>Contact us: Email✉: contact@getfitgym.com | Phone📞: +91 9876543210 | Address: JNTUA College of Engineering, Pulivendula</p>
    </footer>
<script>
    function showSection(sectionId) {
        let sections = document.querySelectorAll('div[id]');
        sections.forEach(section => section.style.display = 'none');
        document.getElementById(sectionId).style.display = 'block';
    }
    showSection('home');
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>