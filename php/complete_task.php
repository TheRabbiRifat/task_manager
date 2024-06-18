<?php
session_start();
require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $task_id = $_POST['task_id'];

    $sql = "UPDATE tasks SET status = 'Completed' WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $task_id);

    if ($stmt->execute()) {
        echo "Task completed successfully.";
    } else {
        echo "Error completing task.";
    }

    $stmt->close();
    $conn->close();
}
?>
