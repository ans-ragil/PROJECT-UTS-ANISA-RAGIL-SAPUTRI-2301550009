<?php
require_once '../auth/check_session.php';
checkSession();
require_once '../config/database.php';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $conn = connectDB();
    
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $nama_lengkap = mysqli_real_escape_string($conn, $_POST['nama_lengkap']);
    $role = mysqli_real_escape_string($conn, $_POST['role']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    
    if(!empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $query = "UPDATE users SET 
                  username = '$username',
                  password = '$password',
                  nama_lengkap = '$nama_lengkap',
                  role = '$role',
                  status = '$status'
                  WHERE id = $id";
    } else {
        $query = "UPDATE users SET 
                  username = '$username',
                  nama_lengkap = '$nama_lengkap',
                  role = '$role',
                  status = '$status'
                  WHERE id = $id";
    }
    
    if(mysqli_query($conn, $query)) {
        $_SESSION['message'] = "Data user berhasil diupdate";
    } else {
        $_SESSION['message'] = "Error: " . mysqli_error($conn);
    }
    
    header('Location: ../users.php');
    exit();
}
?>