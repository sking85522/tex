<?php
session_start();
include '../includes/db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Handle logout action
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard - Tech Elevate X</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { margin: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f8f9fc; color: #5a5c69; display: flex; min-height: 100vh; }

        /* Sidebar */
        .sidebar { width: 250px; background-color: #17a2b8; color: white; display: flex; flex-direction: column; }
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

        .welcome-card { background: white; border-radius: 5px; box-shadow: 0 0.15rem 1.75rem 0 rgba(58,59,69,.15); padding: 30px; border-left: 5px solid #17a2b8; }
        .welcome-card h2 { margin-top: 0; color: #17a2b8; }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-brand">
            <i class="fas fa-user-circle"></i> User Panel
        </div>
        <div class="sidebar-nav">
            <a href="index.php" class="active"><i class="fas fa-fw fa-home"></i> Dashboard</a>
            <a href="#"><i class="fas fa-fw fa-user-edit"></i> Profile</a>
            <a href="#"><i class="fas fa-fw fa-file-invoice"></i> Orders</a>
            <a href="#"><i class="fas fa-fw fa-headset"></i> Support</a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">

        <!-- Topbar -->
        <div class="topbar">
            <div>
                <a href="../index.php" style="text-decoration: none; color: #858796;"><i class="fas fa-globe"></i> Back to Site</a>
            </div>
            <div class="topbar-user">
                <span style="font-size: 0.9rem; font-weight: bold;"><?php echo htmlspecialchars($_SESSION['username']); ?></span>
                <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($_SESSION['username']); ?>&background=17a2b8&color=fff" alt="User Profile">
                <a href="index.php?logout=true" style="margin-left: 15px; color: #e74a3b; text-decoration: none;"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </div>

        <!-- Page Content -->
        <div class="page-content">
            <h1>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>

            <div class="welcome-card">
                <h2>Your Dashboard</h2>
                <p>Welcome to the Tech Elevate X user dashboard. Here you can manage your profile, view your service orders, and contact support.</p>
                <p><i>(Note: This is a prototype user dashboard. Advanced features will be added soon.)</i></p>
            </div>

        </div>
    </div>

</body>
</html>
