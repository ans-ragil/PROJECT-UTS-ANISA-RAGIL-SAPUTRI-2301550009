<?php
function checkSession() {
    session_start();
    if (!isset($_SESSION['user_id'])) {
        header("Location: ../login.php");
        exit();
    }
}

function checkAdmin() {
    if ($_SESSION['role'] !== 'admin') {
        header("Location: ../dashboard.php");
        exit();
    }
}