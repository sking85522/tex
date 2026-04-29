<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'add') {
        $stmt = $pdo->prepare("INSERT INTO portfolio (title, description, category, image_url, demo_url) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$_POST['title'], $_POST['description'], $_POST['category'], $_POST['image_url'], $_POST['demo_url']]);
    } elseif ($_POST['action'] === 'delete') {
        $stmt = $pdo->prepare("DELETE FROM portfolio WHERE id = ?");
        $stmt->execute([$_POST['id']]);
    }

    elseif ($_POST['action'] === 'ai_generate') {
        require_once __DIR__ . '/../brain/core/Autoloader.php';
        try {
            if (class_exists('Core\AIEngine')) {
                $engine = new \Core\Engine();
                $topic = $_POST['ai_topic'];
                $prompt = "Generate a portfolio project description for a project named '" . $topic . "'. Keep it under 2 sentences.";
                $res = $engine->processPrompt($prompt);
                $desc = $res['response'] ?? 'A highly optimized digital solution built by Tech Elevate X.';

                if (str_contains($desc, 'I have not learned')) {
                    $desc = "A state-of-the-art software solution tailored for business automation and scalability, developed natively by Tech Elevate X.";
                }

                $stmt = $pdo->prepare("INSERT INTO portfolio (title, description, category, image_url, demo_url) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$topic, $desc, 'AI Generated', 'https://source.unsplash.com/random/400x300/?technology,software', '#']);
            }
        } catch (\Exception $e) {}
    }
}

$portfolio = [];
try {
    $stmt = $pdo->query("SELECT * FROM portfolio ORDER BY id DESC");
    $portfolio = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) { }
?>
<?php include 'includes/header.php'; ?>

            <h1>Manage Portfolio</h1>


            <div class="card shadow-sm border-info mb-4 p-4">
                <h3 class="text-info"><i class="bi bi-magic"></i> Auto-Generate Portfolio Case Study</h3>
                <form action="portfolio.php" method="POST" style="display: flex; gap: 10px; align-items: center;">
                    <input type="hidden" name="action" value="ai_generate">
                    <input type="text" name="ai_topic" class="form-control" placeholder="Project Name (e.g., E-Commerce Platform)" required>
                    <button type="submit" class="btn btn-info text-white" style="min-width: 150px;">Generate Case Study</button>
                </form>
            </div>

            <div class="card shadow-sm border-0 mb-4 p-4">
                <h3>Add New Project</h3>
                <form action="portfolio.php" method="POST" style="display: flex; flex-wrap: wrap; gap: 15px;">
                    <input type="hidden" name="action" value="add">
                    <div class="mb-3" style="flex: 1; min-width: 200px;"><input type="text" name="title" placeholder="Project Title" required></div>
                    <div class="mb-3" style="flex: 1; min-width: 200px;"><input type="text" name="category" placeholder="Category (e.g., Web Dev)" required></div>
                    <div class="mb-3" style="flex: 1; min-width: 200px;"><input type="url" name="image_url" placeholder="Image URL"></div>
                    <div class="mb-3" style="flex: 1; min-width: 200px;"><input type="url" name="demo_url" placeholder="Demo URL (Live Link)"></div>
                    <div class="mb-3" style="flex: 100%;"><textarea name="description" placeholder="Short description" rows="2" required></textarea></div>
                    <button type="submit" class="btn btn-primary">Add Project</button>
                </form>
            </div>

            <div class="card shadow-sm border-0 mb-4 p-4">
                <h3>Existing Projects</h3>
                <table class="table table-hover align-middle">
                    <thead class="table-light"><tr><th>ID</th><th>Title</th><th>Category</th><th>Demo</th><th>Action</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach($portfolio as $p): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($p['id']); ?></td>
                            <td><?php echo htmlspecialchars($p['title']); ?></td>
                            <td><?php echo htmlspecialchars($p['category']); ?></td>
                            <td><a href="<?php echo htmlspecialchars($p['demo_url']); ?>" target="_blank">Link</a></td>
                            <td>
                                <form action="portfolio.php" method="POST" style="display:inline;">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($p['id']); ?>">
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?');">Delete</button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php include 'includes/footer.php'; ?>
