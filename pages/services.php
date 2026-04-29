<?php
require_once '../includes/header.php';

if (class_exists('Core\AIEngine')) {
    $ai = new \Core\AIEngine($pdo);
}
// Localized Detection
$detected_currency = 'INR';
$currency_symbol = '₹';

$hero_desc = "Tech Elevate X builds self-evolving software ecosystems and local-first AI models that automate business scaling without external API dependencies.";
if (class_exists('Core\Engine')) {
    try {
        $engine = new \Core\Engine();
        $res = $engine->processPrompt("Write a 1 sentence punchy subtitle for an IT services page that offers AI development and web development.");
        if (!str_contains($res['response'], 'I have not learned')) {
            $hero_desc = $res['response'];
        }
    } catch (\Exception $e) {}
}


// AI Intelligence: Pre-sort services by 'Hot' interest
$services = [];
try {
    $stmt = $pdo->query("SELECT * FROM services ORDER BY price_inr DESC");
    $services = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(Exception $e) {
    $services = [
        ['title' => 'Core AI Integration', 'description' => 'Deploy autonomous HRITIK models on local infrastructure.', 'icon_class' => 'fa-robot', 'price_inr' => 25000],
        ['title' => 'Enterprise Web Systems', 'description' => 'High-performance dynamic portals for global scale.', 'icon_class' => 'fa-code', 'price_inr' => 15000]
    ];
}
?>

<section class="services-hero" style="padding: 140px 0 60px 0; text-align: center; background: radial-gradient(circle at top, rgba(99, 102, 241, 0.1) 0%, transparent 70%);">
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
                <h3 style="margin-bottom: 8px;"><i class="fas fa-magic" style="color: var(--primary);"></i> Build Your Custom Solution</h3>
                <p class="text-muted" style="font-size: 0.9rem;">Answer 4 questions and HRITIK will generate a custom quote for your business.</p>
            </div>
            <a href="javascript:void(0)" class="btn btn-primary" onclick="toggleConfigurator()">Launch Configurator</a>
        </div>
    </div>
</section>

<section class="services-grid-page" style="padding: 80px 0;">
    <div class="container">
        <div class="services-grid">
            <?php foreach($services as $index => $service): ?>
            <div class="glass-card service-card" data-aos="fade-up" data-aos-delay="<?php echo $index * 100; ?>" style="position: relative; overflow: hidden;">
                
                <!-- HRITIK Pulse for Top Service -->
                <?php if($index === 0): ?>
                    <div style="position: absolute; top: 15px; right: 15px; background: var(--secondary); color: white; padding: 4px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 700; box-shadow: 0 0 15px var(--secondary);">
                        <i class="fas fa-bolt"></i> HRITIK TOP PICK
                    </div>
                <?php endif; ?>

                <div class="service-icon">
                    <i class="fas <?php echo $service['icon_class'] ?? 'fa-cube'; ?>"></i>
                </div>
                
                <h3 style="font-size: 1.6rem; font-weight: 700; margin-bottom: 12px;"><?php echo $service['title']; ?></h3>
                <p class="text-muted" style="line-height: 1.8; margin-bottom: 30px;"><?php echo $service['description']; ?></p>
                
                <div style="display: flex; align-items: baseline; gap: 10px; margin-bottom: 30px; padding-top: 20px; border-top: 1px solid var(--glass-border);">
                    <span style="font-size: 2rem; font-weight: 800; color: white;"><?php echo $currency_symbol . number_format($service['price_inr']); ?></span>
                    <span class="text-muted" style="font-size: 0.9rem;">/ Est. Quote</span>
                </div>

                <a href="contact.php?service=<?php echo urlencode($service['title']); ?>" class="btn btn-outline" style="width: 100%;">Request System Design</a>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php include '../includes/footer.php'; ?>