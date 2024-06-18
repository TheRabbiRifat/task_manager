<?php
session_start();
require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $assigned_to = $_SESSION['user_id'];

    $sql = "SELECT * FROM tasks WHERE assigned_to = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $assigned_to);
    $stmt->execute();
    $result = $stmt->get_result();

    $tasks = [];
    while ($row = $result->fetch_assoc()) {
        $tasks[] = $row;
    }

    echo json_encode($tasks);

    $stmt->close();
    $conn->close();
}
?>
