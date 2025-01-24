<?php
require_once 'auth/check_session.php';
checkSession();
require_once 'config/database.php';
$conn = connectDB();

// Get statistics
$query = "SELECT 
    (SELECT COUNT(*) FROM produksi) as total_produksi,
    (SELECT IFNULL(SUM(jumlah), 0) FROM penjualan) as total_penjualan,
    (SELECT IFNULL(SUM(stok), 0) FROM produk) as total_stok";
$result = mysqli_query($conn, $query);
$stats = mysqli_fetch_assoc($result);
?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Manajemen Pakaian Wanita</title>
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
        .dashboard-stats .card {
            border: none;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
        }
        .dashboard-stats .card:hover {
            transform: translateY(-10px);
        }
        .card-title {
            font-size: 1rem;
            color: #555;
        }
        .card h3 {
            color: #333;
        }
        .bg-primary {
            background-color: #B8BAA3 !important;
        }
        .bg-success {
            background-color: #B8BAA3 !important;
        }
        .bg-info {
            background-color: #B8BAA3 !important;
        }
        .table th, .table td {
            vertical-align: middle;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark navbar-custom">
        <div class="container">
            <a class="navbar-brand" href="dashboard.php">
                <i class='bx bx-store-alt'></i> Manajemen Pakaian Wanita
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="dashboard.php">
                            <i class='bx bx-home'></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="kelolaproduk.php">
                            <i class='bx bx-cube'></i> Kelola Produk
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="penjualan.php">
                            <i class='bx bx-cart'></i> Penjualan
                        </a>
                    </li>
                    <?php if($_SESSION['role'] == 'admin'): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="users.php">
                            <i class='bx bx-user'></i> Kelola User
                        </a>
                    </li>
                    <?php endif; ?>
                </ul>
                <div class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" 
                           data-bs-toggle="dropdown">
                           <i class='bx bx-user-circle'></i> <?php echo $_SESSION['nama_lengkap']; ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="profile.php">
                                    <i class='bx bx-user'></i> Profile
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" href="logout.php">
                                    <i class='bx bx-log-out'></i> Logout
                                </a>
                            </li>
                        </ul>
                    </li>
                </div>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Selamat Datang, <?php echo $_SESSION['nama_lengkap']; ?>!</h5>
                        <p class="card-text">
                            Anda login sebagai <?php echo ucfirst($_SESSION['role']); ?>. 
                            Silakan gunakan menu di atas untuk navigasi.
                        </p>
                    </div>
                </div>
            </div>
        </div>

       <!-- Statistik Dashboard -->
       <div class="row dashboard-stats">
            <div class="col-md-4">
                <div class="card bg-primary text-white mb-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title mb-0">Jenis Produksi</h6>
                                <h3 class="mt-2 mb-0"><?php echo number_format($stats['total_produksi']); ?></h3>
                            </div>
                            <i class='bx bx-package bx-lg'></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-success text-white mb-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title mb-0">Total Penjualan</h6>
                                <h3 class="mt-2 mb-0">Rp <?php echo number_format($stats['total_penjualan']); ?></h3>
                            </div>
                            <i class='bx bx-money bx-lg'></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-info text-white mb-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title mb-0">Stok Tersedia</h6>
                                <h3 class="mt-2 mb-0"><?php echo number_format($stats['total_stok']); ?> Kg</h3>
                            </div>
                            <i class='bx bx-box bx-lg'></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

<!-- Aktivitas Terakhir -->
<div class="row mt-5">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Log Aktivitas Terbaru</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>User ID</th>
                                <th>Aktivitas</th>
                                <th>Waktu</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $activityQuery = "SELECT id, user_id, activity, created_at FROM activity_logs ORDER BY created_at DESC LIMIT 5";
                            $activityResult = mysqli_query($conn, $activityQuery);
                            
                            if ($activityResult && mysqli_num_rows($activityResult) > 0) {
                                while ($row = mysqli_fetch_assoc($activityResult)) {
                                    echo "<tr>";
                                    echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['user_id']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['activity']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['created_at']) . "</td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='4' class='text-center'>Tidak ada aktivitas terbaru.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Produk Terlaris -->
<div class="row mt-5">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Terkahir Produksi</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Tanggal Produksi</th>
                                <th>Nama Produk</th>
                                <th>Total Terjual</th>
                                <th>Total Pendapatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Query yang sudah disesuaikan
                            $query = "SELECT 
                                        Nama_Produk,
                                        stok,
                                        harga,
                                        tanggal,
                                        (stok * harga) as total_pendapatan
                                     FROM produksi 
                                     WHERE MONTH(tanggal) = MONTH(CURRENT_DATE())
                                     AND YEAR(tanggal) = YEAR(CURRENT_DATE())
                                     ORDER BY tanggal DESC
                                     LIMIT 5";
                            
                            // Tambahkan error handling
                            $result = mysqli_query($conn, $query);
                            if (!$result) {
                                echo "<tr><td colspan='4' class='text-center'>Terjadi kesalahan dalam mengambil data</td></tr>";
                                error_log("Query Error: " . mysqli_error($conn));
                            } else {
                                while($row = mysqli_fetch_assoc($result)) {
                                    echo "<tr>";
                                    echo "<td>" . htmlspecialchars($row['tanggal']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['Nama_Produk']) . "</td>";
                                    echo "<td>" . number_format($row['stok']) . " Kg</td>";
                                    echo "<td>Rp " . number_format($row['total_pendapatan']) . "</td>";
                                    echo "</tr>";
                                }
                                
                                // Jika tidak ada data
                                if (mysqli_num_rows($result) == 0) {
                                    echo "<tr><td colspan='4' class='text-center'>Belum ada data penjualan untuk bulan ini</td></tr>";
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


      <!-- Status Persediaan -->
<div class="row mt-5">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Status Persediaan Kritis</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Nama Produk</th>
                                <th>Stok Tersisa</th>
                                <th>Kategori</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Query yang sudah disesuaikan dengan struktur tabel produksi
                            $query = "SELECT 
                                        id,
                                        Nama_Produk,
                                        stok,
                                        kategori
                                     FROM produksi
                                     WHERE stok <= 10
                                     ORDER BY stok ASC
                                     LIMIT 5";
                            
                            // Tambahkan error handling
                            $result = mysqli_query($conn, $query);
                            if (!$result) {
                                echo "<tr><td colspan='5' class='text-center'>Terjadi kesalahan dalam mengambil data</td></tr>";
                                error_log("Query Error: " . mysqli_error($conn));
                            } else {
                                while($row = mysqli_fetch_assoc($result)) {
                                    $status_class = $row['stok'] == 0 ? 'danger' : 'warning';
                                    $status_text = $row['stok'] == 0 ? 'Habis' : 'Stok Menipis';
                                    
                                    echo "<tr>";
                                    echo "<td>" . htmlspecialchars($row['Nama_Produk']) . "</td>";
                                    echo "<td>" . number_format($row['stok']) . " item</td>";
                                    echo "<td>" . htmlspecialchars($row['kategori']) . "</td>";
                                    echo "<td><span class='badge bg-{$status_class}'>{$status_text}</span></td>";
                                    echo "<td><a href='kelolaproduk.php?id=" . $row['id'] . "' class='btn btn-sm btn-primary'>Tambah Stok</a></td>";
                                    echo "</tr>";
                                }
                                
                                // Jika tidak ada data
                                if (mysqli_num_rows($result) == 0) {
                                    echo "<tr><td colspan='5' class='text-center'>Tidak ada produk dengan stok kritis</td></tr>";
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/script.js"></script>
</body>
</html>