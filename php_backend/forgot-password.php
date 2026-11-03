<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
require_once __DIR__ . '/DB.php';
$message = '';
$error = '';
$method = ($_SERVER['REQUEST_METHOD'] === 'POST') ? ($_POST['method'] ?? 'email') : 'email';
if (!in_array($method, ['email', 'phone'], true)) $method = 'email';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $identifier = trim($_POST['identifier'] ?? '');
    if ($identifier === '') $error = 'Enter the email address or phone number registered to your account.';
    elseif ($method === 'email' && !filter_var($identifier, FILTER_VALIDATE_EMAIL)) $error = 'Enter a valid email address.';
    else {
        $user = false;
        try {
            if ($method === 'email') {
                $stmt = $pdo->prepare('SELECT id, name, email FROM users WHERE email = ? LIMIT 1');
                $stmt->execute([strtolower($identifier)]);
            } else {
                $stmt = $pdo->prepare('SELECT id, name, email FROM users WHERE phone = ? LIMIT 1');
                $stmt->execute([$identifier]);
            }
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Password recovery lookup failed: ' . $e->getMessage());
            $error = ($method === 'phone') ? 'Phone recovery needs the database update in password_recovery_setup.sql. Run that update, or choose email recovery.' : 'We could not process the request right now. Please try again later.';
        }
        if (!$error) {
            $message = 'If the details match an account, a password reset link will be sent to the email address registered to that account. Check your inbox and spam folder.';
            if ($user) {
                try {
                    $pdo->exec("CREATE TABLE IF NOT EXISTS password_resets (id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, user_id INT(10) UNSIGNED NOT NULL, token_hash CHAR(64) NOT NULL UNIQUE, expires_at DATETIME NOT NULL, used_at DATETIME NULL, created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, INDEX (user_id), INDEX (expires_at)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
                    $token = bin2hex(random_bytes(32));
                    $stmt = $pdo->prepare('INSERT INTO password_resets (user_id, token_hash, expires_at) VALUES (?, ?, DATE_ADD(NOW(), INTERVAL 1 HOUR))');
                    $stmt->execute([(int)$user['id'], hash('sha256', $token)]);
                    $https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
                    $scheme = $https ? 'https' : 'http';
                    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
                    $basePath = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
                    $resetUrl = $scheme . '://' . $host . $basePath . '/reset-password.php?token=' . urlencode($token);
                    $subject = 'Reset your Green Urban Farms password';
                    $body = "Hello " . ($user['name'] ?: 'there') . ",\n\nA password reset was requested for your Green Urban Farms account. Use the link below within one hour:\n\n" . $resetUrl . "\n\nIf you did not request this, you can ignore this email.\n";
                    $from = getenv('GUF_RESET_FROM_EMAIL') ?: ('no-reply@' . preg_replace('/^www\./', '', $host));
                    $headers = "From: Green Urban Farms <{$from}>\r\nContent-Type: text/plain; charset=UTF-8";
                    if (!@mail($user['email'], $subject, $body, $headers)) error_log('Password reset email could not be sent. Configure PHP mail/SMTP for this host.');
                } catch (Throwable $e) {
                    error_log('Password reset request failed: ' . $e->getMessage());
                }
            }
        }
    }
}
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Forgot Password | Green Urban Farms</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/auth.css">
</head>

<body>
    <main class="container section">
        <section class="recovery-card"><a href="login.php">← Back to login</a>
            <h1>Forgot your password?</h1>
            <p>Find your account using the email address or phone number you registered with. For security, the reset link is sent to the email address saved on your account.</p>
            <?php if ($error !== ''): ?><p class="error" role="alert"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p><?php endif; ?>
            <?php if ($message !== ''): ?><p class="message" role="status"><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></p><?php endif; ?>
            <form method="post" action="forgot-password.php" class="form">
                <section class="field"><label for="recovery-method">Recover account using</label><select id="recovery-method" name="method">
                        <option value="email" <?= $method === 'email' ? 'selected' : '' ?>>Email address</option>
                        <option value="phone" <?= $method === 'phone' ? 'selected' : '' ?>>Phone number</option>
                    </select></section>
                <section class="field"><label for="identifier" id="identifier-label"><?= $method === 'phone' ? 'Registered phone number' : 'Registered email address' ?></label><input id="identifier" name="identifier" value="<?= htmlspecialchars($_POST['identifier'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required autocomplete="<?= $method === 'phone' ? 'tel' : 'email' ?>" <?= $method === 'phone' ? 'type="tel"' : 'type="email"' ?> maxlength="190"></section><button class="btn btn-primary" type="submit">Send password reset link</button>
            </form>
        </section>
    </main>
    <script src="../js/recovery.js" defer></script>
</body>

</html>