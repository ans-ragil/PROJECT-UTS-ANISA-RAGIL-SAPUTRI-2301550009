<?php
session_start();
if(isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

require_once 'config/database.php';
$error = "";

if(isset($_POST['login'])) {
    $conn = connectDB();
    
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];
    
    if(empty($username) || empty($password)) {
        $error = "Username dan password harus diisi!";
    } else {
        $query = "SELECT * FROM users WHERE username = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if($user = mysqli_fetch_assoc($result)) {
            if($password === $user['password']) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['nama_lengkap'] = $user['nama_lengkap'];
                $_SESSION['role'] = $user['role'];
                
                // Log successful login
                $ip = $_SERVER['REMOTE_ADDR'];
                $log_query = "INSERT INTO login_logs (user_id, ip_address, status) VALUES (?, ?, 'success')";
                $log_stmt = mysqli_prepare($conn, $log_query);
                mysqli_stmt_bind_param($log_stmt, "is", $user['id'], $ip);
                mysqli_stmt_execute($log_stmt);
                
                header("Location: dashboard.php");
                exit();
            } else {
                $error = "Password salah!";
            }
        } else {
            $error = "Username tidak ditemukan!";
        }
        mysqli_close($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Manajemen Pakaian Wanita</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #B8BAA3, #A2A486);
            font-family: Arial, sans-serif;
            color: #333;
        }
        .login-container {
            margin-top: 8%;
        }
        .card {
            border: none;
            border-radius: 15px;
            overflow: hidden;
        }
        .card-body {
            background: #ffffff;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            padding: 2rem;
        }
        .btn-primary {
            background-color: #6D745E;
            border-color: #6D745E;
            transition: all 0.3s;
        }
        .btn-primary:hover {
            background-color: #B8BAA3;
            border-color: #B8BAA3;
            color: #fff;
        }
        .form-control {
            border: 1px solid #B8BAA3;
            border-radius: 10px;
        }
        .form-control:focus {
            border-color: #6D745E;
            box-shadow: none;
        }
        .card-title {
            font-size: 1.5rem;
            color: #6D745E;
            font-weight: bold;
        }
        .alert-danger {
            background-color: #F8D7DA;
            color: #842029;
            border-color: #F5C2C7;
            border-radius: 10px;
        }
    </style>
</head>
<body>
    <div class="container login-container">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card shadow">
                    <div class="card-body">
                        <h4 class="card-title text-center mb-4">Login Sistem</h4>
                        <?php if($error): ?>
                            <div class="alert alert-danger text-center"> <?php echo $error; ?> </div>
                        <?php endif; ?>
                        
                        <form id="loginForm" method="POST" action="">
                            <div class="mb-3">
                                <label class="form-label">Username</label>
                                <input type="text" name="username" id="username" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" name="password" id="password" class="form-control" required>
                            </div>
                            <div class="mb-3 form-check">
                                <input type="checkbox" name="remember" class="form-check-input" id="remember">
                                <label class="form-check-label" for="remember">Remember me</label>
                            </div>
                            <button type="submit" name="login" class="btn btn-primary w-100">Login</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
