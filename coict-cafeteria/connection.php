<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
<?php
$servername = "localhost";
$username = "root";
$password = "newpassword";
$dbname = "CoICT_CAFETERIA";
$conn = new mysqli($servername, $username, $password, $dbname);

//check connection
if($conn->connect_error){
    die("connection failed: " . $conn->connect_error);
}
//echo "connected successfully";
?>
