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
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Users - Tech Elevate X Admin</title>
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
        .alert { background: #d4edda; color: #155724; padding: 10px; border-radius: 4px; margin-bottom: 15px; }
    </style>
</head>
<body>
    <?php include 'includes/sidebar.php'; ?>
    <div class="main-content">
        <div class="topbar">
            <div><i class="fas fa-bars"></i></div>
            <div><a href="index.php?logout=true" style="color:#e74a3b; text-decoration:none;"><i class="fas fa-sign-out-alt"></i> Logout</a></div>
        </div>
        <div class="page-content">
            <h1>Registered Users List</h1>
            <?php if($msg) echo "<div class='alert'>$msg</div>"; ?>

            <div class="card">
                <table>
                    <thead>
                        <tr><th>ID</th><th>Username</th><th>Email</th><th>Registered On</th><th>Action</th></tr>
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
                                    <button type="submit" class="btn-sm"><i class="fas fa-trash"></i> Delete User</button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
