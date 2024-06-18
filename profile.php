<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: signin.php");
    exit();
}

require_once 'php/config.php';

$message_password = ''; // Separate message for change password
$message_email = '';    // Separate message for change email

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['change_password'])) {
        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];

        // Validate password change
        $response = changePassword($current_password, $new_password, $confirm_password);

        if ($response['status'] === 'success') {
            $message_password = '<p class="success-message">' . htmlspecialchars($response['message']) . '</p>';
        } else {
            $message_password = '<p class="error-message">' . htmlspecialchars($response['message']) . '</p>';
        }
    } elseif (isset($_POST['change_email'])) {
        $new_email = $_POST['new_email'];

        // Validate email change
        $response = changeEmail($new_email);

        if ($response['status'] === 'success') {
            $message_email = '<p class="success-message">' . htmlspecialchars($response['message']) . '</p>';
            $_SESSION['email'] = $new_email; // Update session email
        } else {
            $message_email = '<p class="error-message">' . htmlspecialchars($response['message']) . '</p>';
        }
    }
}

function changePassword($current_password, $new_password, $confirm_password) {
    global $conn, $_SESSION;

    $user_id = $_SESSION['user_id'];

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

    $stmt->close();
    return $response;
}

function changeEmail($new_email) {
    global $conn, $_SESSION;

    $user_id = $_SESSION['user_id'];

    // Check if email is already taken
    $sql = "SELECT id FROM users WHERE email = ? AND id != ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $new_email, $user_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $response['status'] = 'error';
        $response['message'] = 'Email address is already in use.';
    } else {
        // Update the email address
        $sql = "UPDATE users SET email = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $new_email, $user_id);

        if ($stmt->execute()) {
            $response['status'] = 'success';
            $response['message'] = 'Email address changed successfully.';
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Failed to change email address. Please try again.';
        }
    }

    $stmt->close();
    return $response;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - Task Manager App</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container">
        <h1>My Profile</h1>
        <a href="dashboard.php" class="btn">Dashboard</a>
        <a href="php/logout.php" class="btn">Logout</a>

        <div class="profile-info">
            <h2>User Information</h2>
            <p><strong>Username:</strong> <?php echo htmlspecialchars($_SESSION['username'] ?? 'Unknown'); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($_SESSION['email'] ?? 'Unknown'); ?></p>
        </div>

        <div class="change-password">
            <h2>Change Password</h2>
            <?php echo $message_password; ?>
            <form id="change-password-form" action="profile.php" method="POST">
                <input type="password" name="current_password" placeholder="Current Password" required>
                <input type="password" name="new_password" placeholder="New Password" required>
                <input type="password" name="confirm_password" placeholder="Confirm New Password" required>
                <button type="submit" name="change_password" class="btn">Change Password</button>
            </form>
        </div>

        <div class="change-email">
            <h2>Change Email</h2>
            <?php echo $message_email; ?>
            <form id="change-email-form" action="profile.php" method="POST">
                <input type="email" name="new_email" placeholder="New Email Address" required>
                <button type="submit" name="change_email" class="btn">Change Email</button>
            </form>
        </div>
    </div>
</body>
</html>

