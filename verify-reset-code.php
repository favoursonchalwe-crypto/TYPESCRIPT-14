<?php

require_once __DIR__ . '/recovery-common.php';

$error = '';
$ok = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!recovery_check_csrf()) {

        $error = 'Session expired. Refresh the page and try again.';
    } else {

        $phone = preg_replace('/[\s()-]/', '', trim($_POST['phone'] ?? ''));
        $code = trim($_POST['code'] ?? '');

        // Normalize Zambian phone numbers.
        if (preg_match('/^0[97][0-9]{8}$/', $phone)) {
            $phone = '+260' . substr($phone, 1);
        } elseif (preg_match('/^260[97][0-9]{8}$/', $phone)) {
            $phone = '+' . $phone;
        }

        if (!preg_match('/^\+260[97][0-9]{8}$/', $phone)) {

            $error = 'Enter a valid Zambian mobile number, for example +260971234567.';
        } elseif (!preg_match('/^[0-9]{6}$/', $code)) {

            $error = 'Enter the six-digit verification code.';
        } else {

            $q = $pdo->prepare("
                SELECT
                    pr.id,
                    pr.user_id
                FROM password_resets pr
                JOIN users u ON u.id = pr.user_id
                WHERE pr.method = 'sms'
                AND pr.secret_hash = ?
                AND u.phone = ?
                AND pr.used_at IS NULL
                AND pr.expires_at > NOW()
                ORDER BY pr.id DESC
                LIMIT 1
            ");

            $q->execute([
                hash('sha256', $code),
                $phone
            ]);

            $row = $q->fetch();

            if ($row) {

                // Generate a temporary password-reset ticket.
                $ticket = bin2hex(random_bytes(32));

                $_SESSION['verified_reset_user'] = (int)$row['user_id'];
                $_SESSION['verified_reset_ticket'] = $ticket;
                $_SESSION['verified_reset_expires'] = time() + 600;

                // Mark the SMS code as used.
                $pdo->prepare("
                    UPDATE password_resets
                    SET used_at = NOW()
                    WHERE id = ?
                ")->execute([$row['id']]);

                header(
                    'Location: reset-password.php?ticket=' . urlencode($ticket)
                );

                exit;
            } else {

                $error = 'That code is invalid or has expired. Request a new code.';
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

    <title>Verify SMS Code | Green Urban Farms</title>

    <link rel="stylesheet" href="../css/style.css">
</head>

<body>

    <?php include __DIR__ . '/header.php'; ?>

    <main>

        <section class="page-hero page-banner page-banner-login">
            <section class="container">

                <h1>Verify your phone</h1>

                <p class="lead">
                    Enter the code sent to your registered mobile number.
                </p>

            </section>
        </section>

        <section class="section section-soft">
            <section class="container">

                <section class="form-card auth-card">

                    <h2>SMS Verification</h2>

                    <?php if ($error): ?>
                        <p role="alert"><?= h($error) ?></p>
                    <?php endif; ?>

                    <form method="post" class="form">

                        <input
                            type="hidden"
                            name="csrf"
                            value="<?= h(recovery_csrf()) ?>">

                        <section class="field">

                            <label for="phone">
                                Registered phone number
                            </label>

                            <input
                                type="tel"
                                id="phone"
                                name="phone"
                                placeholder="+260971234567"
                                autocomplete="tel"
                                required>

                        </section>

                        <section class="field">

                            <label for="code">
                                Six-digit verification code
                            </label>

                            <input
                                type="text"
                                id="code"
                                name="code"
                                inputmode="numeric"
                                pattern="[0-9]{6}"
                                maxlength="6"
                                placeholder="Enter your code"
                                required>

                        </section>

                        <button class="btn btn-primary" type="submit">
                            Verify Code
                        </button>

                    </form>

                    <p>
                        <a href="forgot-password.php">
                            Request another verification code
                        </a>
                    </p>

                </section>

            </section>
        </section>

    </main>

    <?php include __DIR__ . '/footer.php'; ?>

</body>

</html>