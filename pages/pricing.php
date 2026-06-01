<?php
require_once '../includes/header.php';

// Fetch JSON Data
$jsonFile = __DIR__ . '/../data/site_data.json';
$siteData = file_exists($jsonFile) ? json_decode(file_get_contents($jsonFile), true) : [];
$pricing = $siteData['pricing'] ?? [];

// Manage Currency Toggle Preference via Query Param & Session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_GET['currency']) && in_array(strtolower($_GET['currency']), ['inr', 'usd'])) {
    $_SESSION['preferred_currency'] = strtolower($_GET['currency']);
}

// Default to INR if no preference is set, since owner is in India
$currency_key = $_SESSION['preferred_currency'] ?? 'inr';
$currency_symbol = ($currency_key === 'inr') ? '₹' : '$';
?>

<section class="page-header hero" style="padding: 160px 0 80px; text-align: center; border-bottom: 1px solid var(--glass-border);">
    <div class="container hero-content">
        <h1 class="hero-title">Transparent <span class="text-gradient-primary">Investment</span></h1>
        <p class="hero-desc">Deploy scalable architectures at transparent rates. Engineer your next venture with confidence.</p>

        <!-- Currency Toggle -->
        <div style="margin: 20px 0; display: flex; justify-content: center; gap: 10px;">
            <a href="?currency=inr" class="btn <?= $currency_key === 'inr' ? 'btn-primary' : 'btn-outline' ?>" style="padding: 8px 20px; border-radius: 20px;">INR (₹)</a>
            <a href="?currency=usd" class="btn <?= $currency_key === 'usd' ? 'btn-primary' : 'btn-outline' ?>" style="padding: 8px 20px; border-radius: 20px;">USD ($)</a>
        </div>

        <?php if(!empty($pricing['special_rate'])): ?>
        <div style="display: inline-block; background: var(--primary-glow); border: 1px solid var(--primary); padding: 10px 20px; border-radius: 50px; margin-top: 20px; color: var(--text-main); font-weight: bold;">
            <i class="fas fa-star" style="color: gold;"></i>
            Special Hourly Rate: <?= $currency_symbol . $pricing['special_rate'][$currency_key] ?>/hr
            <span style="font-weight: normal; font-size: 0.9rem;"> - <?= $pricing['special_rate']['description'] ?></span>
        </div>
        <?php endif; ?>
    </div>
</section>

<section style="padding: 60px 0; background: var(--bg-main);">
    <div class="container">

        <!-- Web Development -->
        <h2 class="font-heading" style="margin-bottom: 30px; font-size: 2rem; border-bottom: 2px solid var(--primary); display: inline-block; padding-bottom: 10px;">Web Development</h2>
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 30px; margin-bottom: 60px;">
            <?php foreach($pricing['web_development'] ?? [] as $plan): ?>
            <div class="glass-card" data-aos="fade-up" style="padding: 30px;">
                <h3 style="font-size: 1.5rem; margin-bottom: 10px; color: var(--text-main);"><?= htmlspecialchars($plan['name']) ?></h3>
                <div style="font-size: 2rem; font-weight: 800; margin-bottom: 20px; color: var(--primary);">
                    <?= $currency_symbol . number_format($plan[$currency_key]) ?>
                </div>
                <ul style="list-style: none; padding: 0; margin-bottom: 20px; color: var(--text-muted);">
                    <?php foreach($plan['features'] as $feature): ?>
                        <li style="margin-bottom: 8px;"><i class="fas fa-check" style="color: var(--secondary); margin-right: 8px;"></i> <?= htmlspecialchars($feature) ?></li>
                    <?php endforeach; ?>
                </ul>
                <a href="contact.php?plan=<?= urlencode($plan['name']) ?>" class="btn btn-outline" style="width: 100%; text-align: center;">Get Started</a>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Static Sites -->
        <h2 class="font-heading" style="margin-bottom: 30px; font-size: 2rem; border-bottom: 2px solid var(--primary); display: inline-block; padding-bottom: 10px;">Static Websites</h2>
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 30px; margin-bottom: 60px;">
            <?php foreach($pricing['static_sites'] ?? [] as $plan): ?>
            <div class="glass-card" data-aos="fade-up" style="padding: 30px;">
                <h3 style="font-size: 1.5rem; margin-bottom: 10px; color: var(--text-main);"><?= htmlspecialchars($plan['name']) ?></h3>
                <div style="font-size: 2rem; font-weight: 800; margin-bottom: 20px; color: var(--primary);">
                    <?= $currency_symbol . number_format($plan[$currency_key]) ?>
                </div>
                <ul style="list-style: none; padding: 0; margin-bottom: 20px; color: var(--text-muted);">
                    <?php foreach($plan['features'] as $feature): ?>
                        <li style="margin-bottom: 8px;"><i class="fas fa-check" style="color: var(--secondary); margin-right: 8px;"></i> <?= htmlspecialchars($feature) ?></li>
                    <?php endforeach; ?>
                </ul>
                <a href="contact.php?plan=<?= urlencode($plan['name']) ?>" class="btn btn-outline" style="width: 100%; text-align: center;">Get Started</a>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- App Development -->
        <h2 class="font-heading" style="margin-bottom: 30px; font-size: 2rem; border-bottom: 2px solid var(--primary); display: inline-block; padding-bottom: 10px;">Mobile Apps</h2>
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 30px; margin-bottom: 60px;">
            <?php foreach($pricing['app_development'] ?? [] as $plan): ?>
            <div class="glass-card" data-aos="fade-up" style="padding: 30px;">
                <h3 style="font-size: 1.5rem; margin-bottom: 10px; color: var(--text-main);"><?= htmlspecialchars($plan['name']) ?></h3>
                <div style="font-size: 2rem; font-weight: 800; margin-bottom: 20px; color: var(--primary);">
                    <?= $currency_symbol . number_format($plan[$currency_key]) ?>
                </div>
                <ul style="list-style: none; padding: 0; margin-bottom: 20px; color: var(--text-muted);">
                    <?php foreach($plan['features'] as $feature): ?>
                        <li style="margin-bottom: 8px;"><i class="fas fa-check" style="color: var(--secondary); margin-right: 8px;"></i> <?= htmlspecialchars($feature) ?></li>
                    <?php endforeach; ?>
                </ul>
                <a href="contact.php?plan=<?= urlencode($plan['name']) ?>" class="btn btn-outline" style="width: 100%; text-align: center;">Get Started</a>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Maintenance -->
        <h2 class="font-heading" style="margin-bottom: 30px; font-size: 2rem; border-bottom: 2px solid var(--primary); display: inline-block; padding-bottom: 10px;">Maintenance & Support</h2>
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 30px; margin-bottom: 60px;">
            <?php foreach($pricing['maintenance'] ?? [] as $plan): ?>
            <div class="glass-card" data-aos="fade-up" style="padding: 30px;">
                <h3 style="font-size: 1.5rem; margin-bottom: 10px; color: var(--text-main);"><?= htmlspecialchars($plan['name']) ?></h3>
                <div style="font-size: 2rem; font-weight: 800; margin-bottom: 20px; color: var(--primary);">
                    <?= $currency_symbol . number_format($plan[$currency_key]) ?> <small style="font-size:1rem; color:var(--text-muted);">/ mo</small>
                </div>
                <ul style="list-style: none; padding: 0; margin-bottom: 20px; color: var(--text-muted);">
                    <?php foreach($plan['features'] as $feature): ?>
                        <li style="margin-bottom: 8px;"><i class="fas fa-check" style="color: var(--secondary); margin-right: 8px;"></i> <?= htmlspecialchars($feature) ?></li>
                    <?php endforeach; ?>
                </ul>
                <a href="contact.php?plan=<?= urlencode($plan['name']) ?>" class="btn btn-outline" style="width: 100%; text-align: center;">Get Started</a>
            </div>
            <?php endforeach; ?>
        </div>

    </div>
</section>

<?php include '../includes/footer.php'; ?>
