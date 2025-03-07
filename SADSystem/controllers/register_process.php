<?php
require '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $phone_number = trim($_POST['phone_number']);
    $address = trim($_POST['address']);
    $role = trim($_POST['role']);
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Check if email already exists
    $stmt = $conn->prepare("SELECT email FROM Users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        header("Location: ../register.php?error=Email already registered");
        exit();
    }

    // Insert new user
    $stmt = $conn->prepare("INSERT INTO Users (full_name, email, password, phone_number, address, role) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $full_name, $email, $hashed_password, $phone_number, $address, $role);

    if ($stmt->execute()) {
        header("Location: ../register.php?success=1");
        exit();
    } else {
        header("Location: ../register.php?error=Registration failed");
        exit();
    }
}
?>
