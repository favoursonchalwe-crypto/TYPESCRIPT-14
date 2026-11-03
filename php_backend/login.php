<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
require_once __DIR__ . '/db.php';
if (!empty($_SESSION['user_id'])) {
    header('Location: ' . (($_SESSION['user_role'] ?? '') === 'admin' ? 'admin.php' : 'index.php'));
    exit;
}
$error = '';
$notice = '';
$mode = ((($_POST['action'] ?? '') === 'register') || (($_GET['action'] ?? '') === 'register') || (isset($_GET['register']))) ? 'register' : 'login';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = strtolower(trim($_POST['email'] ?? ''));
    $password = $_POST['password'] ?? '';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || $password === '') $error = 'Enter a valid email and password.';
    elseif ($mode === 'register') {
        $name = trim($_POST['name'] ?? '');
        if ($name === '') $error = 'Enter your name.';
        elseif (strlen($password) < 8) $error = 'Password must be at least 8 characters.';
        else {
            $q = $pdo->prepare('SELECT id FROM users WHERE email=?');
            $q->execute([$email]);
            if ($q->fetch()) {
                $error = 'That email already has an account.';
                $mode = 'login';
            } else {
                $q = $pdo->prepare('INSERT INTO users (name,email,password,role) VALUES (?,?,?,?)');
                $q->execute([$name, $email, password_hash($password, PASSWORD_DEFAULT), 'user']);
                $notice = 'Account created. Please log in.';
                $mode = 'login';
            }
        }
    } else {
        $q = $pdo->prepare('SELECT id,name,email,password,role FROM users WHERE email=? LIMIT 1');
        $q->execute([$email]);
        $u = $q->fetch();
        if ($u && password_verify($password, $u['password'])) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = (int)$u['id'];
            $_SESSION['user_name'] = $u['name'];
            $_SESSION['user_email'] = $u['email'];
            $_SESSION['user_role'] = $u['role'];
            header('Location: ' . ($u['role'] === 'admin' ? 'admin.php' : 'index.php'));
            exit;
        }
        $error = 'Email or password is incorrect.';
    }
}
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Login / Register | Green Urban Farms</title>
    <link rel="stylesheet" href="../css/style.css">
    <script src="../js/script.js" defer></script>
</head>

<body>
    <?php include __DIR__ . '/header.php'; ?><main>
        <section class="page-hero page-banner page-banner-login">
            <section class="container">
                <p class="page-kicker">YOUR GROWING JOURNEY</p>
                <h1><?= $mode === 'register' ? 'Create your account' : 'Welcome back' ?></h1>
                <p class="lead">Connect with Green Urban Farms and take part in practical learning and community growing opportunities.</p>
            </section>
        </section>
        <section class="section section-soft">
            <section class="container">
                <section class="form-card auth-card"><a href="index.php" class="text-green">← Back to Home</a>
                    <h2><?= $mode === 'register' ? 'Create an Account' : 'Log In' ?></h2><?php if ($error): ?><p role="alert"><?= htmlspecialchars($error) ?></p><?php endif; ?><?php if ($notice): ?><p role="status"><?= htmlspecialchars($notice) ?></p><?php endif; ?><form method="post" class="form"><input type="hidden" name="action" value="<?= $mode === 'register' ? 'register' : 'login' ?>"><?php if ($mode === 'register'): ?><section class="field"><label for="auth-name">Full name</label><input id="auth-name" name="name" autocomplete="name" required></section><?php endif; ?><section class="field"><label for="auth-email">Email address</label><input id="auth-email" type="email" name="email" autocomplete="email" required></section>
                        <section class="field"><label for="auth-password">Password</label><input id="auth-password" type="password" name="password" minlength="8" autocomplete="<?= $mode === 'register' ? 'new-password' : 'current-password' ?>" required></section><button class="btn btn-primary" type="submit"><?= $mode === 'register' ? 'Create Account' : 'Log In' ?></button>
                    </form>
                    <p><?php if ($mode === 'register'): ?>Already registered? <a href="login.php">Log in</a><?php else: ?>New to Green Urban Farms? <a href="login.php?action=register">Create an account</a><?php endif; ?></p>
                </section>
            </section>
        </section>
    </main><?php include __DIR__ . '/footer.php'; ?></body>

</html>