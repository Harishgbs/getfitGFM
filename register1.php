<?php
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Adjust the path as necessary
include './php/dbcon.php'; // Include your database connection file

if (!$con) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Function to generate a random verification code
function generateRandomCode() {
    return str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT); // Generate a 6-digit code
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'send_verification') {
            $email = $_POST['email'];
            $code = generateRandomCode();
            $_SESSION['verification_code'] = $code; // Store the code in session

            // Send verification email
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com'; // Set the SMTP server to send through
                $mail->SMTPAuth = true;
                $mail->Username = 'harishgb3805@gmail.com'; // Your Gmail address
                $mail->Password = 'upwg icqq fgvn rlqv'; // Your Gmail password or App Password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption
                $mail->Port = 587; // TCP port to connect to

                $mail->setFrom('harishgb3805@gmail.com', 'GetFit');
                $mail->addAddress($email); // Add a recipient

                $mail->isHTML(true);
                $mail->Subject = 'Verification Code';
                $mail->Body    = "Your verification code is: <strong>$code</strong><br>It is valid for 15 minutes.";

                $mail->send();
                echo json_encode(['status' => 'success']);
            } catch (Exception $e) {
                echo json_encode(['status' => 'error', 'message' => $mail->ErrorInfo]);
            }
            exit;
        } elseif ($_POST['action'] === 'verify_code') {
            $userCode = $_POST['code'];
            if (isset($_SESSION['verification_code']) && $_SESSION['verification_code'] === $userCode) {
                echo json_encode(['status' => 'success']);
            } else {
                echo json_encode(['status' => 'error']);
            }
            exit;
        } elseif ($_POST['action'] === 'register') {
            // Registration logic for students or faculty
            $userType = $_POST['user_type']; // 'student' or 'faculty'
            $username = $_POST['username'];
            $email = $_POST['email'];
            $pswd = password_hash($_POST['password'], PASSWORD_BCRYPT);

            if ($userType === 'student') {
                $roll_no = $_POST['rollno'];
                // Prepare the SQL statement
                $stmt = $con->prepare("INSERT INTO students (username, roll_no, email, password) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("ssss", $username, $roll_no, $email, $pswd);
            } else {
                $faculty_id = $_POST['faculty_id'];
                // Prepare the SQL statement
                $stmt = $con->prepare("INSERT INTO faculty (username, faculty_id, email, password) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("ssss", $username, $faculty_id, $email, $pswd);
            }

            if ($stmt->execute()) {
                // Optionally store user info in session
                $_SESSION['username'] = $username;
                header("Location: ../login.html");
                exit();
            } else {
                echo "Error: " . $stmt->error;
            }

            $stmt->close();
        }
    }
}

$con->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - GetFit</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background-image: url('./images/bg.avif');
            background-size: cover;
        }
    </style>
</head>
<body class="d-flex justify-content-center align-items-center vh-100">
    <div class="container w-50 shadow-lg border border-secondary rounded p-4 text-white" style="background-color: rgba(0, 0, 0, 0.6);">
        <ul class="nav nav-tabs" id="registerTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="student-tab" data-bs-toggle="tab" data-bs-target="#student" type="button" role="tab" aria-controls="student" aria-selected="true">Student</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="faculty-tab" data-bs-toggle="tab" data-bs-target="#faculty" type="button" role="tab" aria-controls="faculty" aria-selected="false">Faculty</button>
            </li>
        </ul>
        
        <div class="tab-content mt-3" id="registerTabsContent">
            <!-- Student Registration Form -->
            <div class="tab-pane fade show active" id="student" role="tabpanel" aria-labelledby="student-tab">
                <form onsubmit="return false;" class="was-validated" id="studentSignupForm">
                    <h2 class="text-white d-flex justify-content-center rounded-bottom">Register as Student</h2>
                    <div class="mb-3">
                        <label for="student_username" class="form-label">Username:</label>
                        <input type="text" class="form-control" name="username" id="student_username" placeholder="Enter username" required>
                    </div>
                    <div class="mb-3">
                        <label for="rollno" class="form-label">Roll Number:</label>
                        <input type="text" class="form-control" name="rollno" id="rollno" placeholder="Enter Roll Number" required>
                    </div>
                    <div class="mb-3">
                        <label for="student_email" class="form-label">Email:</label>
                        <input type="email" class="form-control" name="email" id="student_email" placeholder="Enter your email" required>
                    </div>
                    <div class="mb-3">
                        <label for="student_password" class="form-label">Password:</label>
                        <input type="password" class="form-control" name="password" id="student_password" placeholder="Enter Password" required pattern=".{8,}">
                    </div>
                    <div class="mb-3">
                        <button type="button" class="btn btn-primary" onclick="sendVerificationCode('student')">Send Verification Code</button>
                    </div>
                    <div class="mb-3" id="student_verification" style="display: none;">
                        <label for="student_verification_code" class="form-label">Enter Verification Code:</label>
                        <input type="text" class="form-control" id="student_verification_code" placeholder="Enter the code" required>
                    </div>
                    <div class="d-flex justify-content-center">
                        <button class="btn btn-success" onclick="submitForm('student')">Submit</button>
                    </div>
                    <div class="d-flex justify-content-center">
                        <p>I already have an account?<a href="./login.php" class="text-primary">Signin</a></p>
                    </div>
                </form>
            </div>
            
            <!-- Faculty Registration Form -->
            <div class="tab-pane fade" id="faculty" role="tabpanel" aria-labelledby="faculty-tab">
                <form onsubmit="return false;" class="was-validated" id="facultySignupForm">
                    <h2 class="text-white d-flex justify-content-center rounded-bottom">Register as Faculty</h2>
                    <div class="mb-3">
                        <label for="faculty_username" class="form-label">Username:</label>
                        <input type="text" class="form-control" name="username" id="faculty_username" placeholder="Enter username" required>
                    </div>
                    <div class="mb-3">
                        <label for="faculty_id" class="form-label">Faculty ID:</label>
                        <input type="text" class="form-control" name="faculty_id" id="faculty_id" placeholder="Enter Faculty ID" required>
                    </div>
                    <div class="mb-3">
                        <label for="faculty_email" class="form-label">Email:</label>
                        <input type="email" class="form-control" name="email" id="faculty_email" placeholder="Enter your email" required>
                    </div>
                    <div class="mb-3">
                        <label for="faculty_password" class="form-label">Password:</label>
                        <input type="password" class="form-control" name="password" id="faculty_password" placeholder="Enter Password" required pattern=".{8,}">
                    </div>
                    <div class="mb-3">
                        <button type="button" class="btn btn-primary" onclick="sendVerificationCode('faculty')">Send Verification Code</button>
                    </div>
                    <div class="mb-3" id="faculty_verification" style="display: none;">
                        <label for="faculty_verification_code" class="form-label">Enter Verification Code:</label>
                        <input type="text" class="form-control" id="faculty_verification_code" placeholder="Enter the code" required>
                    </div>

                    <div class="d-flex justify-content-center">
                        <button class="btn btn-success" onclick="submitForm('faculty')">Submit</button>
                    </div>
                    <div class="d-flex justify-content-center">
                        <p>I already have an account?<a href="./login.php" class="text-primary">Signin</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function sendVerificationCode(userType) {
            const emailInput = userType === 'student' ? document.getElementById('student_email') : document.getElementById('faculty_email');
            const email = emailInput.value;

            // Send the verification code to the email using PHP
            fetch('', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({
                    action: 'send_verification',
                    email: email
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    // Show the verification input field
                    if (userType === 'student') {
                        document.getElementById('student_verification').style.display = 'block';
                    } else {
                        document.getElementById('faculty_verification').style.display = 'block';
                    }
                    alert("Verification code sent to your email!");
                } else {
                    alert("Failed to send verification code: " + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }

        function submitForm(userType) {
            const verificationCodeInput = userType === 'student' ? document.getElementById('student_verification_code') : document.getElementById('faculty_verification_code');
            const userCode = verificationCodeInput.value;

            // Verify the code
            fetch('', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({
                    action: 'verify_code',
                    code: userCode
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    alert("Email verified successfully!");

                    // Gather form data
                    const formData = new FormData(userType === 'student' ? document.getElementById('studentSignupForm') : document.getElementById('facultySignupForm'));
                    formData.append('action', 'register');
                    formData.append('user_type', userType); // Add user type to the form data

                    // Send form data to the server
                    fetch('', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.text())
                    .then(result => {
                        alert("Registration successful!");
                        window.location.href='login.php' // You can handle the response from the server here
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
                } else {
                    alert("Verification code is incorrect. Please try again.");
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }
    </script>
</body>
</html>