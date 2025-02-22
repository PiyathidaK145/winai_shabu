<?php
$servername = "localhost";
$username = "root";
$password = "123456";
$dbname = "a_shabu";

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "เชื่อมต่อฐานข้อมูลสำเร็จ!";
} catch (PDOException $e) {
    echo "เชื่อมต่อฐานข้อมูลไม่สำเร็จ: " . $e->getMessage();
}
?>
