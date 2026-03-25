<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($_POST as $key => $value) {
        if (strpos($key, 'setting_') === 0) {
            $setting_key = substr($key, 8);
            try {
                $stmt = $pdo->prepare("UPDATE site_settings SET setting_value = ? WHERE setting_key = ?");
                $stmt->execute([$value, $setting_key]);
            } catch (PDOException $e) {
                // Handle error
            }
        }
    }
    $msg = "Settings updated successfully!";
}

$settings = [];
try {
    $stmt = $pdo->query("SELECT setting_key, setting_value FROM site_settings");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $settings[$row['setting_key']] = $row['setting_value'];
    }
} catch (PDOException $e) { }

?>
<?php include 'includes/header.php'; ?>

            <h1>Site Settings (CMS)</h1>
            <?php if(isset($msg)) echo "<div class='alert-success'>$msg</div>"; ?>
            <div class="card shadow-sm border-0 mb-4 p-4">
                <form action="" method="POST">
                    <h3>Home Page</h3>
                    <div class="mb-3">
                        <label>Hero Title</label>
                        <input type="text" name="setting_home_hero_title" value="<?php echo htmlspecialchars($settings['home_hero_title'] ?? ''); ?>">
                    </div>
                    <div class="mb-3">
                        <label>Hero Subtitle</label>
                        <textarea name="setting_home_hero_subtitle" rows="3"><?php echo htmlspecialchars($settings['home_hero_subtitle'] ?? ''); ?></textarea>
                    </div>

                    <h3>About Page</h3>
                    <div class="mb-3">
                        <label>About Heading</label>
                        <input type="text" name="setting_about_heading" value="<?php echo htmlspecialchars($settings['about_heading'] ?? ''); ?>">
                    </div>
                    <div class="mb-3">
                        <label>About Content</label>
                        <textarea name="setting_about_content" rows="4"><?php echo htmlspecialchars($settings['about_content'] ?? ''); ?></textarea>
                    </div>

                    <h3>Contact Info</h3>
                    <div class="mb-3">
                        <label>Email</label>
                        <input type="email" name="setting_contact_email" value="<?php echo htmlspecialchars($settings['contact_email'] ?? ''); ?>">
                    </div>
                    <div class="mb-3">
                        <label>Phone</label>
                        <input type="text" name="setting_contact_phone" value="<?php echo htmlspecialchars($settings['contact_phone'] ?? ''); ?>">
                    </div>
                    <div class="mb-3">
                        <label>Address</label>
                        <input type="text" name="setting_contact_address" value="<?php echo htmlspecialchars($settings['contact_address'] ?? ''); ?>">
                    </div>

                    <button type="submit" class="btn btn-primary">Save Settings</button>
                </form>
            </div>
        <?php include 'includes/footer.php'; ?>
