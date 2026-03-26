<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$projects = [];

try {
    $stmt = $pdo->prepare("SELECT * FROM client_projects WHERE user_id = ? ORDER BY id DESC");
    $stmt->execute([$user_id]);
    $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Client Portal - Tech Elevate X</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        body { font-family: 'Nunito', sans-serif; background-color: #f4f6f9; }
        .sidebar { width: 250px; height: 100vh; position: fixed; background: #1c2331; color: white; }
        .sidebar .brand { padding: 20px; font-size: 1.3rem; font-weight: bold; border-bottom: 1px solid rgba(255,255,255,0.1); text-align: center; }
        .nav-link { color: #d1d3e2; padding: 12px 20px; display: flex; align-items: center; transition: 0.3s; text-decoration: none; }
        .nav-link i { margin-right: 10px; }
        .nav-link.active, .nav-link:hover { background: rgba(255,255,255,0.1); color: white; border-left: 4px solid #1cc88a; }
        .main { margin-left: 250px; padding: 30px; }

        .progress-circle { width: 100px; height: 100px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; font-weight: bold; border: 8px solid #f8f9fa; }
    </style>
</head>
<body>

    <div class="sidebar">
        <div class="brand"><i class="bi bi-person-circle text-success"></i> Client Portal</div>
        <div class="mt-4">
            <a href="index.php" class="nav-link active"><i class="bi bi-kanban"></i> My Projects</a>
            <a href="../contact.php" class="nav-link"><i class="bi bi-headset"></i> Support / Contact</a>
            <a href="../index.php" class="nav-link"><i class="bi bi-globe"></i> Back to Website</a>
        </div>
        <div style="position: absolute; bottom: 20px; width: 100%;">
            <a href="index.php?logout=true" class="nav-link text-danger"><i class="bi bi-box-arrow-right"></i> Logout</a>
        </div>
    </div>

    <div class="main">
        <h2 class="fw-bold mb-1">Welcome back, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
        <p class="text-muted mb-4">Track the live progress of your active IT solutions.</p>

        <?php if(empty($projects)): ?>
            <div class="card shadow-sm border-0 text-center py-5">
                <i class="bi bi-emoji-smile text-muted" style="font-size: 4rem;"></i>
                <h4 class="mt-3">No Active Projects</h4>
                <p class="text-muted">You haven't started any projects with us yet. Ready to elevate your business?</p>
                <a href="../services.php" class="btn btn-success mt-2">View Our Services</a>
            </div>
        <?php else: foreach($projects as $p):
            $progress = (int)$p['progress_percent'];
            // Determine color based on progress
            $color = '#dc3545'; // danger
            if ($progress > 25) $color = '#ffc107'; // warning
            if ($progress > 60) $color = '#0d6efd'; // primary
            if ($progress == 100) $color = '#198754'; // success

            // Format amounts
            $total = '$' . number_format($p['total_cost'], 2);
            $paid = '$' . number_format($p['paid_amount'], 2);
            if ($p['total_cost'] > 5000) { // Simple logic to show INR if numbers look large
                $total = '₹' . number_format($p['total_cost'], 0);
                $paid = '₹' . number_format($p['paid_amount'], 0);
            }
        ?>
            <div class="card shadow-sm border-0 mb-4 p-4">
                <div class="row align-items-center">
                    <div class="col-md-2 text-center mb-3 mb-md-0">
                        <div class="progress-circle mx-auto" style="border-color: <?php echo $color; ?>; color: <?php echo $color; ?>;">
                            <?php echo $progress; ?>%
                        </div>
                        <span class="badge bg-light text-dark border mt-2 px-3 py-2 rounded-pill"><?php echo htmlspecialchars($p['status_phase']); ?></span>
                    </div>
                    <div class="col-md-7">
                        <h4 class="fw-bold text-dark mb-1"><?php echo htmlspecialchars($p['project_name']); ?></h4>
                        <p class="text-muted small mb-3">Started: <?php echo date('M d, Y', strtotime($p['created_at'])); ?> &bull; Last Updated: <?php echo date('M d', strtotime($p['updated_at'])); ?></p>
                        <p class="mb-0 text-secondary"><?php echo htmlspecialchars($p['description']); ?></p>

                        <div class="progress mt-3" style="height: 10px;">
                            <div class="progress-bar progress-bar-striped <?php echo ($progress == 100) ? '' : 'progress-bar-animated'; ?>" role="progressbar" style="width: <?php echo $progress; ?>%; background-color: <?php echo $color; ?>;" aria-valuenow="<?php echo $progress; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                    <div class="col-md-3 text-md-end text-center mt-4 mt-md-0 border-start ps-md-4">
                        <h6 class="text-muted mb-1 text-uppercase small fw-bold">Project Cost</h6>
                        <h4 class="fw-bold mb-3"><?php echo $total; ?></h4>

                        <h6 class="text-muted mb-1 text-uppercase small fw-bold">Amount Paid</h6>
                        <h5 class="text-success fw-bold mb-3"><?php echo $paid; ?></h5>

                        <?php if($p['total_cost'] > $p['paid_amount']): ?>
                            <a href="../contact.php?subject=Payment%20for%20Project%20<?php echo $p['id']; ?>" class="btn btn-sm btn-outline-primary rounded-pill w-100"><i class="bi bi-credit-card"></i> Pay Balance</a>
                        <?php else: ?>
                            <span class="badge bg-success w-100 py-2 rounded-pill"><i class="bi bi-check-circle"></i> Fully Paid</span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; endif; ?>

    </div>

</body>
</html>
