<?php
require_once __DIR__ . '/../../php_backend/DB.php';
$stmt = $pdo->prepare("SELECT id, title, description, file_path, created_at FROM resources WHERE category = ? AND file_path IS NOT NULL AND file_path <> '' ORDER BY created_at DESC");
$stmt->execute(['Beginner Guide']);
$guides = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
 <head>
  <meta charset="utf-8"/>
  <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
  <title>
   Beginner Guides | Green Urban Farms
  </title>
  <link href="../../css/style.css" rel="stylesheet"/>
  <script defer="" src="../../js/script.js">
  </script>
 </head>
 <body>
  <header class="site-header">
   <section class="container navbar">
    <a class="brand" href="../../index.html">
     <img alt="" src="../../images/Logo.png"/>
     <span>
      GREEN
      <span>
       URBAN FARMS
      </span>
     </span>
    </a>
    <button aria-expanded="false" aria-label="Open menu" class="menu-btn" type="button">
     ☰
    </button>
    <nav aria-label="Main navigation">
     <ul class="nav-links">
      <li>
       <a href="../../index.html">
        Home
       </a>
      </li>
      <li>
       <a href="../../programs/index.html">
        Programs
       </a>
      </li>
      <li>
       <a href="../../about/index.html">
        About Us
       </a>
      </li>
      <li>
       <a href="../../resources/index.html">
        Resources
       </a>
      </li>
      <li>
       <a href="../../events/index.html">
        Events
       </a>
      </li>
      <li>
       <a href="../../get-involved/index.html">
        Get Involved
       </a>
      </li>
      <li>
       <a href="../../contact/index.html">
        Contact Us
       </a>
      </li>
      <li>
       <a href="../../login/index.html">
        Log In
       </a>
      </li>
     </ul>
    </nav>
   </section>
  </header>
  <main>
   <section class="page-hero"><section class="container"><p class="badge text-green">GREEN URBAN FARMS</p><h1>Beginner Guides</h1><p class="lead">Download practical guides uploaded by our administrators and use them as you start growing food in urban spaces.</p></section></section>
   <section class="section"><section class="container content">
    <h2>Available guides</h2>
    <?php if (!$guides): ?><p>No beginner guides have been uploaded yet. Please check again soon.</p><?php else: ?>
    <section class="cards"><?php foreach ($guides as $guide): ?><article class="card"><h3><?= htmlspecialchars($guide['title'], ENT_QUOTES, 'UTF-8') ?></h3><?php if (!empty($guide['description'])): ?><p><?= nl2br(htmlspecialchars($guide['description'], ENT_QUOTES, 'UTF-8')) ?></p><?php endif; ?><p><small>Added <?= htmlspecialchars($guide['created_at'] ?? '', ENT_QUOTES, 'UTF-8') ?></small></p><a class="btn btn-primary" href="../../php_backend/<?= htmlspecialchars(ltrim($guide['file_path'], '/'), ENT_QUOTES, 'UTF-8') ?>" download>Download guide</a></article><?php endforeach; ?></section>
    <?php endif; ?>
    <section class="callout"><h3>New to urban farming?</h3><p>Read our practical tips on choosing a growing space, selecting crops, watering and harvesting.</p><a class="btn btn-blue" href="../growing-tips/index.html">View Growing Tips</a></section>
   </section></section>
  </main>
  <footer class="site-footer">
   <section class="container">
    <section class="footer-grid">
     <section>
      <h3>
       Green Urban Farms
      </h3>
      <p>
       Practical knowledge for greener communities.
      </p>
     </section>
     <section>
      <h4>
       Explore
      </h4>
      <ul class="footer-links">
       <li>
        <a href="../../programs/index.html">
         Programs
        </a>
       </li>
       <li>
        <a href="../../resources/index.html">
         Resources
        </a>
       </li>
       <li>
        <a href="../../events/index.html">
         Events
        </a>
       </li>
      </ul>
     </section>
     <section>
      <h4>
       Contact
      </h4>
      <ul class="footer-links">
       <li>
        <a href="../../get-involved/index.html">
         Get Involved
        </a>
       </li>
       <li>
        <a href="../../contact/index.html">
         Contact Us
        </a>
       </li>
      </ul>
     </section>
    </section>
    <section class="copyright">
     &copy;
     <span data-year="">
      2026
     </span>
     Green Urban Farms.
    </section>
   </section>
  </footer>
 </body>
</html>
