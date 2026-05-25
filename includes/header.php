<?php
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/site_settings.php';

// Dynamic Path Resolver
$is_in_subfolder = (basename(dirname($_SERVER['PHP_SELF'])) === 'pages' || basename(dirname($_SERVER['PHP_SELF'])) === 'user' || basename(dirname($_SERVER['PHP_SELF'])) === 'admin');
$root_prefix = $is_in_subfolder ? '../' : '';

// Dynamic SEO Engine (Self-Ranking & Contextual) - Ensuring safe defaults
$current_page = basename($_SERVER['PHP_SELF'] ?? 'index.php');
$seo_title = get_setting('home_hero_title', 'Tech Elevate X | Intelligent Enterprise Systems');
$seo_desc = get_setting('home_hero_subtitle', 'Deploy scalable, high-performance web ecosystems and enterprise software engineered for the AI-driven era.');
$seo_keywords = "AI Startup, Enterprise Software, Tech Elevate X, Systems Architecture, Intelligent Solutions";
$seo_url = "https://techelevatex.in/" . $current_page;
$schema_type = "Organization";

// Contextual adjustments based on URL
if ($current_page == 'services.php') {
    $seo_title = "Our IT Services | Tech Elevate X";
    $seo_desc = "Explore our top-tier web, app development services designed to scale your business.";
    $seo_keywords .= ", IT Services, App Development, Custom Software";
} elseif ($current_page == 'portfolio.php') {
    $seo_title = "100+ Successful Projects | Tech Elevate X Portfolio";
    $seo_desc = "View our live portfolio of high-performance e-commerce, CRM, and CRM platforms built for global clients.";
    $schema_type = "CollectionPage";
} elseif ($current_page == 'blog.php' && isset($_GET['slug'])) {
    // Dynamic Blog SEO fetching
    $postsFile = __DIR__ . '/../admin/storage/content/posts.json';
    if (file_exists($postsFile)) {
        $postsData = json_decode(file_get_contents($postsFile), true);
        if (is_array($postsData)) {
            foreach ($postsData as $p) {
                if ($p['slug'] === $_GET['slug']) {
                    $seo_title = $p['title'] . " | Tech Elevate X Blog";
                    $seo_desc = isset($p['meta_description']) ? $p['meta_description'] : "Read our latest blog post on Tech Elevate X.";
                    $seo_keywords = isset($p['meta_keywords']) ? $p['meta_keywords'] : "Tech, Blog, Software";
                    $schema_type = "Article";
                    break;
                }
            }
        }
    }
}
?>
<?php
// Security Headers (HSTS, CSP, X-Frame-Options, X-XSS-Protection)
header("Strict-Transport-Security: max-age=31536000; includeSubDomains");
header("Content-Security-Policy: default-src 'self' https: 'unsafe-inline' 'unsafe-eval'; img-src 'self' data: https:; font-src 'self' data: https:;");
header("X-Frame-Options: SAMEORIGIN");
header("X-XSS-Protection: 0");
header("X-Content-Type-Options: nosniff");
header("Referrer-Policy: no-referrer-when-downgrade");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($seo_title); ?></title>
    <meta name="description" content="<?php echo htmlspecialchars($seo_desc); ?>">
    <meta name="keywords" content="<?php echo htmlspecialchars($seo_keywords); ?>">

    <!-- Open Graph / Social Meta Tags -->
    <meta property="og:title" content="<?php echo htmlspecialchars($seo_title); ?>">
    <meta property="og:description" content="<?php echo htmlspecialchars($seo_desc); ?>">
    <meta property="og:url" content="<?php echo htmlspecialchars($seo_url); ?>">
    <meta property="og:type" content="<?php echo $schema_type === 'Article' ? 'article' : 'website'; ?>">
    
    <link rel="canonical" href="<?php echo htmlspecialchars($seo_url); ?>">

    <link rel="manifest" href="<?php echo $root_prefix; ?>manifest.json">
    <meta name="theme-color" content="#000000">

    <!-- Structured Data (JSON-LD) -->
    <?php if ($current_page == 'index.php'): ?>
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "Organization",
      "name": "Tech Elevate X",
      "url": "https://techelevatex.in",
      "logo": "https://techelevatex.in/assets/images/logo.png",
      "contactPoint": {
        "@type": "ContactPoint",
        "telephone": "+1-234-567-8900",
        "contactType": "customer service"
      }
    }
    </script>
    <?php endif; ?>
    <?php if ($schema_type == 'Article'): ?>
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "Article",
      "headline": "<?php echo htmlspecialchars($seo_title); ?>",
      "description": "<?php echo htmlspecialchars($seo_desc); ?>"
    }
    </script>
    <?php endif; ?>
    
    <!-- Resource Hints for faster loading -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://cdnjs.cloudflare.com">
    <link rel="preconnect" href="https://unpkg.com">

    <!-- Critical CSS -->
    <link rel="stylesheet" href="<?php echo $root_prefix; ?>assets/css/style.css">

    <!-- Non-critical CSS loaded asynchronously to prevent render blocking -->
    <link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Outfit:wght@500;700;800&family=Inter:wght@400;600&display=swap" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Outfit:wght@500;700;800&family=Inter:wght@400;600&display=swap"></noscript>

    <link rel="preload" as="style" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"></noscript>
    
    <!-- Premium Visual Libraries -->
    <link rel="preload" as="style" href="https://unpkg.com/aos@next/dist/aos.css" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css"></noscript>

    <link rel="preload" as="style" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"></noscript>

    <script>window.rootPrefix = '<?php echo $root_prefix; ?>';</script>
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
                    <li><a href="<?php echo $root_prefix; ?>pages/blog.php">Blog</a></li>
                    <li><a href="<?php echo $root_prefix; ?>pages/careers.php">Careers</a></li>
                    <li><a href="<?php echo $root_prefix; ?>pages/contact.php">Contact</a></li>
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
