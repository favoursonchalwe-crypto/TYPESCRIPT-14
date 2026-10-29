```php
<?php
session_start();

require_once __DIR__ . '/db.php';

$success = '';
$error = '';

$name = '';
$email = '';
$phone = '';
$interests = '';
$experience = '';
$availability = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $interests = trim($_POST['interests'] ?? '');
    $experience = trim($_POST['experience'] ?? '');
    $availability = trim($_POST['availability'] ?? '');

    if (
        $name === '' ||
        !filter_var($email, FILTER_VALIDATE_EMAIL) ||
        $interests === '' ||
        $availability === ''
    ) {
        $error = 'Please complete all required fields and enter a valid email address.';

    } elseif (strlen($name) > 100 || strlen($email) > 150 || strlen($phone) > 30) {
        $error = 'One or more fields exceed the allowed length.';

    } else {
        try {
            $stmt = $pdo->prepare("
                INSERT INTO volunteer_applications
                    (name, email, phone, interests, experience, availability, status)
                VALUES
                    (?, ?, ?, ?, ?, ?, 'pending')
            ");

            $stmt->execute([
                $name,
                $email,
                $phone !== '' ? $phone : null,
                $interests,
                $experience !== '' ? $experience : null,
                $availability
            ]);

            $success = 'Thank you! Your volunteer application has been submitted successfully.';

            $name = '';
            $email = '';
            $phone = '';
            $interests = '';
            $experience = '';
            $availability = '';

        } catch (PDOException $e) {
            error_log('Volunteer application error: ' . $e->getMessage());
            $error = 'We could not submit your application. Please try again later.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Get Involved | Green Urban Farms</title>

    <link rel="stylesheet" href="../css/style.css">
</head>

<body>

<?php include __DIR__ . '/header.php'; ?>

<main class="container section">

    <h1>Get Involved</h1>

    <p>
        Join Green Urban Farms and help us promote sustainable
        agriculture and community development.
    </p>

    <?php if ($success !== ''): ?>
        <p class="success" role="status">
            <?= htmlspecialchars($success, ENT_QUOTES, 'UTF-8') ?>
        </p>
    <?php endif; ?>

    <?php if ($error !== ''): ?>
        <p class="error" role="alert">
            <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
        </p>
    <?php endif; ?>

    <form class="form" method="POST" action="get-involved.php">

        <label for="name">Full Name *</label>
        <input
            type="text"
            id="name"
            name="name"
            maxlength="100"
            value="<?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?>"
            required
        >

        <label for="email">Email Address *</label>
        <input
            type="email"
            id="email"
            name="email"
            maxlength="150"
            value="<?= htmlspecialchars($email, ENT_QUOTES, 'UTF-8') ?>"
            required
        >

        <label for="phone">Phone Number</label>
        <input
            type="tel"
            id="phone"
            name="phone"
            maxlength="30"
            value="<?= htmlspecialchars($phone, ENT_QUOTES, 'UTF-8') ?>"
        >

        <label for="interests">Areas of Interest *</label>
        <select id="interests" name="interests" required>
            <option value="">Select an area</option>
            <option value="Community Gardening" <?= $interests === 'Community Gardening' ? 'selected' : '' ?>>Community Gardening</option>
            <option value="Urban Farming" <?= $interests === 'Urban Farming' ? 'selected' : '' ?>>Urban Farming</option>
            <option value="Environmental Education" <?= $interests === 'Environmental Education' ? 'selected' : '' ?>>Environmental Education</option>
            <option value="Community Outreach" <?= $interests === 'Community Outreach' ? 'selected' : '' ?>>Community Outreach</option>
            <option value="Other" <?= $interests === 'Other' ? 'selected' : '' ?>>Other</option>
        </select>

        <label for="experience">Previous Experience</label>
        <textarea
            id="experience"
            name="experience"
            rows="4"
        ><?= htmlspecialchars($experience, ENT_QUOTES, 'UTF-8') ?></textarea>

        <label for="availability">Availability *</label>
        <select id="availability" name="availability" required>
            <option value="">Select your availability</option>
            <option value="Weekdays" <?= $availability === 'Weekdays' ? 'selected' : '' ?>>Weekdays</option>
            <option value="Weekends" <?= $availability === 'Weekends' ? 'selected' : '' ?>>Weekends</option>
            <option value="Both" <?= $availability === 'Both' ? 'selected' : '' ?>>Both weekdays and weekends</option>
        </select>

        <button type="submit" class="btn btn-primary">
            Submit Application
        </button>

    </form>

</main>

</body>
</html>
```
