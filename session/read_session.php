<?php
session_start();
if(isset($_SESSION['username'])) {
    echo "Welcome " . $_SESSION['username'];
    echo "<br>Role: " . $_SESSION['role'];
} else {
    echo "No session found!";
}
?>