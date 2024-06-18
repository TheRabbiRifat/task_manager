<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: signin.php");
    exit();
}
require_once 'config.php';

// Fetch pending tasks with assigned by and assigned time
$user_id = $_SESSION['user_id'];
$sql = "
    SELECT 
        tasks.id, tasks.title, tasks.description, tasks.due_date, 
        tasks.priority, tasks.assigned_by, tasks.assigned_time,
        users.username AS assigned_to_username, 
        assigned_by_user.username AS assigned_by_username
    FROM 
        tasks 
    LEFT JOIN 
        users ON tasks.assigned_to = users.id
    LEFT JOIN 
        users AS assigned_by_user ON tasks.assigned_by = assigned_by_user.id
    WHERE 
        tasks.assigned_to = ? 
    AND 
        tasks.status = 'pending'
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$pending_tasks = [];
while ($row = $result->fetch_assoc()) {
    $pending_tasks[] = $row;
}

$stmt->close();
$conn->close();

header('Content-Type: application/json');
echo json_encode($pending_tasks);
?>
