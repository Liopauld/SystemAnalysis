<?php
session_start(); // Ensure session is started
require '../config/db.php'; // Ensure correct path

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../dashboard.php");
    exit();
}

if (isset($_GET['id'], $_GET['status'])) {
    $id = intval($_GET['id']);
    $status = $_GET['status'];

    // Update the status of the pickup request
    $stmt = $conn->prepare("UPDATE PickupRequests SET status = ? WHERE id = ?");
    if (!$stmt) {
        header("Location: ../admin/manage_pickups.php?error=Database error: " . $conn->error);
        exit();
    }
    $stmt->bind_param("si", $status, $id);
    if (!$stmt->execute()) {
        header("Location: ../admin/manage_pickups.php?error=Failed to update status");
        exit();
    }
    $stmt->close();

    if ($status === 'Approved') {
        // Get the requested pickup day (e.g., "Monday")
        $pickup_query = $conn->prepare("SELECT pickup_day FROM PickupRequests WHERE id = ?");
        if (!$pickup_query) {
            header("Location: ../admin/manage_pickups.php?error=Database error: " . $conn->error);
            exit();
        }
        $pickup_query->bind_param("i", $id);
        $pickup_query->execute();
        $pickup_query->bind_result($pickup_day);
        $pickup_query->fetch();
        $pickup_query->close();

        if ($pickup_day) {
            // Auto-generate collection dates for the rest of the month
            $current_date = new DateTime('first day of this month'); // Start from the first day of the month
            $end_date = new DateTime('last day of this month'); // End at the last day of the month

            while ($current_date <= $end_date) {
                if ($current_date->format('l') === $pickup_day) {
                    $collection_date = $current_date->format('Y-m-d');

                    // Insert collection date into PickupSchedules
                    $insert_schedule = $conn->prepare("INSERT INTO PickupSchedules (request_id, collection_date) VALUES (?, ?)");
                    if (!$insert_schedule) {
                        header("Location: ../admin/manage_pickups.php?error=Database error: " . $conn->error);
                        exit();
                    }
                    $insert_schedule->bind_param("is", $id, $collection_date);
                    if (!$insert_schedule->execute()) {
                        header("Location: ../admin/manage_pickups.php?error=Failed to insert collection date");
                        exit();
                    }
                    $insert_schedule->close();
                }
                $current_date->modify('+1 day'); // Move to the next day
            }
        }
    }

    header("Location: ../admin/manage_pickups.php?success=1");
    exit();
} else {
    header("Location: ../admin/manage_pickups.php?error=Invalid Request");
    exit();
}
?>
