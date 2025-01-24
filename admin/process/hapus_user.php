<?php
require_once '../auth/check_session.php';
checkSession();
require_once '../config/database.php';

if(isset($_GET['id'])) {
    $conn = connectDB();
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    
    // Cek apakah user yang akan dihapus bukan user yang sedang login
    if($id != $_SESSION['user_id']) {
        $query = "DELETE FROM users WHERE id = $id";
        
        if(mysqli_query($conn, $query)) {
            $_SESSION['message'] = "User berhasil dihapus";
        } else {
            $_SESSION['message'] = "Error: " . mysqli_error($conn);
        }
    } else {
        $_SESSION['message'] = "Tidak dapat menghapus user yang sedang aktif";
    }
    
    header('Location: ../users.php');
    exit();
}
?>