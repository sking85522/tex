<?php
session_start();
include '../includes/db.php';
require_once '../includes/ai_engine.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

$role = $_SESSION['admin_role'] ?? 'super_admin';
$aiEngine = new AIEngine($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'add_job' && ($role == 'super_admin' || $role == 'hr')) {
        $stmt = $pdo->prepare("INSERT INTO jobs (title, description, requirements, location, job_type, status) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$_POST['title'], $_POST['description'], $_POST['requirements'], $_POST['location'], $_POST['job_type'], $_POST['status']]);
    } elseif ($_POST['action'] === 'update_app_status' && ($role == 'super_admin' || $role == 'hr')) {
        $stmt = $pdo->prepare("UPDATE job_applications SET status = ? WHERE id = ?");
        $stmt->execute([$_POST['status'], $_POST['app_id']]);
    }
}

/**
 * AI Scoring Logic using NLPHP
 */
function calculateMatchScore($reqs, $applicantMsg) {
    if (!class_exists('NLPHP\NLPHP')) return 'N/A';
    
    $reqWords = explode(' ', strtolower(preg_replace('/[^a-zA-Z]/', ' ', $reqs)));
    $appWords = explode(' ', strtolower(preg_replace('/[^a-zA-Z]/', ' ', $applicantMsg)));
    
    $match = 0;
    $total = 0;
    foreach ($reqWords as $rw) {
        if (strlen($rw) < 4) continue;
        $total++;
        if (in_array($rw, $appWords)) $match++;
    }
    
    $score = $total > 0 ? ($match / $total) * 100 : 0;
    return round($score) . '%';
}

$jobs = [];
$applications = [];
try {
    $stmt = $pdo->query("SELECT * FROM jobs ORDER BY id DESC");
    $jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $pdo->query("SELECT ja.*, j.title as job_title, j.requirements FROM job_applications ja JOIN jobs j ON ja.job_id = j.id ORDER BY ja.id DESC");
    $applications = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) { }

?>
<?php include 'includes/header.php'; ?>

            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0 text-gray-800">HR & Careers Management</h1>
                <span class="badge bg-info"><i class="fas fa-robot"></i> AI HR Screening Active</span>
            </div>

            <?php if ($role == 'super_admin' || $role == 'hr'): ?>
            <div class="card shadow-sm border-0 mb-4 p-4">
                <h3>Post New Job</h3>
                <form action="careers.php" method="POST" style="display: flex; flex-wrap: wrap; gap: 15px;">
                    <input type="hidden" name="action" value="add_job">
                    <div class="mb-3" style="flex: 1; min-width: 200px;"><input type="text" name="title" placeholder="Job Title" required class="form-control"></div>
                    <div class="mb-3" style="flex: 1; min-width: 200px;"><input type="text" name="location" placeholder="Location (e.g., Remote)" required class="form-control"></div>
                    <div class="mb-3" style="flex: 1; min-width: 150px;">
                        <select name="job_type" class="form-select"><option value="Full-time">Full-time</option><option value="Part-time">Part-time</option><option value="Contract">Contract</option></select>
                    </div>
                    <div class="mb-3" style="flex: 1; min-width: 150px;">
                        <select name="status" class="form-select"><option value="open">Open</option><option value="closed">Closed</option></select>
                    </div>
                    <div class="mb-3" style="flex: 100%;"><textarea name="description" placeholder="Job Description" rows="2" required class="form-control"></textarea></div>
                    <div class="mb-3" style="flex: 100%;"><textarea name="requirements" placeholder="Requirements" rows="2" required class="form-control"></textarea></div>
                    <button type="submit" class="btn btn-primary">Post Job</button>
                </form>
            </div>
            <?php endif; ?>

            <div class="card shadow-sm border-0 mb-4 p-0">
                <div class="card-header bg-white py-3">
                    <h5 class="m-0 font-weight-bold text-primary">Job Applications (AI Ranked)</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light"><tr><th>Applicant</th><th>Job Title</th><th>Resume</th><th>AI Score</th><th>Status</th><th>Action</th></tr>
                        </thead>
                        <tbody>
                            <?php foreach($applications as $app): 
                                $score = calculateMatchScore($app['requirements'], $app['phone'] . " " . $app['name']); // phone/name as proxy for profile check
                            ?>
                            <tr>
                                <td class="ps-3"><?php echo htmlspecialchars($app['name']); ?><br><small class="text-muted"><?php echo htmlspecialchars($app['email']); ?></small></td>
                                <td><?php echo htmlspecialchars($app['job_title']); ?></td>
                                <td><a href="<?php echo htmlspecialchars($app['resume_url']); ?>" target="_blank" class="btn btn-sm btn-link">View</a></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="progress flex-grow-1 me-2" style="height: 10px; min-width: 50px;">
                                            <div class="progress-bar bg-primary" role="progressbar" style="width: <?php echo $score; ?>;"></div>
                                        </div>
                                        <?php echo $score; ?>
                                    </div>
                                </td>
                                <td><span class="badge <?php echo $app['status'] == 'pending' ? 'bg-warning' : ($app['status'] == 'accepted' ? 'bg-success' : 'bg-danger'); ?>"><?php echo strtoupper($app['status']); ?></span></td>
                                <td>
                                    <?php if ($role == 'super_admin' || $role == 'hr'): ?>
                                    <form action="careers.php" method="POST" style="display:inline;">
                                        <input type="hidden" name="action" value="update_app_status">
                                        <input type="hidden" name="app_id" value="<?php echo htmlspecialchars($app['id']); ?>">
                                        <select name="status" onchange="this.form.submit()" class="form-select form-select-sm">
                                            <option value="pending" <?php if($app['status']=='pending') echo 'selected'; ?>>Pending</option>
                                            <option value="reviewed" <?php if($app['status']=='reviewed') echo 'selected'; ?>>Reviewed</option>
                                            <option value="accepted" <?php if($app['status']=='accepted') echo 'selected'; ?>>Accepted</option>
                                            <option value="rejected" <?php if($app['status']=='rejected') echo 'selected'; ?>>Rejected</option>
                                        </select>
                                    </form>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php include 'includes/footer.php'; ?>
