<?php
include 'config.php';
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $email = $_POST["email"];
  $password = $_POST["password"];
  $confirm = $_POST["confirm"];

  if ($password !== $confirm) {
    $message = "Passwords do not match!";
  } else {
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $email, $hash);

    if ($stmt->execute()) {
      $message = "Signup successful! <a href='login.php'>Login</a>";
    } else {
      $message = "Error: Email may already exist.";
    }
  }
}
?>
<!DOCTYPE html>
<html>
<head><title>Sign Up</title><link rel="stylesheet" href="auth.css"></head>
<body>
<div class="form-container">
  <h2>Sign Up</h2>
  <form method="post">
    <input type="text" name="username" required placeholder="Username">
    <input type="email" name="email" required placeholder="Email">
    <input type="password" name="password" required placeholder="Password">
    <input type="password" name="confirm" required placeholder="Confirm Password">
    <button type="submit">Register</button>
    <p style="color:red;"><?= $message ?></p>
    <p>Already have an account? <a href="login.php">Login</a></p>
  </form>
</div>
</body>
</html>
