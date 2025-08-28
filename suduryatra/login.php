<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Login - Tourist Guide</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <style>
    * { box-sizing: border-box; }
    body {
      font-family: 'Segoe UI', sans-serif;
      margin: 0; padding: 0;
      background: linear-gradient(to right, #00695c, #26a69a);
      display: flex; justify-content: center; align-items: center;
      height: 100vh;
    }
    .login-container {
      background-color: #fff;
      padding: 40px;
      border-radius: 10px;
      width: 100%;
      max-width: 400px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    }
    .login-container h2 {
      text-align: center;
      margin-bottom: 15px;
      color: #00796b;
    }
    .form-group { margin-bottom: 20px; }
    .form-group label { display: block; margin-bottom: 6px; font-weight: 600; }
    .form-group input {
      width: 100%; padding: 10px; border: 1px solid #ccc;
      border-radius: 6px; outline: none; font-size: 14px;
    }
    .login-btn {
      width: 100%; padding: 12px; background-color: #00796b;
      color: white; font-size: 16px; border: none; border-radius: 6px;
      cursor: pointer; transition: background-color 0.3s;
    }
    .login-btn:hover { background-color: #004d40; }
    .register-link { text-align: center; margin-top: 15px; }
    .register-link a { color: #00796b; text-decoration: none; font-weight: bold; }
    .register-link a:hover { text-decoration: underline; }
    #togglePassword { position: absolute; top: 38px; right: 10px; cursor: pointer; color: #888; font-size:18px; }
  </style>
</head>
<body>

<div class="login-container">
  <h2>Login to Tourist Guide</h2>
  
  <?php
  session_start();
  if(isset($_SESSION['error'])){
      echo "<p style='color:red; font-weight:bold; margin-bottom:15px;'>".$_SESSION['error']."</p>";
      unset($_SESSION['error']);
  }
  if(isset($_SESSION['success'])){
      echo "<p style='color:green; font-weight:bold; margin-bottom:15px;'>".$_SESSION['success']."</p>";
      unset($_SESSION['success']);
  }
  ?>

  <form action="backend/login.php" method="post" autocomplete="off">
    <div class="form-group">
      <label>Email</label>
      <input type="email" name="email" placeholder="Enter your email" required autocomplete="off" />
    </div>
    
    <div class="form-group" style="position: relative;">
      <label>Password</label>
      <input type="password" id="password" name="pass" placeholder="Enter your password" required autocomplete="new-password" />
      <i class="fa-solid fa-eye" id="togglePassword"></i>
    </div>

  <button type="submit" name="login-btn" class="login-btn">Login</button>
    <a href="forgetpassword.php">Forget your password?</a>

    <div class="register-link">
      Don't have an account? <a href="register.php">Register here</a>
    </div>
    <div style="text-align:center; margin-top:20px;">
    <a href="index.php" style="color:#00796b; font-weight:bold; text-decoration:none;">
      ← Back to Home
    </a>
  </div>
  </form>
</div>

<script>
const passwordInput = document.getElementById("password");
const togglePassword = document.getElementById("togglePassword");

togglePassword.addEventListener("click", () => {
  if (passwordInput.type === "password") {
    passwordInput.type = "text";
    togglePassword.classList.remove("fa-eye");
    togglePassword.classList.add("fa-eye-slash");
  } else {
    passwordInput.type = "password";
    togglePassword.classList.remove("fa-eye-slash");
    togglePassword.classList.add("fa-eye");
  }
});
</script>
</body>
</html>
