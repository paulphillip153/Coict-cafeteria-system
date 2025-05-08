<?php
session_start();
include("connection.php");

$msg = '';

if (isset($_POST['submit'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    // Check if email exists
    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $_SESSION['reset_email'] = $email;
        header('Location: reset-password.php');
        exit;
    } else {
        $msg = "No account found with that email.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password | CoICT Cafeteria</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css"> <!-- Your custom styles -->
</head>
<body>
    <div class="form">
        <form action="" method="post">
            <h2>Forgot Password</h2>
            <?php if ($msg): ?>
                <p class="msg"><?= $msg ?></p>
            <?php endif; ?>
            <div class="form-group">
                <input type="email" name="email" placeholder="Enter your registered email" class="form-control" required>
            </div><br>
            <button type="submit" name="submit" class="btn">Send Reset Link</button>
            <p><a href="login.php">Back to Login</a></p>
        </form>
    </div>
</body>
</html>
