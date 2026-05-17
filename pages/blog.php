<?php
require_once '../includes/header.php';

// Basic mock blog data since we don't have a full DB blog system setup yet.
// In a real scenario, this would load from $pdo or a JSON file.
$posts = [
    [
        'title' => 'Why Static Websites are Making a Comeback',
        'slug' => 'static-websites-comeback',
        'excerpt' => 'Discover how modern tooling and performance demands are bringing static sites back to the forefront of web development.',
        'date' => '2023-10-15',
        'author' => 'Tech Elevate X Team'
    ],
    [
        'title' => 'The Future of Mobile App Development with Flutter',
        'slug' => 'future-mobile-app-flutter',
        'excerpt' => 'Analyze the cross-platform advantages and performance metrics of using Flutter for enterprise mobile applications.',
        'date' => '2023-11-02',
        'author' => 'Tech Elevate X Team'
    ],
    [
        'title' => 'Optimizing Enterprise Cloud Architecture',
        'slug' => 'optimizing-cloud-architecture',
        'excerpt' => 'Learn the best practices for scaling your server infrastructure while keeping costs low and uptime high.',
        'date' => '2023-11-20',
        'author' => 'Tech Elevate X Team'
    ]
];
?>

<section class="page-header" style="background: var(--bg-deep); padding: 120px 0 60px; text-align: center; border-bottom: 1px solid var(--glass-border);">
    <div class="container">
        <h1 class="text-gradient" style="font-size: 3.5rem;">Insights & Engineering</h1>
        <p class="text-muted" style="max-width: 700px; margin: 20px auto; font-size: 1.1rem;">Read our latest thoughts on software engineering, web development, and digital transformation.</p>
    </div>
</section>

<section style="padding: 80px 0; background: var(--bg-main);">
    <div class="container">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 30px;">
            <?php foreach($posts as $post): ?>
            <div class="glass-card" data-aos="fade-up" style="padding: 30px; display: flex; flex-direction: column;">
                <div style="font-size: 0.85rem; color: var(--primary); margin-bottom: 10px; font-weight: 600;">
                    <?= $post['date'] ?> • By <?= $post['author'] ?>
                </div>
                <h3 style="margin-bottom: 15px; color: var(--text-main); font-size: 1.4rem;"><?= htmlspecialchars($post['title']) ?></h3>
                <p class="text-muted" style="margin-bottom: 24px; flex-grow: 1;"><?= htmlspecialchars($post['excerpt']) ?></p>
                <a href="#" class="btn btn-outline" style="align-self: flex-start;">Read Article</a>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php include '../includes/footer.php'; ?>
