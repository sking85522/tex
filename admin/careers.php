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
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Careers - Tech Elevate X Admin</title>
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
        .btn-primary { background: #4e73df; color: white; border: none; padding: 10px 20px; cursor: pointer; }
        .form-group { margin-bottom: 15px; }
        .form-group input, .form-group textarea, .form-group select { width: 100%; padding: 10px; border: 1px solid #ddd; box-sizing: border-box; }
        select { padding: 5px; }
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
            <h1>HR & Careers Management</h1>

            <?php if ($role == 'super_admin' || $role == 'hr'): ?>
            <div class="card">
                <h3>Post New Job</h3>
                <form action="careers.php" method="POST" style="display: flex; flex-wrap: wrap; gap: 15px;">
                    <input type="hidden" name="action" value="add_job">
                    <div class="form-group" style="flex: 1; min-width: 200px;"><input type="text" name="title" placeholder="Job Title" required></div>
                    <div class="form-group" style="flex: 1; min-width: 200px;"><input type="text" name="location" placeholder="Location (e.g., Remote)" required></div>
                    <div class="form-group" style="flex: 1; min-width: 150px;">
                        <select name="job_type"><option value="Full-time">Full-time</option><option value="Part-time">Part-time</option><option value="Contract">Contract</option></select>
                    </div>
                    <div class="form-group" style="flex: 1; min-width: 150px;">
                        <select name="status"><option value="open">Open</option><option value="closed">Closed</option></select>
                    </div>
                    <div class="form-group" style="flex: 100%;"><textarea name="description" placeholder="Job Description" rows="2" required></textarea></div>
                    <div class="form-group" style="flex: 100%;"><textarea name="requirements" placeholder="Requirements" rows="2" required></textarea></div>
                    <button type="submit" class="btn-primary">Post Job</button>
                </form>
            </div>
            <?php else: ?>
                <div class="card"><p style="color:red;">You do not have permission to post jobs or change applicant status. (HR or Super Admin only)</p></div>
            <?php endif; ?>

            <div class="card">
                <h3>Job Applications</h3>
                <table>
                    <thead>
                        <tr><th>ID</th><th>Applicant</th><th>Job Title</th><th>Resume</th><th>Status</th><th>Action</th></tr>
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
        </div>
    </div>
</body>
</html>
