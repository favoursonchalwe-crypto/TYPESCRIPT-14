<?php
require_once __DIR__ . '/db.php';
$items = $pdo->query("SELECT id, title, description, image, status, created_at FROM programs WHERE status = 'active' ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Programs | Green Urban Farms</title><link rel="stylesheet" href="../css/style.css"><script src="../js/script.js" defer></script></head><body>
<?php include __DIR__ . '/header.php'; ?><main>
<section class="page-hero page-banner page-banner-programs"><section class="container"><p class="page-kicker">LEARN • PRACTISE • GROW</p><h1>Our Programs</h1><p class="lead">Practical learning and community activities that make sustainable urban agriculture accessible to everyone.</p><section class="actions"><a class="btn btn-primary" href="get-involved.php">Join a Program</a><a class="btn btn-outline" href="contact.php">Ask a Question</a></section></section></section>
<section class="section"><section class="container"><section class="section-head"><h2>Explore our opportunities</h2><p>Choose a programme and take the next step in your growing journey.</p></section>
<?php if (!$items): ?><section class="empty-state"><h3>Programs are coming soon</h3><p>New programmes will appear here as they are published. Contact us to learn about upcoming opportunities.</p><a class="btn btn-primary" href="contact.php">Contact Us</a></section><?php else: ?><section class="cards">
<?php foreach ($items as $p): ?><article class="card"><section class="icon">🌱</section><h3><?= htmlspecialchars($p['title']) ?></h3><?php if (!empty($p['image'])): ?><img class="listing-image" src="<?= htmlspecialchars($p['image']) ?>" alt="<?= htmlspecialchars($p['title']) ?>"><?php endif; ?><p><?= nl2br(htmlspecialchars($p['description'] ?? '')) ?></p><a class="btn btn-primary" href="get-involved.php">Get Involved</a></article><?php endforeach; ?>
</section><?php endif; ?></section></section>
<section class="section section-soft"><section class="container"><section class="cta-band"><section><h2>Grow your skills with us</h2><p>Whether you are a beginner or an experienced grower, there is a place for you in our community.</p></section><section class="actions"><a class="btn btn-secondary" href="get-involved.php">Take Part</a></section></section></section></section>
</main><?php include __DIR__ . '/footer.php'; ?></body></html>
