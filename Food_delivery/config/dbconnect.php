<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "food_delivery";
$port = 3307; // or 3306 if default works

$conn = new mysqli($servername, $username, $password, $dbname, $port);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
