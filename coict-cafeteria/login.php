<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include("connection.php");

$msg = '';

if (isset($_POST['submit'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password']; // raw input

    $select = "SELECT * FROM users WHERE email = '$email'";
    $select_user = mysqli_query($conn, $select);

    if (mysqli_num_rows($select_user) > 0) {
        $row = mysqli_fetch_assoc($select_user);
        
        // Verify password (if password was hashed with password_hash())
        if (password_verify($password, $row['password'])) {
            if ($row['user_type'] == 'user') {
                $_SESSION['user'] = $row['email'];
                $_SESSION['user_id'] = $row['user_id'];
                header('Location: user-dashboard.php');
                exit;
            } elseif ($row['user_type'] == 'admin') {
                $_SESSION['admin'] = $row['email'];
                $_SESSION['id'] = $row['id'];
                header('Location: admin.php');
                exit;
            } elseif ($row['user_type'] == 'cashier') {
                $_SESSION['cashier'] = $row['email'];
                $_SESSION['id'] = $row['id'];
                header('Location: cashier.php');
                exit;
            }
        } else {
            $msg = "Incorrect email or password.";
        }
    } else {
        $msg = "User not found.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | CoICT Cafeteria</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>
<body>
    <div class="form">
        <form action="" method="post">
            <h2>Login</h2>
            <?php if ($msg != ''): ?>
                <p class="msg alert alert-danger"><?= $msg ?></p>
            <?php endif; ?>
            <div class="form-group">
                <input type="email" name="email" placeholder="Enter your email" class="form-control" required>
            </div><br>
            <div class="form-group">
                <input type="password" name="password" placeholder="Enter your password" class="form-control" required>
            </div><br>
            <button type="submit" name="submit" class="btn btn-primary font-weight-bold">Login</button>
            <div style="margin-top: 10px;">
                <a href="forgot-password.php">Forgot Password?</a>
            </div>
            <p>Don't have an account? <a href="register.php">Register Now</a></p>
        </form>
    </div>
</body>
</html>
