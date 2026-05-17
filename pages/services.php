<?php
require_once '../includes/header.php';

// Fetch JSON Data
$jsonFile = __DIR__ . '/../data/site_data.json';
$siteData = file_exists($jsonFile) ? json_decode(file_get_contents($jsonFile), true) : [];
$services = $siteData['services'] ?? [];

$hero_desc = "Tech Elevate X builds high-performance software ecosystems and enterprise tools that automate business scaling without constraints.";
?>

<section class="services-hero" style="padding: 140px 0 60px 0; text-align: center; background: var(--bg-main);">
    <div class="container" data-aos="fade-up">
        <h1 style="font-size: clamp(2.5rem, 5vw, 4rem); font-weight: 800; margin-bottom: 20px;">Enterprise <span class="text-gradient">Capabilities</span></h1>
        <p class="text-muted" style="max-width: 700px; margin: 0 auto; font-size: 1.2rem;"><?php echo htmlspecialchars($hero_desc); ?></p>
    </div>
</section>

<!-- Interactive Project Configurator Entry -->
<section style="padding: 20px 0;">
    <div class="container">
        <div class="glass-card" style="display: flex; align-items: center; justify-content: space-between; gap: 30px; flex-wrap: wrap; border: 1px solid var(--primary);">
            <div style="flex: 1; min-width: 300px;">
                <h3 style="margin-bottom: 8px; color: var(--text-main);"><i class="fas fa-magic" style="color: var(--primary);"></i> Build Your Custom Solution</h3>
                <p class="text-muted" style="font-size: 0.9rem;">Contact us to generate a custom quote for your business.</p>
            </div>
            <a href="contact.php" class="btn btn-primary">Contact Us</a>
        </div>
    </div>
</section>

<section class="services-grid-page" style="padding: 80px 0;">
    <div class="container">
        <div class="services-grid">
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