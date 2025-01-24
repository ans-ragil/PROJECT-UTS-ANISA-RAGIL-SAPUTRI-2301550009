<?php
require_once '../auth/check_session.php';
checkSession();
require_once '../config/database.php';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $conn = connectDB();
    
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $nama_lengkap = mysqli_real_escape_string($conn, $_POST['nama_lengkap']);
    $role = mysqli_real_escape_string($conn, $_POST['role']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    
    $query = "INSERT INTO users (username, password, nama_lengkap, role, status) 
              VALUES ('$username', '$password', '$nama_lengkap', '$role', '$status')";
    
    if(mysqli_query($conn, $query)) {
        $_SESSION['message'] = "User berhasil ditambahkan";
    } else {
        $_SESSION['message'] = "Error: " . mysqli_error($conn);
    }
    
    header('Location: ../users.php');
    exit();
}
?>