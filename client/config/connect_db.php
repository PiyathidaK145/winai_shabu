<?php
$servername = "localhost";
$username = "root";
$password = "123456"; 
$dbname = "a_shabu";

$conn = new mysqli($servername, $username, $password, $dbname);
// เชื่อมต่อฐานข้อมูล
$conn = new mysqli($host, $user, $password, $database);
if ($conn->connect_error) {
    die("❌ การเชื่อมต่อล้มเหลว: " . $conn->connect_error);
}
