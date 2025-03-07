<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'includes/header.php';
require 'config/db.php';

$user_id = $_SESSION['user_id'];

// Placeholder reward options
$rewards = [
    
    ["name" => "Lorem Ipsum", "points" => 250],
    ["name" => "Dolor sit Amet", "points" => 500]
];
?>

<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-body text-center">
            <h2 class="card-title">Redeem Rewards</h2>
            <p class="text-muted">Use your earned points to redeem exciting rewards.</p>
        </div>
    </div>

    <div class="mt-4">
        <h4>🎁 Available Rewards</h4>
        <div class="list-group">
            <?php foreach ($rewards as $reward): ?>
                <div class="list-group-item d-flex justify-content-between">
                    <span><strong><?= htmlspecialchars($reward['name']) ?></strong> - <?= $reward['points'] ?> points</span>
                    <button class="btn btn-success btn-sm">Redeem</button>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
