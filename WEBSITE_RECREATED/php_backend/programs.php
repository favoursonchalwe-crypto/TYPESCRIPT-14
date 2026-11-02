<?php
require_once __DIR__ . '/DB.php';
require_once __DIR__ . '/auth.php';

$stmt = $pdo->query("
    SELECT id, title, description, image, status, created_at
    FROM programs 
    WHERE status = 'active' 
    ORDER BY created_at DESC
    ");
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Programs | Green Urban Farms</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<?php include __DIR__ . '/header.php'; ?>
<main class="container section">
    <h1>Our Programs</h1>
    <?php if (!$items): ?><p>Programs will be listed here soon.</p><?php endif; ?>
    <section class="cards">
        <?php foreach ($items as $program): ?>
            <article class="card">
                <?php if (!empty($program['image'])): ?>
                    <img class="img-fluid" src="<?= htmlspecialchars($program['image'], ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($program['title'], ENT_QUOTES, 'UTF-8') ?>">
                <?php endif; ?>
                <h2><?= htmlspecialchars($program['title'], ENT_QUOTES, 'UTF-8') ?></h2>
                <p><?= nl2br(htmlspecialchars($program['description'] ?? '', ENT_QUOTES, 'UTF-8')) ?></p>
                <a href="get-involved.php">Get involved</a>
            </article>
        <?php endforeach; ?>
    </section>
</main>
</body>
</html>
