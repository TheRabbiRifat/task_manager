<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: signin.php");
    exit();
}
require_once 'config.php';

// Fetch completed tasks with assigned by
$user_id = $_SESSION['user_id'];
$sql = "
    SELECT 
        tasks.id, tasks.title, tasks.description, tasks.due_date, 
        tasks.priority, tasks.status,
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
        tasks.status = 'Completed'
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$completed_tasks = [];
while ($row = $result->fetch_assoc()) {
    $completed_tasks[] = $row;
}

$stmt->close();
$conn->close();

header('Content-Type: application/json');
echo json_encode($completed_tasks);
?>
