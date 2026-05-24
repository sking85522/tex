<?php 
require_once 'includes/header.php';
?>


<!-- Premium Hero Section -->
<main id="app-root">
    <section class="hero" aria-label="Hero Section" style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap;">
        <div class="container" style="display: flex; align-items: center; gap: 40px; flex-wrap: wrap;">
            <div class="hero-content" style="flex: 1; min-width: 300px; text-align: left;">
                <div class="hero-text" data-aos="fade-right">
                    <div class="hero-tag">
                        <i class="fas fa-microchip"></i>
                        <span>Next-Gen Tech Infrastructure</span>
                    </div>
                    <h1 class="hero-title" style="text-align: left;">
                        Accelerate with <br>
                        <span class="text-gradient-primary">Intelligent Systems</span>
                    </h1>
                    <p class="hero-desc" style="text-align: left; margin-left: 0;">
                        Deploy scalable, high-performance web ecosystems and enterprise software engineered for the AI-driven era.
                    </p>
                    <div class="hero-actions" style="display: flex; gap: 16px; justify-content: flex-start; margin-top: 32px;">
                        <a href="pages/contact.php" class="btn btn-primary" aria-label="Start Project">Deploy Now</a>
                        <a href="pages/services.php" class="btn btn-outline" aria-label="Explore Solutions">View Architecture</a>
                    </div>
                </div>
            </div>

            <div class="hero-visual" data-aos="fade-left" style="flex: 1; min-width: 300px; display: flex; justify-content: center;">
                <img src="https://images.unsplash.com/photo-1620712943543-bcc4688e7485?auto=format&fit=crop&q=80&w=600" alt="Futuristic AI Technology Representation" fetchpriority="high" style="width: 100%; border-radius: 24px; box-shadow: var(--glass-shadow); object-fit: cover; border: 1px solid var(--glass-border);">
            </div>
        </div>
    </section>

    <!-- Capabilities Discovery -->
    <section class="services" style="padding: 100px 0;" aria-label="Our Services">
        <div class="container">
            <div class="section-header" data-aos="fade-up">
                <h2 class="font-heading">Core Capabilities</h2>
                <p class="text-muted" style="max-width: 600px; margin: 0 auto;">High-velocity enterprise modules designed to integrate seamlessly and scale infinitely.</p>
            </div>
            
            <div class="services-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px;">
                <?php 
                try {
                    $stmt = $pdo->query("SELECT * FROM services LIMIT 6");
                    while($service = $stmt->fetch()): 
                ?>
                <div class="glass-card service-card" data-aos="fade-up">
                    <div class="service-icon">
                        <i class="<?php echo $service['icon_class']; ?>" aria-hidden="true"></i>
                    </div>
                    <h3 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 12px;"><?php echo $service['title']; ?></h3>
                    <p class="text-muted" style="margin: 0 0 24px; font-size: 0.95rem;"><?php echo $service['description']; ?></p>
                    <a href="pages/services.php" style="color: var(--primary); font-weight: 600; display: inline-flex; align-items: center; gap: 8px; text-decoration: none; font-size: 0.9rem; transition: 0.2s;">
                        Analyze Architecture <i class="fas fa-arrow-right" aria-hidden="true"></i>
                    </a>
                </div>
                <?php endwhile; } catch(Exception $e) {
                    // Fallback if DB is empty
                    echo '<p class="text-muted">Database sync in progress... System initializing.</p>';
                } ?>
            </div>
        </div>
    </section>

    <!-- Strategic Trust Grid -->
    <section class="trust-grid" style="padding: 80px 0; border-top: 1px solid var(--glass-border); background: var(--bg-main);" aria-label="Trust and Tech Stack">
        <div class="container">
            <div style="display: flex; justify-content: space-between; align-items: center; gap: 40px; flex-wrap: wrap;">
                <div style="opacity: 0.8;">
                    <h4 style="font-size: 0.85rem; letter-spacing: 0.2em; color: var(--text-muted); margin-bottom: 16px; font-weight: 600;">OUR ARCHITECTURE STACK</h4>
                    <div style="display: flex; gap: 32px; font-size: 1.5rem; color: var(--text-muted);">
                        <i class="fab fa-php" title="PHP" aria-label="PHP"></i>
                        <i class="fab fa-js" title="JavaScript" aria-label="JavaScript"></i>
                        <i class="fas fa-database" title="Database" aria-label="Database"></i>
                        <i class="fab fa-aws" title="Cloud" aria-label="Cloud Services"></i>
                    </div>
                </div>
                <div class="glass-card" style="padding: 20px 40px; border-radius: 100px; display: flex; flex-wrap: wrap; gap: 32px; font-size: 0.9rem; font-weight: 600; background: rgba(255,255,255,0.02);">
                    <span style="display: flex; align-items: center; gap: 8px;"><i class="fas fa-shield-alt" style="color: var(--primary);" aria-hidden="true"></i> Zero-Trust Security</span>
                    <span style="display: flex; align-items: center; gap: 8px;"><i class="fas fa-tachometer-alt" style="color: var(--secondary);" aria-hidden="true"></i> Sub-second Latency</span>
                    <span style="display: flex; align-items: center; gap: 8px;"><i class="fas fa-layer-group" style="color: var(--accent);" aria-hidden="true"></i> Infinite Scalability</span>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include 'includes/footer.php'; ?>
