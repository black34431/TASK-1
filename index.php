<?php
session_start();

// Dummy user storage (session-only)
if (!isset($_SESSION['users'])) {
    $_SESSION['users'] = [];
}

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: inked_clothing_popup.php");
    exit();
}

// Handle login
$loginError = '';
if (isset($_POST['login'])) {
    $user = $_POST['username'];
    $pass = $_POST['password'];

    if (isset($_SESSION['users'][$user]) && $_SESSION['users'][$user] === $pass) {
        $_SESSION['username'] = $user;
    } else {
        $loginError = "Invalid username or password";
    }
}

// Handle signup
$signupMsg = '';
if (isset($_POST['signup'])) {
    $newUser = $_POST['new_username'];
    $newPass = $_POST['new_password'];

    if (isset($_SESSION['users'][$newUser])) {
        $signupMsg = "Username already taken.";
    } else {
        $_SESSION['users'][$newUser] = $newPass;
        $signupMsg = "Signup successful! You can now login.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>INKED CLOTHING</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Segoe UI', sans-serif;
    }

    body {
      background-color: #fff;
      color: #333;
    }

    header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 20px 50px;
      background-color: #111;
    }

    .logo {
      font-size: 28px;
      font-weight: bold;
      color: #fff;
      letter-spacing: 2px;
    }

    nav ul {
      display: flex;
      list-style: none;
    }

    nav ul li {
      margin-left: 30px;
    }

    nav ul li a {
      text-decoration: none;
      color: #fff;
      font-weight: 500;
      transition: color 0.3s;
      cursor: pointer;
    }

    nav ul li a:hover {
      color: #f39c12;
    }

    .hero {
      position: relative;
      height: 90vh;
      overflow: hidden;
    }

    .slideshow {
      height: 100%;
      width: 100%;
      position: absolute;
      z-index: -1;
    }

    .slide {
      width: 100%;
      height: 100%;
      object-fit: cover;
      display: none;
    }

    .hero-text {
      position: absolute;
      top: 40%;
      left: 50%;
      transform: translate(-50%, -40%);
      text-align: center;
      color: #fff;
      background: rgba(0, 0, 0, 0.5);
      padding: 40px;
      border-radius: 10px;
    }

    .hero-text h1 {
      font-size: 48px;
      margin-bottom: 10px;
    }

    .hero-text p {
      font-size: 18px;
      margin-bottom: 20px;
    }

    .cta {
      padding: 12px 30px;
      background: #f39c12;
      color: #fff;
      text-decoration: none;
      font-weight: bold;
      border-radius: 5px;
      transition: background 0.3s;
    }

    .cta:hover {
      background: #e67e22;
    }

    .features {
      padding: 60px 20px;
      text-align: center;
      background-color: #fafafa;
    }

    .features h2 {
      font-size: 32px;
      margin-bottom: 40px;
    }

    .feature-items {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 30px;
    }

    .feature {
      width: 300px;
      background: #fff;
      border-radius: 10px;
      padding: 30px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }

    .feature h3 {
      margin-bottom: 15px;
      color: #111;
    }

    .feature p {
      font-size: 14px;
      color: #555;
    }

    .modal {
      display: none;
      position: fixed;
      z-index: 1000;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      overflow: auto;
      background-color: rgba(0,0,0,0.6);
    }

    .modal-content {
      background-color: #fff;
      margin: 10% auto;
      padding: 30px;
      border: 1px solid #888;
      width: 90%;
      max-width: 400px;
      border-radius: 8px;
      position: relative;
    }

    .close {
      color: #aaa;
      position: absolute;
      top: 10px;
      right: 20px;
      font-size: 28px;
      font-weight: bold;
      cursor: pointer;
    }

    input[type="text"], input[type="password"] {
      width: 100%;
      padding: 10px;
      margin: 10px 0;
    }

    input[type="submit"] {
      padding: 10px 20px;
      background: #f39c12;
      border: none;
      color: #fff;
      cursor: pointer;
    }

    .error, .success {
      text-align: center;
      margin-top: 10px;
    }

    .error { color: red; }
    .success { color: green; }

    .welcome {
      text-align: center;
      padding: 20px;
      font-size: 20px;
    }
  </style>
</head>
<body>

<header>
  <div class="logo">INKED CLOTHING</div>
  <nav>
    <ul>
      <li><a href="#">Home</a></li>
      <li><a href="#">Shop</a></li>
      <li><a href="#">About</a></li>
      <li><a href="#">Contact</a></li>
      <?php if (isset($_SESSION['username'])): ?>
        <li><a href="?logout=true">Logout</a></li>
      <?php else: ?>
        <li><a onclick="document.getElementById('loginModal').style.display='block'">Login</a></li>
        <li><a onclick="document.getElementById('signupModal').style.display='block'">Sign Up</a></li>
      <?php endif; ?>
    </ul>
  </nav>
</header>

<section class="hero">
  <div class="slideshow">
    <img src="https://i.pinimg.com/736x/0b/64/c5/0b64c5e20dc5b9e6cc81bc6b50e2fb01--funky-dresses-evening-dresses.jpg" class="slide" />
    <img src="https://wallpapercave.com/wp/wp4021518.jpg" class="slide" />
    <img src="https://d27fp5ulgfd7w2.cloudfront.net/wp-content/uploads/2022/10/17173636/Black-Male-Fashion-Influencers-1920x1080-1.jpg" class="slide" />
    <img src="https://wallpaperaccess.com/full/8973351.jpg" class="slide" />
    <img src="https://wallpaperaccess.com/full/2489629.jpg" class="slide" />
    <img src="https://png.pngtree.com/thumb_back/fh260/background/20240603/pngtree-clothing-on-hangers-at-the-show-image_15739081.jpg" class="slide" />
    <img src="https://wallpaperaccess.com/full/6064769.jpg" class="slide" />
    <img src="https://t4.ftcdn.net/jpg/05/96/62/65/360_F_596626503_jrzjZNYStDexiWxQFqO7oCh6M8PdMlJs.jpg" class="slide" />
    <img src="https://png.pngtree.com/background/20230427/original/pngtree-hanging-on-a-rack-in-dark-room-picture-image_2494555.jpg" class="slide" />
    <img src="https://miro.medium.com/v2/resize:fit:1200/0*JTPFKy4fnpAHXEb8.jpeg" class="slide" />
  </div>
  <div class="hero-text">
    <h1>Style. Bold. You.</h1>
    <p>Discover bold fashion that speaks your identity.</p>
    <a href="#" class="cta">Explore Now</a>
  </div>
</section>

<section class="features">
  <h2>Why Choose INKED CLOTHING?</h2>
  <div class="feature-items">
    <div class="feature">
      <h3>Unique Designs</h3>
      <p>Bold and exclusive clothing pieces that express who you are.</p>
    </div>
    <div class="feature">
      <h3>Quality Fabrics</h3>
      <p>Crafted with premium materials for comfort and style.</p>
    </div>
    <div class="feature">
      <h3>Fast Delivery</h3>
      <p>Speedy shipping to get your outfits to your door in no time.</p>
    </div>
  </div>
</section>

<?php if (!isset($_SESSION['username'])): ?>
  <div id="loginModal" class="modal">
    <div class="modal-content">
      <span class="close" onclick="document.getElementById('loginModal').style.display='none'">&times;</span>
      <h2>Login</h2>
      <form method="post">
        <input type="text" name="username" placeholder="Username" required />
        <input type="password" name="password" placeholder="Password" required />
        <input type="submit" name="login" value="Login" />
      </form>
      <div class="error"><?= $loginError ?></div>
    </div>
  </div>

  <div id="signupModal" class="modal">
    <div class="modal-content">
      <span class="close" onclick="document.getElementById('signupModal').style.display='none'">&times;</span>
      <h2>Sign Up</h2>
      <form method="post">
        <input type="text" name="new_username" placeholder="New Username" required />
        <input type="password" name="new_password" placeholder="New Password" required />
        <input type="submit" name="signup" value="Sign Up" />
      </form>
      <div class="<?= strpos($signupMsg, 'successful') !== false ? 'success' : 'error' ?>">
        <?= $signupMsg ?>
      </div>
    </div>
  </div>
<?php else: ?>
  <div class="welcome">Welcome, <?= htmlspecialchars($_SESSION['username']) ?>!</div>
<?php endif; ?>

<script>
  let slides = document.querySelectorAll('.slide');
  let index = 0;

  function showSlides() {
    slides.forEach((slide, i) => {
      slide.style.display = i === index ? 'block' : 'none';
    });
    index = (index + 1) % slides.length;
  }

  showSlides();
  setInterval(showSlides, 3000);
</script>

</body>
</html>
