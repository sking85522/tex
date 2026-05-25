<?php
require_once '../includes/header.php';
$about_desc = "Welcome to Tech Elevate X, a premier software development agency.";
?>

<section class="page-header hero" style="padding: 160px 0 80px; text-align: center; border-bottom: 1px solid var(--glass-border);">
    <div class="container hero-content">
        <h1 class="hero-title">About <span class="text-gradient-primary">Tech Elevate X</span></h1>
        <p class="hero-desc">Discover the engineers and visionaries architecting the future of enterprise software.</p>
    </div>
</section>

<section class="about-page" style="padding: 100px 0;">
    <div class="container">
        <div class="about-content" style="display: flex; gap: 60px; align-items: center; flex-wrap: wrap;">
            <div style="flex: 1; min-width: 300px;" data-aos="fade-right">
                <h2 class="font-heading" style="font-size: 2.5rem; margin-bottom: 24px;">Pioneering the <span class="text-gradient">Digital Frontier</span></h2>
                <p class="text-muted" style="margin-bottom: 20px; font-size: 1.1rem; line-height: 1.8;"><?php echo htmlspecialchars($about_desc); ?></p>
                <p class="text-muted" style="margin-bottom: 30px; font-size: 1.1rem; line-height: 1.8;">We assemble elite cohorts of systems engineers, UX architects, and algorithmic thinkers. We don't just write code; we synthesize intelligent systems that scale autonomously and secure your operational future.</p>

                <div class="glass-card" style="padding: 24px;">
                    <h3 style="margin-bottom: 12px; color: var(--text-main); display: flex; align-items: center; gap: 8px;"><i class="fas fa-rocket" style="color: var(--primary);"></i> Our Directive</h3>
                    <p class="text-muted" style="font-size: 1rem;">To engineer scalable, fault-tolerant, and intelligent tech infrastructure that acts as a force multiplier for global enterprises.</p>
                </div>
            </div>
            <div style="flex: 1; min-width: 300px;" data-aos="fade-left">
                <img src="https://images.unsplash.com/photo-1522071820081-009f0129c71c?auto=format&fit=crop&q=80&w=800" alt="Tech Elevate X Engineering Team Collaborating" loading="lazy" width="800" height="533" style="width: 100%; height: auto; border-radius: 20px; box-shadow: var(--glass-shadow); border: 1px solid var(--glass-border);">
            </div>
        </div>
    </div>
</section>

<?php include '../includes/footer.php'; ?>
