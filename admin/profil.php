<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Poppins', sans-serif;
        }

        .profile-container {
            background-color: #e0e0d1;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            max-width: 700px;
            margin: 80px auto;
        }

        h1 {
            font-size: 2.5rem;
            color: #343a40;
        }

        .btn-primary {
            background-color: #5a5c51;
            border: none;
        }

        .btn-primary:hover {
            background-color: #6c6e63;
        }

        .icon-container {
            font-size: 5rem;
            color: #5a5c51;
            margin-bottom: 20px;
        }

        .btn-dashboard {
            background-color: #6c757d;
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            transition: background-color 0.3s ease;
            display: inline-block;
            text-decoration: none;
        }

        .btn-dashboard:hover {
            background-color: #5a6268;
            color: #fff;
        }
    </style>
</head>
<body>
    <div class="profile-container text-center">
        <div class="icon-container">
            <i class="bi bi-person-circle"></i>
        </div>
        <h1>Profil Admin</h1>
        <p>Selamat datang, <strong>ragil</strong></p>
        
        <form>
            <div class="mb-3 text-start">
                <label for="username" class="form-label">Username:</label>
                <input type="text" class="form-control" id="username" value="ragil" readonly>
            </div>

            <div class="mb-3 text-start">
                <label for="nama" class="form-label">Nama Lengkap:</label>
                <input type="text" class="form-control" id="nama" value="Anisa Ragil Saputri" readonly>
            </div>

            <div class="mb-4 text-start">
                <label for="role" class="form-label">Role:</label>
                <input type="text" class="form-control" id="role" value="admin" readonly>
            </div>

            <div class="mb-4 text-start">
                <label for="status" class="form-label">Status:</label>
                <input type="text" class="form-control" id="status" value="aktif" readonly>
            </div>

            <button type="submit" class="btn btn-primary w-100">Update Profil</button>
        </form>

        <div class="mt-4">
            <a href="dashboard.php" class="btn-dashboard">&#8592; Kembali ke Dashboard</a>
        </div>
    </div>

    <script>
        document.querySelector('form').onsubmit = function(e) {
            e.preventDefault();
            alert('Profil berhasil diperbarui!');
        };
    </script>
</body>
</html>
