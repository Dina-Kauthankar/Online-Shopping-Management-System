<?php
$host = "localhost";      // or "127.0.0.1"
$user = "root";           // your MySQL username
$pass = "";               // your MySQL password (keep empty if using XAMPP default)
$db   = "online_shopping_DB2"; // your database name

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("<h3 style='color:red;'>Connection failed: " . mysqli_connect_error() . "</h3>");
}
// echo "<h3 style='color:green;'>Database connected successfully!</h3>"; // for testing
?>
