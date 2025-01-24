<?php if (isset($_GET['status']) && isset($_GET['message'])): ?>
    <div class="alert alert-<?php echo $_GET['status'] == 'success' ? 'success' : 'danger'; ?> alert-dismissible fade show" role="alert">
        <?php echo htmlspecialchars($_GET['message']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>
<?php
require_once 'auth/check_session.php';
checkSession();
require_once 'config/database.php';
$conn = connectDB();

// Query untuk mengambil data penjualan terbaru
$query_penjualan = "SELECT p.*, pr.nama_produk, u.nama_lengkap as nama_user 
                    FROM penjualan p 
                    LEFT JOIN produk pr ON p.produk_id = pr.id 
                    LEFT JOIN users u ON p.user_id = u.id
                    ORDER BY p.tanggal DESC 
                    LIMIT 10";
$result_penjualan = $conn->query($query_penjualan);

// Query untuk menghitung total penjualan hari ini
$query_hari_ini = "SELECT SUM(total) as total_hari_ini, COUNT(*) as transaksi_hari_ini FROM penjualan WHERE DATE(tanggal) = CURDATE()";
$result_hari_ini = $conn->query($query_hari_ini);
$row_hari_ini = $result_hari_ini->fetch_assoc();

// Query untuk menghitung total penjualan bulan ini
$query_bulan_ini = "SELECT SUM(total) as total_bulan_ini FROM penjualan WHERE MONTH(tanggal) = MONTH(CURDATE()) AND YEAR(tanggal) = YEAR(CURDATE())";
$result_bulan_ini = $conn->query($query_bulan_ini);
$row_bulan_ini = $result_bulan_ini->fetch_assoc();

$penjualan_hari_ini = $row_hari_ini['total_hari_ini'] ?? 0;
$transaksi_hari_ini = $row_hari_ini['transaksi_hari_ini'] ?? 0;
$penjualan_bulan_ini = $row_bulan_ini['total_bulan_ini'] ?? 0;


?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Penjualan - Manajemen Pakaian Wanita</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.0.7/css/boxicons.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <style>
        body {
            background-color: #F9F9F9;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .navbar-custom {
            background-color: #6B705C;
        }
        .navbar-custom .nav-link, .navbar-custom .navbar-brand {
            color: #ffffff;
        }
        .stats-card {
            border: none;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
            background-color: #B8BAA3;
            color: #333333;
        }
        .stats-card:hover {
            transform: translateY(-5px);
        }
        .btn-custom {
            background-color: #6B705C;
            color: white;
        }
        .btn-custom:hover {
            background-color: #565A49;
            color: white;
        }
        .table-container {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }
        .icon-box {
            width: 45px;
            height: 45px;
            background-color: rgba(255, 255, 255, 0.2);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
        }
    </style>
</head>
<body>
    <!-- Navbar sama seperti sebelumnya -->
    <?php include 'includes/navbar.php'; ?>

    <div class="container mt-4">
        <!-- Tombol Tambah Penjualan -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0">Data Penjualan</h4>
            <button type="button" class="btn btn-custom" data-bs-toggle="modal" data-bs-target="#tambahPenjualan">
                <i class='bx bx-plus'></i> Tambah Penjualan
            </button>
        </div>

    <!-- Statistik Penjualan -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="icon-box">
                            <i class='bx bx-money bx-sm'></i>
                        </div>
                        <div>
                            <h6 class="card-title mb-0">Penjualan Hari Ini</h6>
                            <h4 class="mb-0">Rp <?php echo number_format($penjualan_hari_ini); ?></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="icon-box">
                            <i class='bx bx-calendar bx-sm'></i>
                        </div>
                        <div>
                            <h6 class="card-title mb-0">Penjualan Bulan Ini</h6>
                            <h4 class="mb-0">Rp <?php echo number_format($penjualan_bulan_ini); ?></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="icon-box">
                            <i class='bx bx-cart bx-sm'></i>
                        </div>
                        <div>
                            <h6 class="card-title mb-0">Transaksi Hari Ini</h6>
                            <h4 class="mb-0"><?php echo $transaksi_hari_ini; ?> Transaksi</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

        <div class="container mt-4">
    <h4 class="mb-4">Aktivitas Terkahir Penjualan</h4>
    <table class="table table-striped table-hover">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Tanggal</th>
                <th>Nama Produk</th>
                <th>Jumlah</th>
                <th>Harga Satuan</th>
                <th>Total</th>
                <th>Customer</th>
                <th>Nama User</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <?php if ($result_penjualan->num_rows > 0): ?>
                        <?php while($row = $result_penjualan->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row["id"]; ?></td>
                                <td><?php echo $row["tanggal"]; ?></td>
                                <td><?php echo $row["nama_produk"]; ?></td>
                                <td><?php echo $row["jumlah"]; ?></td>
                                <td><?php echo number_format($row["harga_satuan"]); ?></td>
                                <td><?php echo number_format($row["total"]); ?></td>
                                <td><?php echo $row["customer"]; ?></td>
                                <td><?php echo $row["nama_user"]; ?></td>
                                <td>
                                    <a href="process/edit_penjualan.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                    <a href="process/hapus_penjualan.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus?')">Hapus</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="9" class="text-center">Tidak ada data penjualan.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

  <!-- Modal Tambah Penjualan -->
<div class="modal fade" id="tambahPenjualan" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Penjualan Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="process/tambah_penjualan.php" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Tanggal</label>
                        <input type="date" class="form-control" name="tanggal" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Customer</label>
                        <input type="text" class="form-control" name="customer" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Produk</label>
                        <select class="form-select" name="produk_id" required>
                            <option value="">Pilih Produk</option>
                            <?php
                            $query_produk = "SELECT * FROM produk WHERE stok > 0";
                            $result_produk = mysqli_query($conn, $query_produk);
                            while($produk = mysqli_fetch_assoc($result_produk)):
                            ?>
                            <option value="<?php echo $produk['id']; ?>" 
                                    data-harga="<?php echo $produk['harga']; ?>" 
                                    data-stok="<?php echo $produk['stok']; ?>">
                                <?php echo $produk['nama_produk']; ?> (Stok: <?php echo $produk['stok']; ?>)
                            </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jumlah</label>
                        <input type="number" class="form-control" name="jumlah" id="jumlah" required min="1">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Harga Satuan (Rp)</label>
                        <input type="number" class="form-control" name="harga_satuan" id="harga_satuan" required readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Total (Rp)</label>
                        <input type="number" class="form-control" name="total" id="total" readonly>
                    </div>
                    <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']; ?>">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-custom">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Tambahkan di bagian script
$(document).ready(function() {
    $('select[name="produk_id"]').change(function() {
        var harga = $('option:selected', this).data('harga');
        var stok = $('option:selected', this).data('stok');
        $('#harga_satuan').val(harga);
        $('#jumlah').attr('max', stok); // Tambahkan maksimum stok
        hitungTotal();
    });

    $('#jumlah').on('input', function() {
        var max = parseInt($(this).attr('max'));
        var val = parseInt($(this).val());
        if (val > max) {
            alert('Jumlah melebihi stok tersedia!');
            $(this).val(max);
        }
        hitungTotal();
    });
});

    </script>
</body>
</html>