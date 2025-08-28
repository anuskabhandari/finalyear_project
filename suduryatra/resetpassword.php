<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Reset Password - Tourist Guide</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
/* Same styling as before */
body {
    font-family: 'Segoe UI', sans-serif;
    margin: 0;
    padding: 0;
    background: linear-gradient(to right, #00695c, #26a69a);
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}
.reset-container { background:#fff; padding:40px; border-radius:10px; width:100%; max-width:400px; box-shadow:0 4px 15px rgba(0,0,0,0.2); text-align:center; }
.reset-container h2 { color:#00796b; margin-bottom:10px; }
.reset-container p { margin-bottom:25px; color:#555; font-size:14px; }
.form-group { margin-bottom:20px; text-align:left; }
.form-group label { display:block; margin-bottom:6px; font-weight:600; color:#333; }
.form-group input { width:100%; padding:10px; border:1px solid #ccc; border-radius:6px; outline:none; font-size:14px; }
.form-group input:focus { border-color:#00796b; box-shadow:0 0 5px rgba(0,121,107,0.3); }
#message { margin-bottom:15px; font-weight:600; }
.submit-btn { width:100%; padding:12px; background-color:#00796b; color:white; font-size:16px; border:none; border-radius:6px; cursor:pointer; transition:0.3s; }
.submit-btn:hover { background-color:#004d40; }
.back-link { margin-top:15px; }
.back-link a { color:#00796b; text-decoration:none; font-weight:bold; }
.back-link a:hover { text-decoration:underline; }
@media (max-width:480px){ .reset-container{ padding:30px 20px; } }
</style>
</head>
<body>

<div class="reset-container">
  <h2>Reset Password</h2>
  <p>Enter your new password below</p>

  <form id="resetForm">
    <div class="form-group">
      <label>New Password</label>
      <input type="password" id="password" name="password" placeholder="Enter new password" required />
    </div>
    <div class="form-group">
      <label>Confirm Password</label>
      <input type="password" id="confirm_password" placeholder="Confirm new password" required />
    </div>

    <div id="message"></div>

    <button type="submit" class="submit-btn">Reset Password</button>
    <div class="back-link">
      <a href="login.php"><i class="fa-solid fa-arrow-left"></i> Back to Login</a>
    </div>
  </form>
</div>

<script>
document.getElementById('resetForm').addEventListener('submit', function(e){
    e.preventDefault();
    const password = document.getElementById('password').value;
    const confirm_password = document.getElementById('confirm_password').value;
    const messageDiv = document.getElementById('message');

    if(password !== confirm_password){
        messageDiv.innerHTML = "Passwords do not match!";
        messageDiv.style.color = 'red';
        return;
    }

    fetch('backend/reset_password.php', {
        method: 'POST',
        headers: {'Content-Type':'application/x-www-form-urlencoded'},
        body: `password=${encodeURIComponent(password)}`
    })
    .then(res => res.json())
    .then(data => {
        messageDiv.innerHTML = data.message;
        messageDiv.style.color = data.success ? 'green' : 'red';
        if(data.success){
            setTimeout(()=>{ window.location.href='login.php'; }, 2000);
        }
    })
    .catch(()=>{
        messageDiv.innerHTML = "Error resetting password.";
        messageDiv.style.color = 'red';
    });
});
</script>

</body>
</html>
