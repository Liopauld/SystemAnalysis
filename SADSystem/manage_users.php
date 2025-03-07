<?php
session_start();
include 'includes/header.php';
include 'config/db.php'; // Ensure this contains your database connection

// Check if the user is an admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo "<script>alert('Access Denied!'); window.location.href='../index.php';</script>";
    exit();
}

// Fetch users from the database
$query = "SELECT user_id, full_name, email, phone_number, address, role FROM users";
$result = mysqli_query($conn, $query);

// Handle role update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_role'])) {
    $user_id = $_POST['user_id'];
    $new_role = $_POST['role'];
    $update_query = "UPDATE users SET role = '$new_role' WHERE user_id = $user_id";
    if (mysqli_query($conn, $update_query)) {
        echo "<script>alert('User role updated successfully!'); window.location.href='manage_users.php';</script>";
    } else {
        echo "<script>alert('Error updating role.');</script>";
    }
}
?>

<div class="container mt-4">
    <h2 class="mb-4">Manage Users</h2>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Address</th>
                <th>Role</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                <tr>
                    <td><?php echo $row['user_id']; ?></td>
                    <td><?php echo $row['full_name']; ?></td>
                    <td><?php echo $row['email']; ?></td>
                    <td><?php echo $row['phone_number']; ?></td>
                    <td><?php echo $row['address']; ?></td>
                    <td>
                        <form method="POST">
                            <input type="hidden" name="user_id" value="<?php echo $row['user_id']; ?>">
                            <select name="role" class="form-select">
                                <option value="resident" <?php if ($row['role'] == 'resident') echo 'selected'; ?>>Resident</option>
                                <option value="admin" <?php if ($row['role'] == 'admin') echo 'selected'; ?>>Admin</option>
                                <option value="collector" <?php if ($row['role'] == 'collector') echo 'selected'; ?>>Collector</option>
                            </select>
                    </td>
                    <td>
                        <button type="submit" name="update_role" class="btn btn-primary">Update</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include 'includes/footer.php'; ?>
