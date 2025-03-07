<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'includes/header.php';
require 'config/db.php';

$user_id = $_SESSION['user_id'];

// Fetch user details
$sql_user = "SELECT full_name, role FROM users WHERE user_id = ?";
$stmt_user = $conn->prepare($sql_user);
$stmt_user->bind_param("i", $user_id);
$stmt_user->execute();
$result_user = $stmt_user->get_result();
$user = $result_user->fetch_assoc();

// Redirect admin users to the admin dashboard
if ($user['role'] === 'admin') {
    header("Location: admin_dashboard.php");
    exit();
}

$full_name = $user['full_name'] ?? 'User';

// Fetch all upcoming collection dates for regular users
$sql = "SELECT p.waste_type, ps.collection_date, ps.collection_time, p.status, p.pickup_location
        FROM pickuprequests p 
        JOIN pickup_schedules ps ON p.request_id = ps.request_id 
        WHERE p.user_id = ? AND p.status = 'approved' 
        ORDER BY ps.collection_date ASC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-body text-center">
            <h2 class="card-title">Welcome, <?= htmlspecialchars($full_name) ?>!</h2>
            <p class="text-muted">Manage your waste pickups, report issues, and redeem rewards.</p>
        </div>
    </div>

    <!-- Dashboard Buttons -->
    <div class="row mt-4">
        <div class="col-md-3">
            <a href="pickup.php" class="btn btn-primary w-100 p-3">🛻 Request Pickup</a>
        </div>
        <div class="col-md-3">
            <a href="pickup_history.php" class="btn btn-info w-100 p-3">📜 View History</a>
        </div>
        <div class="col-md-3">
            <a href="report_issue.php" class="btn btn-warning w-100 p-3">⚠️ Report Issue</a>
        </div>
        <div class="col-md-3">
            <a href="redeem_rewards.php" class="btn btn-success w-100 p-3">🎁 Redeem Rewards</a>
        </div>
    </div>

    <!-- Upcoming Pickup Info -->
    <div class="mt-4">
        <h4>📅 Upcoming Pickups</h4>
        <?php if ($result->num_rows > 0): ?>
            <div class="list-group">
                <?php while ($pickup = $result->fetch_assoc()): ?>
                    <div class="list-group-item">
                        <div class="d-flex justify-content-between">
                            <div>
                                <strong>🗑️ Waste Type:</strong> <?= htmlspecialchars($pickup['waste_type']) ?><br>
                                <strong>📅 Date:</strong> <?= htmlspecialchars($pickup['collection_date']) ?><br>
                                <strong>⏰ Time:</strong> <?= htmlspecialchars($pickup['collection_time']) ?><br>   
                                <strong>📍 Location:</strong> <?= htmlspecialchars($pickup['pickup_location']) ?>
                            </div>
                            <div class="align-self-center">
                                
                                </span>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div class="alert alert-warning mt-2">No upcoming pickups scheduled.</div>
        <?php endif; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
