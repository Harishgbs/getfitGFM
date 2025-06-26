<?php
session_start();
include "./php/dbcon.php"; // Include your database connection
require 'vendor/autoload.php'; // PHPMailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$login_error = $forgot_error = $forgot_success = $reset_error = $reset_success = "";

// Handle login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check in students
    $stmt = $con->prepare("SELECT * FROM students WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();

    // Check in faculty if not found
    if (!$user) {
        $stmt = $con->prepare("SELECT * FROM faculty WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();
    }

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['username'] = $user['username'];
        $_SESSION['email'] = $user['email'];
        $usertype = $_SESSION['user'] = isset($user['roll_no']) ? 'student' : 'faculty';
        $_SESSION['id'] = $user[$usertype === 'student' ? 'roll_no' : 'faculty_id'];
        header("Location: dashboard.php");
        exit();
    } else {
        $login_error = "Invalid email or password.";
    }
}

// Handle admin login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['admin_username']) && isset($_POST['admin_password'])) {
    $admin_username = $_POST['admin_username'];
    $admin_password = $_POST['admin_password'];

    // Default admin credentials
    $default_username = 'admin';
    $default_password = 'password123'; // Change this to a secure password

    if ($admin_username === $default_username && $admin_password === $default_password) {
        $_SESSION['admin'] = true; // Set admin session variable
        header("Location: admin_dashboard.php"); // Redirect to admin dashboard
        exit();
    } else {
        $login_error = "Invalid admin username or password.";
    }
}

// Handle forgot password
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['forgot_password'])) {
    $email = $_POST['forgot_email'];

    // Check in students
    $stmt = $con->prepare("SELECT * FROM students WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();

    // Check in faculty if not found
    if (!$user) {
        $stmt = $con->prepare("SELECT * FROM faculty WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();
    }

    if ($user) {
        $token = bin2hex(random_bytes(50));
        $table = isset($user['roll_no']) ? 'students' : 'faculty';

        $stmt = $con->prepare("UPDATE $table SET reset_token = ? WHERE email = ?");
        $stmt->bind_param("ss", $token, $email);
        if ($stmt->execute()) {
            if (sendResetEmail($email, $token)) {
                $forgot_success = "Password reset link sent to your email.";
            } else {
                $forgot_error = "Failed to send email.";
            }
        } else {
            $forgot_error = "Database error.";
        }
    } else {
        $forgot_error = "Email not found.";
    }
}

// Handle password reset
if (isset($_GET['token'])) {
    $token = $_GET['token'];

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reset_password'])) {
        $new_password = password_hash($_POST['new_password'], PASSWORD_BCRYPT);

        $stmt = $con->prepare("SELECT * FROM students WHERE reset_token = ?");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();

        if (!$user) {
            $stmt = $con->prepare("SELECT * FROM faculty WHERE reset_token = ?");
            $stmt->bind_param("s", $token);
            $stmt->execute();
            $user = $stmt->get_result()->fetch_assoc();
        }

        if ($user) {
            $table = isset($user['roll_no']) ? 'students' : 'faculty';
            $stmt = $con->prepare("UPDATE $table SET password = ?, reset_token = NULL WHERE reset_token = ?");
            $stmt->bind_param("ss", $new_password, $token);
            if ($stmt->execute()) {
                $reset_success = "Password reset successfully.";
            } else {
                $reset_error = "Error updating password.";
            }
        } else {
            $reset_error = "Invalid token.";
        }
    }
}

// PHPMailer function
function sendResetEmail($to, $token)
{
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'harishgb3805@gmail.com';
        $mail->Password = 'upwg icqq fgvn rlqv'; // App-specific password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('harishgb3805@gmail.com', 'GetFit');
        $mail->addAddress($to);
        $mail->isHTML(true);
        $mail->Subject = 'Password Reset Link';
        $mail->Body = 'Click to reset: <a href="http://localhost/getfit/login.php?token=' . $token . '">Reset Password</a>';
        $mail->AltBody = 'http://localhost/getfit/login.php?token=' . $token;

        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login - GetFit</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <style>
        body {
            background: url('./images/bg.avif') no-repeat center center fixed;
            background-size: cover;
        }
        .container {
            background: rgba(0, 0, 0, 0.7);
            padding: 30px;
            border-radius: 10px;
            color: white;
        }
    </style>
</head>
<body class="d-flex justify-content-center align-items-center vh-100">
    <div class="container w-75">
        <?php if (isset($_GET['token'])): ?>
            <h3>Reset Password</h3>
            <?php if ($reset_error): ?><div class="alert alert-danger"><?php echo $reset_error; ?></div><?php endif; ?>
            <?php if ($reset_success): ?><div class="alert alert-success"><?php echo $reset_success; ?></div>
            <a href="login.php" class="btn btn-primary">Go to Login</a>
            <?php else: ?>
            <form method="post">
                <div class="mb-3">
                    <label for="new_password">New Password</label>
                    <input type="password" name="new_password" class="form-control" required>
                </div>
                <button type="submit" name="reset_password" class="btn btn-success">Reset</button>
            </form>
            <?php endif; ?>
        <?php else: ?>
            <ul class="nav nav-tabs" id="tabs" role="tablist">
                <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#student">Student Login</button></li>
                <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#faculty">Faculty Login</button></li>
                <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#admin">Admin Login</button></li>
            </ul>
            <div class="tab-content mt-3">
                <div class="tab-pane fade show active" id="student">
                    <?php if ($login_error): ?><div class="alert alert-danger"><?php echo $login_error; ?></div><?php endif; ?>
                    <form method="post">
                        <input type="hidden" name="login">
                        <div class="mb-3"><label>Email</label><input type="email" name="email" class="form-control" required></div>
                        <div class="mb-3"><label>Password</label><input type="password" name="password" class="form-control" required></div>
                        <button type="submit" class="btn btn-success">Login</button>
                        <p>I don't have an account? <a href="./register1.php">click here</a> </p>

                        <p class="mt-2"><a href="#" data-bs-toggle="modal" data-bs-target="#forgotModal">Forgot Password?</a></p>
                    </form>
                </div>
                <div class="tab-pane fade" id="faculty">
                    <form method="post">
                        <input type="hidden" name="login">
                        <div class="mb-3"><label>Email</label><input type="email" name="email" class="form-control" required></div>
                        <div class="mb-3"><label>Password</label><input type="password" name="password" class="form-control" required></div>
                        <button type="submit" class="btn btn-success">Login</button>
                        <p>I don't have an account? <a href="./register1.php">click here</a> </p>
                        <p class="mt-2"><a href="#" data-bs-toggle="modal" data-bs-target="#forgotModal">Forgot Password?</a></p>
                    </form>
                </div>
                <div class="tab-pane fade" id="admin">
                    <form method="post">
                        <input type="hidden" name="login">
                        <div class="mb-3"><label>Username</label><input type="text" name="admin_username" class="form-control" required></div>
                        <div class="mb-3"><label>Password</label><input type="password" name="admin_password" class="form-control" required></div>
                        <button type="submit" class="btn btn-success">Login</button>
                    </form>
                </div>
            </div>

            <!-- Forgot Modal -->
            <div class="modal fade" id="forgotModal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content text-black">
                        <div class="modal-header"><h5>Forgot Password</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                        <form method="post">
                            <div class="modal-body">
                                <?php if ($forgot_error): ?><div class="alert alert-danger"><?php echo $forgot_error; ?></div><?php endif; ?>
                                <?php if ($forgot_success): ?><div class="alert alert-success"><?php echo $forgot_success; ?></div><?php endif; ?>
                                <input type="hidden" name="forgot_password">
                                <label>Email</label>
                                <input type="email" name="forgot_email" class="form-control" required>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-primary">Send Reset Link</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>