<?php
session_start();
include '../includes/db.php';

// Check if user is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Handle logout action
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit();
}

$stats = ['users' => 0, 'services' => 0, 'messages' => 0];
$recent_messages = [];

try {
    // We don't have a users table yet, let's mock it or just use 0
    $stmt = $pdo->query("SELECT COUNT(*) FROM services");
    $stats['services'] = $stmt->fetchColumn();

    $stmt = $pdo->query("SELECT COUNT(*) FROM messages");
    $stats['messages'] = $stmt->fetchColumn();

    // Recent messages
    $stmt = $pdo->query("SELECT * FROM messages ORDER BY id DESC LIMIT 5");
    $recent_messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    // Log error or ignore for now
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Tech Elevate X</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { margin: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f8f9fc; color: #5a5c69; display: flex; min-height: 100vh; }

        /* Sidebar */
        .sidebar { width: 250px; background-color: #4e73df; color: white; display: flex; flex-direction: column; }
        .sidebar-brand { padding: 20px; text-align: center; font-size: 1.2rem; font-weight: bold; border-bottom: 1px solid rgba(255,255,255,0.1); }
        .sidebar-nav { flex-grow: 1; padding: 20px 0; }
        .sidebar-nav a { display: block; padding: 15px 20px; color: rgba(255,255,255,0.8); text-decoration: none; transition: background 0.2s; }
        .sidebar-nav a:hover, .sidebar-nav a.active { background-color: rgba(255,255,255,0.1); color: white; border-left: 4px solid white; }
        .sidebar-nav a i { margin-right: 10px; width: 20px; text-align: center; }

        /* Main Content */
        .main-content { flex-grow: 1; display: flex; flex-direction: column; }

        /* Topbar */
        .topbar { background-color: white; height: 70px; display: flex; justify-content: space-between; align-items: center; padding: 0 20px; box-shadow: 0 .15rem 1.75rem 0 rgba(58,59,69,.15); }
        .topbar-user { display: flex; align-items: center; gap: 10px; }
        .topbar-user img { width: 40px; height: 40px; border-radius: 50%; }

        /* Page Content */
        .page-content { padding: 30px; }
        h1 { margin-top: 0; font-weight: 400; color: #5a5c69; margin-bottom: 30px; }

        /* Cards */
        .dashboard-cards { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px; }
        .card { background: white; border-radius: 5px; box-shadow: 0 0.15rem 1.75rem 0 rgba(58,59,69,.15); padding: 20px; display: flex; align-items: center; justify-content: space-between; border-left: 5px solid #4e73df; }
        .card.success { border-left-color: #1cc88a; }
        .card.warning { border-left-color: #f6c23e; }
        .card-info h3 { margin: 0; font-size: 0.8rem; text-transform: uppercase; color: #4e73df; margin-bottom: 5px; }
        .card.success .card-info h3 { color: #1cc88a; }
        .card.warning .card-info h3 { color: #f6c23e; }
        .card-info p { margin: 0; font-size: 1.5rem; font-weight: bold; color: #5a5c69; }
        .card i { font-size: 2rem; color: #dddfeb; }

        /* Recent Activity Table */
        .recent-activity { background: white; border-radius: 5px; box-shadow: 0 0.15rem 1.75rem 0 rgba(58,59,69,.15); padding: 20px; }
        .recent-activity h2 { margin-top: 0; font-size: 1.2rem; border-bottom: 1px solid #e3e6f0; padding-bottom: 10px; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 12px 15px; text-align: left; border-bottom: 1px solid #e3e6f0; }
        th { font-weight: 600; color: #858796; }

        .btn-sm { padding: 5px 10px; font-size: 0.8rem; border-radius: 3px; background: #e74a3b; color: white; text-decoration: none; border: none; cursor: pointer; }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-brand">
            <i class="fas fa-rocket"></i> Tech Elevate X Admin
        </div>
        <div class="sidebar-nav">
            <a href="index.php" class="active"><i class="fas fa-fw fa-tachometer-alt"></i> Dashboard</a>
            <a href="#"><i class="fas fa-fw fa-users"></i> Users</a>
            <a href="#"><i class="fas fa-fw fa-cogs"></i> Services</a>
            <a href="#"><i class="fas fa-fw fa-envelope"></i> Messages</a>
            <a href="#"><i class="fas fa-fw fa-robot"></i> Chatbot Settings</a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">

        <!-- Topbar -->
        <div class="topbar">
            <div>
                <button style="border:none; background:none; cursor:pointer; font-size:1.2rem; color:#858796;"><i class="fas fa-bars"></i></button>
            </div>
            <div class="topbar-user">
                <span style="font-size: 0.9rem; font-weight: bold;"><?php echo htmlspecialchars($_SESSION['admin_username']); ?></span>
                <img src="https://ui-avatars.com/api/?name=Admin&background=4e73df&color=fff" alt="Admin Profile">
                <a href="index.php?logout=true" style="margin-left: 15px; color: #e74a3b; text-decoration: none;"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </div>

        <!-- Page Content -->
        <div class="page-content">
            <h1>Dashboard Overview</h1>

            <div class="dashboard-cards">
                <div class="card">
                    <div class="card-info">
                        <h3>Total Users</h3>
                        <p><?php echo $stats['users']; ?></p>
                    </div>
                    <i class="fas fa-users"></i>
                </div>

                <div class="card success">
                    <div class="card-info">
                        <h3>Active Services</h3>
                        <p><?php echo $stats['services']; ?></p>
                    </div>
                    <i class="fas fa-clipboard-list"></i>
                </div>

                <div class="card warning">
                    <div class="card-info">
                        <h3>Unread Messages</h3>
                        <p><?php echo $stats['messages']; ?></p>
                    </div>
                    <i class="fas fa-comments"></i>
                </div>
            </div>

            <div class="recent-activity">
                <h2>Recent Messages</h2>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Message Snippet</th>

                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($recent_messages)): ?>
                        <tr><td colspan="5" style="text-align:center;">No recent messages</td></tr>
                        <?php else: foreach($recent_messages as $msg): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($msg['id']); ?></td>
                            <td><?php echo htmlspecialchars($msg['name']); ?></td>
                            <td><?php echo htmlspecialchars($msg['email']); ?></td>
                            <td><?php echo htmlspecialchars(substr($msg['message'], 0, 30)) . '...'; ?></td>
                            <td><button class="btn-sm">View</button></td>
                        </tr>
                        <?php endforeach; endif; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

</body>
</html>
