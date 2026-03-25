<?php
require_once 'includes/db.php';
require_once 'includes/site_settings.php';

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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($seo_title); ?></title>
    <meta name="description" content="<?php echo htmlspecialchars($seo_desc); ?>">
    <meta name="keywords" content="<?php echo htmlspecialchars($seo_keywords); ?>">
    <meta name="author" content="Tech Elevate X">

    <!-- Open Graph for Social Media -->
    <meta property="og:title" content="<?php echo htmlspecialchars($seo_title); ?>">
    <meta property="og:description" content="<?php echo htmlspecialchars($seo_desc); ?>">
    <meta property="og:url" content="<?php echo htmlspecialchars($seo_url); ?>">
    <meta property="og:type" content="website">

    <!-- JSON-LD Schema.org Structured Data for Google Rich Snippets -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "<?php echo $schema_type; ?>",
      "name": "Tech Elevate X",
      "url": "https://techelevatex.in",
      "logo": "https://techelevatex.in/assets/img/logo.png",
      "description": "<?php echo htmlspecialchars($seo_desc); ?>"
    }
    </script>

    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

    <div id="google_translate_element" style="position:absolute; top:10px; right:120px; z-index:9999;"></div>
    <script type="text/javascript">
    function googleTranslateElementInit() {
      new google.translate.TranslateElement({pageLanguage: 'en', layout: google.translate.TranslateElement.InlineLayout.SIMPLE}, 'google_translate_element');
    }
    </script>
    <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>


    <!-- Navigation -->
    <header class="navbar">
        <div class="container nav-container">
            <div class="logo">
                <a href="index.php"><h1>Tech Elevate X</h1></a>
            </div>
            <nav class="nav-links">
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="about.php">About Us</a></li>
                    <li><a href="services.php">Services</a></li><li><a href="ai-solutions.php" style="color:var(--primary-color); font-weight:bold;">AI Solutions <i class="fas fa-robot"></i></a></li>
                    <li><a href="portfolio.php">Portfolio</a></li><li><a href="careers.php">Careers</a></li>
                    <li><a href="contact.php">Contact Us</a></li>
                    <li><a href="user/login.php" class="btn btn-outline">Login</a></li><li><a href="user/register.php" class="btn btn-primary" style="margin-left:10px;">Sign Up</a></li>
                </ul>
            </nav>
            <div class="hamburger">
                <i class="fas fa-bars"></i>
            </div>
        </div>
    </header>

    <!-- Main Content Area Starts -->
    <main>
