<?php
require_once '../config/database.php';
require_once '../auth/check_session.php';
checkSession();

$conn = connectDB();

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    
    mysqli_begin_transaction($conn);
    
    try {
        // Dapatkan informasi penjualan
        $query_info = "SELECT produk_id, jumlah FROM penjualan WHERE id = $id";
        $result_info = mysqli_query($conn, $query_info);
        $info = mysqli_fetch_assoc($result_info);
        
        // Kembalikan stok
        $query_update_stok = "UPDATE produk SET stok = stok + {$info['jumlah']} 
                             WHERE id = {$info['produk_id']}";
        mysqli_query($conn, $query_update_stok);
        
        // Hapus penjualan
        $query_delete = "DELETE FROM penjualan WHERE id = $id";
        mysqli_query($conn, $query_delete);
        
        // Perbarui statistik (opsional jika menggunakan cache)
        $cache_file = '../cache/dashboard.json';
        if (file_exists($cache_file)) {
            unlink($cache_file); // Hapus cache agar data baru dimuat
        }
        
        mysqli_commit($conn);
        header('Location: ../penjualan.php?status=success&message=Data penjualan berhasil dihapus');
    } catch (Exception $e) {
        mysqli_rollback($conn);
        header('Location: ../penjualan.php?status=error&message=Gagal menghapus data penjualan');
    }
}
