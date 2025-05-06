<?php
$host = 'db4free.net';        // external host
$db   = 'homedirect';         // your db name
$user = 'homedirect_user';    // your db username
$pass = 'yourpassword';       // your db password

try {
    $conn = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
