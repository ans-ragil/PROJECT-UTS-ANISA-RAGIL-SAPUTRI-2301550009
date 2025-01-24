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
              
            
            <nav class="navbar navbar-expand-lg navbar-dark bg-dark navbar-custom">
        
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

            </ul>
            <div class="navbar-nav">
                <!-- User dropdown -->
            </div>
        </div>
    </div>
</nav>