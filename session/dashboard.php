<?php
session_start();

// Cek apakah user sudah login
if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
</head>
<body>
    <h2>Welcome, <?php echo $_SESSION['name']; ?>!</h2>
    <p>Username: <?php echo $_SESSION['username']; ?></p>
    <a href="logout.php">Logout</a>
</body>
</html>
<?php
//session_start();

// Set timeout session 30 menit
$timeout = 1800; // 30 menit * 60 detik

if(isset($_SESSION['last_activity'])) {
    $inactive = time() - $_SESSION['last_activity'];
    if($inactive >= $timeout) {
        session_destroy();
        header("Location: login.php");
        exit();
    }
}

$_SESSION['last_activity'] = time();
?>