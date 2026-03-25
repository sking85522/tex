<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['admin_id']) || $_SESSION['admin_role'] !== 'super_admin') {
    header("Location: login.php");
    exit();
}

$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'add') {
        try {
            $stmt = $pdo->prepare("INSERT INTO client_projects (user_id, project_name, description, status_phase, progress_percent, total_cost, paid_amount) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$_POST['user_id'], $_POST['project_name'], $_POST['description'], $_POST['status_phase'], $_POST['progress_percent'], $_POST['total_cost'], $_POST['paid_amount']]);
            $msg = "Project assigned to client successfully.";
        } catch (PDOException $e) { $msg = "Error assigning project."; }
    } elseif ($_POST['action'] === 'update') {
        try {
            $stmt = $pdo->prepare("UPDATE client_projects SET status_phase = ?, progress_percent = ?, paid_amount = ? WHERE id = ?");
            $stmt->execute([$_POST['status_phase'], $_POST['progress_percent'], $_POST['paid_amount'], $_POST['project_id']]);
            $msg = "Project progress updated.";
        } catch (PDOException $e) { $msg = "Error updating project."; }
    } elseif ($_POST['action'] === 'delete') {
        try {
            $stmt = $pdo->prepare("DELETE FROM client_projects WHERE id = ?");
            $stmt->execute([$_POST['project_id']]);
            $msg = "Project deleted.";
        } catch (PDOException $e) { $msg = "Error deleting project."; }
    }
}

$users = [];
$projects = [];
try {
    $stmt = $pdo->query("SELECT id, username, email FROM users ORDER BY id DESC");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $pdo->query("SELECT cp.*, u.username as client_name, u.email as client_email FROM client_projects cp JOIN users u ON cp.user_id = u.id ORDER BY cp.id DESC");
    $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {}

$current_page = basename($_SERVER['PHP_SELF']);
?>
<?php include 'includes/header.php'; ?>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0 text-dark">Live Project Tracker</h2>
    </div>

    <?php if($msg) echo "<div class='alert alert-info shadow-sm'>$msg</div>"; ?>

    <div class="card shadow-sm border-0 mb-4 p-4">
        <h5 class="fw-bold mb-3"><i class="bi bi-plus-circle text-primary me-2"></i> Start New Project for Client</h5>
        <form action="projects.php" method="POST" class="row g-3">
            <input type="hidden" name="action" value="add">

            <div class="col-md-4">
                <label class="form-label fw-semibold">Select Registered Client</label>
                <select name="user_id" class="form-select" required>
                    <option value="">-- Choose Client --</option>
                    <?php foreach($users as $u): ?>
                        <option value="<?php echo $u['id']; ?>"><?php echo htmlspecialchars($u['username'] . ' (' . $u['email'] . ')'); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-8">
                <label class="form-label fw-semibold">Project Name</label>
                <input type="text" name="project_name" class="form-control" placeholder="e.g. E-Commerce Mobile App" required>
            </div>

            <div class="col-md-4">
                <label class="form-label fw-semibold">Current Phase / Status</label>
                <input type="text" name="status_phase" class="form-control" value="Requirement Gathering" required>
            </div>
            <div class="col-md-2">
                <label class="form-label fw-semibold">Progress (%)</label>
                <input type="number" name="progress_percent" class="form-control" min="0" max="100" value="5" required>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold">Total Cost ($/₹)</label>
                <input type="number" step="0.01" name="total_cost" class="form-control" value="0.00" required>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold">Amount Paid ($/₹)</label>
                <input type="number" step="0.01" name="paid_amount" class="form-control" value="0.00" required>
            </div>

            <div class="col-12">
                <label class="form-label fw-semibold">Short Description</label>
                <textarea name="description" class="form-control" rows="2" placeholder="Brief details about the project..."></textarea>
            </div>

            <div class="col-12 text-end">
                <button type="submit" class="btn btn-primary px-4"><i class="bi bi-save me-1"></i> Assign & Start Project</button>
            </div>
        </form>
    </div>

    <div class="card shadow-sm border-0 mb-4 p-0">
        <div class="card-header bg-white border-bottom pt-3 pb-2">
            <h5 class="fw-bold mb-0 text-dark"><i class="bi bi-card-list me-2 text-info"></i> Active Projects Tracking</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">ID</th>
                            <th>Client Info</th>
                            <th>Project & Phase</th>
                            <th class="text-center">Progress</th>
                            <th class="text-end">Financials</th>
                            <th class="text-center pe-4">Quick Update</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($projects)): ?>
                        <tr><td colspan="6" class="text-center py-4 text-muted">No projects currently tracked.</td></tr>
                        <?php else: foreach($projects as $p): ?>
                        <tr>
                            <td class="ps-4 fw-bold text-muted">#<?php echo $p['id']; ?></td>
                            <td>
                                <div class="fw-semibold text-dark"><?php echo htmlspecialchars($p['client_name']); ?></div>
                                <div class="text-muted small"><?php echo htmlspecialchars($p['client_email']); ?></div>
                            </td>
                            <td>
                                <div class="fw-bold text-primary mb-1"><?php echo htmlspecialchars($p['project_name']); ?></div>
                                <span class="badge bg-secondary rounded-pill fw-normal"><?php echo htmlspecialchars($p['status_phase']); ?></span>
                            </td>
                            <td class="text-center">
                                <div class="progress mb-1" style="height: 6px; width: 80px; margin: 0 auto;">
                                    <div class="progress-bar bg-<?php echo ($p['progress_percent'] == 100) ? 'success' : 'primary'; ?>" role="progressbar" style="width: <?php echo $p['progress_percent']; ?>%;" aria-valuenow="<?php echo $p['progress_percent']; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <span class="small fw-bold <?php echo ($p['progress_percent'] == 100) ? 'text-success' : 'text-muted'; ?>"><?php echo $p['progress_percent']; ?>%</span>
                            </td>
                            <td class="text-end">
                                <div class="small fw-bold">Total: <?php echo number_format($p['total_cost'], 0); ?></div>
                                <div class="small text-success fw-bold">Paid: <?php echo number_format($p['paid_amount'], 0); ?></div>
                            </td>
                            <td class="text-center pe-4" style="min-width: 250px;">
                                <!-- Quick Update Form within Table -->
                                <form action="projects.php" method="POST" class="d-flex align-items-center gap-1 justify-content-end">
                                    <input type="hidden" name="action" value="update">
                                    <input type="hidden" name="project_id" value="<?php echo $p['id']; ?>">
                                    <input type="text" name="status_phase" value="<?php echo htmlspecialchars($p['status_phase']); ?>" class="form-control form-control-sm" style="width:100px;" title="Phase" required>
                                    <input type="number" name="progress_percent" value="<?php echo $p['progress_percent']; ?>" class="form-control form-control-sm" style="width:60px;" min="0" max="100" title="%" required>
                                    <input type="number" step="0.01" name="paid_amount" value="<?php echo $p['paid_amount']; ?>" class="form-control form-control-sm" style="width:70px;" title="Paid" required>
                                    <button type="submit" class="btn btn-sm btn-success rounded-pill px-2" title="Save"><i class="bi bi-check2"></i></button>
                                </form>
                                <form action="projects.php" method="POST" class="mt-1 text-end" onsubmit="return confirm('Delete this project?');">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="project_id" value="<?php echo $p['id']; ?>">
                                    <button type="submit" class="btn btn-sm btn-link text-danger p-0 m-0"><i class="bi bi-trash"></i> Drop</button>
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
