<?php
function formatRupiah($angka) {
    return "Rp " . number_format($angka, 0, ',', '.');
}

function formatTanggal($tanggal) {
    return date('d/m/Y', strtotime($tanggal));
}

function getStatus($status) {
    $badges = [
        'pending' => 'bg-warning',
        'success' => 'bg-success',
        'failed' => 'bg-danger'
    ];
    return "<span class='badge " . $badges[$status] . "'>" . ucfirst($status) . "</span>";
}

function logActivity($conn, $user_id, $activity) {
    $query = "INSERT INTO activity_logs (user_id, activity) VALUES (?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "is", $user_id, $activity);
    mysqli_stmt_execute($stmt);
}
