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
    <title>GetFit</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <style>
                .profile-pic {
            width: 70px;
            height: 70px;
            cursor: pointer;
            border: 2px solid white;
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
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



    <!-- About Us Section -->
    <section id="about" class="section bg-light p-5">
        <h2 class="text-center">About Us</h2>
        <p class="text-center">GETFIT is dedicated to helping you achieve your fitness goals. With professional trainers, modern equipment, and personalized plans, we ensure every workout counts.</p>
        <p class="text-center">Founded in 2010, we've transformed thousands of lives by promoting a healthy lifestyle and a strong community.</p>
    </section>

    <!-- Infrastructure Section -->
    <section id="infrastructure" class="section bg-light p-5">
        <h2 class="text-center">Our Infrastructure</h2>
        <div class="gallery-container">
            <div class="gallery d-flex flex-wrap justify-content-center">
                <img src="https://www.sphoorthyengg.ac.in/public/assets/infrastructure/gym/3.jpg" class="img-fluid m-2" alt="Gym Equipment" style="width: 300px; border-radius: 8px;">
                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcREoIhuAKUjY3EEJ_CKk0UcZeJ41LaVkPaIbQ&s" class="img-fluid m-2" alt="Workout Area" style="width: 300px; border-radius: 8px;">
                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRdsYeapWYaP45lj3qS-lM3hhC0WCdEcK4SZ_NRnFWe7Ge3lxT_O3wB4JgqsAplLSHNhsY&usqp=CAU" class="img-fluid m-2" alt="Cardio Section" style="width: 300px; border-radius: 8px;">
                <img src="https://www.frontsigns.com/wp-content/uploads/2021/07/gym-wall-art-design.jpg" class="img-fluid m-2" alt="Reception Area" style="width: 300px; border-radius: 8px;">
                <img src="https://www.psprint.com/sites/default/files/special/opt-yoga-banner-1.jpg" class="img-fluid m-2" alt="Yoga Sessions" style="width: 300px; border-radius: 8px;">
                <img src="https://thumbs.dreamstime.com/b/gym-trainers-happy-arms-crossed-47297720.jpg" class="img-fluid m-2" alt="Trainers" style="width: 300px; border-radius: 8px;">
                <img src="https://st3.depositphotos.com/1765561/14853/i/450/depositphotos_148533399-stock-photo-modern-gym-with-dumbbell-set.jpg" class="img-fluid m-2" alt="Acrobatics" style="width: 300px; border-radius: 8px;">
            </div>
        </div>
    </section>

    <footer class="bg-dark text-white text-center p-3">
        <p>&copy; 2025 GETFIT Gym. All Rights Reserved</p>
        <p>Contact us: Email✉: contact@getfitgym.com | Phone📞: +91 9876543210 | Address: JNTUA College of Engineering, Pulivendula</p>
    </footer>

    <!-- JavaScript -->
    <script>
        document.getElementById('toggleGallery').addEventListener('click', function() {
            const gallery = document.querySelector('.gallery');
            gallery.style.display = (gallery.style.display === 'none' || gallery.style.display === '') ? 'flex' : 'none';
        });
    </script>

</body>
</html>