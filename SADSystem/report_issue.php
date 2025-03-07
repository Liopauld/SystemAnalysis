<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'includes/header.php';
require 'config/db.php';

$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $issue_type = trim($_POST['issue_type']);
    $description = trim($_POST['description']);
    $image = $_FILES['image'] ?? null;
    $image_path = null;

    // Validate inputs
    if (empty($issue_type) || empty($description)) {
        $error = "Please fill in all fields.";
    } else {
        // Handle image upload
        if ($image && $image['error'] == 0) {
            $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
            if (in_array($image['type'], $allowed_types)) {
                $image_path = 'uploads/' . time() . '_' . basename($image['name']);
                move_uploaded_file($image['tmp_name'], $image_path);
            } else {
                $error = "Invalid image format. Only JPG, PNG, and GIF are allowed.";
            }
        }
    }

    if (!isset($error)) {
        $sql = "INSERT INTO reported_issues (user_id, issue_type, description, image_path) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isss", $user_id, $issue_type, $description, $image_path);

        if ($stmt->execute()) {
            $success = "Issue reported successfully!";
        } else {
            $error = "Error reporting the issue. Please try again.";
        }
    }
}
?>

<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-body text-center">
            <h2 class="card-title">Report an Issue</h2>
            <p class="text-muted">Help us improve by reporting any issues you encounter.</p>
        </div>
    </div>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger"> <?= htmlspecialchars($error) ?> </div>
    <?php elseif (isset($success)): ?>
        <div class="alert alert-success"> <?= htmlspecialchars($success) ?> </div>
    <?php endif; ?>

    <div class="mt-4">
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="issue_type" class="form-label">Issue Type</label>
                <input type="text" name="issue_type" id="issue_type" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea name="description" id="description" class="form-control" rows="4" required></textarea>
            </div>
            <div class="mb-3">
                <label for="image" class="form-label">Upload Image (optional)</label>
                <input type="file" name="image" id="image" class="form-control">
            </div>
            <button type="submit" class="btn btn-primary">Submit Issue</button>
        </form>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
