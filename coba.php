<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Penjualan</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .notif-btn {
            position: absolute;
            top: 20px;
            right: 20px;
            background: none;
            border: none;
            cursor: pointer;
            font-size: 1.5em;
        }
        .notif-btn:hover {
            color: #007BFF;
        }
        .card {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin: 15px;
            text-align: center;
        }
        .cards {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
        }
        .chart {
            margin: 30px 0;
        }
        .sidebar {
            background-color: #343a40;
            color: white;
            padding: 20px;
            height: 100vh;
            width: 250px;
            position: fixed;
        }
        .sidebar ul {
            list-style: none;
            padding: 0;
        }
        .sidebar ul li a {
            color: white;
            text-decoration: none;
            display: block;
            padding: 10px 0;
        }
        .sidebar ul li a:hover {
            background-color: #495057;
        }
        .content {
            margin-left: 270px;
            padding: 20px;
        }
        .header {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <aside class="sidebar">
            <h2>Dashboard</h2>
            <ul>
                <li><a href="#">Beranda</a></li>
                <li><a href="#">Kelola Produk</a></li>
                <li><a href="#">Penjualan</a></li>
                <li><a href="#">Kelola User</a></li>
                <li><a href="#">Laporan</a></li>
            </ul>
        </aside>
        <main class="content">
            <header class="header">
                <h1>Selamat Datang di Dashboard</h1>
                <p>Ringkasan Penjualan Bulan Ini</p>
                <button class="notif-btn" onclick="alert('Anda memiliki 5 notifikasi baru!')">
                    <i class="fas fa-bell"></i>
                </button>
            </header>
            <section class="cards">
                <div class="card">
                    <h3>Total Penjualan</h3>
                    <p>Rp 125.000.000</p>
                </div>
                <div class="card">
                    <h3>Produk Terjual</h3>
                    <p>350 Item</p>
                </div>
                <div class="card">
                    <h3>Pelanggan Baru</h3>
                    <p>45 Orang</p>
                </div>
                <div class="card">
                    <h3>Stok Tersisa</h3>
                    <p>1.200 Item</p>
                </div>
            </section>
            <section class="chart">
                <canvas id="salesChart"></canvas>
            </section>
        </main>
    </div>

    <script>
        const ctx = document.getElementById('salesChart').getContext('2d');
        const salesChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
                datasets: [{
                    label: 'Penjualan',
                    data: [12000000, 15000000, 17000000, 20000000, 25000000, 12500000],
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: true
                    }
                }
            }
        });
    </script>
</body>
</html>
