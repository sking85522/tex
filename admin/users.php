<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_SESSION['admin_role']) && $_SESSION['admin_role'] !== 'super_admin') {
    die("Access Denied.");
}

$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'delete') {
        try {
            $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
            $stmt->execute([$_POST['user_id']]);
            $msg = "User successfully removed.";
        } catch (PDOException $e) {
            $msg = "Error deleting user.";
        }
    }
}

$users = [];
try {
    $stmt = $pdo->query("SELECT * FROM users ORDER BY id DESC");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {}
?>
<?php include 'includes/header.php'; ?>

            <h1>Registered Users List</h1>
            <?php if($msg) echo "<div class='alert'>$msg</div>"; ?>

            <div class="card shadow-sm border-0 mb-4 p-4">
                <table class="table table-hover align-middle">
                    <thead class="table-light"><tr><th>ID</th><th>Username</th><th>Email</th><th>Registered On</th><th>Action</th></tr>
                    </thead>
                    <tbody>
                        <?php if(empty($users)): ?>
                            <tr><td colspan="5" style="text-align:center;">No users registered yet.</td></tr>
                        <?php else: foreach($users as $u): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($u['id']); ?></td>
                            <td><?php echo htmlspecialchars($u['username']); ?></td>
                            <td><?php echo htmlspecialchars($u['email']); ?></td>
                            <td><?php echo date('M d, Y', strtotime($u['created_at'])); ?></td>
                            <td>
                                <form action="users.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.');">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($u['id']); ?>">
                                    <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i> Delete User</button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; endif; ?>
                    </tbody>
                </table>
            </div>
        <?php include 'includes/footer.php'; ?>
