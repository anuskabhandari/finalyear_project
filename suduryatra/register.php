<?php
  require "backend/login.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Register - Tourist Guide</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      margin: 0;
      background: #e0f2f1;
    }

    header {
      background: linear-gradient(to right, #00796b, #43a047);
      padding: 20px;
      text-align: center;
      color: white;
      font-size: 26px;
    }

    .container {
      max-width: 500px;
      margin: 40px auto;
      background-color: #ffffff;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    h2 {
      text-align: center;
      color: #00796b;
      margin-bottom: 20px;
    }

    .form-group {
      margin-bottom: 18px;
    }

    .form-group label {
      display: block;
      font-weight: bold;
      margin-bottom: 6px;
    }

    .form-group input {
      width: 100%;
      padding: 10px;
      border-radius: 6px;
      border: 1px solid #ccc;
    }

    .submit-btn {
      background-color: #00796b;
      color: white;
      width: 100%;
      padding: 12px;
      font-size: 16px;
      border: none;
      border-radius: 6px;
      cursor: pointer;
    }

    .submit-btn:hover {
      background-color: #004d40;
    }

    .login-link {
      display: block;
      text-align: center;
      margin-top: 15px;
      color: #00796b;
      text-decoration: none;
      font-weight: bold;
    }

    .login-link:hover {
      text-decoration: underline;
    }
    .form-group select {
    width: 100%;
    padding: 10px;
    border-radius: 6px;
    border: 1px solid #ccc;
    font-size: 14px;
    background-color: #f9f9f9;
    color: #333;
    appearance: none;
    outline: none;
    cursor: pointer;
    transition: border 0.3s, box-shadow 0.3s;
  }

  .form-group select:focus {
    border: 1px solid #00796b;
    box-shadow: 0 0 5px rgba(0,121,107,0.5);
  }

  .form-group::after {
    content: "\f078"; /* Font Awesome down arrow */
    font-family: "Font Awesome 6 Free";
    font-weight: 900;
    position: absolute;
    right: 12px;
    top: 40px;
    pointer-events: none;
    color: #00796b;
  }
  </style>
</head>
<body>

<header>
  Create Your Account - Tourist Guide
</header>

<div class="container">
  <h2>Register</h2>
  <form action="" method="post" >

    <div class="form-group">
      <label>First Name</label>
      <input type="text" name="first_name" required />
    </div>

    <div class="form-group">
      <label>Last Name</label>
      <input type="text" name="last_name" required />
    </div>

    <div class="form-group">
      <label>Email</label>
      <input type="email" name="email" required />
    </div>
    <div class="form-group">
    <label>Select Role</label>
    <select name="role" required>
      <option value="">-- Choose a role --</option>
      <option value="2">User</option>
      <option value="3">Tour Operator</option>
    </select>
  </div>

   <div class="form-group" style="position: relative;">
      <label>Password</label>
      <input type="password" id="password" name="password" required minlength="6" />
       <i class="fa fa-eye" id="togglePassword" style="cursor:pointer; position:absolute; top:38px; right:10px;"></i>
    </div>

   <div class="form-group" style="position: relative;">
      <label>Confirm Password</label>
      <input type="password" id="confirmPassword" name="confirm_password" required />
 <i class="fa fa-eye" id="toggleConfirmPassword" style="cursor:pointer; position:absolute; top:38px; right:10px;"></i>
    </div>
<button type="submit" name="signup-btn" class="submit-btn">Register</button>

  </form>

  <a class="login-link" href="login.php">Already have an account? Login here</a>
</div>
<script>
  function validatePassword() {
    var pw = document.getElementById("password").value;
    var cpw = document.getElementById("confirmPassword").value;
    if (pw !== cpw) {
      alert("Passwords do not match!");
      return false;
    }
    return true;
  }
</script>
<script>
  // Password toggle for main password
  document.getElementById("togglePassword").addEventListener("click", function () {
    const input = document.getElementById("password");
    const type = input.type === "password" ? "text" : "password";
    input.type = type;
    this.classList.toggle("fa-eye");
    this.classList.toggle("fa-eye-slash");
  });

  // Password toggle for confirm password
  document.getElementById("toggleConfirmPassword").addEventListener("click", function () {
    const input = document.getElementById("confirmPassword");
    const type = input.type === "password" ? "text" : "password";
    input.type = type;
    this.classList.toggle("fa-eye");
    this.classList.toggle("fa-eye-slash");
  });
</script>

</body>
</html>
