<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'add') {
        $stmt = $pdo->prepare("INSERT INTO portfolio (title, description, category, image_url, demo_url) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$_POST['title'], $_POST['description'], $_POST['category'], $_POST['image_url'], $_POST['demo_url']]);
    } elseif ($_POST['action'] === 'delete') {
        $stmt = $pdo->prepare("DELETE FROM portfolio WHERE id = ?");
        $stmt->execute([$_POST['id']]);
    }
}

$portfolio = [];
try {
    $stmt = $pdo->query("SELECT * FROM portfolio ORDER BY id DESC");
    $portfolio = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) { }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Portfolio - Tech Elevate X Admin</title>
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
        .form-group { margin-bottom: 15px; }
        .form-group input, .form-group textarea { width: 100%; padding: 10px; border: 1px solid #ddd; box-sizing: border-box; }
        .btn-primary { background: #4e73df; color: white; border: none; padding: 10px 20px; cursor: pointer; }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-brand"><i class="fas fa-rocket"></i> Admin Panel</div>
        <div class="sidebar-nav">
            <a href="index.php"><i class="fas fa-fw fa-tachometer-alt"></i> Dashboard</a>
            <a href="settings.php"><i class="fas fa-fw fa-cogs"></i> CMS Settings</a>
            <a href="portfolio.php" class="active"><i class="fas fa-fw fa-briefcase"></i> Portfolio</a>
        </div>
    </div>
    <div class="main-content">
        <div class="topbar">
            <div><i class="fas fa-bars"></i></div>
            <div><a href="index.php?logout=true" style="color:#e74a3b; text-decoration:none;"><i class="fas fa-sign-out-alt"></i> Logout</a></div>
        </div>
        <div class="page-content">
            <h1>Manage Portfolio</h1>

            <div class="card">
                <h3>Add New Project</h3>
                <form action="portfolio.php" method="POST" style="display: flex; flex-wrap: wrap; gap: 15px;">
                    <input type="hidden" name="action" value="add">
                    <div class="form-group" style="flex: 1; min-width: 200px;"><input type="text" name="title" placeholder="Project Title" required></div>
                    <div class="form-group" style="flex: 1; min-width: 200px;"><input type="text" name="category" placeholder="Category (e.g., Web Dev)" required></div>
                    <div class="form-group" style="flex: 1; min-width: 200px;"><input type="url" name="image_url" placeholder="Image URL"></div>
                    <div class="form-group" style="flex: 1; min-width: 200px;"><input type="url" name="demo_url" placeholder="Demo URL (Live Link)"></div>
                    <div class="form-group" style="flex: 100%;"><textarea name="description" placeholder="Short description" rows="2" required></textarea></div>
                    <button type="submit" class="btn-primary">Add Project</button>
                </form>
            </div>

            <div class="card">
                <h3>Existing Projects</h3>
                <table>
                    <thead>
                        <tr><th>ID</th><th>Title</th><th>Category</th><th>Demo</th><th>Action</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach($portfolio as $p): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($p['id']); ?></td>
                            <td><?php echo htmlspecialchars($p['title']); ?></td>
                            <td><?php echo htmlspecialchars($p['category']); ?></td>
                            <td><a href="<?php echo htmlspecialchars($p['demo_url']); ?>" target="_blank">Link</a></td>
                            <td>
                                <form action="portfolio.php" method="POST" style="display:inline;">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($p['id']); ?>">
                                    <button type="submit" class="btn-sm" onclick="return confirm('Are you sure?');">Delete</button>
                                </form>
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
