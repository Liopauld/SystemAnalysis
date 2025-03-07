<?php
session_start();
include 'includes/header.php';
require 'config/db.php';

// Redirect to login if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user details
$sql = "SELECT full_name, email, phone_number, address, role FROM Users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Close statement after fetching
$stmt->close();

// Check if user exists
if (!$user) {
    echo "<p class='alert alert-danger'>User not found.</p>";
    include 'includes/footer.php';
    exit();
}
?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h2 class="text-center">Edit Profile</h2>

            <?php
            if (isset($_GET['success'])) {
                echo '<div class="alert alert-success">Profile updated successfully!</div>';
            }
            if (isset($_GET['error'])) {
                echo '<div class="alert alert-danger">' . htmlspecialchars($_GET['error']) . '</div>';
            }
            ?>

            <form action="controllers/update_profile.php" method="POST">
                <div class="mb-3">
                    <label>Full Name:</label>
                    <input type="text" name="full_name" class="form-control" value="<?= htmlspecialchars($user['full_name']) ?>" required>
                </div>
                <div class="mb-3">
                    <label>Email (cannot be changed):</label>
                    <input type="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" disabled>
                </div>
                <div class="mb-3">
                    <label>Phone Number:</label>
                    <input type="text" name="phone_number" class="form-control" value="<?= htmlspecialchars($user['phone_number']) ?>" required>
                </div>
                <div class="mb-3">
                    <label>Address:</label>
                    <textarea name="address" class="form-control" required><?= htmlspecialchars($user['address']) ?></textarea>
                </div>
                <div class="mb-3">
                    <label>Role (cannot be changed):</label>
                    <input type="text" class="form-control" value="<?= htmlspecialchars($user['role']) ?>" disabled>
                </div>
                <input type="hidden" name="user_id" value="<?= $user_id ?>">
                <button type="submit" class="btn btn-primary w-100">Update Profile</button>
            </form>

            <!-- Change Password Button -->
            <button type="button" class="btn btn-warning mt-3 w-100" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                Change Password
            </button>
        </div>
    </div>
</div>

<!-- Change Password Modal -->
<div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="changePasswordLabel">Change Password</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="controllers/change_password.php" method="POST">
                    <div class="mb-3">
                        <label class="form-label">Old Password</label>
                        <input type="password" name="old_password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">New Password</label>
                        <input type="password" name="new_password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Confirm New Password</label>
                        <input type="password" name="confirm_password" class="form-control" required>
                    </div>
                    <input type="hidden" name="user_id" value="<?= $user_id ?>">
                    <button type="submit" class="btn btn-primary w-100">Update Password</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Include Bootstrap JavaScript (for modal functionality) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<?php include 'includes/footer.php'; ?>
