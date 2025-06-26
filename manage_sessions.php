<?php
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

// Handle adding a new session
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_session'])) {
    $time = $_POST['time'];
    $max_participants = $_POST['max_participants'];

    // Insert the new session into the database
    $stmt = $con->prepare("INSERT INTO sessions (time, max_participants) VALUES (?, ?)");
    $stmt->bind_param("si", $time, $max_participants);

    if ($stmt->execute()) {
        $add_success = "Session added successfully!";
    } else {
        $add_error = "Error adding session: " . $stmt->error;
    }
}

// Handle editing a session
if (isset($_GET['edit'])) {
    $session_id = $_GET['edit'];
    $session_query = "SELECT * FROM sessions WHERE id = ?";
    $stmt = $con->prepare($session_query);
    $stmt->bind_param("i", $session_id);
    $stmt->execute();
    $session_result = $stmt->get_result();
    $session = $session_result->fetch_assoc();
}

// Update session
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_session'])) {
    $session_id = $_POST['session_id'];
    $time = $_POST['time'];
    $max_participants = $_POST['max_participants'];

    $stmt = $con->prepare("UPDATE sessions SET time = ?, max_participants = ? WHERE id = ?");
    $stmt->bind_param("sii", $time, $max_participants, $session_id);

    if ($stmt->execute()) {
        header("Location: manage_sessions.php"); // Redirect to the same page after update
        exit();
    } else {
        $add_error = "Error updating session: " . $stmt->error;
    }
}

// Handle deleting a session
if (isset($_GET['delete'])) {
    $session_id = $_GET['delete'];
    $stmt = $con->prepare("DELETE FROM sessions WHERE id = ?");
    $stmt->bind_param("i", $session_id);

    if ($stmt->execute()) {
        header("Location: manage_sessions.php"); // Redirect to the same page after deletion
        exit();
    } else {
        $add_error = "Error deleting session: " . $stmt->error;
    }
}

// Fetch existing sessions
$sessions_query = "SELECT * FROM sessions";
$sessions_result = mysqli_query($con, $sessions_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Sessions</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-4">
        <h2 class="text-center">Manage Workout Sessions</h2>

        <!-- Add New Session -->
        <div class="card p-3 mt-3">
            <h4>Add New Session</h4>
            <?php if ($add_success): ?>
                <div class="alert alert-success"><?php echo $add_success; ?></div>
            <?php endif; ?>
            <?php if ($add_error): ?>
                <div class="alert alert-danger"><?php echo $add_error; ?></div>
            <?php endif; ?>
            <form method="post">
                <div class="mb-3">
                    <label class="form-label">Time</label>
                    <input type="time" class="form-control" name="time" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Max Participants</label>
                    <input type="number" class="form-control" name="max_participants" placeholder="e.g., 20" required>
                </div>
                <button type="submit" name="add_session" class="btn btn-success">Add Session</button>
            </form>
        </div>

        <!-- Edit Session -->
        <?php if (isset($session)): ?>
            <div class="card p-3 mt-3">
                <h4>Edit Session</h4>
                <form method="post">
                    <input type="hidden" name="session_id" value="<?php echo $session['id']; ?>">
                    <div class="mb-3">
                        <label class="form-label">Time</label>
                        <input type="time" class="form-control" name="time" value="<?php echo $session['time']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Max Participants</label>
                        <input type="number" class="form-control" name="max_participants" value="<?php echo $session['max_participants']; ?>" required>
                    </div>
                    <button type="submit" name="update_session" class="btn btn-warning">Update Session</button>
                </form>
            </div>
        <?php endif; ?>

        <!-- Session List -->
        <h4 class="mt-4">Existing Sessions</h4>
        <table class="table table-bordered mt-2">
            <thead class="table-dark">
                <tr>
                    <th>Session ID</th>
                    <th>Time</th>
                    <th>Max Participants</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($session = mysqli_fetch_assoc($sessions_result)): ?>
                    <tr>
                        <td><?php echo $session['id']; ?></td>
                        <td><?php echo $session['time']; ?></td>
                        <td><?php echo $session['max_participants']; ?></td>
                        <td>
                            <a href="manage_sessions.php?edit=<?php echo $session['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="manage_sessions.php?delete=<?php echo $session['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this session?');">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <a href="admin_dashboard.php" class="btn btn-primary">Back to Dashboard</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>