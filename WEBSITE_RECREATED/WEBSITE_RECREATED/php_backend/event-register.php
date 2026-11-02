<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

require_once __DIR__ . '/db.php';

$eventId = filter_input(INPUT_GET, 'event_id', FILTER_VALIDATE_INT);

if (!$eventId && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $eventId = filter_input(INPUT_POST, 'event_id', FILTER_VALIDATE_INT);
}

$event = null;
$error = '';
$success = '';

if ($eventId) {
    $stmt = $pdo->prepare("
        SELECT id, title, event_date, location
        FROM events
        WHERE id = ? AND status = 'upcoming'
    ");

    $stmt->execute([$eventId]);
    $event = $stmt->fetch(PDO::FETCH_ASSOC);
}

if (!$event) {
    http_response_code(404);
    $error = 'This event could not be found or is not available for registration.';
}

elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = trim($_POST['full_name'] ?? '');
    $email = strtolower(trim($_POST['email'] ?? ''));
    $phone = trim($_POST['phone'] ?? '');

    if ($name === '') {
        $error = 'Please enter your full name.';
    } elseif (strlen($name) > 100) {
        $error = 'Your name must not exceed 100 characters.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } elseif (strlen($email) > 150) {
        $error = 'Your email address is too long.';
    } elseif (strlen($phone) > 30) {
        $error = 'Your phone number must not exceed 30 characters.';
    } else {

        try {
            /*
             * Use the actual column names in the database.
             * id and registered_at are generated automatically.
             */
            $stmt = $pdo->prepare("
                INSERT INTO event_registrations
                    (event_id, name, email, phone)
                VALUES
                    (?, ?, ?, ?)
            ");

            $stmt->execute([
                $eventId,
                $name,
                $email,
                $phone !== '' ? $phone : null
            ]);

            $success = 'You are registered for this event successfully!';

        } catch (PDOException $e) {

            // Record the technical error in the PHP error log.
            error_log('Event registration error: ' . $e->getMessage());

            if ($e->getCode() === '23000') {
                $error = 'This email may already be registered for this event.';
            } else {
                $error = 'We could not complete registration. Please try again later.';
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

    <title>Event Registration | Green Urban Farms</title>

   <link rel="stylesheet" href="../css/style.css">
</head>

<body>

<?php include __DIR__ . '/header.php'; ?>

<main class="container section">

    <h1>Event Registration</h1>

    <?php if ($event): ?>

        <h2><?= htmlspecialchars($event['title'], ENT_QUOTES, 'UTF-8') ?></h2>

        <p>
            <?= htmlspecialchars($event['event_date'], ENT_QUOTES, 'UTF-8') ?>
            ·
            <?= htmlspecialchars($event['location'] ?? 'Location to be announced', ENT_QUOTES, 'UTF-8') ?>
        </p>

    <?php endif; ?>

    <?php if ($error !== ''): ?>

        <p class="error" role="alert">
            <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
        </p>

    <?php endif; ?>

    <?php if ($success !== ''): ?>

        <p class="success" role="status">
            <?= htmlspecialchars($success, ENT_QUOTES, 'UTF-8') ?>
        </p>

    <?php elseif ($event): ?>

        <form class="form" method="post"
              action="event-register.php?event_id=<?= (int) $event['id'] ?>">

            <input
                type="hidden"
                name="event_id"
                value="<?= (int) $event['id'] ?>"
            >

            <label for="full_name">Full name</label>
            <input
                type="text"
                id="full_name"
                name="full_name"
                maxlength="100"
                required
            >

            <label for="email">Email address</label>
            <input
                type="email"
                id="email"
                name="email"
                maxlength="150"
                required
            >

            <label for="phone">Phone number</label>
            <input
                type="tel"
                id="phone"
                name="phone"
                maxlength="30"
            >

            <button type="submit" class="btn btn-primary">
                Register for Event
            </button>

        </form>

    <?php endif; ?>

</main>

</body>
</html>