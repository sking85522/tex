<?php include 'includes/header.php';
include 'includes/db.php';

$portfolio = [];
try {
    $stmt = $pdo->query("SELECT * FROM portfolio ORDER BY id DESC");
    $portfolio = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    // Handle error
}
?>

<section class="page-header" style="background-color: var(--primary-color); color: white; text-align: center; padding: 100px 0;">
    <div class="container">
        <h1 style="font-size: 3rem; margin-bottom: 20px;">Our Portfolio</h1>
        <p style="font-size: 1.2rem;">Over 100+ successful projects delivered globally.</p>
    </div>
</section>

<section class="portfolio-page" style="padding: 60px 0;">
    <div class="container">
        <div class="portfolio-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px;">
            <?php foreach($portfolio as $item): ?>
            <div class="portfolio-item" style="background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 5px 15px rgba(0,0,0,0.1); transition: transform 0.3s;">
                <img src="<?php echo htmlspecialchars($item['image_url']); ?>" alt="<?php echo htmlspecialchars($item['title']); ?>" style="width: 100%; height: 200px; object-fit: cover;">
                <div style="padding: 20px;">
                    <span style="display: inline-block; padding: 5px 10px; background: rgba(13, 110, 253, 0.1); color: var(--primary-color); border-radius: 5px; font-size: 0.8rem; font-weight: bold; margin-bottom: 10px;"><?php echo htmlspecialchars($item['category']); ?></span>
                    <h3 style="margin-top: 0; margin-bottom: 10px;"><?php echo htmlspecialchars($item['title']); ?></h3>
                    <p style="color: #666; margin-bottom: 20px; font-size: 0.95rem;"><?php echo htmlspecialchars($item['description']); ?></p>
                    <a href="<?php echo htmlspecialchars($item['demo_url']); ?>" target="_blank" class="btn btn-outline" style="display: block; text-align: center;">View Demo</a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>