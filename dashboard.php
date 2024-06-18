<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: signin.php");
    exit();
}
require_once 'php/config.php';

// Fetch the total number of pending tasks
$user_id = $_SESSION['user_id'];
$sql = "SELECT COUNT(*) AS total_pending FROM tasks WHERE assigned_to = ? AND status = 'pending'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($total_pending);
$stmt->fetch();
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Task Manager App</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container">
        <h1>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></h1>
        <div class="button-group">
            <a href="profile.php" class="btn">My Profile</a>
            <a href="php/logout.php" class="btn">Logout</a>
            <a href="pending_tasks.php" class="btn">Pending Tasks</a>
            <a href="completed_tasks.php" class="btn">Completed Tasks</a>
        </div>

        <h2>Task Manager</h2>

        <div class="total-pending-tasks">
            <h2>Total Pending Tasks: <?php echo $total_pending; ?></h2>
        </div>

        <div class="add-task">
    <h2>Add New Task</h2>
    <form id="add-task-form" action="php/add_task.php" method="POST">
        <input type="text" name="title" placeholder="Title" required>
        <textarea name="description" placeholder="Description"></textarea>
        <input type="date" name="due_date" required>
        <select name="priority" required>
            <option value="Low">Low</option>
            <option value="Medium">Medium</option>
            <option value="High">High</option>
        </select>
        <select name="assigned_to" required id="assigned-to">
            <option value="">Assign to...</option>
            <!-- Options will be populated by JavaScript -->
        </select>
        <button type="submit" class="btn">Add Task</button>
    </form>
</div>
        </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            fetchUsers();

            function fetchUsers() {
                fetch('php/get_users.php')
                    .then(response => response.json())
                    .then(users => {
                        const assignedToSelect = document.getElementById('assigned-to');
                        assignedToSelect.innerHTML = '<option value="">Assign to...</option>';

                        users.forEach(user => {
                            const option = document.createElement('option');
                            option.value = user.id;
                            option.textContent = `${user.username} [${user.email}]`;
                            assignedToSelect.appendChild(option);
                        });
                    })
                    .catch(error => console.error('Error fetching users:', error));
            }
        });
    </script>
</body>
</html>
