<?php
require_once '../includes/header.php';

// Fetch blog posts from the JSON admin storage
$postsFile = __DIR__ . '/../admin/storage/content/posts.json';
$posts = [];
if (file_exists($postsFile)) {
    $postsData = json_decode(file_get_contents($postsFile), true);
    if (is_array($postsData)) {
        // Filter only published posts and sort by newest first
        foreach ($postsData as $p) {
            if (isset($p['status']) && $p['status'] === 'published') {
                $posts[] = $p;
            }
        }
        usort($posts, function($a, $b) {
            return strtotime($b['created_at']) - strtotime($a['created_at']);
        });
    }
}
?>

<section class="page-header hero" style="padding: 160px 0 80px; text-align: center; border-bottom: 1px solid var(--glass-border);">
    <div class="container hero-content">
        <h1 class="hero-title">Insights & <span class="text-gradient-primary">Engineering</span></h1>
        <p class="hero-desc">Explore our technical deep dives, architectural decisions, and thoughts on scaling intelligent systems.</p>
    </div>
</section>

<section style="padding: 100px 0; background: var(--bg-main);">
    <div class="container">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 32px;">
            <?php if (empty($posts)): ?>
                <div class="glass-card" style="grid-column: 1 / -1; text-align: center; padding: 40px;">
                    <h3 style="color: var(--text-main);">More insights coming soon.</h3>
                    <p class="text-muted">We are currently writing new articles. Check back later!</p>
                </div>
            <?php else: ?>
                <?php
                if (!function_exists('wp_trim_words')) {
                    function wp_trim_words( $text, $num_words = 55, $more = '...' ) {
                        $words_array = preg_split( "/[\n\r\t ]+/", $text, $num_words + 1, PREG_SPLIT_NO_EMPTY );
                        if ( count( $words_array ) > $num_words ) {
                            array_pop( $words_array );
                            $text = implode( ' ', $words_array );
                            $text = $text . $more;
                        }
                        return $text;
                    }
                }
                foreach($posts as $post):
                    // Generate a brief excerpt if one isn't explicitly provided
                    $excerpt = $post['excerpt'] ?? wp_trim_words(strip_tags($post['content'] ?? ''), 20, '...');
                    $date = date('M d, Y', strtotime($post['created_at'] ?? 'now'));
                ?>
                <div class="glass-card" data-aos="fade-up" style="padding: 30px; display: flex; flex-direction: column;">
                    <div style="font-size: 0.85rem; color: var(--primary); margin-bottom: 10px; font-weight: 600;">
                        <?= $date ?> • By Tech Elevate X
                    </div>
                    <h3 style="margin-bottom: 15px; color: var(--text-main); font-size: 1.4rem;"><?= htmlspecialchars($post['title']) ?></h3>
                    <p class="text-muted" style="margin-bottom: 24px; flex-grow: 1;"><?= htmlspecialchars($excerpt) ?></p>
                    <a href="?slug=<?= urlencode($post['slug']) ?>" class="btn btn-outline" style="align-self: flex-start;">Read Article</a>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php include '../includes/footer.php'; ?>
