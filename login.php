<?php
session_start();
require_once 'config/config.php';
require_once 'includes/functions.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitizeInput($_POST['username']);
    $password = sanitizeInput($_POST['password']);
    
    if (login($username, $password)) {
        header('Location: admin/dashboard.php');
        exit();
    } else {
        $error = 'Invalid username or password';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NOSTROMO - Login</title>
    <link href="admin/assets/css/login.css" rel="stylesheet">
</head>
<body>
    <div class="login-container">
        <div class="logo-container">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 500 120">
                <path d="M250 10 L290 50 L250 90 L210 50 Z"/>
                <path d="M200 30 L300 30 L250 80 Z"/>
                <text x="150" y="110" font-family="monospace" font-size="14">WEYLAND-YUTANI</text>
            </svg>
        </div>
        <h1 class="title">NOSTROMO</h1>
        <?php if ($error): ?>
            <div class="error-message"><?php echo $error; ?></div>
        <?php endif; ?>
        <form method="POST" action="">
            <div class="form-group">
                <label for="username">USERNAME</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">PASSWORD</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn-login">ENTER</button>
        </form>
        <div class="password-recovery">
            <a href="#">PASSWORD RECOVERY</a>
        </div>
    </div>
</body>
</html>