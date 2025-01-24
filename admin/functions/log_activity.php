<?php
function logActivity($user, $activity) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO activity_logs (user, activity) VALUES (?, ?)");
    $stmt->bind_param("ss", $user, $activity);
    $stmt->execute();
    $stmt->close();
}

?>