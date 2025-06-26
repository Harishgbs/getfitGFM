<?php

use function PHPSTORM_META\type;

session_start();
include './php/dbcon.php'; // Include your database connection

// Check if the user is logged in as admin
if (!isset($_SESSION['admin'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}

// Initialize variables
$add_success = "";
$add_error = "";

// Handle adding a new student
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_student'])) {
    $username = $_POST['username'];
    $roll_no = $_POST['roll_no'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Hash the password
    $reset_token = bin2hex(random_bytes(16)); // Generate a reset token

    // Insert the new student into the database
    $stmt = $con->prepare("INSERT INTO students (username, roll_no, email, password, reset_token) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $username, $roll_no, $email, $password, $reset_token);

    if ($stmt->execute()) {
        $add_success = "Student added successfully!";
    } else {
        $add_error = "Error adding student: " . $stmt->error;
    }
}

// Handle adding a new faculty
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_faculty'])) {
    $username = $_POST['username'];
    $faculty_id = $_POST['faculty_id'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Hash the password
    $reset_token = bin2hex(random_bytes(16)); // Generate a reset token

    // Insert the new faculty into the database
    $stmt = $con->prepare("INSERT INTO faculty (username, faculty_id, email, password, reset_token) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $username, $faculty_id, $email, $password, $reset_token);

    if ($stmt->execute()) {
        $add_success = "Faculty added successfully!";
    } else {
        $add_error = "Error adding faculty: " . $stmt->error;
    }
}

// Handle editing a user
if (isset($_GET['edit'])) {
    $user_id = $_GET['edit'];
    $user_type = $_GET['type'];
    if ($user_type === 'student') {
        $user_query = "SELECT * FROM students WHERE roll_no = ?";
    } else {
        $user_query = "SELECT * FROM faculty WHERE faculty_id = ?";
    }

    $stmt = $con->prepare($user_query);
    echo(is_string($user_id));
    $stmt->bind_param("s", $user_id);
    $stmt->execute();
    $user_result = $stmt->get_result();
    $user = $user_result->fetch_assoc();
}

// Update user
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_user'])) {
    $user_id = $_POST['user_id'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password']; // You may want to handle password hashing here as well

    if ($_POST['user_type'] === 'student') {
        $roll_no = $_POST['roll_no'];
        $stmt = $con->prepare("UPDATE students SET username = ?, roll_no = ?, email = ?, password = ? WHERE id = ?");
        $stmt->bind_param("ssssi", $username, $roll_no, $email, $password, $user_id);
    } else {
        $faculty_id = $_POST['faculty_id'];
        $stmt = $con->prepare("UPDATE faculty SET username = ?, faculty_id = ?, email = ?, password = ? WHERE id = ?");
        $stmt->bind_param("ssssi", $username, $faculty_id, $email, $password, $user_id);
    }

    if ($stmt->execute()) {
        header("Location: manage_users.php"); // Redirect to the same page after update
        exit();
    } else {
        $add_error = "Error updating user: " . $stmt->error;
    }
}

// Handle deleting a user
if (isset($_GET['delete'])) {
    $user_id = $_GET['delete'];
    $user_type = $_GET['type'];

    if ($user_type === 'student') {
        $stmt = $con->prepare("DELETE FROM students WHERE id = ?");
    } else {
        $stmt = $con->prepare("DELETE FROM faculty WHERE id = ?");
    }

    $stmt->bind_param("i", $user_id);

    if ($stmt->execute()) {
        header("Location: manage_users.php"); // Redirect to the same page after deletion
        exit();
    } else {
        $add_error = "Error deleting user: " . $stmt->error;
    }
}

// Fetch existing students
$students_query = "SELECT * FROM students";
$students_result = mysqli_query($con, $students_query);

// Fetch existing faculty
$faculty_query = "SELECT * FROM faculty";
$faculty_result = mysqli_query($con, $faculty_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-4">
        <h2 class="text-center">Manage Users</h2>

        <!-- Add New Student -->
        <div class="card p-3 mt-3">
            <h4>Add New Student</h4>
            <?php if ($add_success): ?>
                <div class="alert alert-success"><?php echo $add_success; ?></div>
            <?php endif; ?>
            <?php if ($add_error): ?>
                <div class="alert alert-danger"><?php echo $add_error; ?></div>
            <?php endif; ?>
            <form method="post">
                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <input type="text" class="form-control" name="username" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Roll No</label>
                    <input type="text" class="form-control" name="roll_no" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" name="email" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" class="form-control" name="password" required>
                </div>
                <button type="submit" name="add_student" class="btn btn-success">Add Student</button>
            </form>
        </div>

        <!-- Add New Faculty -->
        <div class="card p-3 mt-3">
            <h4>Add New Faculty</h4>
            <form method="post">
                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <input type="text" class="form-control" name="username" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Faculty ID</label>
                    <input type="text" class="form-control" name="faculty_id" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" name="email" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" class="form-control" name="password" required>
                </div>
                <button type="submit" name="add_faculty" class="btn btn-success">Add Faculty</button>
            </form>
        </div>

        <!-- Students List -->
        <h4 class="mt-4">Existing Students</h4>
        <table class="table table-bordered mt-2">
            <thead class="table-dark">
                <tr>
                    <th>Username</th>
                    <th>Roll No</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($student = mysqli_fetch_assoc($students_result)): ?>
                    <tr>
                        <td><?php echo $student['username']; ?></td>
                        <td><?php echo $student['roll_no']; ?></td>
                        <td><?php echo $student['email']; ?></td>
                        <td>
                            <a href="manage_users.php?edit=<?php echo $student['roll_no']; ?>&type=student" class="btn btn-warning btn-sm">Edit</a>
                            <a href="manage_users.php?delete=<?php echo $student['roll_no']; ?>&type=student" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this student?');">Delete</a>
                            <button class="btn btn-info btn-sm" onclick="copyToClipboard('<?php echo $student['username']; ?>')">Copy</button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <!-- Faculty List -->
        <h4 class="mt-4">Existing Faculty</h4>
        <table class="table table-bordered mt-2">
            <thead class="table-dark">
                <tr>
                    <th>Username</th>
                    <th>Faculty ID</th>
                    <th>Email</th>
                    <th>Password</th>
                    <th>Reset Token</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($faculty = mysqli_fetch_assoc($faculty_result)): ?>
                    <tr>
                        <td><?php echo $faculty['username']; ?></td>
                        <td><?php echo $faculty['faculty_id']; ?></td>
                        <td><?php echo $faculty['email']; ?></td>
                        <td><?php echo $faculty['password']; ?></td>
                        <td><?php echo $faculty['reset_token']; ?></td>
                        <td>
                            <a href="manage_users.php?edit=<?php echo $faculty['id']; ?>&type=faculty" class="btn btn-warning btn-sm">Edit</a>
                            <a href="manage_users.php?delete=<?php echo $faculty['id']; ?>&type=faculty" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this faculty member?');">Delete</a>
                            <button class="btn btn-info btn-sm" onclick="copyToClipboard('<?php echo $faculty['username']; ?>')">Copy</button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <!-- Edit User Form -->
        <?php if (isset($user)): ?>
            <div class="card p-3 mt-3">
                <h4>Edit User</h4>
                <form method="post">
                    <input type="hidden" name="user_id" value="<?php echo $user['roll_no']; ?>">
                    <input type="hidden" name="user_type" value="<?php echo isset($user['roll_no']) ? 'student' : 'faculty'; ?>">
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" class="form-control" name="username" value="<?php echo $user['username']; ?>" required>
                    </div>
                    <?php if (isset($user['roll_no'])): ?>
                        <div class="mb-3">
                            <label class="form-label">Roll No</label>
                            <input type="text" class="form-control" name="roll_no" value="<?php echo $user['roll_no']; ?>" required>
                        </div>
                    <?php else: ?>
                        <div class="mb-3">
                            <label class="form-label">Faculty ID</label>
                            <input type="text" class="form-control" name="faculty_id" value="<?php echo $user['faculty_id']; ?>" required>
                        </div>
                    <?php endif;?>
                                        <div class="mb-3">
                                        <label class="form-label">Email</label>
                                        <input type="email" class="form-control" name="email" value="<?php echo $user['email']; ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Password</label>
                                        <input type="password" class="form-control" name="password" placeholder="Leave blank to keep current password">
                                    </div>
                                    <button type="submit" name="update_user" class="btn btn-warning">Update User</button>
                                </form>
                            </div>
                        <?php endif; ?>
                
                        <a href="admin_dashboard.php" class="btn btn-primary mt-3">Back to Dashboard</a>
                    </div>
                
                    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
                    <script>
                        function copyToClipboard(text) {
                            navigator.clipboard.writeText(text).then(function() {
                                alert('Copied to clipboard: ' + text);
                            }, function(err) {
                                console.error('Could not copy text: ', err);
                            });
                        }
                    </script>
                </body>
                </html>