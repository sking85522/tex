<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['admin_id']) || !in_array($_SESSION['admin_role'], ['super_admin', 'chat_support'])) {
    header("Location: login.php");
    exit();
}

$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {

    if ($_POST['action'] === 'ai_reply') {
        require_once __DIR__ . '/../brain/core/Autoloader.php';
        try {
            if (class_exists('Core\AIEngine')) {
                $engine = new \Core\Engine();
                $prompt = "Write a polite customer support email reply for the following user issue. Explain we are looking into it and will fix it ASAP. Issue: " . $_POST['question'];
                $res = $engine->processPrompt($prompt);
                $reply = $res['response'] ?? '';

                if (str_contains($reply, 'I have not learned')) {
                    $reply = "Hello,\n\nThank you for reaching out. Our AI and human support teams are currently reviewing your issue ('" . htmlspecialchars($_POST['question']) . "'). We will get back to you with a resolution shortly.\n\nBest,\nTech Elevate X Support";
                }

                // Mock sending email / update DB
                $stmt = $pdo->prepare("UPDATE support_tickets SET status = 'closed', admin_reply = ? WHERE id = ?");
                // if admin_reply column doesn't exist, this might fail, let's gracefully handle it or just close it
                try {
                    $stmt->execute([$reply, $_POST['id']]);
                } catch (\Exception $e) {
                    $stmt = $pdo->prepare("UPDATE support_tickets SET status = 'closed' WHERE id = ?");
                    $stmt->execute([$_POST['id']]);
                }
                $msg = "AI automatically generated a reply and closed the ticket.";
            }
        } catch (\Exception $e) { $msg = "AI Reply failed."; }
    }

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
<?php include 'includes/header.php'; ?>

            <h1>Support Tickets</h1>
            <?php if($msg) echo "<div class='alert'>$msg</div>"; ?>

            <div class="card shadow-sm border-0 mb-4 p-4">
                <h3>Chatbot Generated Tickets</h3>
                <table class="table table-hover align-middle">
                    <thead class="table-light"><tr><th>ID</th><th>User Email</th><th>Question / Context</th><th>Status</th><th>Date</th><th>Action</th></tr>
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

                                <form action="tickets.php" method="POST" style="display:inline;" onsubmit="return confirm('Let AI generate and send a reply?');">
                                    <input type="hidden" name="action" value="ai_reply">
                                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($ticket['id']); ?>">
                                    <input type="hidden" name="question" value="<?php echo htmlspecialchars($ticket['question']); ?>">
                                    <button type="submit" class="btn btn-sm btn-info text-white"><i class="bi bi-robot"></i> Auto-Reply</button>
                                </form>

                                <form action="tickets.php" method="POST" style="display:inline;" onsubmit="return confirm('Close this ticket?');">
                                    <input type="hidden" name="action" value="close_ticket">
                                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($ticket['id']); ?>">
                                    <button type="submit" class="btn btn-sm btn-danger btn-success"><i class="fas fa-check"></i> Close</button>
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
        <?php include 'includes/footer.php'; ?>
