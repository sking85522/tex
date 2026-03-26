<?php
include 'includes/header.php';
include 'includes/db.php';

$blogs = [];
try {
    $stmt = $pdo->query("SELECT b.*, a.username as author_name FROM blogs b JOIN admins a ON b.author_id = a.id WHERE b.status = 'published' ORDER BY b.created_at DESC");
    $blogs = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {}

// Simple AI Auto-generate mock fallback
if (empty($blogs)) {
    $blogs = [
        ['id' => 1, 'title' => 'Top 5 AI Trends Transforming the Tech Industry in 2024', 'slug' => 'top-5-ai-trends', 'content' => 'Artificial intelligence is moving faster than ever. Here is what you need to know...', 'image_url' => 'https://via.placeholder.com/600x400?text=AI+Trends', 'author_name' => 'AI System', 'created_at' => date('Y-m-d H:i:s')]
    ];
}
?>

<section class="page-header" style="background-color: var(--primary-color); color: white; text-align: center; padding: 100px 0;">
    <div class="container">
        <h1 style="font-size: 3rem; margin-bottom: 20px;">Tech & AI Blog</h1>
        <p style="font-size: 1.2rem;">Insights and latest updates from Tech Elevate X experts.</p>
    </div>
</section>

<section class="blog-page" style="padding: 60px 0; background-color: #f9f9f9;">
    <div class="container">
        <div class="blog-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px;">
            <?php foreach($blogs as $blog): ?>
            <div class="blog-card" style="background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 5px 15px rgba(0,0,0,0.1); transition: transform 0.3s;">
                <img src="<?php echo htmlspecialchars($blog['image_url']); ?>" alt="<?php echo htmlspecialchars($blog['title']); ?>" style="width: 100%; height: 200px; object-fit: cover;">
                <div style="padding: 20px;">
                    <span style="font-size: 0.85rem; color: #999;"><i class="fas fa-calendar-alt"></i> <?php echo date('M d, Y', strtotime($blog['created_at'])); ?> | <i class="fas fa-user"></i> <?php echo htmlspecialchars($blog['author_name']); ?></span>
                    <h3 style="margin: 15px 0; font-size: 1.3rem;"><a href="blog.php?slug=<?php echo htmlspecialchars($blog['slug']); ?>" style="color: var(--dark-color); text-decoration: none;"><?php echo htmlspecialchars($blog['title']); ?></a></h3>
                    <p style="color: #666; margin-bottom: 20px; font-size: 0.95rem;"><?php echo htmlspecialchars(substr($blog['content'], 0, 100)) . '...'; ?></p>
                    <a href="blog.php?slug=<?php echo htmlspecialchars($blog['slug']); ?>" class="btn btn-outline" style="padding: 8px 15px; font-size: 0.9rem;">Read More</a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
