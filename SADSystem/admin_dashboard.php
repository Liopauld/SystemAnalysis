<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'includes/header.php';
require 'config/db.php';

// Check if the user is an admin
$user_id = $_SESSION['user_id'];
$sql_role = "SELECT role FROM users WHERE user_id = ?";
$stmt_role = $conn->prepare($sql_role);
$stmt_role->bind_param("i", $user_id);
$stmt_role->execute();
$result_role = $stmt_role->get_result();
$user = $result_role->fetch_assoc();

if ($user['role'] !== 'admin') {
    header("Location: ../dashboard.php");
    exit();
}

// Fetch pending pickup requests
$sql_pickups = "SELECT p.request_id, u.full_name, p.waste_type, p.schedule_day, p.status 
                FROM pickuprequests p
                JOIN users u ON p.user_id = u.user_id
                WHERE p.status = 'pending'
                ORDER BY p.request_id ASC";
$result_pickups = $conn->query($sql_pickups);
?>

<div class="container mt-5">
    <div class="row">
        <div class="col-lg-10 mx-auto">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-dark text-white">
                    <h3 class="mb-0">Admin Dashboard</h3>
                </div>
                <div class="card-body">

                    <!-- Success/Error Messages -->
                    <?php if (isset($_GET['success'])): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?= htmlspecialchars($_GET['success']) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php elseif (isset($_GET['error'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?= htmlspecialchars($_GET['error']) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <!-- Dashboard Buttons -->
                    <div class="d-flex flex-wrap gap-2 mb-4">
                        <a href="manage_users.php" class="btn btn-info"><i class="bi bi-people-fill"></i> Manage Users</a>
                        <a href="manage_schedules.php" class="btn btn-warning"><i class="bi bi-calendar-check"></i> Manage Pickup Schedules</a>
                        <a href="view_reports.php" class="btn btn-danger"><i class="bi bi-clipboard-data"></i> View Reports</a>
                    </div>

                    <!-- Pending Pickup Requests Table -->
                    <h4 class="mt-4">Pending Pickup Requests</h4>

                    <?php if ($result_pickups && $result_pickups->num_rows > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered align-middle">
                                <thead class="table-dark">
                                    <tr>
                                        <th>#</th>
                                        <th>User</th>
                                        <th>Waste Type</th>
                                        <th>Pickup Day</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($pickup = $result_pickups->fetch_assoc()): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($pickup['request_id']) ?></td>
                                            <td><?= htmlspecialchars($pickup['full_name']) ?></td>
                                            <td><?= htmlspecialchars($pickup['waste_type']) ?></td>
                                            <td><?= htmlspecialchars($pickup['schedule_day']) ?></td>
                                            <td>
                                                <span class="badge bg-warning text-dark"><?= htmlspecialchars($pickup['status']) ?></span>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group" role="group">
                                                    <a href="admin/approve_pickup.php?id=<?= $pickup['request_id'] ?>" class="btn btn-success btn-sm">
                                                        <i class="bi bi-check-circle"></i> Approve
                                                    </a>
                                                    <a href="admin/reject_pickup.php?id=<?= $pickup['request_id'] ?>" class="btn btn-danger btn-sm">
                                                        <i class="bi bi-x-circle"></i> Reject
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-warning">No pending pickup requests.</div>
                    <?php endif; ?>

                </div> <!-- End card-body -->
            </div> <!-- End card -->
        </div> <!-- End col -->
    </div> <!-- End row -->
</div> <!-- End container -->

<?php include 'includes/footer.php'; ?>
