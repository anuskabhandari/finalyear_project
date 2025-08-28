<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require "func.php";

// -------------------- LOGIN --------------------
if (isset($_POST['login-btn'])) {
    $email = trim($_POST['email'] ?? '');
    $pass = trim($_POST['pass'] ?? '');
    $errors = [];

    if (empty($email) || empty($pass)) {
        $errors[] = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    if (empty($errors)) {
        $role = login($email, $pass);

        if ($role === 1) {
            header("Location: ../admin/admin_dashboard.php");
            exit();
        } elseif ($role == 2) {
            header("Location: ../dashboard.php");
            exit();
        } elseif ($role == 3) {
            header("Location: ../touroperator/tour_operatordashboard.php");
            exit();
        } elseif ($role === 'pending') {
            $_SESSION['error'] = "❌ Your account is pending admin approval.";
        } elseif ($role === 'rejected') {
            $_SESSION['error'] = "❌ Your account has been rejected by admin.";
        } else {
            $_SESSION['error'] = "❌ Invalid email or password.";
        }
    } else {
        $_SESSION['error'] = implode('<br>', $errors);
    }

    header("Location: ../login.php");
    exit();
}

// -------------------- SIGNUP --------------------
if (isset($_POST['signup-btn'])) {
    $fname = trim($_POST['first_name'] ?? '');
    $lname = trim($_POST['last_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $pass = trim($_POST['password'] ?? '');
    $role = $_POST['role'] ?? '';
    $errors = [];

    if (empty($fname) || empty($lname) || empty($email) || empty($pass) || empty($role)) {
        $errors[] = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    $uppercase = preg_match('@[A-Z]@', $pass);
    $lowercase = preg_match('@[a-z]@', $pass);
    $number = preg_match('@[0-9]@', $pass);
    $specialChars = preg_match('@[^\w]@', $pass);

    if (!$uppercase || !$lowercase || !$number || !$specialChars || strlen($pass) < 8) {
        $errors[] = "Password must be at least 8 characters and include uppercase, lowercase, number, and special character.";
    }

    if (empty($errors)) {
        if (signup($fname, $lname, $email, $pass, $role)) {
            $_SESSION['success'] = "✅ Signup successful. Please log in.";
        } else {
            $_SESSION['error'] = "❌ Signup failed. Email might already be in use.";
        }
    } else {
        $_SESSION['error'] = implode('<br>', $errors);
    }

    header("Location: ../register.php");
    exit();
}

// -------------------- LOGIN FUNCTION --------------------
function login($email, $pass) {
    $mysqli = dbConnect();
    $stmt = $mysqli->prepare("SELECT * FROM user WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row) {
        $fetch_pass = $row['password'];
        $pass = trim($pass);

        if ((substr($fetch_pass,0,4)==='$2y$'||substr($fetch_pass,0,4)==='$2a$') && password_verify($pass, $fetch_pass)) {
            if ($row['role_id']==3) {
                if($row['status']=='pending') return 'pending';
                if($row['status']=='rejected') return 'rejected';
            }
            $_SESSION['user_id']=$row['user_id'];
            $_SESSION['role_id']=$row['role_id'];
            return $row['role_id'];
        } elseif($pass === trim($fetch_pass)) {
            if ($row['role_id']==3) {
                if($row['status']=='pending') return 'pending';
                if($row['status']=='rejected') return 'rejected';
            }
            $_SESSION['user_id']=$row['user_id'];
            $_SESSION['role_id']=$row['role_id'];
            return $row['role_id'];
        }
    }

    $stmt->close();
    $mysqli->close();
    return false;
}

// -------------------- SIGNUP FUNCTION --------------------
function signup($firstname, $lastname, $email, $password, $role) {
    $mysqli = dbConnect();
    $check = $mysqli->prepare("SELECT * FROM user WHERE email=?");
    $check->bind_param("s",$email);
    $check->execute();
    $check->store_result();

    if($check->num_rows>0){ $check->close(); $mysqli->close(); return false; }
    $check->close();

    $hashed_password = password_hash($password,PASSWORD_DEFAULT);
    $status = ($role==3)?'pending':'approved';

    $stmt = $mysqli->prepare("INSERT INTO user(first_name,last_name,password,email,role_id,status) VALUES(?,?,?,?,?,?)");
    $stmt->bind_param("ssssds",$firstname,$lastname,$hashed_password,$email,$role,$status);
    $result = $stmt->execute();
    $stmt->close();
    $mysqli->close();
    return $result;
}
?>

<!-- -------------------- HTML + JS for auto-disappearing messages -------------------- -->
<?php if(isset($_SESSION['error'])): ?>
    <div id="msg" style="background: #f8d7da; color: #842029; padding: 10px; margin-bottom: 10px;">
        <?= $_SESSION['error']; unset($_SESSION['error']); ?>
    </div>
<?php endif; ?>

<?php if(isset($_SESSION['success'])): ?>
    <div id="msg" style="background: #d1e7dd; color: #0f5132; padding: 10px; margin-bottom: 10px;">
        <?= $_SESSION['success']; unset($_SESSION['success']); ?>
    </div>
<?php endif; ?>

<script>
    setTimeout(function() {
        var msg = document.getElementById('msg');
        if(msg) msg.style.display = 'none';
    }, 3000); // disappears after 3 seconds
</script>
