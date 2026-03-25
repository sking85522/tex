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
<?php include 'includes/header.php'; ?>

            <h1>NLP Chatbot Training Data</h1>
            <?php if($msg) echo "<div class='alert'>$msg</div>"; ?>

            <div class="card shadow-sm border-0 mb-4 p-4">
                <h3>Train Chatbot with New Concept</h3>
                <form action="chatbot.php" method="POST" style="display: flex; flex-wrap: wrap; gap: 15px;">
                    <input type="hidden" name="action" value="add">
                    <div class="mb-3" style="flex: 100%;">
                        <label>Keywords (comma separated e.g. "price, cost, fee, charge")</label>
                        <input type="text" name="keywords" required>
                    </div>
                    <div class="mb-3" style="flex: 100%;">
                        <label>Bot Answer</label>
                        <textarea name="answer" required rows="3"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Save Training Data</button>
                </form>
            </div>

            <div class="card shadow-sm border-0 mb-4 p-4">
                <h3>Current Training Data</h3>
                <table class="table table-hover align-middle">
                    <thead class="table-light"><tr><th>ID</th><th>Keywords</th><th>Answer</th><th>Action</th></tr>
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
                                    <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i> Delete</button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; endif; ?>
                    </tbody>
                </table>
            </div>
        <?php include 'includes/footer.php'; ?>
