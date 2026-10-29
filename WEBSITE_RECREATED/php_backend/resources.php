<?php
require_once __DIR__ . '/DB.php';
$stmt = $pdo->query("SELECT id, title, description, file_path, resource_url, category, created_at FROM resources ORDER BY created_at DESC");
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Resources | Green Urban Farms</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<?php include __DIR__ . '/header.php'; ?>
<main class="container section">
    <h1>Farming Resources</h1>
    <?php if (!$items): ?><p>Resources will be added soon.</p><?php endif; ?>
    <section class="cards">
        <?php foreach ($items as $resource): ?>
            <article class="card">
                <h2><?= htmlspecialchars($resource['title'], ENT_QUOTES, 'UTF-8') ?></h2>
                <?php if (!empty($resource['category'])): ?><p><strong><?= htmlspecialchars($resource['category'], ENT_QUOTES, 'UTF-8') ?></strong></p><?php endif; ?>
                <p><?= nl2br(htmlspecialchars($resource['description'] ?? '', ENT_QUOTES, 'UTF-8')) ?></p>
                <?php if (!empty($resource['resource_url'])): ?><p><a href="<?= htmlspecialchars($resource['resource_url'], ENT_QUOTES, 'UTF-8') ?>" target="_blank" rel="noopener noreferrer">Open resource</a></p>
                <?php elseif (!empty($resource['file_path'])): ?><p><a href="<?= htmlspecialchars($resource['file_path'], ENT_QUOTES, 'UTF-8') ?>">Download resource</a></p><?php endif; ?>
            </article>
        <?php endforeach; ?>
    </section>
</main>
</body>
</html>
