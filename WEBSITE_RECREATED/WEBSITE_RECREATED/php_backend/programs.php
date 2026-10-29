<?php
require_once __DIR__ . '/DB.php';
require_once __DIR__ . '/auth.php';

$category = strtolower(trim($_GET['category'] ?? 'all'));
$sql = "SELECT id,title,description,image,status,created_at FROM programs WHERE status IN ('active','published')";
$params = [];
if ($category === 'workshops') { $sql .= " AND (LOWER(title) LIKE ? OR LOWER(description) LIKE ?)"; $params = ['%workshop%', '%workshop%']; }
elseif ($category === 'community-days' || $category === 'community-day') { $sql .= " AND (LOWER(title) LIKE ? OR LOWER(description) LIKE ?)"; $params = ['%community%', '%community%']; }
$sql .= ' ORDER BY created_at DESC';
$stmt = $pdo->prepare($sql); $stmt->execute($params);
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
    <h1><?= htmlspecialchars($category === 'workshops' ? 'Available Workshops' : (($category === 'community-days' || $category === 'community-day') ? 'Community Day Programs' : 'Our Programs'), ENT_QUOTES, 'UTF-8') ?></h1>
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
