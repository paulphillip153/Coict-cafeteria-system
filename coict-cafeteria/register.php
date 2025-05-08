<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include("connection.php");

$msg = '';

if (isset($_POST['submit'])) {
    $name       = mysqli_real_escape_string($conn, $_POST['name']);
    $email      = mysqli_real_escape_string($conn, $_POST['email']);
    $password   = $_POST['password'];
    $cpassword  = $_POST['cpassword'];
    $user_type  = $_POST['user_type'];

    // Check if passwords match
    if ($password !== $cpassword) {
        $msg = "Passwords do not match!";
    } else {
        // Check if user already exists
        $check_sql = "SELECT * FROM users WHERE email = ?";
        $stmt = mysqli_prepare($conn, $check_sql);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            $msg = "User already exists!";
        } else {
            // Hash password
           $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert new user
            $insert_sql = "INSERT INTO users (name, email, password, user_type) VALUES (?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $insert_sql);
            mysqli_stmt_bind_param($stmt, "ssss", $name, $email, $hashed_password, $user_type);
            mysqli_stmt_execute($stmt);

            header('Location: login.php');
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | CoICT Cafeteria</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>
<body>
    <div class="form">
        <form action="" method="post">
            <h2>Create Account</h2>
            <?php if ($msg != ''): ?>
                <p class="msg alert alert-danger"><?= $msg ?></p>
            <?php endif; ?>
            <div class="form-group">
                <input type="text" name="name" placeholder="Enter your name" class="form-control" required>
            </div><br>
            <div class="form-group">
                <input type="email" name="email" placeholder="Enter your email" class="form-control" required>
            </div><br>
            <div class="form-group">
                <select name="user_type" class="form-control">
                    <option value="user">User</option>
                    <option value="admin">Admin</option>
                    <option value="cashier">Cashier</option>
                </select>
            </div><br>
            <div class="form-group"> 
                <input type="password" name="password" placeholder="Enter your password" class="form-control" required>
            </div><br>
            <div class="form-group">
                <input type="password" name="cpassword" placeholder="Confirm your password" class="form-control" required>
            </div><br>
            <button type="submit" name="submit" class="btn btn-success font-weight-bold">Register</button>
            <p>Already have an account? <a href="login.php">Login Now</a></p>
        </form>
    </div>
</body>
</html>
