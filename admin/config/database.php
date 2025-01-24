<?php
function connectDB() {
    $host = "localhost";
    $user = "root";
    $pass = "";
    $db = "manajemen_pakaian_wanita";
    
    $conn = mysqli_connect($host, $user, $pass, $db);
    if (!$conn) {
        die("Koneksi gagal: " . mysqli_connect_error());
    }
    return $conn;
}
