<?php
include 'includes/header.php';
include 'includes/db.php';

// Fetch only 3 services for the homepage
try {
    $stmt = $pdo->query("SELECT * FROM services ORDER BY id ASC LIMIT 3");
    $services = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $services = [];
}
?>

<!-- Hero Section -->
<section class="hero" id="home">
    <div class="container hero-content">
        <div class="hero-text">
            <h1><?php echo htmlspecialchars(get_setting("home_hero_title", "Transform Your Business With Tech Elevate X")); ?></h1>
            <p><?php echo htmlspecialchars(get_setting("home_hero_subtitle", "We provide world-class web development, software solutions, and IT services to scale your business to new heights.")); ?></p>
            <div class="hero-buttons">
                <a href="services.php" class="btn btn-primary">Our Services</a>
                <a href="contact.php" class="btn btn-outline">Contact Us</a>
            </div>
        </div>
        <div class="hero-image">
            <img src="https://via.placeholder.com/500x400" alt="Tech Elevate X Hero Image">
        </div>
    </div>
</section>

<!-- About Section -->
<section class="about" id="about">
    <div class="container about-content">
        <div class="about-image">
            <img src="https://via.placeholder.com/500x400" alt="About Tech Elevate X">
        </div>
        <div class="about-text">
            <h2>About Us</h2>
            <p>At Tech Elevate X, we believe in delivering cutting-edge software and web solutions. With a team of expert developers, designers, and strategists, we elevate brands in the digital space.</p>
            <ul>
                <li><i class="fas fa-check-circle"></i> Experienced Team of Professionals</li>
                <li><i class="fas fa-check-circle"></i> Custom Software & Web Development</li>
                <li><i class="fas fa-check-circle"></i> 24/7 Support and Maintenance</li>
            </ul>
            <a href="about.php" class="btn btn-secondary">Read More</a>
        </div>
    </div>
</section>

<!-- Services Section (Brief) -->
<section class="services" id="services">
    <div class="container">
        <div class="section-title">
            <h2>Our Services</h2>
            <p>What we offer to help your business grow</p>
        </div>
        <div class="services-grid">
            <?php foreach ($services as $service): ?>
            <div class="service-card">
                <div class="service-icon"><i class="<?php echo htmlspecialchars($service['icon_class']); ?>"></i></div>
                <h3><?php echo htmlspecialchars($service['title']); ?></h3>
                <p><?php echo htmlspecialchars($service['description']); ?></p>
            </div>
            <?php endforeach; ?>
        </div>
        <div class="text-center mt-2">
            <a href="services.php" class="btn btn-primary">View All Services</a>
        </div>
    </div>
</section>

<!-- Call to Action -->
<section class="cta">
    <div class="container cta-content">
        <h2>Ready to Elevate Your Business?</h2>
        <p>Let's build something amazing together.</p>
        <a href="contact.php" class="btn btn-primary">Get in Touch Now</a>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
