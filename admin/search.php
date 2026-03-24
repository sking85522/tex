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
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Advanced Search - Tech Elevate X Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { margin: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f8f9fc; color: #5a5c69; display: flex; min-height: 100vh; }
        .sidebar { width: 250px; background-color: #4e73df; color: white; display: flex; flex-direction: column; }
        .sidebar-brand { padding: 20px; text-align: center; font-size: 1.2rem; font-weight: bold; border-bottom: 1px solid rgba(255,255,255,0.1); }
        .sidebar-nav { flex-grow: 1; padding: 20px 0; }
        .sidebar-nav a { display: block; padding: 15px 20px; color: rgba(255,255,255,0.8); text-decoration: none; transition: background 0.2s; }
        .sidebar-nav a:hover, .sidebar-nav a.active { background-color: rgba(255,255,255,0.1); color: white; border-left: 4px solid white; }
        .main-content { flex-grow: 1; display: flex; flex-direction: column; }
        .topbar { background-color: white; height: 70px; display: flex; justify-content: space-between; align-items: center; padding: 0 20px; box-shadow: 0 .15rem 1.75rem 0 rgba(58,59,69,.15); }
        .page-content { padding: 30px; }
        .card { background: white; border-radius: 5px; box-shadow: 0 0.15rem 1.75rem 0 rgba(58,59,69,.15); padding: 20px; margin-bottom: 20px; }
        .form-group { display:flex; gap:10px; }
        .form-group input { flex-grow:1; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-size:1.1rem; }
        .btn-primary { background: #4e73df; color: white; border: none; padding: 10px 20px; cursor: pointer; border-radius: 4px; font-size:1.1rem; }
        .result-item { padding: 15px; border-bottom: 1px solid #eee; }
        .result-item:last-child { border-bottom: none; }
        .score { font-size: 0.8rem; color: #1cc88a; font-weight: bold; background: #e3fbed; padding: 2px 5px; border-radius: 3px; }
    </style>
</head>
<body>
    <?php include 'includes/sidebar.php'; ?>
    <div class="main-content">
        <div class="topbar">
            <div><i class="fas fa-bars"></i></div>
            <div><a href="index.php?logout=true" style="color:#e74a3b; text-decoration:none;"><i class="fas fa-sign-out-alt"></i> Logout</a></div>
        </div>
        <div class="page-content">
            <h1>Advanced Ticket Search (Powered by SearchPHP)</h1>
            <div class="card">
                <form action="search.php" method="POST" class="form-group">
                    <input type="text" name="search_term" placeholder="Search across all support tickets..." required value="<?php echo htmlspecialchars($_POST['search_term'] ?? ''); ?>">
                    <button type="submit" class="btn-primary"><i class="fas fa-search"></i> Search</button>
                </form>
            </div>

            <?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
            <div class="card">
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
        </div>
    </div>
</body>
</html>
