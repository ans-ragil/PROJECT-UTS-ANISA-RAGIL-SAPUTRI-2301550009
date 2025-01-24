<?php
$host = "localhost";
$dbuser = "root";
$dbpass = "";
$dbname = "manajemen_pakaian_wanita";

$conn = mysqli_connect($host, $dbuser, $dbpass, $dbname);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>