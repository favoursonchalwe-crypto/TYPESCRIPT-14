<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/db.php';
require_admin();

$users = $pdo->query("
			SELECT id, name, email, role, created_at
			FROM users
			ORDER BY created_at DESC
			")->fetchAll(PDO::FETCH_ASSOC);
$messages = $pdo->query("
			SELECT id, name, email, subject, message, created_at, status
			FROM contact_messages
			ORDER BY created_at DESC
			LIMIT 50
			")->fetchAll(PDO::FETCH_ASSOC);
$apps = $pdo->query("
			SELECT *
			FROM volunteer_applications
			ORDER BY created_at DESC
			LIMIT 50
			")->fetchAll(PDO::FETCH_ASSOC);
$registrations = $pdo->query("
			SELECT er.*, e.title AS event_title
			FROM event_registrations er
			LEFT JOIN events e ON e.id = er.event_id
			ORDER BY er.id DESC
			LIMIT 50
			")->fetchAll(PDO::FETCH_ASSOC);
$note = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$kind = $_POST['kind'] ?? '';

	if ($kind === 'program') {
		$title = trim($_POST['title'] ?? '');
		$description = trim($_POST['description'] ?? '');

		if ($title !== '' && $description !== '') {
			$q = $pdo->prepare("
            INSERT INTO programs (title, description, status)
            VALUES (?, ?, 'active')
        ");

			$q->execute([
				$title,
				$description
			]);

			$note = 'Program published successfully.';
		} else {
			$note = 'Please enter the Program Title and Description.';
		}
	} elseif ($kind === 'event') {
		$title = trim($_POST['title'] ?? '');
		$description = trim($_POST['description'] ?? '');
		$location = trim($_POST['location'] ?? '');
		$eventDate = trim($_POST['start_datetime'] ?? '');

		if ($title !== '' && $description !== '' && $eventDate !== '') {
			$eventDate = str_replace('T', ' ', $eventDate);

			if (strlen($eventDate) === 16) {
				$eventDate .= ':00';
			}

			$q = $pdo->prepare("
			 INSERT INTO events
			 (title, description, event_date, location, status)
			 VALUES 
			 (?, ?, ?, ?, 'upcoming')
			 ");

			$q->execute([
				$title,
				$description,
				$eventDate,
				$location
			]);

			$note = 'Event published successfully.';
		} else {
			$note = 'Please enter the event title, description, and date.';
		}
	}
}
?>

<!doctype html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<title>GUF Admin</title>
	<link rel="stylesheet" href="../css/style.css">
	<script src="../js/script.js" defer></script>
</head>

<body>
	<?php include __DIR__ . '/header.php'; ?>
	<main class="container section">
		<a href="index.php">View website</a>
		| <a href="logout.php">Log out</a>
		<section class="page-hero page-banner page-banner-admin">
			<p class="page-kicker">SITE MANAGEMENT</p>
			<h1>Admin Dashboard</h1>
			<p class="lead">Manage users, publish programmes and events, and review community activity.</p>
		</section>
		<?php if ($note !== ''): ?>
			<p><?= htmlspecialchars($note) ?></p>
		<?php endif; ?>
		<section class="admin-panel">
			<h2>Users (<?= count($users) ?>)</h2>
			<div class="table-wrap">
				<table>
					<tr>
						<th>Name</th>
						<th>Email</th>
						<th>Role</th>
						<th>Created</th>
					</tr>
					<?php foreach ($users as $u): ?>
						<tr>
							<td><?= h($u['name']) ?></td>
							<td><?= h($u['email']) ?></td>
							<td><?= h($u['role']) ?></td>
							<td><?= h($u['created_at']) ?></td>
						</tr>
					<?php endforeach; ?>
				</table>
			</div>
		</section>
		<section class="admin-panel">
			<h2>Publish a program</h2>
			<form method="post">
				<input type="hidden" name="kind" value="program">
				<p><input name="title" placeholder="Program title" required></p>
				<p><input name="slug" placeholder="unique-url-slug" required></p>
				<p><input name="summary" placeholder="Short summary"></p>
				<p><textarea name="description" placeholder="Description" required></textarea></p>
				<button class="btn btn-primary" type="submit">Publish Program</button>
			</form>
		</section>
		<section class="admin-panel">
			<h2>Publish an event</h2>
			<form method="post">
				<input type="hidden" name="kind" value="event">
				<p><input name="title" placeholder="Event title" required></p>
				<p><input name="slug" placeholder="unique-url-slug" required></p>
				<p><textarea name="description" placeholder="Description" required></textarea></p>
				<p><input name="location" placeholder="Location"></p>
				<p><label>Date and time <input type="datetime-local" name="start_datetime" required></label></p>
				<button class="btn btn-primary" type="submit">Publish Event</button>
			</form>
		</section>
		<section class="admin-panel">
			<h2>Recent event registrations</h2>
			<?php foreach ($registrations as $r): ?>
				<article class="card">
					<strong><?= h($r['full_name']) ?> — <?= h($r['event_title']) ?></strong>
					<p><?= h($r['email']) ?> | <?= h($r['registration_status']) ?> | <?= h($r['registered_at']) ?></p>
				</article>
			<?php endforeach; ?>
		</section>
		<section class="admin-panel">
			<h2>Recent contact messages</h2>
			<?php foreach ($messages as $m): ?>
				<article class="card">
					<strong><?= h($m['name']) ?> (<?= h($m['email']) ?>)</strong>
					<p><?= h($m['subject'] ?? 'No subject') ?></p>
					<p><?= nl2br(h($m['message'])) ?></p>
				</article>
			<?php endforeach; ?>
		</section>
		<section class="admin-panel">
			<h2>Recent applications</h2>
			<?php foreach ($apps as $a): ?>
				<article class="card">
					<strong><?= h($a['name']) ?> — <?= h($a['phone']) ?></strong>
					<p><?= h($a['email']) ?> | <?= h($a['status']) ?></p>
				</article>
			<?php endforeach; ?>
		</section>
	</main>
	<?php include __DIR__ . '/footer.php'; ?>
</body>

</html>