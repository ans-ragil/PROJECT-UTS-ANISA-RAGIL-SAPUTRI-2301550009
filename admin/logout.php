<?php
session_start();
require_once 'config/database.php';
$conn = connectDB();

// Log logout activity
if(isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $ip = $_SERVER['REMOTE_ADDR'];
    $query = "INSERT INTO login_logs (user_id, ip_address, status, action) VALUES (?, ?, 'success', 'logout')";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "is", $user_id, $ip);
    mysqli_stmt_execute($stmt);
}

session_destroy();
header("Location: login.php");
exit();
