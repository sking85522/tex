<?php
require_once '../includes/header.php';

// Fetch JSON Data
$jsonFile = __DIR__ . '/../data/site_data.json';
$siteData = file_exists($jsonFile) ? json_decode(file_get_contents($jsonFile), true) : [];
$services = $siteData['services'] ?? [];

$hero_desc = "Tech Elevate X builds high-performance software ecosystems and enterprise tools that automate business scaling without constraints.";
?>

<section class="services-hero hero" style="padding: 160px 0 80px 0; text-align: center; background: var(--bg-main);">
    <div class="container hero-content" data-aos="fade-up">
        <h1 class="hero-title">Core <span class="text-gradient-primary">Capabilities</span></h1>
        <p class="hero-desc"><?php echo htmlspecialchars($hero_desc); ?></p>
    </div>
</section>

<!-- Interactive Project Configurator Entry -->
<section style="padding: 20px 0;">
    <div class="container">
        <div class="glass-card" style="display: flex; align-items: center; justify-content: space-between; gap: 30px; flex-wrap: wrap; border: 1px solid rgba(59, 130, 246, 0.3);">
            <div style="flex: 1; min-width: 300px;">
                <h3 style="margin-bottom: 8px; color: var(--text-main);"><i class="fas fa-microchip" style="color: var(--primary);"></i> Initialize Custom Architecture</h3>
                <p class="text-muted" style="font-size: 0.9rem;">Engage our engineering team to design and deploy your custom solution.</p>
            </div>
            <a href="contact.php" class="btn btn-primary">Deploy Project</a>
        </div>
    </div>
</section>

<section class="services-grid-page" style="padding: 100px 0;">
    <div class="container">
        <div class="services-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 32px;">
            <?php foreach($services as $index => $service): ?>
            <div class="glass-card service-card" data-aos="fade-up" data-aos-delay="<?php echo $index * 100; ?>" style="position: relative; overflow: hidden;">

                <?php if($index === 0): ?>
                    <div style="position: absolute; top: 15px; right: 15px; background: var(--secondary); color: white; padding: 4px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 700; box-shadow: 0 0 15px var(--secondary);">
                        <i class="fas fa-bolt"></i> TOP PICK
                    </div>
                <?php endif; ?>

                <div class="service-icon">
                    <i class="fas <?php echo $service['icon_class'] ?? 'fa-cube'; ?>"></i>
                </div>

                <h3 style="font-size: 1.6rem; font-weight: 700; margin-bottom: 12px; color: var(--text-main);"><?php echo $service['title']; ?></h3>
                <p class="text-muted" style="line-height: 1.8; margin-bottom: 30px;"><?php echo $service['description']; ?></p>

                <a href="contact.php?service=<?php echo urlencode($service['title']); ?>" class="btn btn-outline" style="width: 100%; text-align: center;">Request System Design</a>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php include '../includes/footer.php'; ?>