<?php
$host = "localhost";
$user = "root"; // default xampp
$pass = "";
$db   = "myshop";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) die("DB Connection failed: " . $conn->connect_error);

if (session_status() == PHP_SESSION_NONE) session_start();
?>
