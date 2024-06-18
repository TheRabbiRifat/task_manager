<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign In - Task Manager App</title>
  <link rel="stylesheet" href="css/styles.css">
</head>
<body>
  <div class="container">
    <h1>Sign In</h1>
    <form id="signin-form" action="php/signin.php" method="POST" class="form">
      <input type="text" name="username" placeholder="Username / Email" required>
      <input type="password" name="password" placeholder="Password" required>
      <button type="submit" class="btn">Sign In</button>
    </form>
    <p>Don't have an account yet? <a href="signup.php">Sign Up</a></p>
  </div>
</body>
</html>
