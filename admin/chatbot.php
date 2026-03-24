<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['admin_id']) || !in_array($_SESSION['admin_role'], ['super_admin', 'chat_support'])) {
    header("Location: login.php");
    exit();
}

$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'add') {
        try {
            $stmt = $pdo->prepare("INSERT INTO chatbot_training (keywords, answer) VALUES (?, ?)");
            $stmt->execute([$_POST['keywords'], $_POST['answer']]);
            $msg = "Training data added successfully.";
        } catch (PDOException $e) { $msg = "Error adding training data."; }
    } elseif ($_POST['action'] === 'delete') {
        try {
            $stmt = $pdo->prepare("DELETE FROM chatbot_training WHERE id = ?");
            $stmt->execute([$_POST['id']]);
            $msg = "Training data removed.";
        } catch (PDOException $e) { $msg = "Error removing data."; }
    }
}

$training_data = [];
try {
    $stmt = $pdo->query("SELECT * FROM chatbot_training ORDER BY id DESC");
    $training_data = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Train Chatbot (NLP) - Tech Elevate X Admin</title>
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
        .alert { background: #d4edda; color: #155724; padding: 10px; border-radius: 4px; margin-bottom: 15px; }
        .form-group { margin-bottom: 15px; }
        .form-group input, .form-group textarea { width: 100%; padding: 10px; border: 1px solid #ddd; box-sizing: border-box; border-radius:4px;}
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
            <h1>NLP Chatbot Training Data</h1>
            <?php if($msg) echo "<div class='alert'>$msg</div>"; ?>

            <div class="card">
                <h3>Train Chatbot with New Concept</h3>
                <form action="chatbot.php" method="POST" style="display: flex; flex-wrap: wrap; gap: 15px;">
                    <input type="hidden" name="action" value="add">
                    <div class="form-group" style="flex: 100%;">
                        <label>Keywords (comma separated e.g. "price, cost, fee, charge")</label>
                        <input type="text" name="keywords" required>
                    </div>
                    <div class="form-group" style="flex: 100%;">
                        <label>Bot Answer</label>
                        <textarea name="answer" required rows="3"></textarea>
                    </div>
                    <button type="submit" class="btn-primary">Save Training Data</button>
                </form>
            </div>

            <div class="card">
                <h3>Current Training Data</h3>
                <table>
                    <thead>
                        <tr><th>ID</th><th>Keywords</th><th>Answer</th><th>Action</th></tr>
                    </thead>
                    <tbody>
                        <?php if(empty($training_data)): ?>
                            <tr><td colspan="4" style="text-align:center;">No data found.</td></tr>
                        <?php else: foreach($training_data as $data): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($data['id']); ?></td>
                            <td><strong style="color: #4e73df;"><?php echo htmlspecialchars($data['keywords']); ?></strong></td>
                            <td><?php echo htmlspecialchars($data['answer']); ?></td>
                            <td>
                                <form action="chatbot.php" method="POST" onsubmit="return confirm('Delete this training item?');">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($data['id']); ?>">
                                    <button type="submit" class="btn-sm"><i class="fas fa-trash"></i> Delete</button>
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
