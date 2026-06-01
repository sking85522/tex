<?php include '../includes/header.php'; ?>

<section class="page-header" style="background: var(--primary); padding: 120px 0 60px; text-align: center;">
    <div class="container">
        <h1 class="text-gradient" style="font-size: 3.5rem; filter: brightness(1.5);">Our Portfolio</h1>
        <p class="text-muted" style="max-width: 700px; margin: 20px auto; font-size: 1.1rem;">Explore the high-performance digital ecosystems built by Tech Elevate X and  .</p>
    </div>
</section>

<section style="padding: 80px 0;">
    <div class="container">
        <div class="services-grid">
            <?php
            $projects = [
                ['title' => 'Nexus ERP', 'desc' => 'Autonomous enterprise resource planning with  forecasting.', 'img' => 'https://via.placeholder.com/600x400'],
                ['title' => 'Aether Cloud', 'desc' => 'High-frequency trading interface with sub-10ms latency.', 'img' => 'https://via.placeholder.com/600x400'],
                ['title' => 'BioScale ', 'desc' => ' diagnostic platform for pharmaceutical research.', 'img' => 'https://via.placeholder.com/600x400']
            ];
            foreach($projects as $p): ?>
            <div class="glass-card" data-aos="fade-up">
                <img src="<?php echo $p['img']; ?>" alt="<?php echo $p['title']; ?>" loading="lazy" style="border-radius: 12px; margin-bottom: 24px;">
                <h3 style="margin-bottom: 12px;"><?php echo $p['title']; ?></h3>
                <p class="text-muted"><?php echo $p['desc']; ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php include '../includes/footer.php'; ?>