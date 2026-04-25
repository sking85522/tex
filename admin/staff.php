<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

$role = $_SESSION['admin_role'] ?? 'super_admin';

if ($role !== 'super_admin') {
    die("Access Denied. Only Super Admins can manage staff.");
}

$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'add_staff') {
        $username = trim($_POST['username']);
        $email = trim($_POST['email']);
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $role_assigned = $_POST['role'];

        try {
            $stmt = $pdo->prepare("INSERT INTO admins (username, password, email, role) VALUES (?, ?, ?, ?)");
            $stmt->execute([$username, $password, $email, $role_assigned]);
            $msg = "Staff member added successfully.";
        } catch(PDOException $e) {
            $msg = "Error adding staff. Username might already exist.";
        }
    } elseif ($_POST['action'] === 'delete_staff') {
        $id = $_POST['id'];
        if ($id != $_SESSION['admin_id']) { // Prevent self-deletion
            $stmt = $pdo->prepare("DELETE FROM admins WHERE id = ?");
            $stmt->execute([$id]);
            $msg = "Staff member removed.";
        } else {
            $msg = "You cannot delete your own account.";
        }
    }
}

$staff = [];
try {
    $stmt = $pdo->query("SELECT id, username, email, role, created_at FROM admins ORDER BY id ASC");
    $staff = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) { }

?>
<?php include 'includes/header.php'; ?>

            <h1>Staff Management</h1>
            <?php if($msg) echo "<div class='alert'>$msg</div>"; ?>

            <div class="card shadow-sm border-0 mb-4 p-4">
                <h3>Add New Staff Member</h3>
                <form action="staff.php" method="POST" style="display: flex; flex-wrap: wrap; gap: 15px; align-items: flex-end;">
                    <input type="hidden" name="action" value="add_staff">
                    <div class="mb-3" style="flex: 1; min-width: 200px;">
                        <label>Username</label><input type="text" name="username" required>
                    </div>
                    <div class="mb-3" style="flex: 1; min-width: 200px;">
                        <label>Email</label><input type="email" name="email" required>
                    </div>
                    <div class="mb-3" style="flex: 1; min-width: 200px;">
                        <label>Password</label><input type="password" name="password" required>
                    </div>
                    <div class="mb-3" style="flex: 1; min-width: 150px;">
                        <label>Role</label>
                        <select name="role">
                            <option value="super_admin">Super Admin</option>
                            <option value="hr">HR Manager</option>
                            <option value="chat_support">Chat Support</option>
                        </select>
                    </div>
                    <div class="mb-3" style="flex: 1; min-width: 150px;">
                        <button type="submit" class="btn btn-primary" style="width:100%;">Add Staff</button>
                    </div>
                </form>
            </div>

            <div class="card shadow-sm border-0 mb-4 p-4">
                <h3>Staff Directory</h3>
                <table class="table table-hover align-middle">
                    <thead class="table-light"><tr><th>ID</th><th>Username</th><th>Email</th><th>Role</th><th>Action</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach($staff as $member): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($member['id']); ?></td>
                            <td><?php echo htmlspecialchars($member['username']); ?></td>
                            <td><?php echo htmlspecialchars($member['email']); ?></td>
                            <td><span style="background:#e9ecef; padding:3px 8px; border-radius:10px; font-size:0.8rem; font-weight:bold;"><?php echo strtoupper(htmlspecialchars($member['role'])); ?></span></td>
                            <td>
                                <?php if($member['id'] != $_SESSION['admin_id']): ?>
                                <form action="staff.php" method="POST" style="display:inline;">
                                    <input type="hidden" name="action" value="delete_staff">
                                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($member['id']); ?>">
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Remove this staff member?');">Remove</button>
                                </form>
                                <?php else: ?>
                                    <span style="color:#999; font-size:0.8rem;">(You)</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php include 'includes/footer.php'; ?>
