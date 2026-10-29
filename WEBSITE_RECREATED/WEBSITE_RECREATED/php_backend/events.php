<?php
require_once __DIR__ . '/DB.php';
$type = strtolower(trim($_GET['type'] ?? 'all'));
$conditions = ["status = 'upcoming'"];
$params = [];
if ($type === 'community-gardening') { $conditions[] = "(LOWER(title) LIKE ? OR LOWER(description) LIKE ? OR LOWER(COALESCE(location,'')) LIKE ?)"; array_push($params, '%community%', '%garden%', '%garden%'); }
elseif ($type === 'workshop' || $type === 'workshops') { $conditions[] = "(LOWER(title) LIKE ? OR LOWER(description) LIKE ?)"; array_push($params, '%workshop%', '%workshop%'); }
elseif ($type === 'community-day' || $type === 'community-days') { $conditions[] = "(LOWER(title) LIKE ? OR LOWER(description) LIKE ?)"; array_push($params, '%community%', '%community%'); }
$sql = 'SELECT id,title,description,event_date,location,capacity,status,created_at FROM events WHERE '.implode(' AND ', $conditions).' ORDER BY event_date ASC';
$stmt=$pdo->prepare($sql); $stmt->execute($params); $items=$stmt->fetchAll(PDO::FETCH_ASSOC);
$title = ['community-gardening'=>'Community Gardening Events','workshop'=>'Practical Workshops','workshops'=>'Practical Workshops','community-day'=>'Community Days','community-days'=>'Community Days'][$type] ?? 'Upcoming Events';
function h($v){return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');}
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title><?= h($title) ?> | Green Urban Farms</title><link rel="stylesheet" href="../css/style.css"><script src="../js/script.js" defer></script></head><body>
<?php include __DIR__.'/header.php'; ?>
<main class="container section"><p><a href="../events/index.html">← All event categories</a></p><h1><?= h($title) ?></h1><p class="lead">Browse published upcoming events and check the details before registering.</p>
<?php if (!$items): ?><article class="card"><h2>No matching events published yet</h2><p>There are currently no upcoming events in this category. Please check again later or contact Green Urban Farms for information.</p><a class="btn btn-blue" href="../contact/index.html">Contact Us</a></article><?php else: ?><section class="cards"><?php foreach($items as $event): ?><article class="card"><h2><?= h($event['title']) ?></h2><p><?= nl2br(h($event['description'])) ?></p><p><strong>Date and time:</strong> <?= h($event['event_date']) ?></p><p><strong>Location:</strong> <?= h($event['location'] ?: 'To be announced') ?></p><?php if ($event['capacity'] !== null && $event['capacity'] !== ''): ?><p><strong>Capacity:</strong> <?= (int)$event['capacity'] ?> participants</p><?php endif; ?><p><strong>Status:</strong> <?= h(ucfirst($event['status'])) ?></p><a class="btn btn-primary" href="event-register.php?event_id=<?= (int)$event['id'] ?>">Register for this event</a></article><?php endforeach; ?></section><?php endif; ?></main></body></html>
