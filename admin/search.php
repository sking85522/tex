<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

$msg = '';
$results = [];

// Fallback logic inside the page to prevent crashes since library is gitignored
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search_term'])) {
    $term = $_POST['search_term'];

    try {
        if (file_exists('../lib/sciphp/search/autoload.php')) {
            require_once '../lib/sciphp/search/autoload.php';
            $index = new \SearchPHP\Core\Index();

            $stmt = $pdo->query("SELECT id, question, user_email FROM support_tickets");
            $tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($tickets as $ticket) {
                $doc = new \SearchPHP\Core\Document($ticket['id'], $ticket['question'] . " " . $ticket['user_email']);
                $index->addDocument($doc);
            }
            $results = $index->search($term);
        } else {
            // Basic fallback SQL search if library not present
            $stmt = $pdo->prepare("SELECT id, question as match_text FROM support_tickets WHERE question LIKE ? OR user_email LIKE ?");
            $likeTerm = "%" . $term . "%";
            $stmt->execute([$likeTerm, $likeTerm]);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach($rows as $r) {
                $results[] = ['doc_id' => $r['id'], 'score' => 1.0];
            }
        }
    } catch (Exception $e) {
        $msg = "Error performing search.";
    }
}
?>
<?php include 'includes/header.php'; ?>

            <h1>Advanced Ticket Search (Powered by SearchPHP)</h1>
            <div class="card shadow-sm border-0 mb-4 p-4">
                <form action="search.php" method="POST" class="mb-3">
                    <input type="text" name="search_term" placeholder="Search across all support tickets..." required value="<?php echo htmlspecialchars($_POST['search_term'] ?? ''); ?>">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Search</button>
                </form>
            </div>

            <?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
            <div class="card shadow-sm border-0 mb-4 p-4">
                <h3>Search Results</h3>
                <?php if (empty($results)): ?>
                    <p>No results found for "<?php echo htmlspecialchars($_POST['search_term']); ?>".</p>
                <?php else: ?>
                    <?php foreach($results as $res): ?>
                        <div class="result-item">
                            <h4>Ticket ID: <?php echo htmlspecialchars($res['doc_id']); ?> <span class="score">Score: <?php echo number_format($res['score'], 4); ?></span></h4>
                            <a href="tickets.php" style="color:#4e73df; font-size:0.9rem; text-decoration:none;">Go to Tickets &rarr;</a>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        <?php include 'includes/footer.php'; ?>
