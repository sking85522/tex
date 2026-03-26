<?php
session_start();
include '../includes/db.php';
require_once '../includes/ai_engine.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

$role = $_SESSION['admin_role'] ?? 'super_admin';
if ($role !== 'super_admin') {
    die("Access Denied. Only Super Admins can manage blogs.");
}

$msg = '';
$aiEngine = new AIEngine($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'add') {
        $slug = strtolower(preg_replace('/[^A-Za-z0-9-]+/', '-', $_POST['title']));
        try {
            $stmt = $pdo->prepare("INSERT INTO blogs (title, slug, content, meta_keywords, meta_description, image_url, author, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$_POST['title'], $slug, $_POST['content'], $_POST['meta_keywords'], $_POST['meta_description'], $_POST['image_url'], $_SESSION['admin_username'], $_POST['status']]);
            $msg = "Blog post published successfully.";
    // Simulate Push Notification payload generation
    echo '<script>
        if (Notification.permission === "granted") {
            new Notification("New Tech Insight Published!", {
                body: "We just posted a new article on Tech Elevate X.",
                icon: "../assets/img/logo.png"
            });
        }
    </script>';

        } catch (PDOException $e) { $msg = "Error publishing blog: " . $e->getMessage(); }
    } elseif ($_POST['action'] === 'delete') {
        try {
            $stmt = $pdo->prepare("DELETE FROM blogs WHERE id = ?");
            $stmt->execute([$_POST['id']]);
            $msg = "Blog post removed.";
        } catch (PDOException $e) { $msg = "Error removing data."; }
    } elseif ($_POST['action'] === 'ai_generate') {
        $topic = $_POST['ai_topic'];
        $generatedContent = $aiEngine->generateBlogContent($topic);
        $slug = strtolower(preg_replace('/[^A-Za-z0-9-]+/', '-', $topic));
        try {
            $stmt = $pdo->prepare("INSERT INTO blogs (title, slug, content, meta_keywords, meta_description, image_url, author, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $topic,
                $slug . '-' . rand(10,99),
                $generatedContent,
                str_replace(' ', ', ', $topic) . ', AI, Tech, Trends',
                'A comprehensive guide about ' . $topic,
                'https://source.unsplash.com/random/600x400/?technology,ai',
                'AI Engine',
                'published'
            ]);
            $msg = "AI Auto-Blog generated and published!";
        } catch (PDOException $e) { $msg = "Error generating AI blog: " . $e->getMessage(); }
    }
}

$blogs = [];
try {
    $stmt = $pdo->query("SELECT * FROM blogs ORDER BY id DESC");
    $blogs = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {}
$current_page = basename($_SERVER['PHP_SELF']);
?>
<?php include 'includes/header.php'; ?>

            <h1>Blog & SEO Management</h1>
            <?php if($msg) echo "<div class='alert'>$msg</div>"; ?>

            <div class="card shadow-sm border-0 mb-4 p-4">
                <h3>Write New Post</h3>
                <form action="blog.php" method="POST" style="display: flex; flex-wrap: wrap; gap: 15px;">
                    <input type="hidden" name="action" value="add">
                    <div class="mb-3" style="flex: 1; min-width: 200px;">
                        <label>Blog Title (Auto SEO Slug)</label><input type="text" name="title" required>
                    </div>
                    <div class="mb-3" style="flex: 1; min-width: 200px;">
                        <label>Feature Image URL</label><input type="url" name="image_url" placeholder="https://..." required>
                    </div>
                    <div class="mb-3" style="flex: 1; min-width: 150px;">
                        <label>Visibility Status</label>
                        <select name="status"><option value="published">Published</option><option value="draft">Draft</option></select>
                    </div>
                    <div class="mb-3" style="flex: 100%;">
                        <label>Content (HTML/Markdown)</label><textarea name="content" required rows="5"></textarea>
                    </div>
                    <div class="mb-3" style="flex: 1; min-width: 250px;">
                        <label>SEO Keywords (Comma Separated)</label><input type="text" name="meta_keywords" placeholder="AI, Tech, Software">
                    </div>
                    <div class="mb-3" style="flex: 1; min-width: 250px;">
                        <label>SEO Meta Description</label><input type="text" name="meta_description" placeholder="A short 150 char description for Google.">
                    </div>
                    <button type="submit" class="btn btn-primary" style="flex: 100%;">Publish AI/Tech Blog</button>
                </form>
            </div>

            <div class="card shadow-sm border-0 mb-4 p-4">
                <h3>Published Posts</h3>
                <table class="table table-hover align-middle">
                    <thead class="table-light"><tr><th>ID</th><th>Title</th><th>Status</th><th>Date</th><th>Action</th></tr>
                    </thead>
                    <tbody>
                        <?php if(empty($blogs)): ?>
                            <tr><td colspan="5" style="text-align:center;">No blog posts found.</td></tr>
                        <?php else: foreach($blogs as $blog): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($blog['id']); ?></td>
                            <td><a href="../blog.php?slug=<?php echo htmlspecialchars($blog['slug']); ?>" target="_blank" style="text-decoration:none; color:#4e73df; font-weight:bold;"><?php echo htmlspecialchars($blog['title']); ?></a></td>
                            <td><span style="background:<?php echo $blog['status'] == 'published' ? '#1cc88a' : '#f6c23e'; ?>; color:white; padding:3px 8px; border-radius:10px; font-size:0.8rem;"><?php echo strtoupper(htmlspecialchars($blog['status'])); ?></span></td>
                            <td><?php echo date('M d, Y', strtotime($blog['created_at'])); ?></td>
                            <td>
                                <form action="blog.php" method="POST" onsubmit="return confirm('Delete this post?');">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($blog['id']); ?>">
                                    <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i> Delete</button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; endif; ?>
                    </tbody>
                </table>
            </div>
        <?php include 'includes/footer.php'; ?>
