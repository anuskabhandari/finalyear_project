<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Forgot Password - Tourist Guide</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
* { box-sizing: border-box; }
body { font-family: 'Segoe UI', sans-serif; margin:0; padding:0; background: linear-gradient(to right,#00695c,#26a69a); display:flex; justify-content:center; align-items:center; height:100vh; }
.forgot-container { background:#fff; padding:40px; border-radius:10px; width:100%; max-width:400px; box-shadow:0 4px 15px rgba(0,0,0,0.2); animation:fadeIn 0.5s ease-in-out; }
.forgot-container h2 { text-align:center; margin-bottom:20px; color:#00796b; }
.forgot-container p { text-align:center; margin-bottom:10px; font-size:14px; color:#444; }
.form-group { margin-bottom:15px; }
.form-group label { display:block; margin-bottom:6px; font-weight:600; }
.form-group input { width:100%; padding:10px; border:1px solid #ccc; border-radius:6px; outline:none; font-size:14px; }
.submit-btn { width:100%; padding:12px; background-color:#00796b; color:white; font-size:16px; border:none; border-radius:6px; cursor:pointer; transition:0.3s; }
.submit-btn:hover { background-color:#004d40; }
.back-link { text-align:center; margin-top:15px; }
.back-link a { color:#00796b; text-decoration:none; font-weight:bold; }
.back-link a:hover { text-decoration:underline; }
.message { text-align:center; font-size:14px; margin-bottom:15px; color:red; }
#otp-group { display:none; }
@keyframes fadeIn { from {opacity:0; transform:translateY(-10px);} to {opacity:1; transform:translateY(0);} }
</style>
</head>
<body>

<div class="forgot-container">
  <h2>Forgot Password</h2>
  <p>Enter your email to reset your password</p>

  <form id="forgotForm">
    <div class="form-group">
      <label>Email</label>
      <input type="email" id="email" name="email" placeholder="Enter your email" required>
    </div>

    <div class="message" id="emailMessage"></div>

    <div class="form-group" id="otp-group">
      <label>Enter OTP</label>
      <input type="text" id="otp" name="otp" placeholder="Enter OTP" maxlength="6" pattern="\d{6}">
    </div>

    <button type="submit" class="submit-btn" id="sendBtn">Send Reset OTP</button>
    <button type="button" class="submit-btn" id="verifyBtn" style="display:none;">Verify OTP</button>

    <div class="back-link">
      <a href="login.php"><i class="fa-solid fa-arrow-left"></i> Back to Login</a>
    </div>
  </form>
</div>

<script>
const emailInput = document.getElementById('email');
const otpInput = document.getElementById('otp');
const emailMessage = document.getElementById('emailMessage');
const otpGroup = document.getElementById('otp-group');
const sendBtn = document.getElementById('sendBtn');
const verifyBtn = document.getElementById('verifyBtn');

// Send OTP
sendBtn.addEventListener('click', function(e){
    e.preventDefault();

    fetch('backend/forgetpassword.php', {
        method:'POST',
        headers:{'Content-Type':'application/x-www-form-urlencoded'},
        body: `email=${encodeURIComponent(emailInput.value)}`
    })
    .then(res=>res.json())
    .then(data=>{
        if(data.success){
            emailMessage.style.color='green';
            emailMessage.textContent='OTP sent to your email!';
            otpGroup.style.display='block';
            otpInput.required = true; // now make OTP required
            sendBtn.style.display='none';
            verifyBtn.style.display='block';
        } else {
            emailMessage.style.color='red';
            emailMessage.textContent=data.message;
        }
    })
    .catch(()=> {
        emailMessage.style.color='red';
        emailMessage.textContent='Error sending OTP';
    });
});

// Verify OTP
verifyBtn.addEventListener('click', function(){
    fetch('backend/verify_otp.php',{
        method:'POST',
        headers:{'Content-Type':'application/x-www-form-urlencoded'},
        body:`email=${encodeURIComponent(emailInput.value)}&otp=${encodeURIComponent(otpInput.value)}`
    })
    .then(res=>res.json())
    .then(data=>{
        if(data.success){
            window.location.href='resetpassword.php';
        } else {
            emailMessage.style.color='red';
            emailMessage.textContent=data.message;
        }
    })
    .catch(()=>{
        emailMessage.style.color='red';
        emailMessage.textContent='Error verifying OTP';
    });
});
</script>

</body>
</html>
