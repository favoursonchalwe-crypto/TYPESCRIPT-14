<?php
require_once __DIR__ . '/DB.php';
$stmt = $pdo->query("SELECT id, title, description, event_date, location, capacity, status, created_at FROM events WHERE status = 'upcoming' ORDER BY event_date ASC");
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Events | Green Urban Farms</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<?php include __DIR__ . '/header.php'; ?>
<main class="container section">
    <h1>Upcoming Events</h1>
    <?php if (!$items): ?><p>No upcoming events have been published yet.</p><?php endif; ?>
    <section class="cards">
        <?php foreach ($items as $event): ?>
            <article class="card">
                <h2><?= htmlspecialchars($event['title'], ENT_QUOTES, 'UTF-8') ?></h2>
                <p><?= nl2br(htmlspecialchars($event['description'], ENT_QUOTES, 'UTF-8')) ?></p>
                <p><?= htmlspecialchars($event['event_date'], ENT_QUOTES, 'UTF-8') ?> — <?= htmlspecialchars($event['location'] ?? 'Location to be announced', ENT_QUOTES, 'UTF-8') ?></p>
                <a class="btn btn-primary" href="event-register.php?event_id=<?= (int)$event['id'] ?>">Register for this event</a>
            </article>
        <?php endforeach; ?>
    </section>
</main>
</body>
</html>
