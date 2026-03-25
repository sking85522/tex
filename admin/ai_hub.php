<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

$role = $_SESSION['admin_role'] ?? 'super_admin';
if (!in_array($role, ['super_admin', 'chat_support'])) {
    die("Access Denied. Only Super Admins or Chat Support can manage the AI Brain.");
}

$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'add_knowledge') {
        try {
            $stmt = $pdo->prepare("INSERT INTO ai_knowledge (topic, learned_content, confidence_score) VALUES (?, ?, ?)");
            $stmt->execute([$_POST['topic'], $_POST['learned_content'], $_POST['confidence']]);
            $msg = "New knowledge successfully injected into the AI Brain.";
        } catch (PDOException $e) { $msg = "Error injecting knowledge."; }
    } elseif ($_POST['action'] === 'delete_knowledge') {
        try {
            $stmt = $pdo->prepare("DELETE FROM ai_knowledge WHERE id = ?");
            $stmt->execute([$_POST['id']]);
            $msg = "Neural pathway removed.";
        } catch (PDOException $e) { $msg = "Error removing data."; }
    } elseif ($_POST['action'] === 'boost_confidence') {
        try {
            $stmt = $pdo->prepare("UPDATE ai_knowledge SET confidence_score = 100 WHERE id = ?");
            $stmt->execute([$_POST['id']]);
            $msg = "AI confidence boosted to 100% for this topic.";
        } catch (PDOException $e) { $msg = "Error updating confidence."; }
    }
}

$knowledge = [];
$total_learned = 0;
$avg_confidence = 0;

try {
    $stmt = $pdo->query("SELECT * FROM ai_knowledge ORDER BY confidence_score DESC, id DESC");
    $knowledge = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $total_learned = count($knowledge);
    if($total_learned > 0) {
        $stmt = $pdo->query("SELECT AVG(confidence_score) FROM ai_knowledge");
        $avg_confidence = round($stmt->fetchColumn(), 1);
    }
} catch (PDOException $e) {}

$current_page = basename($_SERVER['PHP_SELF']);
?>
<?php include 'includes/header.php'; ?>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0 text-dark"><i class="bi bi-cpu text-primary me-2"></i> AI Brain & Neural Hub</h2>
    </div>

    <?php if($msg) echo "<div class='alert alert-info shadow-sm'>$msg</div>"; ?>

    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="card shadow-sm border-0 h-100 p-4" style="border-left: 5px solid #6610f2 !important;">
                <h5 class="fw-bold mb-3 text-dark">Neural Health & Statistics</h5>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-muted">Total Learned Concepts:</span>
                    <span class="fs-4 fw-bold text-dark"><?php echo $total_learned; ?></span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-muted">Average AI Confidence:</span>
                    <span class="fs-4 fw-bold <?php echo ($avg_confidence > 75) ? 'text-success' : 'text-warning'; ?>"><?php echo $avg_confidence; ?>%</span>
                </div>
                <div class="progress mt-2" style="height: 8px;">
                    <div class="progress-bar bg-<?php echo ($avg_confidence > 75) ? 'success' : 'warning'; ?>" role="progressbar" style="width: <?php echo $avg_confidence; ?>%;"></div>
                </div>
                <p class="text-muted small mt-3 mb-0"><i class="bi bi-info-circle"></i> A confidence score > 75% means the AI will automatically use this response in sales chats without human intervention.</p>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm border-0 h-100 p-4">
                <h5 class="fw-bold mb-3"><i class="bi bi-plus-circle text-primary me-2"></i> Manually Inject Knowledge</h5>
                <form action="ai_hub.php" method="POST">
                    <input type="hidden" name="action" value="add_knowledge">
                    <div class="mb-3">
                        <input type="text" name="topic" class="form-control" placeholder="Topic Keyword (e.g., 'React Development', 'Pricing Discount')" required>
                    </div>
                    <div class="mb-3">
                        <textarea name="learned_content" class="form-control" rows="3" placeholder="The exact response or knowledge the AI should use when asked about this topic..." required></textarea>
                    </div>
                    <div class="row align-items-center">
                        <div class="col-8">
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">Confidence:</span>
                                <input type="number" name="confidence" class="form-control border-start-0" min="0" max="100" value="100" required>
                                <span class="input-group-text bg-light">%</span>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <button type="submit" class="btn btn-primary w-100"><i class="bi bi-upload me-1"></i> Inject</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0 mb-4 p-0">
        <div class="card-header bg-white border-bottom pt-3 pb-2">
            <h5 class="fw-bold mb-0 text-dark"><i class="bi bi-database text-info me-2"></i> Current Knowledge Base (Self-Learned & Manual)</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Topic Pattern</th>
                            <th>AI Response / Knowledge Content</th>
                            <th class="text-center">Confidence</th>
                            <th class="text-end pe-4">Override Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($knowledge)): ?>
                        <tr><td colspan="4" class="text-center py-4 text-muted">The AI Brain is currently empty. Start training it!</td></tr>
                        <?php else: foreach($knowledge as $k):
                            $conf = (int)$k['confidence_score'];
                            $badge = 'success';
                            if($conf < 80) $badge = 'warning text-dark';
                            if($conf < 50) $badge = 'danger';
                        ?>
                        <tr>
                            <td class="ps-4 fw-bold text-primary" style="white-space:nowrap;"><?php echo htmlspecialchars($k['topic']); ?></td>
                            <td class="text-muted small" style="max-width:400px;"><?php echo htmlspecialchars($k['learned_content']); ?></td>
                            <td class="text-center">
                                <span class="badge bg-<?php echo $badge; ?> rounded-pill px-3 py-2"><?php echo $conf; ?>%</span>
                            </td>
                            <td class="text-end pe-4" style="min-width: 200px;">
                                <?php if($conf < 100): ?>
                                <form action="ai_hub.php" method="POST" class="d-inline">
                                    <input type="hidden" name="action" value="boost_confidence">
                                    <input type="hidden" name="id" value="<?php echo $k['id']; ?>">
                                    <button type="submit" class="btn btn-sm btn-outline-success rounded-pill px-3 me-1" title="Approve & Set to 100%"><i class="bi bi-check-lg"></i> Verify</button>
                                </form>
                                <?php endif; ?>
                                <form action="ai_hub.php" method="POST" class="d-inline" onsubmit="return confirm('Erase this memory from the AI Brain?');">
                                    <input type="hidden" name="action" value="delete_knowledge">
                                    <input type="hidden" name="id" value="<?php echo $k['id']; ?>">
                                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3"><i class="bi bi-eraser"></i> Erase</button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

<?php include 'includes/footer.php'; ?>
