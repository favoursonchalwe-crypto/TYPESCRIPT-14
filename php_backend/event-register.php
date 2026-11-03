<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
require_once __DIR__ . '/db.php';
$eventId = filter_input(INPUT_GET, 'event_id', FILTER_VALIDATE_INT);
if (!$eventId && $_SERVER['REQUEST_METHOD'] === 'POST') $eventId = filter_input(INPUT_POST, 'event_id', FILTER_VALIDATE_INT);
$q = $pdo->prepare("SELECT id,title,event_date,location FROM events WHERE id=? AND status='upcoming'");
$q->execute([$eventId]);
$event = $q->fetch();
$error = '';
$success = '';
if (!$event) {
    http_response_code(404);
    $error = 'This event could not be found or is not published.';
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['full_name'] ?? '');
    $email = strtolower(trim($_POST['email'] ?? ''));
    $phone = trim($_POST['phone'] ?? '');
    if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) $error = 'Please enter your full name and a valid email.';
    else {
        try {
            $uid = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : null;
            $q = $pdo->prepare("INSERT INTO event_registrations(event_id,user_id,full_name,email,phone) VALUES(?,?,?,?,?)");
            $q->execute([$eventId, $uid, $name, $email, $phone ?: null]);
            $success = 'You are registered for this event.';
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') $error = 'This email is already registered for this event.';
            else $error = 'We could not complete the registration. Please try again.';
        }
    }
}
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Event Registration | Green Urban Farms</title>
    <link rel="stylesheet" href="../css/style.css">
    <script src="../js/script.js" defer></script>
</head>

<body>
    <?php include __DIR__ . '/header.php'; ?><main>
        <section class="page-hero">
            <section class="container">
                <p class="page-kicker">JOIN THE COMMUNITY</p>
                <h1>Event Registration</h1>
                <p class="lead">Complete the form below to reserve your place.</p>
            </section>
        </section>
        <section class="section">
            <section class="container"><?php if ($event): ?><h2><?= htmlspecialchars($event['title']) ?></h2>
                    <p><?= htmlspecialchars($event['event_date']) ?> · <?= htmlspecialchars($event['location'] ?? 'Location to be announced') ?></p><?php endif; ?><?php if ($error): ?><p role="alert"><?= htmlspecialchars($error) ?></p><?php endif; ?><?php if ($success): ?><p role="status"><?= htmlspecialchars($success) ?></p><?php elseif ($event): ?><form class="form-card" method="post"><input type="hidden" name="event_id" value="<?= (int)$event['id'] ?>"><label for="full_name">Full name</label><input id="full_name" name="full_name" maxlength="150" required><label for="email">Email</label><input id="email" name="email" type="email" maxlength="190" required><label for="phone">Phone</label><input id="phone" name="phone" maxlength="40"><button class="btn btn-primary">Register</button></form><?php endif; ?>
            </section>
        </section>
    </main><?php include __DIR__ . '/footer.php'; ?>
</body>

</html>