<?php
require '../config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    session_start();

    $user_id = $_SESSION['user_id']; // Ensure session security
    $waste_type = $_POST['waste_type'];
    $pickup_day = $_POST['pickup_day'];
    $collection_time = $_POST['collection_time'];
    $pickup_location = $_POST['pickup_location'];
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];

    // Validate inputs
    if (empty($waste_type) || empty($pickup_day) || empty($collection_time) || empty($pickup_location) || empty($latitude) || empty($longitude)) {
        header("Location: ../pickup.php?error=Please complete all fields.");
        exit();
    }

    // Map days of the week to their numerical representation (Sunday = 0, Monday = 1, ..., Saturday = 6)
    $daysMap = [
        "Sunday" => 0, "Monday" => 1, "Tuesday" => 2, "Wednesday" => 3, 
        "Thursday" => 4, "Friday" => 5, "Saturday" => 6
    ];

    // Get today's date and its day of the week
    $today = new DateTime();
    $todayDayIndex = (int)$today->format('w'); // 0 (Sunday) to 6 (Saturday)
    $selectedDayIndex = $daysMap[$pickup_day];

    // Calculate the next occurrence of the selected day
    $daysUntilNextPickup = ($selectedDayIndex - $todayDayIndex + 7) % 7;
    if ($daysUntilNextPickup == 0) {
        $daysUntilNextPickup = 7; // If today is the selected day, schedule for next week
    }

    $collectionDate = $today->modify("+$daysUntilNextPickup days")->format('Y-m-d');

    // Check if the user already has a request for the same location on the same collection date
    $check_sql = "SELECT request_id FROM pickuprequests 
                  WHERE user_id = ? 
                  AND collection_date = ? 
                  AND pickup_location = ?";

    $stmt = $conn->prepare($check_sql);
    $stmt->bind_param("iss", $user_id, $collectionDate, $pickup_location);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        header("Location: ../pickup.php?error=You already have a pickup request for this location on this date.");
    } else {
        // Insert the request with the correct collection date
        $insert_sql = "INSERT INTO pickuprequests (user_id, waste_type, schedule_day, collection_time, pickup_location, latitude, longitude, status, collection_date) 
                       VALUES (?, ?, ?, ?, ?, ?, ?, 'Pending', ?)";
        $stmt = $conn->prepare($insert_sql);
        $stmt->bind_param("isssssss", $user_id, $waste_type, $pickup_day, $collection_time, $pickup_location, $latitude, $longitude, $collectionDate);

        if ($stmt->execute()) {
            header("Location: ../pickup.php?success=Your pickup request has been scheduled for " . $collectionDate);
        } else {
            header("Location: ../pickup.php?error=Failed to submit request.");
        }
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: ../index.php");
    exit();
}
?>
