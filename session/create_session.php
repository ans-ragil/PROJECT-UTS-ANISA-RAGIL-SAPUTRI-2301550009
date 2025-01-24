<?php
session_start();
$_SESSION['username'] = "John Doe";
$_SESSION['role'] = "admin";
echo "Session telah dibuat!";
?>