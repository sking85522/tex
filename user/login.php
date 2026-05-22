<?php
session_start();
include '../includes/db.php';

$error = '';
$success = '';

if (isset($_SESSION['register_success'])) {
    $success = $_SESSION['register_success'];
    unset($_SESSION['register_success']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if (!empty($username) && !empty($password)) {
        try {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
            $stmt->execute([$username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // In our DB seed, 'user' has password 'password'
            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                header("Location: index.php");
                exit();
            } else {
                $error = 'Invalid username or password.';
            }
        } catch(PDOException $e) {
            $error = 'Database error. Please try again later.';
        }
    } else {
        $error = 'Please enter both username and password.';
    }
}

require_once '../includes/header.php';
?>

<section style="padding: 180px 0 100px; background: var(--bg-deep); min-height: 80vh; display: flex; align-items: center; justify-content: center;">
    <div class="glass-card login-container" style="max-width: 400px; width: 100%; padding: 40px; margin: 0 auto; text-align: center;">
        <h2>User Login</h2>
        <?php if($success): ?>
            <div style="color: #155724; background: #d4edda; padding: 10px; border-radius: 4px; margin-bottom: 15px; font-size: 0.9rem;"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>
        <?php if($error): ?>
            <div class="error-msg"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <form action="login.php" method="POST" id="login-form">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="btn-login">Login</button>
        </form>
        <p style="margin-top: 20px; font-size: 0.9rem;">Don't have an account? <a href="register.php" style="color: var(--primary); text-decoration: none;">Sign up here</a>.</p>
        <a href="../index.php" class="back-link" style="display: inline-block; margin-top: 20px; color: var(--text-muted); text-decoration: none; font-size: 0.9rem;">&larr; Back to Website</a>
    </div>
</section>

<?php require_once '../includes/footer.php'; ?>
