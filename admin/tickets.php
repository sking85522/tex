<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['admin_id']) || !in_array($_SESSION['admin_role'], ['super_admin', 'chat_support'])) {
    header("Location: login.php");
    exit();
}

$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'close_ticket') {
        try {
            $stmt = $pdo->prepare("UPDATE support_tickets SET status = 'closed' WHERE id = ?");
            $stmt->execute([$_POST['id']]);
            $msg = "Ticket closed successfully.";
        } catch (PDOException $e) { $msg = "Error updating ticket."; }
    }
}

$tickets = [];
try {
    $stmt = $pdo->query("SELECT * FROM support_tickets ORDER BY id DESC");
    $tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Support Tickets - Tech Elevate X Admin</title>
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
        .btn-success { background: #1cc88a; }
        .alert { background: #d4edda; color: #155724; padding: 10px; border-radius: 4px; margin-bottom: 15px; }
        .badge-open { background:#f6c23e; color:#fff; padding:3px 8px; border-radius:10px; font-size:0.8rem; font-weight:bold; }
        .badge-closed { background:#858796; color:#fff; padding:3px 8px; border-radius:10px; font-size:0.8rem; font-weight:bold; }
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
            <h1>Support Tickets</h1>
            <?php if($msg) echo "<div class='alert'>$msg</div>"; ?>

            <div class="card">
                <h3>Chatbot Generated Tickets</h3>
                <table>
                    <thead>
                        <tr><th>ID</th><th>User Email</th><th>Question / Context</th><th>Status</th><th>Date</th><th>Action</th></tr>
                    </thead>
                    <tbody>
                        <?php if(empty($tickets)): ?>
                            <tr><td colspan="6" style="text-align:center;">No tickets generated yet.</td></tr>
                        <?php else: foreach($tickets as $ticket): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($ticket['id']); ?></td>
                            <td><a href="mailto:<?php echo htmlspecialchars($ticket['user_email']); ?>"><?php echo htmlspecialchars($ticket['user_email']); ?></a></td>
                            <td style="max-width:300px;"><?php echo htmlspecialchars($ticket['question']); ?></td>
                            <td>
                                <?php if($ticket['status'] == 'open'): ?>
                                    <span class="badge-open">OPEN</span>
                                <?php else: ?>
                                    <span class="badge-closed">CLOSED</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo date('M d, H:i', strtotime($ticket['created_at'])); ?></td>
                            <td>
                                <?php if($ticket['status'] == 'open'): ?>
                                <form action="tickets.php" method="POST" style="display:inline;" onsubmit="return confirm('Close this ticket?');">
                                    <input type="hidden" name="action" value="close_ticket">
                                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($ticket['id']); ?>">
                                    <button type="submit" class="btn-sm btn-success"><i class="fas fa-check"></i> Close</button>
                                </form>
                                <?php else: ?>
                                    <span style="color:#999;font-size:0.8rem;">Resolved</span>
                                <?php endif; ?>
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
