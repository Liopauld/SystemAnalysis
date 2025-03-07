<?php
require '../config/db.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../dashboard.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $check_stmt = $conn->prepare("SELECT status FROM pickuprequests WHERE request_id = ?");
    $check_stmt->bind_param("i", $id);
    $check_stmt->execute();
    $check_stmt->store_result();

    if ($check_stmt->num_rows === 0) {
        exit();
    }

    $stmt = $conn->prepare("UPDATE pickuprequests SET status = 'rejected' WHERE request_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    header("Location: ../admin_dashboard.php");
    exit();
} else {
    exit();
}
?>