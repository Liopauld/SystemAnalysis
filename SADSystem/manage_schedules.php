<?php
session_start();
require 'config/db.php';
include 'includes/header.php';

// Check if the user is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

// Fetch pickup requests
$query = "SELECT pr.request_id, u.full_name, u.address, pr.schedule_day, pr.waste_type, pr.status, pr.pickup_location, pr.collection_date, pr.collection_time 
          FROM pickuprequests pr 
          JOIN users u ON pr.user_id = u.user_id";
$result = mysqli_query($conn, $query);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $request_id = $_POST['request_id'];
    $schedule_day = $_POST['schedule_day'];
    $waste_type = $_POST['waste_type'];
    $status = $_POST['status'];
    $pickup_location = $_POST['pickup_location'];
    $collection_date = $_POST['collection_date'];
    $collection_time = $_POST['collection_time'];

    // Update pickup request
    $update_query = "UPDATE pickuprequests 
                     SET schedule_day = ?, waste_type = ?, status = ?, pickup_location = ?, collection_date = ?, collection_time = ? 
                     WHERE request_id = ?";
    $stmt = mysqli_prepare($conn, $update_query);
    mysqli_stmt_bind_param($stmt, "ssssssi", $schedule_day, $waste_type, $status, $pickup_location, $collection_date, $collection_time, $request_id);

    if (mysqli_stmt_execute($stmt)) {
        // If status is approved, check if already in schedule
        if ($status === 'approved') {
            $check_query = "SELECT 1 FROM pickup_schedules WHERE request_id = ?";
            $stmt_check = mysqli_prepare($conn, $check_query);
            mysqli_stmt_bind_param($stmt_check, "i", $request_id);
            mysqli_stmt_execute($stmt_check);
            mysqli_stmt_store_result($stmt_check);

            if (mysqli_stmt_num_rows($stmt_check) == 0) {
                // Insert only if request_id does not exist
                $insert_schedule = "INSERT INTO pickup_schedules (request_id, collection_date, collection_time) 
                                    VALUES (?, ?, ?)";
                $stmt_schedule = mysqli_prepare($conn, $insert_schedule);
                mysqli_stmt_bind_param($stmt_schedule, "iss", $request_id, $collection_date, $collection_time);
                mysqli_stmt_execute($stmt_schedule);
                $_SESSION['message'] = "Pickup request updated and scheduled successfully!";
            } else {
                $_SESSION['notice'] = "Cannot add request #$request_id. It is already in the schedule.";
            }
            mysqli_stmt_close($stmt_check);
        } 
        
        // If status is rejected, remove from pickup_schedules
        elseif ($status === 'rejected') {
            $delete_schedule = "DELETE FROM pickup_schedules WHERE request_id = ?";
            $stmt_delete = mysqli_prepare($conn, $delete_schedule);
            mysqli_stmt_bind_param($stmt_delete, "i", $request_id);
            mysqli_stmt_execute($stmt_delete);
            mysqli_stmt_close($stmt_delete);
            $_SESSION['message'] = "Pickup request rejected and removed from schedule.";
        }

        header("Location: manage_schedules.php");
        exit();
    } else {
        $_SESSION['error'] = "Error updating request!";
    }
}
?>

<div class="container mt-5">
    <div class="row">
        <div class="col-lg-12 mx-auto">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-dark text-white">
                    <h3 class="mb-0">Manage Pickup Schedules</h3>
                </div>
                <div class="card-body">
                    
                    <!-- Success/Error Messages -->
                    <?php if (isset($_SESSION['message'])): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?= htmlspecialchars($_SESSION['message']); unset($_SESSION['message']); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($_SESSION['notice'])): ?>
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <?= htmlspecialchars($_SESSION['notice']); unset($_SESSION['notice']); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <!-- Pickup Requests Table -->
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th>User</th>
                                    <th>Address</th>
                                    <th>Schedule Day</th>
                                    <th>Waste Type</th>
                                    <th>Status</th>
                                    <th>Pickup Location</th>
                                    <th>Collection Date</th>
                                    <th>Collection Time</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                    <tr>
                                        <form method="POST">
                                            <input type="hidden" name="request_id" value="<?= htmlspecialchars($row['request_id']); ?>">
                                            
                                            <td><?= htmlspecialchars($row['full_name']); ?></td>
                                            <td><?= htmlspecialchars($row['address']); ?></td>
                                            
                                            <td>
                                                <select name="schedule_day" class="form-select">
                                                    <?php
                                                    $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                                                    foreach ($days as $day) {
                                                        echo "<option value='$day' " . ($row['schedule_day'] == $day ? 'selected' : '') . ">$day</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </td>

                                            <td>
                                                <select name="waste_type" class="form-select">
                                                    <option value="biodegradable" <?= ($row['waste_type'] == 'biodegradable') ? 'selected' : ''; ?>>Biodegradable</option>
                                                    <option value="non-biodegradable" <?= ($row['waste_type'] == 'non-biodegradable') ? 'selected' : ''; ?>>Non-Biodegradable</option>
                                                    <option value="recyclable" <?= ($row['waste_type'] == 'recyclable') ? 'selected' : ''; ?>>Recyclable</option>
                                                </select>
                                            </td>

                                            <td>
                                                <select name="status" class="form-select">
                                                    <option value="pending" <?= ($row['status'] == 'pending') ? 'selected' : ''; ?>>Pending</option>
                                                    <option value="approved" <?= ($row['status'] == 'approved') ? 'selected' : ''; ?>>Approved</option>
                                                    <option value="completed" <?= ($row['status'] == 'completed') ? 'selected' : ''; ?>>Completed</option>
                                                    <option value="rejected" <?= ($row['status'] == 'rejected') ? 'selected' : ''; ?>>Rejected</option>
                                                </select>
                                            </td>

                                            <td><input type="text" name="pickup_location" class="form-control" value="<?= htmlspecialchars($row['pickup_location']); ?>"></td>
                                            <td><input type="date" name="collection_date" class="form-control" value="<?= htmlspecialchars($row['collection_date']); ?>"></td>
                                            <td><input type="time" name="collection_time" class="form-control" value="<?= htmlspecialchars($row['collection_time']); ?>"></td>

                                            <td class="text-center">
                                                <button type="submit" name="update" class="btn btn-success btn-sm">
                                                    <i class="bi bi-pencil-square"></i> Update
                                                </button>
                                            </td>
                                        </form>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?> 
