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
$sql_user = "SELECT full_name FROM users WHERE user_id = ?";
$stmt_user = $conn->prepare($sql_user);
$stmt_user->bind_param("i", $user_id);
$stmt_user->execute();
$result_user = $stmt_user->get_result();
$user = $result_user->fetch_assoc();
$full_name = $user['full_name'] ?? 'User';

// Fetch completed pickups
$sql = "SELECT waste_type, collection_date, collection_time, pickup_location 
        FROM pickuprequests WHERE user_id = ? AND status = 'completed' 
        ORDER BY collection_date DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-body text-center">
            <h2 class="card-title">Pickup History</h2>
            <p class="text-muted">Review your past waste pickups.</p>
        </div>
    </div>
    
    <div class="mt-4">
        <h4>📜 Completed Pickups</h4>
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
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div class="alert alert-warning mt-2">No completed pickups found.</div>
        <?php endif; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
