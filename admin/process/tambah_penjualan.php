<?php
require_once '../config/database.php';
require_once '../auth/check_session.php';
checkSession();

$conn = connectDB();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $customer = mysqli_real_escape_string($conn, $_POST['customer']);
    $produk_id = (int)$_POST['produk_id'];
    $jumlah = (int)$_POST['jumlah'];
    $harga_satuan = (float)$_POST['harga_satuan'];
    $total = $jumlah * $harga_satuan;
    $user_id = (int)$_POST['user_id'];
    $tanggal = mysqli_real_escape_string($conn, $_POST['tanggal']) ?: date('Y-m-d'); // Gunakan tanggal dari input atau default ke hari ini

    // Validasi stok
    $query_stok = "SELECT stok FROM produk WHERE id = $produk_id";
    $result_stok = mysqli_query($conn, $query_stok);
    $stok = mysqli_fetch_assoc($result_stok)['stok'];

    if ($jumlah <= $stok) {
        // Begin transaction
        mysqli_begin_transaction($conn);
        
        try {
            // Insert penjualan
            $query = "INSERT INTO penjualan (customer, produk_id, jumlah, harga_satuan, total, user_id, tanggal) 
                     VALUES ('$customer', $produk_id, $jumlah, $harga_satuan, $total, $user_id, '$tanggal')";
            mysqli_query($conn, $query);

            // Update stok
            $query_update_stok = "UPDATE produk SET stok = stok - $jumlah WHERE id = $produk_id";
            mysqli_query($conn, $query_update_stok);

            mysqli_commit($conn);
            header('Location: ../penjualan.php?status=success&message=Penjualan berhasil ditambahkan');
        } catch (Exception $e) {
            mysqli_rollback($conn);
            header('Location: ../penjualan.php?status=error&message=Gagal menambahkan penjualan');
        }
    } else {
        header('Location: ../penjualan.php?status=error&message=Stok tidak mencukupi');
    }
}
?>
