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
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Staff - Tech Elevate X Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { margin: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f8f9fc; color: #5a5c69; display: flex; min-height: 100vh; }
        .sidebar { width: 250px; background-color: #4e73df; color: white; display: flex; flex-direction: column; }
        .sidebar-brand { padding: 20px; text-align: center; font-size: 1.2rem; font-weight: bold; border-bottom: 1px solid rgba(255,255,255,0.1); }
        .sidebar-nav { flex-grow: 1; padding: 20px 0; }
        .sidebar-nav a { display: block; padding: 15px 20px; color: rgba(255,255,255,0.8); text-decoration: none; transition: background 0.2s; }
        .sidebar-nav a:hover, .sidebar-nav a.active { background-color: rgba(255,255,255,0.1); color: white; border-left: 4px solid white; }
        .main-content { flex-grow: 1; display: flex; flex-direction: column; }
        .topbar { background-color: white; height: 70px; display: flex; justify-content: space-between; align-items: center; padding: 0 20px; box-shadow: 0 .15rem 1.75rem 0 rgba(58,59,69,.15); }
        .page-content { padding: 30px; }
        .card { background: white; border-radius: 5px; box-shadow: 0 0.15rem 1.75rem 0 rgba(58,59,69,.15); padding: 20px; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        .btn-sm { padding: 5px 10px; font-size: 0.8rem; background: #e74a3b; color: white; border: none; cursor: pointer; border-radius: 3px; }
        .btn-primary { background: #4e73df; color: white; border: none; padding: 10px 20px; cursor: pointer; border-radius: 4px;}
        .form-group { margin-bottom: 15px; }
        .form-group input, .form-group select { width: 100%; padding: 10px; border: 1px solid #ddd; box-sizing: border-box; border-radius:4px;}
        .alert { background: #f8d7da; color: #721c24; padding: 10px; border-radius: 4px; margin-bottom: 15px; }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-brand"><i class="fas fa-rocket"></i> Admin Panel</div>
        <div class="sidebar-nav">
            <a href="index.php"><i class="fas fa-fw fa-tachometer-alt"></i> Dashboard</a>
            <a href="settings.php"><i class="fas fa-fw fa-cogs"></i> CMS Settings</a>
            <a href="portfolio.php"><i class="fas fa-fw fa-briefcase"></i> Portfolio</a>
            <a href="careers.php"><i class="fas fa-fw fa-user-tie"></i> Careers / HR</a>
            <a href="staff.php" class="active"><i class="fas fa-fw fa-users-cog"></i> Staff Mgmt</a>
        </div>
    </div>
    <div class="main-content">
        <div class="topbar">
            <div><i class="fas fa-bars"></i></div>
            <div><a href="index.php?logout=true" style="color:#e74a3b; text-decoration:none;"><i class="fas fa-sign-out-alt"></i> Logout</a></div>
        </div>
        <div class="page-content">
            <h1>Staff Management</h1>
            <?php if($msg) echo "<div class='alert'>$msg</div>"; ?>

            <div class="card">
                <h3>Add New Staff Member</h3>
                <form action="staff.php" method="POST" style="display: flex; flex-wrap: wrap; gap: 15px; align-items: flex-end;">
                    <input type="hidden" name="action" value="add_staff">
                    <div class="form-group" style="flex: 1; min-width: 200px;">
                        <label>Username</label><input type="text" name="username" required>
                    </div>
                    <div class="form-group" style="flex: 1; min-width: 200px;">
                        <label>Email</label><input type="email" name="email" required>
                    </div>
                    <div class="form-group" style="flex: 1; min-width: 200px;">
                        <label>Password</label><input type="password" name="password" required>
                    </div>
                    <div class="form-group" style="flex: 1; min-width: 150px;">
                        <label>Role</label>
                        <select name="role">
                            <option value="super_admin">Super Admin</option>
                            <option value="hr">HR Manager</option>
                            <option value="chat_support">Chat Support</option>
                        </select>
                    </div>
                    <div class="form-group" style="flex: 1; min-width: 150px;">
                        <button type="submit" class="btn-primary" style="width:100%;">Add Staff</button>
                    </div>
                </form>
            </div>

            <div class="card">
                <h3>Staff Directory</h3>
                <table>
                    <thead>
                        <tr><th>ID</th><th>Username</th><th>Email</th><th>Role</th><th>Action</th></tr>
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
                                    <button type="submit" class="btn-sm" onclick="return confirm('Remove this staff member?');">Remove</button>
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
        </div>
    </div>
</body>
</html>
