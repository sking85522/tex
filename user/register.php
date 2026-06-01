<?php
session_start();
include '../includes/db.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if (!empty($username) && !empty($email) && !empty($password)) {
        if ($password !== $confirm_password) {
            $error = 'Passwords do not match.';
        } else {
            $clientsFile = __DIR__ . '/../data/clients.json';
            $clients = file_exists($clientsFile) ? json_decode(file_get_contents($clientsFile), true) : [];
            if (!is_array($clients)) $clients = [];

            $exists = false;
            foreach ($clients as $client) {
                if ($client['username'] === $username || $client['email'] === $email) {
                    $exists = true;
                    break;
                }
            }

            if ($exists) {
                $error = 'Username or Email already exists.';
            } else {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $clients[] = [
                    'id' => uniqid(),
                    'username' => $username,
                    'email' => $email,
                    'password' => $hashed_password,
                    'created_at' => date('Y-m-d H:i:s')
                ];

                if (file_put_contents($clientsFile, json_encode($clients, JSON_PRETTY_PRINT))) {
                    $_SESSION['register_success'] = "Account created successfully! You can now log in.";
                    header("Location: login.php");
                    exit();
                } else {
                    $error = 'Failed to create account. Please try again.';
                }
            }
        }
    } else {
        $error = 'Please fill out all required fields.';
    }
}

require_once '../includes/header.php';
?>

<section style="padding: 180px 0 100px; background: var(--bg-deep); min-height: 80vh; display: flex; align-items: center; justify-content: center;">
    <div class="glass-card login-container" style="max-width: 400px; width: 100%; padding: 40px; margin: 0 auto; text-align: center;">
        <h2>Create an Account</h2>
        <?php if($error): ?>
            <div class="error-msg"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <form action="register.php" method="POST">
            <div class="form-group">
                <label for="username">Username *</label>
                <input type="text" id="username" name="username" required value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label for="email">Email *</label>
                <input type="email" id="email" name="email" required value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label for="password">Password *</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm Password *</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>
            <button type="submit" class="btn-login">Sign Up</button>
        </form>
        <p style="margin-top: 20px; font-size: 0.9rem;">Already have an account? <a href="login.php" style="color: var(--primary); text-decoration: none;">Login here</a>.</p>
        <a href="../index.php" class="back-link" style="display: inline-block; margin-top: 20px; color: var(--text-muted); text-decoration: none; font-size: 0.9rem;">&larr; Back to Website</a>
    </div>
</section>

<?php require_once '../includes/footer.php'; ?>
