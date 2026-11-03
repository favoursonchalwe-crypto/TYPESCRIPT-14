<?php
?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <meta name="description" content="Learn about Green Urban Farms, our mission and our commitment to sustainable urban agriculture.">
  <title>About Us | Green Urban Farms</title>
  <link rel="stylesheet" href="../css/style.css">
  <script src="../js/script.js" defer></script>
</head>

<body>
  <?php include __DIR__ . '/header.php'; ?>
  <main>
    <section class="hero page-banner page-banner-about">
      <section class="container hero-grid">
        <section>
          <span class="badge">OUR STORY</span>
          <h1>Growing greener, healthier communities.</h1>
          <p class="lead">We help people discover how accessible, sustainable urban farming can improve everyday life, strengthen communities and bring fresh food closer to home.</p>
          <section class="actions"><a class="btn btn-primary" href="get-involved.php">Get Involved</a></section>
        </section>
        <section class="hero-card">
          <img class="section-photo hero-feature-photo" src="../images/Guide.jpeg" alt="Urban farming guide and growing together">
          <h2>Growing better, together.</h2>
          <p>Practical skills, shared knowledge and greener neighbourhoods start with one small step.</p>
        </section>
      </section>
    </section>
    <section class="section">
      <section class="container split">
        <section>
          <h2>Our Mission</h2>
          <p>To empower urban communities to grow fresh, healthy food through sustainable farming education, community participation and accessible urban agriculture initiatives.</p>
          <section class="callout">
            <h3>Our Vision</h3>
            <p>Urban communities where people have the knowledge, confidence and opportunities to grow food responsibly, even when space is limited.</p>
          </section>
        </section>
        <section class="feature-image"><img class="section-photo panel-photo" src="../images/Background.jpg" alt="Green Urban Farms growing environment"></section>
      </section>
    </section>
    <section class="section section-soft">
      <section class="container">
        <section class="section-head">
          <h2>What We Value</h2>
          <p>Our work is guided by practical action and shared responsibility.</p>
        </section>
        <section class="cards">
          <article class="card value-card">
            <section class="icon">♻️</section>
            <h3>Sustainability</h3>
            <p>Responsible growing methods that respect resources and the environment.</p>
          </article>
          <article class="card value-card">
            <section class="icon">📚</section>
            <h3>Accessible Learning</h3>
            <p>Useful knowledge and practical skills for beginners and experienced growers.</p>
          </article>
          <article class="card value-card">
            <section class="icon">🤝</section>
            <h3>Community</h3>
            <p>People learning, sharing and growing together.</p>
          </article>
        </section>
      </section>
    </section>
  </main>
  <?php include __DIR__ . '/footer.php'; ?>
</body>

</html>