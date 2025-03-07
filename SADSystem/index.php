<?php include 'includes/header.php'; ?>

<div class="text-center">
    <h1 class="mb-4">Welcome to Green Bin</h1>
    <p class="lead">A smart waste management system for your community.</p>

    <div class="mt-4">
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="dashboard.php" class="btn btn-warning btn-lg">Go to Dashboard</a>
        <?php else: ?>
            <a href="login.php" class="btn btn-primary btn-lg me-3">Login</a>
            <a href="register.php" class="btn btn-success btn-lg">Register</a>
        <?php endif; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
