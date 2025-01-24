<?php
require_once 'auth/check_session.php';
checkSession();
require_once 'config/database.php';
require_once 'functions/helpers.php';

$page_title = "Data Produk";
include 'includes/header.php';
include 'includes/navbar.php';

$conn = connectDB();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "manajemen_pakaian_wanita";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Cek folder upload gambar
$target_dir = "aset/";
if (!is_dir($target_dir)) {
    mkdir($target_dir, 0755, true);
}

// Handle delete
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    
    // Get image path before deletion
    $query = "SELECT gambar FROM produksi WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    if ($row && file_exists($row['gambar'])) {
        unlink($row['gambar']); // Delete image file
    }
    
    // Delete record
    $query = "DELETE FROM produksi WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
}

// Handle edit
if (isset($_POST['edit'])) {
    $id = $_POST['edit_id'];
    $nama_produk = $_POST['edit_nama_produk'];
    $harga = $_POST['edit_harga'];
    $stok = $_POST['edit_stok'];
    $kategori = $_POST['edit_kategori'];
    $tanggal = $_POST['edit_tanggal'];
    $deskripsi = $_POST['edit_deskripsi'];
    
    if ($_FILES['edit_gambar']['size'] > 0) {
        $gambar = $_FILES['edit_gambar']['name'];
        $file_tmp = $_FILES['edit_gambar']['tmp_name'];
        $file_ext = strtolower(pathinfo($gambar, PATHINFO_EXTENSION));
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
        
        if (in_array($file_ext, $allowed_extensions)) {
            $new_file_name = uniqid() . '.' . $file_ext;
            $target_file = $target_dir . $new_file_name;
            
            // Delete old image
            $query = "SELECT gambar FROM produksi WHERE id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            
            if ($row && file_exists($row['gambar'])) {
                unlink($row['gambar']);
            }
            
            move_uploaded_file($file_tmp, $target_file);
            
            $query = "UPDATE produksi SET Nama_Produk=?, harga=?, stok=?, kategori=?, tanggal=?, deskripsi=?, gambar=? WHERE id=?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("siissssi", $nama_produk, $harga, $stok, $kategori, $tanggal, $deskripsi, $target_file, $id);
        }
    } else {
        $query = "UPDATE produksi SET Nama_Produk=?, harga=?, stok=?, kategori=?, tanggal=?, deskripsi=? WHERE id=?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("siisssi", $nama_produk, $harga, $stok, $kategori, $tanggal, $deskripsi, $id);
    }
    
    if ($stmt->execute()) {
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
}


// Proses form tambah produk
if (isset($_POST['submit'])) {
    $nama_produk = $_POST['nama_produk'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];
    $kategori = $_POST['kategori'];
    $tanggal = $_POST['tanggal'];
    $deskripsi = $_POST['deskripsi'];

    $gambar = $_FILES['gambar']['name'];
    $file_tmp = $_FILES['gambar']['tmp_name'];
    $file_ext = strtolower(pathinfo($gambar, PATHINFO_EXTENSION));
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];

    // Validasi file gambar
    if (in_array($file_ext, $allowed_extensions)) {
        $new_file_name = uniqid() . '.' . $file_ext; // Nama file unik
        $target_file = $target_dir . $new_file_name;

        if (move_uploaded_file($file_tmp, $target_file)) {
            // Query untuk menyimpan data produk
            $query = "INSERT INTO produksi (Nama_Produk, harga, stok, kategori, tanggal, deskripsi, gambar) 
                      VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, "siissss", $nama_produk, $harga, $stok, $kategori, $tanggal, $deskripsi, $target_file);

            if (mysqli_stmt_execute($stmt)) {
                $success = "Produk berhasil ditambahkan!";
            } else {
                $error = "Gagal menyimpan data produk: " . mysqli_error($conn);
            }
        } else {
            $error = "Gagal mengupload gambar.";
        }
    } else {
        $error = "Format file tidak didukung. Hanya jpg, jpeg, png, dan gif yang diperbolehkan.";
    }
}

// Ambil data produksi
$query = "SELECT * FROM produksi";
$result = $conn->query($query);
?>

<!-- Tambahkan library Boxicons -->
<link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">

<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center" style="background-color: #B8BAA3;">
                    <h5 class="mb-0 text-#333333">Data Produk</h5>
                    <button type="button" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#addModal">
                        <i class='bx bx-plus'></i> Tambah Produk
                    </button>
                </div>
                <div class="card-body">
                    <?php if (isset($success)) : ?>
                        <div class="alert alert-success"><?php echo $success; ?></div>
                    <?php elseif (isset($error)) : ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead style="background-color: #B8BAA3; color: white;">
                                <tr>
                                    <th>ID</th>
                                    <th>Nama Produk</th>
                                    <th>Harga</th>
                                    <th>Stok</th>
                                    <th>Kategori</th>
                                    <th>Tanggal</th>
                                    <th>Deskripsi</th>
                                    <th>Gambar</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        echo "<tr>";
                                        echo "<td>" . $row['id'] . "</td>";
                                        echo "<td>" . $row['Nama_Produk'] . "</td>";
                                        echo "<td>Rp " . number_format($row['harga'], 0, ',', '.') . "</td>";
                                        echo "<td>" . $row['stok'] . "</td>";
                                        echo "<td>" . $row['kategori'] . "</td>";
                                        echo "<td>" . $row['tanggal'] . "</td>";
                                        echo "<td>" . $row['deskripsi'] . "</td>";
                                        echo "<td><img src='" . $row['gambar'] . "' alt='Gambar Produk' width='100'></td>";
                                        echo "<td>
                                            <button class='btn btn-sm btn-warning' onclick='editProduk(" . json_encode($row) . ")'>
                                                <i class='bx bx-edit'></i> 
                                            </button>
                                            <a href='?hapus=" . $row['id'] . "' class='btn btn-sm btn-danger' onclick='return confirm(\"Yakin ingin menghapus?\")'>
                                                <i class='bx bx-trash'></i> 
                                            </a>
                                        </td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='9'>Tidak ada data produk.</td></tr>";
                                } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Produk -->
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #B8BAA3;">
                <h5 class="modal-title text-white">Tambah Produk</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Produk</label>
                        <input type="text" name="nama_produk" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Harga</label>
                        <input type="number" name="harga" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Stok</label>
                        <input type="number" name="stok" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kategori</label>
                        <select name="kategori" class="form-select">
                            <option value="atasan">Atasan</option>
                            <option value="bawahan">Bawahan</option>
                            <option value="dress">Dress</option>
                            <option value="outerwear">Jaket</option>
                            <option value="aksesoris">Aksesoris</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tanggal</label>
                        <input type="date" name="tanggal" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Gambar Produk</label>
                        <input type="file" name="gambar" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" name="submit" class="btn btn-success">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #B8BAA3;">
                <h5 class="modal-title text-white">Edit Produk</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" name="edit_id" id="edit_id">
                    <div class="mb-3">
                        <label class="form-label">Nama Produk</label>
                        <input type="text" name="edit_nama_produk" id="edit_nama_produk" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Harga</label>
                        <input type="number" name="edit_harga" id="edit_harga" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Stok</label>
                        <input type="number" name="edit_stok" id="edit_stok" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kategori</label>
                        <select name="edit_kategori" id="edit_kategori" class="form-select">
                            <option value="atasan">Atasan</option>
                            <option value="bawahan">Bawahan</option>
                            <option value="dress">Dress</option>
                            <option value="outerwear">Jaket</option>
                            <option value="aksesoris">Aksesoris</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tanggal</label>
                        <input type="date" name="edit_tanggal" id="edit_tanggal" class="form-control" required>
                    </div>
                    <div class="mb-3">
                    <label class="form-label">Deskripsi</label>
                        <textarea name="edit_deskripsi" id="edit_deskripsi" class="form-control" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Gambar Produk</label>
                        <input type="file" name="edit_gambar" class="form-control">
                        <small class="text-muted">Biarkan kosong jika tidak ingin mengubah gambar</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" name="edit" class="btn btn-warning">Perbarui</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- JavaScript untuk mengisi form edit -->
<script>
function editProduk(data) {
    document.getElementById('edit_id').value = data.id;
    document.getElementById('edit_nama_produk').value = data.Nama_Produk;
    document.getElementById('edit_harga').value = data.harga;
    document.getElementById('edit_stok').value = data.stok;
    document.getElementById('edit_kategori').value = data.kategori;
    document.getElementById('edit_tanggal').value = data.tanggal;
    document.getElementById('edit_deskripsi').value = data.deskripsi;
    
    var editModal = new bootstrap.Modal(document.getElementById('editModal'));
    editModal.show();
}
</script>

<?php include 'includes/footer.php'; ?>