<?php
session_start();
require_once 'config.php';

$response = array();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Fetch the current password hash
    $sql = "SELECT password FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($hashed_password);
    $stmt->fetch();

    if ($stmt->num_rows > 0) {
        if (password_verify($current_password, $hashed_password)) {
            if ($new_password === $confirm_password) {
                // Update the password
                $new_hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $sql = "UPDATE users SET password = ? WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("si", $new_hashed_password, $user_id);

                if ($stmt->execute()) {
                    $response['status'] = 'success';
                    $response['message'] = 'Password changed successfully.';
                } else {
                    $response['status'] = 'error';
                    $response['message'] = 'Failed to change password. Please try again.';
                }
            } else {
                $response['status'] = 'error';
                $response['message'] = 'New password and confirm password do not match.';
            }
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Current password is incorrect.';
        }
    } else {
        $response['status'] = 'error';
        $response['message'] = 'User not found.';
    }

    echo json_encode($response);

    $stmt->close();
    $conn->close();
}
?>
