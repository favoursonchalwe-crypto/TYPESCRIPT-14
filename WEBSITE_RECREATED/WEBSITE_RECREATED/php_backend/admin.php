<?php
    require_once __DIR__ . '/auth.php';
    require_once __DIR__ . '/DB.php';
    require_admin();

    $users = $pdo->query('
        SELECT id, name, email, role, created_at 
        FROM users 
        ORDER BY created_at DESC
        ')
        ->fetchAll(PDO::FETCH_ASSOC);
    $messages = $pdo->query('
        SELECT id, name, email, subject, message, created_at, status 
        FROM contact_messages 
        ORDER BY created_at DESC LIMIT 50
        ')->fetchAll(PDO::FETCH_ASSOC);
    $apps = $pdo->query('
        SELECT id, name, email, phone, interests, experience, availability, status, created_at 
        FROM volunteer_applications 
        ORDER BY created_at DESC LIMIT 50
        ')->fetchAll(PDO::FETCH_ASSOC);
    $registrations = $pdo->query('
        SELECT er.*, e.title AS event_title 
        FROM event_registrations er 
        LEFT JOIN events e ON e.id = er.event_id 
        ORDER BY er.id DESC LIMIT 50
        ')->fetchAll(PDO::FETCH_ASSOC);
    $note = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kind = $_POST['kind'] ?? '';
    if ($kind === 'program') {
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        if ($title !== '' && $description !== '') {
            $q = $pdo->prepare("INSERT INTO programs (title, description, status) VALUES (?, ?, 'published')");
            $q->execute([$title, $description]);
            $note = 'Program published successfully.';
            $users = $pdo->query('SELECT id, name, email, role, created_at FROM users ORDER BY created_at DESC')->fetchAll(PDO::FETCH_ASSOC);
        } else { $note = 'Please enter the program title and description.'; }
    } elseif ($kind === 'guide') {
        $title = trim($_POST['guide_title'] ?? '');
        $description = trim($_POST['guide_description'] ?? '');
        if ($title === '' || empty($_FILES['guide_file']) || $_FILES['guide_file']['error'] !== UPLOAD_ERR_OK) {
            $note = 'Enter a guide title and choose a file to upload.';
        } else {
            $file = $_FILES['guide_file'];
            $maxBytes = 12 * 1024 * 1024;
            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            $allowed = ['pdf', 'doc', 'docx'];
            if ($file['size'] > $maxBytes || !in_array($ext, $allowed, true) || !is_uploaded_file($file['tmp_name'])) {
                $note = 'Upload a PDF, DOC or DOCX file no larger than 12 MB.';
            } else {
                $uploadDir = __DIR__ . '/uploads/resources';
                if (!is_dir($uploadDir)) { mkdir($uploadDir, 0755, true); }
                $safeName = bin2hex(random_bytes(12)) . '.' . $ext;
                if (move_uploaded_file($file['tmp_name'], $uploadDir . '/' . $safeName)) {
                    $relativePath = 'uploads/resources/' . $safeName;
                    $q = $pdo->prepare('INSERT INTO resources (title, description, file_path, category) VALUES (?, ?, ?, ?)');
                    $q->execute([$title, $description, $relativePath, 'Beginner Guide']);
                    $note = 'Beginner guide uploaded successfully and is now available for download.';
                } else { $note = 'The guide could not be saved. Check folder permissions.'; }
            }
        }
    } elseif ($kind === 'event') {
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $location = trim($_POST['location'] ?? '');
        $eventDate = trim($_POST['event_date'] ?? '');
        if ($title !== '' && $description !== '' && $eventDate !== '') {
            $q = $pdo->prepare("INSERT INTO events (title, description, event_date, location, status) VALUES (?, ?, ?, ?, 'upcoming')");
            $q->execute([$title, $description, str_replace('T', ' ', $eventDate) . (strlen($eventDate) === 16 ? ':00' : ''), $location !== '' ? $location : null]);
            $note = 'Event published successfully.';
        } else { $note = 'Please enter the event title, description and date.'; }
    }
    $messages = $pdo->query('SELECT id, name, email, subject, message, created_at, status FROM contact_messages ORDER BY created_at DESC LIMIT 50')->fetchAll(PDO::FETCH_ASSOC);
    $apps = $pdo->query('SELECT id, name, email, phone, interests, experience, availability, status, created_at FROM volunteer_applications ORDER BY created_at DESC LIMIT 50')->fetchAll(PDO::FETCH_ASSOC);
    $registrations = $pdo->query('SELECT er.*, e.title AS event_title FROM event_registrations er LEFT JOIN events e ON e.id = er.event_id ORDER BY er.id DESC LIMIT 50')->fetchAll(PDO::FETCH_ASSOC);
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Dashboard | Green Urban Farms</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<?php include __DIR__ . '/header.php'; ?>
<main class="container section">
    <p><a href="index.php">View website</a> | <a href="logout.php">Log out</a></p>
    <h1>Admin Dashboard</h1>
    <?php if ($note !== ''): ?><p role="status"><?= htmlspecialchars($note, ENT_QUOTES, 'UTF-8') ?></p><?php endif; ?>
    <h2>Users (<?= count($users) ?>)</h2>
    <div class="table-responsive"><table><thead><tr><th>Name</th><th>Email</th><th>Role</th><th>Created</th></tr></thead><tbody>
    <?php foreach ($users as $user): ?><tr><td><?= htmlspecialchars($user['name'], ENT_QUOTES, 'UTF-8') ?></td><td><?= htmlspecialchars($user['email'], ENT_QUOTES, 'UTF-8') ?></td><td><?= htmlspecialchars($user['role'], ENT_QUOTES, 'UTF-8') ?></td><td><?= htmlspecialchars($user['created_at'], ENT_QUOTES, 'UTF-8') ?></td></tr><?php endforeach; ?>
    </tbody></table></div>

    <h2>Upload a beginner guide</h2>
    <p>Upload a PDF, DOC or DOCX guide. It will appear on the Beginner Guides page for users to download.</p>
    <form class="form" method="post" enctype="multipart/form-data"><input type="hidden" name="kind" value="guide"><label for="guide-title">Guide title</label><input id="guide-title" name="guide_title" required maxlength="200"><label for="guide-description">Description</label><textarea id="guide-description" name="guide_description"></textarea><label for="guide-file">Guide file (PDF, DOC or DOCX; max 12 MB)</label><input id="guide-file" type="file" name="guide_file" accept=".pdf,.doc,.docx" required><button class="btn btn-primary" type="submit">Upload guide</button></form>

    <h2>Publish a program</h2>
    <form class="form" method="post"><input type="hidden" name="kind" value="program"><label for="program-title">Title</label><input id="program-title" name="title" required maxlength="200"><label for="program-description">Description</label><textarea id="program-description" name="description" required></textarea><button class="btn btn-primary" type="submit">Publish program</button></form>
    <h2>Publish an event</h2>
    <form class="form" method="post"><input type="hidden" name="kind" value="event"><label for="event-title">Title</label><input id="event-title" name="title" required maxlength="200"><label for="event-description">Description</label><textarea id="event-description" name="description" required></textarea><label for="event-location">Location</label><input id="event-location" name="location" maxlength="200"><label for="event-date">Date and time</label><input id="event-date" type="datetime-local" name="event_date" required><button class="btn btn-primary" type="submit">Publish event</button></form>
    <h2>Recent event registrations</h2>
    <?php foreach ($registrations as $registration): ?><article class="card"><strong><?= htmlspecialchars($registration['full_name'] ?? $registration['name'] ?? 'Visitor', ENT_QUOTES, 'UTF-8') ?> — <?= htmlspecialchars($registration['event_title'] ?? 'Event', ENT_QUOTES, 'UTF-8') ?></strong><p><?= htmlspecialchars($registration['email'] ?? '', ENT_QUOTES, 'UTF-8') ?> | <?= htmlspecialchars($registration['registration_status'] ?? $registration['status'] ?? '', ENT_QUOTES, 'UTF-8') ?></p></article><?php endforeach; ?>
    <h2>Recent contact messages</h2>
    <?php foreach ($messages as $message): ?><article class="card"><strong><?= htmlspecialchars($message['name'], ENT_QUOTES, 'UTF-8') ?> (<?= htmlspecialchars($message['email'], ENT_QUOTES, 'UTF-8') ?>)</strong><p><?= htmlspecialchars($message['subject'] ?? 'No subject', ENT_QUOTES, 'UTF-8') ?></p><p><?= nl2br(htmlspecialchars($message['message'], ENT_QUOTES, 'UTF-8')) ?></p><small><?= htmlspecialchars($message['created_at'], ENT_QUOTES, 'UTF-8') ?></small></article><?php endforeach; ?>
    <h2>Recent volunteer applications</h2>
    <?php foreach ($apps as $app): ?><article class="card"><strong><?= htmlspecialchars($app['name'], ENT_QUOTES, 'UTF-8') ?></strong><p><?= htmlspecialchars($app['email'], ENT_QUOTES, 'UTF-8') ?> | <?= htmlspecialchars($app['phone'] ?? '', ENT_QUOTES, 'UTF-8') ?> | <?= htmlspecialchars($app['status'], ENT_QUOTES, 'UTF-8') ?></p><p><?= nl2br(htmlspecialchars($app['interests'] ?? '', ENT_QUOTES, 'UTF-8')) ?></p></article><?php endforeach; ?>
</main>
</body>
</html>
