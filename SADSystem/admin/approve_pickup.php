<?php
require '../config/db.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../dashboard.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Fetch request details
    $check_stmt = $conn->prepare("SELECT collection_date, collection_time FROM pickuprequests WHERE request_id = ?");
    $check_stmt->bind_param("i", $id);
    $check_stmt->execute();
    $result = $check_stmt->get_result();

    if ($result->num_rows === 0) {
        exit();
    }

    $row = $result->fetch_assoc();
    $collection_date = $row['collection_date'];
    $collection_time = $row['collection_time'];

    // Approve the request
    $stmt = $conn->prepare("UPDATE pickuprequests SET status = 'approved' WHERE request_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    // Insert into pickup_schedules or update if already exists
    $schedule_stmt = $conn->prepare("
        INSERT INTO pickup_schedules (request_id, collection_date, collection_time) 
        VALUES (?, ?, ?) 
        ON DUPLICATE KEY UPDATE 
            collection_date = VALUES(collection_date), 
            collection_time = VALUES(collection_time)
    ");
    $schedule_stmt->bind_param("iss", $id, $collection_date, $collection_time);
    $schedule_stmt->execute();

    header("Location: ../admin_dashboard.php");
    exit();
} else {
    exit();
}
?>
