<?php
session_start();

// Redirect if not logged in as admin
if (!isset($_SESSION['user'])) {
    header('Location: ../auth/login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard | CoICT Cafeteria</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../style.css"> <!-- Optional: external styles -->
    <style>
        body {
            margin: 0;
            padding: 0;
            background-image:url('images/CoICT.jpg');
            font-family: 'sans-serif';
        }

        .dashboard-container {
            max-width: 800px;
            margin: 5% auto;
            background: #ffffff;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        h2 {
            color: #0a66f0;
            margin-bottom: 1.5rem;
        }

        .nav-links a {
            display: inline-block;
            margin: 0 10px;
            padding: 10px 20px;
            background-color: #0a66f0;
            color: #fff;
            border-radius: 8px;
            text-decoration: none;
            transition: background 0.3s ease;
        }

        .nav-links a:hover {
            background-color: #0846a8;
        }

        .logout-link {
            background-color: #d32f2f !important;
        }

        .logout-link:hover {
            background-color: #a31414 !important;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <h2>Welcome customer, <?php echo htmlspecialchars($_SESSION['user_id']); ?>! at CoICT Cafeteria</h2>

        <div class="nav-links">
            <a href="menu.php">View Menu & Place Order</a>
           
            <a href="../users/manage-users.php">Order History</a>
            <a href="login.php" class="logout-link">Logout</a>
        </div>
    </div>
</body>
</html>

