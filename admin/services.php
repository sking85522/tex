<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_SESSION['admin_role']) && $_SESSION['admin_role'] !== 'super_admin') {
    die("Access Denied.");
}

$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'add') {
        try {
            $stmt = $pdo->prepare("INSERT INTO services (title, description, icon, price_inr, price_usd) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$_POST['title'], $_POST['description'], $_POST['icon'], $_POST['price_inr'], $_POST['price_usd']]);
            $msg = "Service added successfully.";
        } catch (PDOException $e) {
            $msg = "Error adding service.";
        }
    } elseif ($_POST['action'] === 'delete') {
        try {
            $stmt = $pdo->prepare("DELETE FROM services WHERE id = ?");
            $stmt->execute([$_POST['service_id']]);
            $msg = "Service successfully removed.";
        } catch (PDOException $e) {
            $msg = "Error deleting service.";
        }
    }
}

$services = [];
try {
    $stmt = $pdo->query("SELECT * FROM services ORDER BY id ASC");
    $services = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {}
?>
<?php include 'includes/header.php'; ?>

            <h1>Manage IT Services & Pricing</h1>
            <?php if($msg) echo "<div class='alert'>$msg</div>"; ?>

            <div class="card shadow-sm border-0 mb-4 p-4">
                <h3>Add New Service</h3>
                <form action="services.php" method="POST" style="display: flex; flex-wrap: wrap; gap: 15px;">
                    <input type="hidden" name="action" value="add">
                    <div class="mb-3" style="flex: 1; min-width: 200px;">
                        <input type="text" name="title" placeholder="Service Title (e.g. App Dev)" required>
                    </div>
                    <div class="mb-3" style="flex: 1; min-width: 150px;">
                        <input type="text" name="icon" placeholder="FontAwesome Class (e.g. fa-mobile-alt)" required>
                    </div>
                    <div class="mb-3" style="flex: 1; min-width: 150px;">
                        <input type="number" step="0.01" name="price_inr" placeholder="Price in ₹ (INR)" required>
                    </div>
                    <div class="mb-3" style="flex: 1; min-width: 150px;">
                        <input type="number" step="0.01" name="price_usd" placeholder="Price in $ (USD)" required>
                    </div>
                    <div class="mb-3" style="flex: 100%;">
                        <textarea name="description" placeholder="Service Description..." required rows="2"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Service</button>
                </form>
            </div>

            <div class="card shadow-sm border-0 mb-4 p-4">
                <h3>Current Services List</h3>
                <table class="table table-hover align-middle">
                    <thead class="table-light"><tr><th>ID</th><th>Icon</th><th>Title</th><th>Price (₹)</th><th>Price ($)</th><th>Action</th></tr>
                    </thead>
                    <tbody>
                        <?php if(empty($services)): ?>
                            <tr><td colspan="6" style="text-align:center;">No services found.</td></tr>
                        <?php else: foreach($services as $s): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($s['id']); ?></td>
                            <td><i class="fas <?php echo htmlspecialchars($s['icon']); ?>"></i></td>
                            <td><strong><?php echo htmlspecialchars($s['title']); ?></strong></td>
                            <td style="color:green; font-weight:bold;">₹<?php echo number_format($s['price_inr']); ?></td>
                            <td style="color:blue; font-weight:bold;">$<?php echo number_format($s['price_usd']); ?></td>
                            <td>
                                <form action="services.php" method="POST" onsubmit="return confirm('Delete this service?');">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="service_id" value="<?php echo htmlspecialchars($s['id']); ?>">
                                    <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i> Delete</button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; endif; ?>
                    </tbody>
                </table>
            </div>
        <?php include 'includes/footer.php'; ?>
