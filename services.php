<?php
require_once 'includes/neural_engine.php';
$neural = new NeuralEngine();
$adaptations = $neural->getUIAdaptations();

include 'includes/header.php';
include 'includes/db.php';

// IP Location Logic for Dynamic Pricing
function getUserCountryCode() {
    $ip = $_SERVER['REMOTE_ADDR'];
    // For local testing, default to generic or mock IP
    if ($ip == '127.0.0.1' || $ip == '::1') {
        // Uncomment to test IN mode locally:
        // return 'IN';
        return 'US'; // Default mock for external
    }

    // Quick API call to get country code
    try {
        $json = @file_get_contents("http://ip-api.com/json/{$ip}?fields=countryCode");
        if ($json) {
            $data = json_decode($json);
            return $data->countryCode ?? 'US';
        }
    } catch(Exception $e) {}

    return 'US'; // Default fallback
}

$countryCode = getUserCountryCode();
$currencySymbol = ($countryCode === 'IN') ? '₹' : '$';
$priceColumn = ($countryCode === 'IN') ? 'price_inr' : 'price_usd';

$services = [];
try {
    $stmt = $pdo->query("SELECT id, title, description, icon, $priceColumn as price FROM services");
    $services = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    // Handling local mock
    $services = [
        ['title' => 'Web Development', 'description' => 'Custom web apps.', 'icon' => 'fa-laptop-code', 'price' => 999],
        ['title' => 'Mobile Apps', 'description' => 'iOS and Android.', 'icon' => 'fa-mobile-alt', 'price' => 1499]
    ];
}

// Sort services so that the "highlight_service" determined by the NeuralEngine comes FIRST
if ($adaptations['highlight_service']) {
    usort($services, function($a, $b) use ($adaptations) {
        if ($a['title'] == $adaptations['highlight_service']) return -1;
        if ($b['title'] == $adaptations['highlight_service']) return 1;
        return 0;
    });
}

?>

<section class="page-header" style="background-color: var(--primary-color); color: white; text-align: center; padding: 100px 0;">
    <div class="container">
        <h1 style="font-size: 3rem; margin-bottom: 20px;">Our Services</h1>
        <p style="font-size: 1.2rem;">Comprehensive IT solutions tailored to your business needs.</p>
    </div>
</section>

<section class="services-page" style="padding: 60px 0; background-color: #f9f9f9;">
    <div class="container">

        <div class="services-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px;">
            <?php foreach($services as $service):
// Sort services so that the "highlight_service" determined by the NeuralEngine comes FIRST
if ($adaptations['highlight_service']) {
    usort($services, function($a, $b) use ($adaptations) {
        if ($a['title'] == $adaptations['highlight_service']) return -1;
        if ($b['title'] == $adaptations['highlight_service']) return 1;
        return 0;
    });
}

?>
            <div class="service-card" style="position: relative; background: white; padding: 40px; border-radius: 10px; text-align: center; box-shadow: 0 5px 20px rgba(0,0,0,0.05); transition: transform 0.3s;">

                <?php if ($adaptations['highlight_service'] == $service['title']): ?>
                    <div style="background-color: #f6c23e; color: #fff; padding: 5px 15px; border-radius: 20px; font-size: 0.8rem; font-weight: bold; position: absolute; top: -15px; left: 50%; transform: translateX(-50%); box-shadow: 0 4px 6px rgba(0,0,0,0.1);">⭐ AI RECOMMENDED FOR YOU</div>
                <?php endif; ?>

                <div class="service-icon" style="width: 80px; height: 80px; margin: 0 auto 20px; background-color: rgba(13, 110, 253, 0.1); border-radius: 50%; display: flex; justify-content: center; align-items: center; color: var(--primary-color); font-size: 2rem;">
                    <i class="fas <?php echo htmlspecialchars($service['icon']);
// Sort services so that the "highlight_service" determined by the NeuralEngine comes FIRST
if ($adaptations['highlight_service']) {
    usort($services, function($a, $b) use ($adaptations) {
        if ($a['title'] == $adaptations['highlight_service']) return -1;
        if ($b['title'] == $adaptations['highlight_service']) return 1;
        return 0;
    });
}

?>"></i>
                </div>
                <h3 style="margin-bottom: 15px; font-size: 1.5rem; color: var(--dark-color);"><?php echo htmlspecialchars($service['title']);
// Sort services so that the "highlight_service" determined by the NeuralEngine comes FIRST
if ($adaptations['highlight_service']) {
    usort($services, function($a, $b) use ($adaptations) {
        if ($a['title'] == $adaptations['highlight_service']) return -1;
        if ($b['title'] == $adaptations['highlight_service']) return 1;
        return 0;
    });
}

?></h3>
                <p style="color: #666; margin-bottom: 20px; line-height: 1.6;"><?php echo htmlspecialchars($service['description']);
// Sort services so that the "highlight_service" determined by the NeuralEngine comes FIRST
if ($adaptations['highlight_service']) {
    usort($services, function($a, $b) use ($adaptations) {
        if ($a['title'] == $adaptations['highlight_service']) return -1;
        if ($b['title'] == $adaptations['highlight_service']) return 1;
        return 0;
    });
}

?></p>

                <div style="font-size: 1.5rem; font-weight: bold; color: var(--primary-color); margin-bottom: 20px;">
                    <?php if ($adaptations['highlight_service'] == $service['title'] && $adaptations['discount_badge']): ?>
                        <span style="text-decoration: line-through; color: #999; font-size: 1rem;"><?php echo $currencySymbol . number_format($service['price'], 0); ?></span>
                        <span style="color: #e74a3b;"><?php echo $currencySymbol . number_format($service['price'] * 0.85, 0); ?></span>
                    <?php else: ?>
                        <?php echo $currencySymbol . number_format($service['price'], 0); ?>
                    <?php endif; ?>
                    <span style="font-size: 0.9rem; color: #999; font-weight: normal;">/project</span>
                </div>

                <a href="contact.php?service=<?php echo urlencode($service['title']);
// Sort services so that the "highlight_service" determined by the NeuralEngine comes FIRST
if ($adaptations['highlight_service']) {
    usort($services, function($a, $b) use ($adaptations) {
        if ($a['title'] == $adaptations['highlight_service']) return -1;
        if ($b['title'] == $adaptations['highlight_service']) return 1;
        return 0;
    });
}

?>" class="btn btn-primary" style="display: block; width: 100%; padding: 12px; box-sizing: border-box;">Request Quote</a>
            </div>
            <?php endforeach;
// Sort services so that the "highlight_service" determined by the NeuralEngine comes FIRST
if ($adaptations['highlight_service']) {
    usort($services, function($a, $b) use ($adaptations) {
        if ($a['title'] == $adaptations['highlight_service']) return -1;
        if ($b['title'] == $adaptations['highlight_service']) return 1;
        return 0;
    });
}

?>
        </div>

    </div>
</section>

<?php include 'includes/footer.php';
// Sort services so that the "highlight_service" determined by the NeuralEngine comes FIRST
if ($adaptations['highlight_service']) {
    usort($services, function($a, $b) use ($adaptations) {
        if ($a['title'] == $adaptations['highlight_service']) return -1;
        if ($b['title'] == $adaptations['highlight_service']) return 1;
        return 0;
    });
}

?>