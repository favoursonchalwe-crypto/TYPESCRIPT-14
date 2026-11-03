<?php
require_once __DIR__ . '/recovery-common.php';
$message = ''; $error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!recovery_check_csrf()) $error = 'Your session expired. Refresh the page and try again.';
    elseif (recovery_rate_limited($pdo, $_SERVER['REMOTE_ADDR'] ?? 'unknown')) $error = 'Too many attempts. Please wait 15 minutes before trying again.';
    else {
        $method = ($_POST['method'] ?? '') === 'sms' ? 'sms' : 'email';
        $identity = trim($_POST['identity'] ?? '');
        if ($identity === '') $error = 'Enter your registered email address or phone number.';
        else {
            if ($method === 'email') {
                $q = $pdo->prepare('SELECT id,email FROM users WHERE email = ? LIMIT 1'); $q->execute([strtolower($identity)]); $user = $q->fetch();
                if ($user) {
                    $token = bin2hex(random_bytes(32)); recovery_save($pdo, (int)$user['id'], 'email', $token, $_SERVER['REMOTE_ADDR'] ?? 'unknown');
                    $link = recovery_base_url() . '/reset-password.php?token=' . urlencode($token);
                    $subject = 'Reset your Green Urban Farms password';
                    $body = "We received a request to reset your password. Use this secure link within 30 minutes:\n\n" . $link . "\n\nIf you did not request this, ignore this email.";
                    $headers = 'From: ' . RECOVERY_FROM_EMAIL . "\r\n" . 'Content-Type: text/plain; charset=UTF-8';
                    @mail($user['email'], $subject, $body, $headers);
                }
            } else {
                // Normalize common Zambian local mobile format to +260.
                $phone = preg_replace('/[\s()-]/', '', $identity);
                if (preg_match('/^0[97][0-9]{8}$/', $phone)) $phone = '+260' . substr($phone, 1);
                elseif (preg_match('/^260[97][0-9]{8}$/', $phone)) $phone = '+' . $phone;
                $q = $pdo->prepare('SELECT id,phone FROM users WHERE phone = ? LIMIT 1'); $q->execute([$phone]); $user = $q->fetch();
                if ($user) {
                    $code = (string)random_int(100000, 999999); recovery_save($pdo, (int)$user['id'], 'sms', $code, $_SERVER['REMOTE_ADDR'] ?? 'unknown');
                    recovery_send_sms($phone, 'Green Urban Farms password reset code: ' . $code . '. It expires in 30 minutes. Do not share it.');
                }
            }
            // Do not disclose whether an account exists.
            $message = $method === 'email' ? 'If the details match an account, a reset link has been sent. Check your inbox and spam folder.' : 'If the details match an account, a verification code has been sent. In Sandbox, check the Africa’s Talking simulator inbox.';
        }
    }
}
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Forgot Password | Green Urban Farms</title><link rel="stylesheet" href="../css/style.css"></head><body>
<?php include __DIR__ . '/header.php'; ?><main><section class="page-hero page-banner page-banner-login"><section class="container"><p class="page-kicker">ACCOUNT SUPPORT</p><h1>Recover your password</h1><p class="lead">Choose email or SMS to securely regain access to your account.</p></section></section><section class="section section-soft"><section class="container"><section class="form-card auth-card"><h2>Forgot Password</h2><?php if($error): ?><p role="alert"><?=h($error)?></p><?php endif; ?><?php if($message): ?><p role="status"><?=h($message)?></p><?php if(($_POST['method']??'')==='sms'): ?><p><a href="verify-reset-code.php">Enter your SMS verification code</a></p><?php endif; ?><?php endif; ?><form method="post" class="form"><input type="hidden" name="csrf" value="<?=h(recovery_csrf())?>"><section class="field"><label for="method">Recovery method</label><select id="method" name="method"><option value="email">Email reset link</option><option value="sms">SMS verification code</option></select></section><section class="field"><label for="identity">Registered email or mobile number</label><input id="identity" name="identity" required autocomplete="email" placeholder="Email address or +260..." /></section><button class="btn btn-primary" type="submit">Continue</button></form><p><a href="login.php">← Back to Login</a></p></section></section></section></main><?php include __DIR__ . '/footer.php'; ?></body></html>
