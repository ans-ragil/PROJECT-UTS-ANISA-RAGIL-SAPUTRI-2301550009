<?php
require_once '../auth/check_session.php';
checkSession();
require_once '../config/database.php';

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    $conn = connectDB();
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    
    $query = "SELECT id, username, nama_lengkap, role, status FROM users WHERE id = $id";
    $result = mysqli_query($conn, $query);
    
    if($row = mysqli_fetch_assoc($result)) {
        echo json_encode($row);
    } else {
        echo json_encode(['error' => 'User tidak ditemukan']);
    }
    exit();
}
?>