<?php
session_start();
include 'config.php';
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST["email"];
  $password = $_POST["password"];

  $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $result = $stmt->get_result();
  $user = $result->fetch_assoc();

  if ($user && password_verify($password, $user["password"])) {
    $_SESSION["username"] = $user["username"];
    header("Location: dashboard.php");
  } else {
    $message = "Invalid login!";
  }
}
?>
<!DOCTYPE html>
<html>
<head><title>Login</title><link rel="stylesheet" href="auth.css"></head>
<body>
<div class="form-container">
  <h2>Login</h2>
  <form method="post">
    <input type="email" name="email" required placeholder="Email">
    <input type="password" name="password" required placeholder="Password">
    <button type="submit">Login</button>
    <p style="color:red;"><?= $message ?></p>
    <p>No account? <a href="signup.php">Sign Up</a></p>
  </form>
</div>
</body>
</html>
