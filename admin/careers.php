<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

$role = $_SESSION['admin_role'] ?? 'super_admin';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'add_job' && ($role == 'super_admin' || $role == 'hr')) {
        $stmt = $pdo->prepare("INSERT INTO jobs (title, description, requirements, location, job_type, status) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$_POST['title'], $_POST['description'], $_POST['requirements'], $_POST['location'], $_POST['job_type'], $_POST['status']]);
    } elseif ($_POST['action'] === 'update_app_status' && ($role == 'super_admin' || $role == 'hr')) {
        $stmt = $pdo->prepare("UPDATE job_applications SET status = ? WHERE id = ?");
        $stmt->execute([$_POST['status'], $_POST['app_id']]);
    }
}

$jobs = [];
$applications = [];
try {
    $stmt = $pdo->query("SELECT * FROM jobs ORDER BY id DESC");
    $jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $pdo->query("SELECT ja.*, j.title as job_title FROM job_applications ja JOIN jobs j ON ja.job_id = j.id ORDER BY ja.id DESC");
    $applications = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) { }

?>
<?php include 'includes/header.php'; ?>

            <h1>HR & Careers Management</h1>

            <?php if ($role == 'super_admin' || $role == 'hr'): ?>
            <div class="card shadow-sm border-0 mb-4 p-4">
                <h3>Post New Job</h3>
                <form action="careers.php" method="POST" style="display: flex; flex-wrap: wrap; gap: 15px;">
                    <input type="hidden" name="action" value="add_job">
                    <div class="mb-3" style="flex: 1; min-width: 200px;"><input type="text" name="title" placeholder="Job Title" required></div>
                    <div class="mb-3" style="flex: 1; min-width: 200px;"><input type="text" name="location" placeholder="Location (e.g., Remote)" required></div>
                    <div class="mb-3" style="flex: 1; min-width: 150px;">
                        <select name="job_type"><option value="Full-time">Full-time</option><option value="Part-time">Part-time</option><option value="Contract">Contract</option></select>
                    </div>
                    <div class="mb-3" style="flex: 1; min-width: 150px;">
                        <select name="status"><option value="open">Open</option><option value="closed">Closed</option></select>
                    </div>
                    <div class="mb-3" style="flex: 100%;"><textarea name="description" placeholder="Job Description" rows="2" required></textarea></div>
                    <div class="mb-3" style="flex: 100%;"><textarea name="requirements" placeholder="Requirements" rows="2" required></textarea></div>
                    <button type="submit" class="btn btn-primary">Post Job</button>
                </form>
            </div>
            <?php else: ?>
                <div class="card shadow-sm border-0 mb-4 p-4"><p style="color:red;">You do not have permission to post jobs or change applicant status. (HR or Super Admin only)</p></div>
            <?php endif; ?>

            <div class="card shadow-sm border-0 mb-4 p-4">
                <h3>Job Applications</h3>
                <table class="table table-hover align-middle">
                    <thead class="table-light"><tr><th>ID</th><th>Applicant</th><th>Job Title</th><th>Resume</th><th>Status</th><th>Action</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach($applications as $app): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($app['id']); ?></td>
                            <td><?php echo htmlspecialchars($app['name']); ?> (<?php echo htmlspecialchars($app['email']); ?>)</td>
                            <td><?php echo htmlspecialchars($app['job_title']); ?></td>
                            <td><a href="<?php echo htmlspecialchars($app['resume_url']); ?>" target="_blank">View Resume</a></td>
                            <td><span style="font-weight:bold; color: <?php echo $app['status'] == 'pending' ? 'orange' : ($app['status'] == 'accepted' ? 'green' : 'red'); ?>;"><?php echo htmlspecialchars($app['status']); ?></span></td>
                            <td>
                                <?php if ($role == 'super_admin' || $role == 'hr'): ?>
                                <form action="careers.php" method="POST" style="display:inline;">
                                    <input type="hidden" name="action" value="update_app_status">
                                    <input type="hidden" name="app_id" value="<?php echo htmlspecialchars($app['id']); ?>">
                                    <select name="status" onchange="this.form.submit()">
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
        <?php include 'includes/footer.php'; ?>
