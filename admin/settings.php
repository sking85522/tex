<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($_POST as $key => $value) {
        if (strpos($key, 'setting_') === 0) {
            $setting_key = substr($key, 8);
            try {
                $stmt = $pdo->prepare("UPDATE site_settings SET setting_value = ? WHERE setting_key = ?");
                $stmt->execute([$value, $setting_key]);
            } catch (PDOException $e) {
                // Handle error
            }
        }
    }
    $msg = "Settings updated successfully!";
}

$settings = [];
try {
    $stmt = $pdo->query("SELECT setting_key, setting_value FROM site_settings");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $settings[$row['setting_key']] = $row['setting_value'];
    }
} catch (PDOException $e) { }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CMS Settings - Tech Elevate X Admin</title>
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
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
        .form-group input, .form-group textarea { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        .btn-primary { background: #4e73df; color: white; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer; }
        .btn-primary:hover { background: #2e59d9; }
        .alert-success { background: #1cc88a; color: white; padding: 15px; border-radius: 4px; margin-bottom: 20px; }
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
            <h1>Site Settings (CMS)</h1>
            <?php if(isset($msg)) echo "<div class='alert-success'>$msg</div>"; ?>
            <div class="card">
                <form action="" method="POST">
                    <h3>Home Page</h3>
                    <div class="form-group">
                        <label>Hero Title</label>
                        <input type="text" name="setting_home_hero_title" value="<?php echo htmlspecialchars($settings['home_hero_title'] ?? ''); ?>">
                    </div>
                    <div class="form-group">
                        <label>Hero Subtitle</label>
                        <textarea name="setting_home_hero_subtitle" rows="3"><?php echo htmlspecialchars($settings['home_hero_subtitle'] ?? ''); ?></textarea>
                    </div>

                    <h3>About Page</h3>
                    <div class="form-group">
                        <label>About Heading</label>
                        <input type="text" name="setting_about_heading" value="<?php echo htmlspecialchars($settings['about_heading'] ?? ''); ?>">
                    </div>
                    <div class="form-group">
                        <label>About Content</label>
                        <textarea name="setting_about_content" rows="4"><?php echo htmlspecialchars($settings['about_content'] ?? ''); ?></textarea>
                    </div>

                    <h3>Contact Info</h3>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="setting_contact_email" value="<?php echo htmlspecialchars($settings['contact_email'] ?? ''); ?>">
                    </div>
                    <div class="form-group">
                        <label>Phone</label>
                        <input type="text" name="setting_contact_phone" value="<?php echo htmlspecialchars($settings['contact_phone'] ?? ''); ?>">
                    </div>
                    <div class="form-group">
                        <label>Address</label>
                        <input type="text" name="setting_contact_address" value="<?php echo htmlspecialchars($settings['contact_address'] ?? ''); ?>">
                    </div>

                    <button type="submit" class="btn-primary">Save Settings</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
