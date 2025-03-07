<?php
require '../config/db.php';
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_POST['user_id']) || $_POST['user_id'] != $_SESSION['user_id']) {
    header("Location: ../profile.php?error=Unauthorized access.");
    exit();
}

$user_id = $_SESSION['user_id'];
$full_name = trim($_POST['full_name']);
$phone_number = trim($_POST['phone_number']);
$address = trim($_POST['address']);

if (empty($full_name) || empty($phone_number) || empty($address)) {
    header("Location: ../profile.php?error=All fields are required.");
    exit();
}

// Update user profile
$sql = "UPDATE Users SET full_name=?, phone_number=?, address=? WHERE user_id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssi", $full_name, $phone_number, $address, $user_id);

if ($stmt->execute()) {
    header("Location: ../profile.php?success=Profile updated successfully.");
} else {
    header("Location: ../profile.php?error=Failed to update profile.");
}

$stmt->close();
$conn->close();
exit();
