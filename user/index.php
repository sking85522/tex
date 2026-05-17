<?php
session_start();
include '../includes/db.php';
require_once '../site_ai/core_engine.php';

if (!isset($_SESSION['user_id'])) {
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Workspace | Tech Elevate X</title>
    
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&family=Inter:wght@400;500;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --sidebar-width: 280px;
        }
        body { background: #0f172a; overflow-x: hidden; }
        
        .workspace-sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            background: rgba(30, 41, 59, 0.5);
            backdrop-filter: blur(10px);
            border-right: 1px solid var(--glass-border);
            z-index: 1000;
            padding: 30px 20px;
        }

        .workspace-main {
            margin-left: var(--sidebar-width);
            padding: 40px;
            min-height: 100vh;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 20px;
            color: var(--text-muted);
            text-decoration: none;
            border-radius: 12px;
            transition: all 0.3s;
            margin-bottom: 8px;
            font-weight: 500;
        }

        .sidebar-link:hover, .sidebar-link.active {
            background: rgba(99, 102, 241, 0.1);
            color: var(--primary-color);
            border: 1px solid rgba(99, 102, 241, 0.2);
        }

        .project-card {
            padding: 40px;
            border: 1px solid var(--glass-border);
            transition: all 0.3s;
            margin-bottom: 30px;
        }

        .project-card:hover { borderColor: var(--primary-color); }

        .project-progress {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            font-weight: 800;
            position: relative;
            background: rgba(0,0,0,0.2);
        }

        .project-progress::after {
            content: '';
            position: absolute;
            inset: -5px;
            border-radius: 50%;
            border: 4px solid var(--primary-color);
            mask-image: linear-gradient(0deg, black var(--p), transparent 0);
        }

        @media (max-width: 992px) {
            .workspace-sidebar { transform: translateX(-100%); }
            .workspace-main { margin-left: 0; padding: 20px; }
        }
    </style>
</head>
<body data-theme="dark">

    <div class="workspace-sidebar">
        <div style="margin-bottom: 50px; padding: 0 20px;">
            <h1 style="font-size: 1.4rem; color: white;"><span style="color: var(--primary-color);">WORKSPACE</span></h1>
        </div>

        <nav>
            <a href="index.php" class="sidebar-link active"><i class="fas fa-project-diagram"></i> Active Projects</a>
            <a href="../contact.php" class="sidebar-link"><i class="fas fa-headset"></i> Support Node</a>
            <a href="../portfolio.php" class="sidebar-link"><i class="fas fa-rocket"></i> Gallery</a>
            <div style="margin-top: 40px; padding: 0 20px; color: var(--text-muted); font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.1em;">System</div>
            <a href="../index.php" class="sidebar-link"><i class="fas fa-home"></i> Main Site</a>
            <a href="index.php?logout=true" class="sidebar-link" style="color: #ef4444;"><i class="fas fa-power-off"></i> Terminate Session</a>
        </nav>
        
        <!-- ALEX Orb in Sidebar -->
        <div style="position: absolute; bottom: 30px; left: 20px; right: 20px; padding: 20px; border-radius: 12px; background: rgba(0,0,0,0.2); text-align: center;">
            <div class="orb" style="width: 20px; height: 20px; margin: 0 auto 10px auto;"></div>
            <small style="color: var(--text-muted);">ALEX Node Secure</small>
        </div>
    </div>

    <div class="workspace-main">
        <header style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 60px;">
            <div>
                <h2 style="font-size: 2rem; font-weight: 800; margin-bottom: 8px;">Uplink Established, <?php echo htmlspecialchars($_SESSION['username']); ?>.</h2>
                <p style="color: var(--text-muted);">Monitoring active deployment clusters and progress.</p>
            </div>
            <div class="glass" style="padding: 10px 20px; display: flex; align-items: center; gap: 15px;">
                <img src="../assets/img/bot_avatar.png" style="width: 40px; border-radius: 50%;" alt="">
                <div style="font-size: 0.8rem;">
                    <span style="color: var(--secondary-color); font-weight: 700;">● System Active</span><br>
                    <span style="color: var(--text-muted);">ALEX Intel: Stable</span>
                </div>
            </div>
        </header>

        <?php if(empty($projects)): ?>
            <div class="glass" style="padding: 80px; text-align: center;">
                <i class="fas fa-folder-open fa-4x" style="color: var(--text-muted); margin-bottom: 30px;"></i>
                <h2>No Active Deployments</h2>
                <p style="color: var(--text-muted); margin-bottom: 30px;">Launch your next-gen software solution through our  Configurator.</p>
                <a href="../services.php" class="btn btn-primary" style="padding: 15px 40px; border-radius: 12px; text-decoration: none; color: white;">Explore Capabilities</a>
            </div>
        <?php else: foreach($projects as $p): 
            $progress = (int)$p['progress_percent'];
            $color = '#6366f1'; // primary
            if ($progress == 100) $color = '#10b981'; // success
        ?>
            <div class="glass project-card" data-aos="fade-up">
                <div style="display: flex; gap: 40px; flex-wrap: wrap; align-items: center;">
                    <div class="project-progress" style="--p: <?php echo $progress; ?>%; borderColor: <?php echo $color; ?>; color: <?php echo $color; ?>;">
                        <?php echo $progress; ?>%
                    </div>
                    
                    <div style="flex: 1; min-width: 300px;">
                        <h3 style="font-size: 1.8rem; font-weight: 700; margin-bottom: 8px;"><?php echo htmlspecialchars($p['project_name']); ?></h3>
                        <p style="color: var(--text-muted); margin-bottom: 24px;"><?php echo htmlspecialchars($p['description']); ?></p>
                        
                        <div style="display: flex; gap: 24px; color: var(--text-muted); font-size: 0.85rem;">
                            <span><i class="fas fa-calendar-alt" style="margin-right: 8px;"></i> Started: <?php echo date('d M, Y', strtotime($p['created_at'])); ?></span>
                            <span><i class="fas fa-sync" style="margin-right: 8px;"></i> Phase: <span style="color: white; font-weight: 600;"><?php echo htmlspecialchars($p['status_phase']); ?></span></span>
                        </div>
                    </div>

                    <div style="min-width: 250px; text-align: right; border-left: 1px solid var(--glass-border); padding-left: 40px;">
                        <div style="margin-bottom: 24px;">
                            <small style="color: var(--text-muted); text-transform: uppercase; font-weight: 700;">Project Value</small>
                            <div style="font-size: 2rem; font-weight: 800;">₹<?php echo number_format($p['total_cost']); ?></div>
                        </div>
                        <a href="../contact.php" class="btn btn-primary" style="width: 100%; border-radius: 12px; text-decoration: none; display: block; padding: 12px; color: white;">System Insight</a>
                    </div>
                </div>
            </div>
        <?php endforeach; endif; ?>
    </div>

    <!-- Background Decoration -->
    <div style="position: fixed; top: 0; right: 0; width: 600px; height: 600px; background: radial-gradient(circle, rgba(99, 102, 241, 0.05) 0%, transparent 70%); z-index: -1;"></div>

    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
    <script>AOS.init();</script>
</body>
</html>
