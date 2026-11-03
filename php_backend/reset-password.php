<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
require_once __DIR__ . '/DB.php';
$token = trim($_GET['token'] ?? $_POST['token'] ?? '');
$error = '';
$success = '';
$reset = false;
if ($token !== '' && preg_match('/^[a-f0-9]{64}$/', $token)) {
    try {
        $stmt = $pdo->prepare('SELECT id, user_id FROM password_resets WHERE token_hash = ? AND used_at IS NULL AND expires_at > NOW() LIMIT 1');
        $stmt->execute([hash('sha256', $token)]);
        $reset = $stmt->fetch(PDO::FETCH_ASSOC) ?: false;
    } catch (PDOException $e) {
        error_log('Password reset token lookup failed: ' . $e->getMessage());
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';
    if (!$reset) $error = 'This reset link is invalid, expired, or has already been used. Please request a new one.';
    elseif (strlen($password) < 8) $error = 'Your new password must be at least 8 characters long.';
    elseif ($password !== $confirm) $error = 'The passwords do not match.';
    else {
        try {
            $pdo->beginTransaction();
            $update = $pdo->prepare('UPDATE users SET password = ? WHERE id = ?');
            $update->execute([password_hash($password, PASSWORD_DEFAULT), (int)$reset['user_id']]);
            $used = $pdo->prepare('UPDATE password_resets SET used_at = NOW() WHERE id = ? AND used_at IS NULL');
            $used->execute([(int)$reset['id']]);
            $pdo->commit();
            $success = 'Your password has been reset successfully. You can now log in.';
            $reset = false;
        } catch (Throwable $e) {
            if ($pdo->inTransaction()) $pdo->rollBack();
            error_log('Password reset update failed: ' . $e->getMessage());
            $error = 'We could not reset your password. Please request a new link and try again.';
        }
    }
}
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reset Password | Green Urban Farms</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/auth.css">
</head>

<body>
    <main class="container section">
        <section class="recovery-card">
            <h1>Choose a new password</h1>
            <?php if ($error !== ''): ?><p class="error" role="alert"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p><?php endif; ?>
            <?php if ($success !== ''): ?><p class="message" role="status"><?= htmlspecialchars($success, ENT_QUOTES, 'UTF-8') ?></p>
                <p><a class="btn btn-primary" href="login.php">Go to login</a></p>
            <?php elseif ($reset): ?><p>Choose a strong password with at least 8 characters.</p>
                <form method="post" action="reset-password.php"><input type="hidden" name="token" value="<?= htmlspecialchars($token, ENT_QUOTES, 'UTF-8') ?>"><label for="password">New password</label><input id="password" name="password" type="password" minlength="8" autocomplete="new-password" required><label for="confirm-password">Confirm new password</label><input id="confirm-password" name="confirm_password" type="password" minlength="8" autocomplete="new-password" required><button class="btn btn-primary" type="submit">Reset password</button></form>
            <?php else: ?><p>This reset link is invalid, expired, or has already been used. Please request a new one.</p><a href="forgot-password.php">Request a new reset link</a><?php endif; ?>
        </section>
    </main>
</body>

</html>