<?php
session_start();
include '../includes/db.php';

// Check if user is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

$stats = ['users' => 0, 'services' => 0, 'messages' => 0, 'revenue' => 0];
$recent_messages = [];

try {
    $stats['services'] = $pdo->query("SELECT COUNT(*) FROM services")->fetchColumn();
    $stats['messages'] = $pdo->query("SELECT COUNT(*) FROM messages")->fetchColumn();
    $stats['users'] = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();

    // Simulate Revenue from Client Projects
    // $stats['revenue'] = $pdo->query("SELECT SUM(paid_amount) FROM client_projects")->fetchColumn();
    $stats['revenue'] = 24500; // Mock revenue for demo

    $recent_messages = $pdo->query("SELECT * FROM messages ORDER BY id DESC LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {}

$current_page = basename($_SERVER['PHP_SELF']);
?>
<?php include 'includes/header.php'; ?>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0 text-dark">Admin Dashboard</h2>
        <span class="badge bg-primary fs-6"><i class="bi bi-clock"></i> <?php echo date('l, M j, Y'); ?></span>
    </div>

    <!-- Small Boxes / Widgets Row -->
    <div class="row g-4 mb-4">
        <!-- Revenue Widget -->
        <div class="col-lg-3 col-sm-6">
            <div class="small-box bg-success text-white">
                <div class="inner">
                    <h3>$<?php echo number_format($stats['revenue']); ?></h3>
                    <p>Total Revenue</p>
                </div>
                <div class="icon"><i class="bi bi-currency-dollar"></i></div>
                <a href="#" class="small-box-footer">View Finances <i class="bi bi-arrow-right-circle"></i></a>
            </div>
        </div>

        <!-- Users Widget -->
        <div class="col-lg-3 col-sm-6">
            <div class="small-box bg-primary text-white">
                <div class="inner">
                    <h3><?php echo $stats['users']; ?></h3>
                    <p>Registered Clients</p>
                </div>
                <div class="icon"><i class="bi bi-people-fill"></i></div>
                <a href="users.php" class="small-box-footer">Manage Users <i class="bi bi-arrow-right-circle"></i></a>
            </div>
        </div>

        <!-- Services Widget -->
        <div class="col-lg-3 col-sm-6">
            <div class="small-box bg-warning text-dark">
                <div class="inner">
                    <h3><?php echo $stats['services']; ?></h3>
                    <p>Active Services</p>
                </div>
                <div class="icon"><i class="bi bi-gear-fill"></i></div>
                <a href="services.php" class="small-box-footer text-dark">Manage Services <i class="bi bi-arrow-right-circle"></i></a>
            </div>
        </div>

        <!-- Messages Widget -->
        <div class="col-lg-3 col-sm-6">
            <div class="small-box bg-danger text-white">
                <div class="inner">
                    <h3><?php echo $stats['messages']; ?></h3>
                    <p>Contact Inquiries</p>
                </div>
                <div class="icon"><i class="bi bi-envelope-paper-fill"></i></div>
                <a href="#" class="small-box-footer">View Messages <i class="bi bi-arrow-right-circle"></i></a>
            </div>
        </div>
    </div>

    <!-- Main Content Row -->
    <div class="row g-4">
        <!-- Left Col: PlotPHP Graphs Simulator -->
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-0 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0 text-primary"><i class="bi bi-graph-up-arrow me-2"></i> Monthly Sales Revenue Prediction</h5>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">This Year</button>
                        <ul class="dropdown-menu shadow-sm">
                            <li><a class="dropdown-item" href="#">This Month</a></li>
                            <li><a class="dropdown-item" href="#">Last Year</a></li>
                        </ul>
                    </div>
                </div>
                <div class="card-body">
                    <div class="bg-light p-3 rounded text-center mb-3">
                        <svg viewBox="0 0 500 200" class="w-100 h-auto bg-white border rounded">
                            <!-- Grid -->
                            <line x1="50" y1="150" x2="450" y2="150" stroke="#e9ecef" stroke-width="1"/>
                            <line x1="50" y1="100" x2="450" y2="100" stroke="#e9ecef" stroke-width="1"/>
                            <line x1="50" y1="50" x2="450" y2="50" stroke="#e9ecef" stroke-width="1"/>

                            <!-- Line -->
                            <polyline fill="none" stroke="#0d6efd" stroke-width="3" points="50,150 100,140 150,110 200,120 250,80 300,90 350,40 400,60 450,20" stroke-linecap="round" stroke-linejoin="round"/>
                            <circle cx="150" cy="110" r="4" fill="#0d6efd"/>
                            <circle cx="250" cy="80" r="4" fill="#0d6efd"/>
                            <circle cx="350" cy="40" r="4" fill="#0d6efd"/>
                            <circle cx="450" cy="20" r="4" fill="#0d6efd"/>

                            <!-- Labels -->
                            <text x="25" y="155" fill="#adb5bd" font-size="10">0k</text>
                            <text x="20" y="105" fill="#adb5bd" font-size="10">10k</text>
                            <text x="20" y="55" fill="#adb5bd" font-size="10">20k</text>
                            <text x="90" y="170" fill="#adb5bd" font-size="10">Jan</text>
                            <text x="190" y="170" fill="#adb5bd" font-size="10">Feb</text>
                            <text x="290" y="170" fill="#adb5bd" font-size="10">Mar</text>
                            <text x="390" y="170" fill="#adb5bd" font-size="10">Apr</text>
                        </svg>
                    </div>
                    <div class="d-flex justify-content-center text-success fw-bold">
                        <i class="bi bi-arrow-up-circle-fill me-1"></i> +14% growth predicted by MLPHP Model
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Col: Project Demographics -->
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
                    <h5 class="fw-bold mb-0 text-dark"><i class="bi bi-pie-chart-fill me-2 text-warning"></i> Project Demographics</h5>
                </div>
                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                    <svg viewBox="0 0 200 200" class="mb-4" style="width:160px; height:160px; transform: rotate(-90deg);">
                        <circle r="80" cx="100" cy="100" fill="transparent" stroke="#f8f9fa" stroke-width="40"/>
                        <circle r="80" cx="100" cy="100" fill="transparent" stroke="#0d6efd" stroke-width="40" stroke-dasharray="502" stroke-dashoffset="150" />
                        <circle r="80" cx="100" cy="100" fill="transparent" stroke="#198754" stroke-width="40" stroke-dasharray="502" stroke-dashoffset="350" />
                        <circle r="80" cx="100" cy="100" fill="transparent" stroke="#ffc107" stroke-width="40" stroke-dasharray="502" stroke-dashoffset="450" />
                    </svg>
                    <ul class="list-unstyled w-100 px-3">
                        <li class="d-flex justify-content-between mb-2">
                            <span><i class="bi bi-circle-fill text-primary me-2"></i> Web Apps</span>
                            <span class="fw-bold">60%</span>
                        </li>
                        <li class="d-flex justify-content-between mb-2">
                            <span><i class="bi bi-circle-fill text-success me-2"></i> Mobile Apps</span>
                            <span class="fw-bold">30%</span>
                        </li>
                        <li class="d-flex justify-content-between mb-0">
                            <span><i class="bi bi-circle-fill text-warning me-2"></i> AI & APIs</span>
                            <span class="fw-bold">10%</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Recent Contact Inquiries -->
        <div class="col-12">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white border-bottom pt-3 pb-2">
                    <h5 class="fw-bold mb-0 text-dark"><i class="bi bi-envelope-open-fill me-2 text-info"></i> Recent Client Inquiries</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">Client Name</th>
                                    <th>Email</th>
                                    <th>Subject / Intent</th>
                                    <th class="text-end pe-4">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(empty($recent_messages)): ?>
                                <tr><td colspan="4" class="text-center py-4 text-muted">No recent inquiries found.</td></tr>
                                <?php else: foreach($recent_messages as $msg): ?>
                                <tr>
                                    <td class="ps-4 fw-semibold text-dark"><?php echo htmlspecialchars($msg['name']); ?></td>
                                    <td><a href="mailto:<?php echo htmlspecialchars($msg['email']); ?>" class="text-decoration-none"><?php echo htmlspecialchars($msg['email']); ?></a></td>
                                    <td class="text-muted text-truncate" style="max-width: 250px;"><?php echo htmlspecialchars($msg['subject'] ?? substr($msg['message'], 0, 30).'...'); ?></td>
                                    <td class="text-end pe-4"><button class="btn btn-sm btn-outline-primary rounded-pill"><i class="bi bi-eye"></i> View</button></td>
                                </tr>
                                <?php endforeach; endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php include 'includes/footer.php'; ?>
