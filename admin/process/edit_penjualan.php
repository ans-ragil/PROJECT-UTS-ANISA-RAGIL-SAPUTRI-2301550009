<?php
require_once '../config/database.php';
require_once '../auth/check_session.php';
checkSession();

$conn = connectDB();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)$_POST['id'];
    $produk_id = (int)$_POST['produk_id'];
    $jumlah_lama = (int)$_POST['jumlah_lama']; // Jumlah sebelum diubah
    $jumlah_baru = (int)$_POST['jumlah_baru'];

    mysqli_begin_transaction($conn);

    try {
        // Perbarui stok produk
        if ($jumlah_baru > $jumlah_lama) {
            // Jika jumlah baru lebih besar, kurangi stok
            $stok_delta = $jumlah_baru - $jumlah_lama;
            $query_update_stok = "UPDATE produk SET stok = stok - $stok_delta WHERE id = $produk_id";
        } elseif ($jumlah_baru < $jumlah_lama) {
            // Jika jumlah baru lebih kecil, tambahkan stok
            $stok_delta = $jumlah_lama - $jumlah_baru;
            $query_update_stok = "UPDATE produk SET stok = stok + $stok_delta WHERE id = $produk_id";
        }

        if (isset($query_update_stok)) {
            mysqli_query($conn, $query_update_stok);
        }

        // Perbarui data penjualan
        $query_update_penjualan = "UPDATE penjualan SET produk_id = $produk_id, jumlah = $jumlah_baru WHERE id = $id";
        mysqli_query($conn, $query_update_penjualan);

        mysqli_commit($conn);
        header('Location: ../penjualan.php?status=success&message=Data penjualan berhasil diperbarui');
    } catch (Exception $e) {
        mysqli_rollback($conn);
        header('Location: ../penjualan.php?status=error&message=Gagal memperbarui data penjualan');
    }
} elseif (isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    // Ambil data penjualan untuk ditampilkan di formulir
    $query = "SELECT 
                  id, 
                  produk_id, 
                  jumlah, 
                  harga_satuan
              FROM penjualan 
              WHERE id = $id";
    $result = mysqli_query($conn, $query);
    $penjualan = mysqli_fetch_assoc($result);

    if (!$penjualan) {
        header('Location: ../penjualan.php?status=error&message=Data tidak ditemukan');
        exit();
    }

    // Ambil daftar produk untuk dropdown
    $query_produk = "SELECT id, Nama_Produk FROM produk";
    $result_produk = mysqli_query($conn, $query_produk);
    $produk_options = '';
    while ($row = mysqli_fetch_assoc($result_produk)) {
        $selected = ($row['id'] == $penjualan['produk_id']) ? 'selected' : '';
        $produk_options .= "<option value='{$row['id']}' {$selected}>{$row['Nama_Produk']}</option>";
    }
} else {
    header('Location: ../penjualan.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Penjualan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Edit Penjualan</h2>
        <form action="edit_penjualan.php" method="POST">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($penjualan['id']); ?>">
            <input type="hidden" name="jumlah_lama" value="<?php echo htmlspecialchars($penjualan['jumlah']); ?>">

            <div class="mb-3">
                <label for="produk_id" class="form-label">Produk</label>
                <select class="form-select" name="produk_id" id="produk_id" required>
                    <option value="">Pilih Produk</option>
                    <?php echo $produk_options; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="jumlah_baru" class="form-label">Jumlah Penjualan</label>
                <input type="number" class="form-control" id="jumlah_baru" name="jumlah_baru" min="1" value="<?php echo htmlspecialchars($penjualan['jumlah']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="harga_satuan" class="form-label">Harga Satuan</label>
                <input type="number" class="form-control" id="harga_satuan" value="<?php echo htmlspecialchars($penjualan['harga_satuan']); ?>" disabled>
            </div>
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            <a href="../penjualan.php" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</body>
</html>
