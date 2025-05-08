<?php
session_start();
include("connection.php");

$msg = '';
$success = '';

if (!isset($_SESSION['reset_email'])) {
    header('Location: forgot-password.php');
    exit;
}

if (isset($_POST['reset'])) {
    $email = $_SESSION['reset_email'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if ($new_password !== $confirm_password) {
        $msg = "Passwords do not match!";
    } else {
        // Hash the new password
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        // Update the password in the database
        $update = "UPDATE users SET password = '$hashed_password' WHERE email = '$email'";
        $result = mysqli_query($conn, $update);

        if ($result) {
            $success = "Password has been reset successfully. You can now <a href='login.php'>login</a>.";
            session_unset(); // Clear the session email
            session_destroy();
        } else {
            $msg = "Failed to reset password. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password | CoICT Cafeteria</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css"> <!-- Your custom CSS -->
</head>
<body>
    <div class="form">
        <form action="" method="post">
            <h2>Reset Password</h2>
            <?php if ($msg): ?>
                <p class="msg"><?= $msg ?></p>
            <?php endif; ?>
            <?php if ($success): ?>
                <p class="msg" style="color: green;"><?= $success ?></p>
            <?php else: ?>
                <div class="form-group">
                    <input type="password" name="new_password" placeholder="Enter new password" class="form-control" required>
                </div><br>
                <div class="form-group">
                    <input type="password" name="confirm_password" placeholder="Confirm new password" class="form-control" required>
                </div><br>
                <button type="submit" name="reset" class="btn">Reset Password</button>
                <p><a href="login.php">Back to Login</a></p>
            <?php endif; ?>
        </form>
    </div>
</body>
</html>
