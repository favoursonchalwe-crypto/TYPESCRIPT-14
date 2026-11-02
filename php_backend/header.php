<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
$loggedIn = !empty($_SESSION['user_id']);
$isAdmin = $loggedIn && (($_SESSION['user_role'] ?? '') === 'admin');
?>
<header class="site-header">
    <section class="container navbar">
        <a class="brand" href="index.php" aria-label="Green Urban Farms home">
            <img src="../images/Logo.png" alt="">
            <span>GREEN <span>URBAN FARMS</span></span>
        </a>
        <button class="menu-btn" type="button" aria-label="Open menu" aria-expanded="false">☰</button>
        <nav aria-label="Main navigation">
            <ul class="nav-links">
                <li><a href="index.php">Home</a></li>
                <li><a href="about.php">About Us</a></li>
                <li><a href="programs.php">Programs</a></li>
                <?php if ($loggedIn): ?>
                <li><a href="resources.php">Resources</a></li>
                <li><a href="events.php">Events</a></li>
                <li><a href="get-involved.php">Get Involved</a></li>
                <li><a href="contact.php">Contact Us</a></li>
                <?php if ($isAdmin): ?>
                <li><a href="admin.php">Admin</a></li>
                <?php endif; ?>
                <li><a href="logout.php">Log out</a></li>
                <?php endif; ?>
            </ul>
        </nav>
        <?php if (!$loggedIn): ?>
        <a class="btn btn-primary nav-login header-login" href="login.php">Login</a>
        <?php endif; ?>
    </section>
</header>
<script defer src="../js/script.js"></script>
