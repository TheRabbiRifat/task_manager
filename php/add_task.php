<?php
session_start();
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['user_id'])) {
        // Handle if user is not logged in
        header("Location: signin.php");
        exit();
    }

    $user_id = $_SESSION['user_id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $due_date = $_POST['due_date'];
    $priority = $_POST['priority'];
    $assigned_to = $_POST['assigned_to'];
    $assigned_by = $user_id; // Automatically assigned by the logged-in user
    $assigned_time = date('Y-m-d H:i:s'); // Current timestamp

    // Insert new task into the database
    $sql = "INSERT INTO tasks (title, description, due_date, priority, status, assigned_to, assigned_by, assigned_time) VALUES (?, ?, ?, ?, 'pending', ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssiss", $title, $description, $due_date, $priority, $assigned_to, $assigned_by, $assigned_time);

    if ($stmt->execute()) {
        // Task added successfully
        header("Location: ../dashboard.php");
        exit();
    } else {
        // Error in SQL execution
        echo "Error: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
} else {
    // Redirect if form is not submitted via POST method
    header("Location: ../dashboard.php");
    exit();
}
?>
