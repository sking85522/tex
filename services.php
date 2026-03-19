<?php
include 'includes/header.php';
include 'includes/db.php'; // Include DB connection

// Fetch services from DB
try {
    $stmt = $pdo->query("SELECT * FROM services ORDER BY id ASC");
    $services = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $services = [];
    $error = "Failed to load services.";
}
?>

<section class="page-header" style="background-color: var(--primary-color); color: white; text-align: center; padding: 100px 0;">
    <div class="container">
        <h1 style="font-size: 3rem; margin-bottom: 20px;">Our Services</h1>
        <p style="font-size: 1.2rem;">Comprehensive IT solutions designed to meet your business needs.</p>
    </div>
</section>

<section class="services-page">
    <div class="container">

        <?php if (isset($error)): ?>
            <p style="text-align:center; color:red;"><?php echo $error; ?></p>
        <?php elseif (empty($services)): ?>
            <p style="text-align:center;">No services found currently. Please check back later.</p>
        <?php else: ?>

        <div class="services-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 40px;">

            <?php foreach ($services as $service): ?>
            <div class="service-card" style="padding: 40px; text-align: center; background: white; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); transition: 0.3s;">
                <i class="<?php echo htmlspecialchars($service['icon_class']); ?>" style="font-size: 3.5rem; color: var(--primary-color); margin-bottom: 20px;"></i>
                <h3 style="margin-bottom: 15px; font-size: 1.5rem;"><?php echo htmlspecialchars($service['title']); ?></h3>
                <p style="color: #666; margin-bottom: 20px;"><?php echo htmlspecialchars($service['description']); ?></p>
                <a href="contact.php" class="btn btn-outline">Request Quote</a>
            </div>
            <?php endforeach; ?>

        </div>

        <?php endif; ?>

    </div>
</section>

<?php include 'includes/footer.php'; ?>
