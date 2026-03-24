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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login - Tech Elevate X</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        body { background-color: #f4f7f6; display: flex; align-items: center; justify-content: center; height: 100vh; margin: 0; }
        .login-container { background: white; padding: 40px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); width: 100%; max-width: 400px; text-align: center; }
        .login-container h2 { margin-top: 0; color: var(--dark-color); margin-bottom: 20px; }
        .form-group { margin-bottom: 20px; text-align: left; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: 600; color: #555; }
        .form-group input { width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        .btn-login { background-color: var(--primary-color); color: white; border: none; padding: 12px 20px; width: 100%; border-radius: 4px; font-size: 1rem; cursor: pointer; transition: background 0.3s; }
        .btn-login:hover { background-color: #0b5ed7; }
        .error-msg { color: #dc3545; background: #f8d7da; padding: 10px; border-radius: 4px; margin-bottom: 15px; font-size: 0.9rem; }
        .back-link { display: inline-block; margin-top: 20px; color: #666; text-decoration: none; font-size: 0.9rem; }
        .back-link:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>User Login</h2>
        <?php if($success): ?>
            <div style="color: #155724; background: #d4edda; padding: 10px; border-radius: 4px; margin-bottom: 15px; font-size: 0.9rem;"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>
        <?php if($error): ?>
            <div class="error-msg"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <form action="login.php" method="POST">
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
        <p style="margin-top: 20px; font-size: 0.9rem;">Don't have an account? <a href="register.php" style="color: var(--primary-color); text-decoration: none;">Sign up here</a>.</p>
        <a href="../index.php" class="back-link">&larr; Back to Website</a>
    </div>
</body>
</html>
