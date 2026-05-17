<?php 
require_once 'includes/header.php';
?>


<!-- Premium Hero Section -->
<main id="app-root">
    <section class="hero">
        <div class="container">
            <div class="hero-grid">
                <div class="hero-text" data-aos="fade-right">
                    <div class="hero-tag">
                        <i class="fas fa-rocket"></i>
                        <span>Premium Digital Agency</span>
                    </div>
                    <h1 class="hero-title">
                        Enterprise Software <br>
                        <span class="text-gradient">Built for the Future</span>
                    </h1>
                    <p class="hero-desc">
                        Tech Elevate X builds high-performance software ecosystems and web applications that automate business scaling.
                    </p>
                    <div class="hero-actions">
                        <a href="pages/contact.php" class="btn btn-primary">Start Project</a>
                        <a href="pages/services.php" class="btn btn-outline">Explore Solutions</a>
                    </div>
                </div>
                
                <div class="hero-visual" data-aos="zoom-in" style="display: flex; justify-content: center; align-items: center;">
                    <img src="https://images.unsplash.com/photo-1498050108023-c5249f4df085?auto=format&fit=crop&q=80&w=600" alt="Hero Image" style="width: 100%; border-radius: 20px; box-shadow: var(--glass-shadow); object-fit: cover;">
                </div>
            </div>
        </div>
    </section>

    <!-- Capabilities Discovery -->
    <section class="services" style="padding: 100px 0;">
        <div class="container">
            <div class="section-header" data-aos="fade-up">
                <h2 class="font-heading">Our Services</h2>
                <p class="text-muted">Explore our suite of enterprise modules designed to integrate seamlessly into your business.</p>
            </div>
            
            <div class="services-grid">
                <?php 
                try {
                    $stmt = $pdo->query("SELECT * FROM services LIMIT 6");
                    while($service = $stmt->fetch()): 
                ?>
                <div class="glass-card service-card" data-aos="fade-up">
                    <div class="service-icon">
                        <i class="<?php echo $service['icon_class']; ?>"></i>
                    </div>
                    <h3><?php echo $service['title']; ?></h3>
                    <p class="text-muted" style="margin: 16px 0 24px; font-size: 0.95rem;"><?php echo $service['description']; ?></p>
                    <a href="pages/services.php" style="color: var(--primary); font-weight: 600; display: flex; align-items: center; gap: 8px;">
                        Analyze Architecture <i class="fas fa-arrow-right"></i>
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
    <section class="trust-grid" style="padding: 80px 0; border-top: 1px solid var(--glass-border); background: var(--bg-main);">
        <div class="container">
            <div style="display: flex; justify-content: space-between; align-items: center; gap: 40px; flex-wrap: wrap;">
                <div style="opacity: 0.6;">
                    <h4 style="font-size: 0.8rem; letter-spacing: 0.2em; color: var(--text-muted); margin-bottom: 16px;">OUR TECH STACK</h4>
                    <div style="display: flex; gap: 32px; font-size: 1.2rem; color: var(--text-main);">
                        <i class="fab fa-php"></i>
                        <i class="fab fa-js"></i>
                        <i class="fas fa-database"></i>
                        <i class="fas fa-network-wired"></i>
                    </div>
                </div>
                <div class="glass-card" style="padding: 16px 32px; border-radius: 100px; display: flex; gap: 32px; font-size: 0.85rem; font-weight: 600;">
                    <span><i class="fas fa-fingerprint" style="color: var(--primary);"></i> Secure Systems</span>
                    <span><i class="fas fa-bolt" style="color: var(--secondary);"></i> High Performance</span>
                    <span><i class="fas fa-shield-check" style="color: var(--accent);"></i> Enterprise Grade</span>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include 'includes/footer.php'; ?>
