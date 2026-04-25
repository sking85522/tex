<?php
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/site_settings.php';

// Dynamic Path Resolver
$is_in_subfolder = (basename(dirname($_SERVER['PHP_SELF'])) === 'pages' || basename(dirname($_SERVER['PHP_SELF'])) === 'user' || basename(dirname($_SERVER['PHP_SELF'])) === 'admin');
$root_prefix = $is_in_subfolder ? '../' : '';

// Dynamic SEO Engine (Self-Ranking & Contextual)
$current_page = basename($_SERVER['PHP_SELF']);
$seo_title = get_setting('home_hero_title', 'Tech Elevate X | AI & IT Services');
$seo_desc = get_setting('home_hero_subtitle', 'We provide world-class web development, AI solutions, and IT services.');
$seo_keywords = "IT Agency, AI Development, Software Solutions, Tech Elevate X, Web Development";
$seo_url = "https://techelevatex.in/" . $current_page;
$schema_type = "Organization";

// Contextual adjustments based on URL
if ($current_page == 'services.php') {
    $seo_title = "Our IT & AI Services | Tech Elevate X";
    $seo_desc = "Explore our top-tier web, app, and AI development services designed to scale your business.";
    $seo_keywords .= ", IT Services, App Development, Custom Software";
} elseif ($current_page == 'portfolio.php') {
    $seo_title = "100+ Successful Projects | Tech Elevate X Portfolio";
    $seo_desc = "View our live portfolio of high-performance e-commerce, CRM, and AI platforms built for global clients.";
    $schema_type = "CollectionPage";
} elseif ($current_page == 'blog.php' && isset($_GET['slug'])) {
    // Dynamic Blog SEO fetching
    try {
        $stmt = $pdo->prepare("SELECT title, meta_description, meta_keywords, image_url FROM blogs WHERE slug = ?");
        $stmt->execute([$_GET['slug']]);
        $blog = $stmt->fetch();
        if ($blog) {
            $seo_title = $blog['title'] . " | Tech Elevate X Blog";
            $seo_desc = $blog['meta_description'];
            $seo_keywords = $blog['meta_keywords'];
            $schema_type = "Article";
        }
    } catch (Exception $e) {}
}

// System Health Check
$system_health = 'Active';
if (isset($pdo) && get_class($pdo) == 'MockPDO') {
    $system_health = 'Classic Mode';
}

// AI "Auto-Design" Engine (UI Adaptations)
if (class_exists('Core\NeuralEngine')) {
    $neural = new \Core\NeuralEngine();
    $adaptations = $neural->getUIAdaptations();
} else {
    $adaptations = ['theme' => 'dark', 'neural_mesh' => true];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HRITIK Neural Portal | Tech Elevate X</title>
    <meta name="description" content="Premium Software & AI Enterprise Portal. Fully independent local neural processing.">
    
    <link rel="manifest" href="manifest.json">
    <meta name="theme-color" content="#6366f1">
    
    <link rel="stylesheet" href="<?php echo $root_prefix; ?>assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@500;700;800&family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <script>window.rootPrefix = '<?php echo $root_prefix; ?>';</script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Premium Visual Libraries -->
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
</head>

<body>

    <script>
        // PWA Service Worker Registration
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('sw.js').then(reg => {
                    console.log('HRITIK Nodes Synchronized.', reg.scope);
                }).catch(err => {
                    console.log('Uplink Interrupted:', err);
                });
            });
        }

        // Location Base System (Local Cache Optimized)
        const cachedLocale = localStorage.getItem('user_locale');
        if (cachedLocale) {
            document.documentElement.lang = cachedLocale;
        }
    </script>

    <!-- AI Theme Engine Initialization -->
    <script>
        const ai_theme = "<?php echo $adaptations['theme_mode'] ?? 'light'; ?>";
        document.documentElement.setAttribute('data-theme', ai_theme);
    </script>

    <!-- Navigation -->
    <header class="navbar">
        <div class="container nav-container">
            <div class="logo">
                <a href="index.php"><h1>Tech Elevate X</h1></a>
            </div>
            <nav class="nav-links">
                <ul style="display: flex; align-items: center; gap: 20px; list-style: none; margin: 0; padding: 0;">
                    <li><a href="<?php echo $root_prefix; ?>index.php">Home</a></li>
                    <li><a href="<?php echo $root_prefix; ?>pages/about.php">About</a></li>
                    <li><a href="<?php echo $root_prefix; ?>pages/services.php">Services</a></li>
                    <li><a href="<?php echo $root_prefix; ?>pages/portfolio.php">Portfolio</a></li>
                    <li><a href="<?php echo $root_prefix; ?>pages/pricing.php">Pricing</a></li>
                    <li><a href="<?php echo $root_prefix; ?>pages/careers.php">Careers</a></li>
                    <li><a href="<?php echo $root_prefix; ?>pages/contact.php">Contact</a></li>
                    <?php if($system_health == 'Active'): ?>
                        <li title="AI Self-Heal Core Online"><span class="badge" style="background: rgba(0, 255, 0, 0.1); color: #00ff00; padding: 4px 8px; border-radius: 20px; font-size: 0.7rem; border: 1px solid #00ff00;"><i class="fas fa-shield-virus"></i> AI Secure</span></li>
                    <?php else: ?>
                        <li title="System currently running in optimized fail-safe mode"><span class="badge" style="background: rgba(255, 165, 0, 0.1); color: orange; padding: 4px 8px; border-radius: 20px; font-size: 0.7rem; border: 1px solid orange;"><i class="fas fa-tools"></i> Optimized</span></li>
                    <?php endif; ?>
                    <li><a href="<?php echo $root_prefix; ?>user/login.php" class="btn btn-outline" style="padding: 8px 15px; border-radius: 5px;">Login</a></li>
                </ul>
            </nav>
            <div class="hamburger">
                <i class="fas fa-bars"></i>
            </div>
        </div>
    </header>

    <!-- Main Content Area Starts -->
    <main>
