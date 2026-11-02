<?php
if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Growing greener, healthier communities. Green Urban Farms supports sustainable urban agriculture.">
  <title>About Us | Green Urban Farms</title>
  <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<?php include __DIR__ . '/header.php'; ?>
<main>

   <section class="hero">
    <section class="container hero-grid">
     <section>
      <span class="badge">
       OUR STORY
      </span>
      <h1>
       Growing greener, healthier communities.
      </h1>
      <p class="lead">
       We help people discover how accessible, sustainable urban farming can improve everyday life, strengthen communities and bring fresh food closer to home.
      </p>
      <section class="actions">
       <a class="btn btn-primary" href="login.php?action=register">
        Get Involved
       </a>
      </section>
     </section>
     <section class="hero-card hero-card-guide">
      <h2>
       Growing better, together.
      </h2>
      <p>
       Practical skills, shared knowledge and greener neighbourhoods start with one small step.
      </p>
     </section>
    </section>
   </section>
   <section class="section">
    <section class="container split">
     <section>
      <h2>
       Our Mission
      </h2>
      <p>
       To empower urban communities to grow fresh, healthy food through sustainable farming education, community participation and accessible urban agriculture initiatives.
      </p>
      <section class="callout">
       <h3>
        Our Vision
       </h3>
       <p>
        Urban communities where people have the knowledge, confidence and opportunities to grow food responsibly, even when space is limited.
       </p>
      </section>
     </section>
    </section>
   </section>
   <section class="section section-soft">
    <section class="container">
     <section class="section-head">
      <h2>
       What We Value
      </h2>
      <p>
       Our work is guided by practical action and shared responsibility.
      </p>
     </section>
     <section class="cards">
      <article class="card">
       <section class="icon">
        ♻️
       </section>
       <h3>
        Sustainability
       </h3>
       <p>
        Responsible growing methods that respect resources and the environment.
       </p>
      </article>
      <article class="card">
       <section class="icon">
        📚
       </section>
       <h3>
        Accessible Learning
       </h3>
       <p>
        Useful knowledge and practical skills for beginners and experienced growers.
       </p>
      </article>
      <article class="card">
       <section class="icon">
        🤝
       </section>
       <h3>
        Community
       </h3>
       <p>
        People learning, sharing and growing together.
       </p>
      </article>
     </section>
    </section>
   </section>
  
</main>
<footer class="site-footer">
  <section class="container footer-grid">
    <section><h3>Green Urban Farms</h3><p>Empowering urban communities to grow fresh, healthy food through sustainable farming education and community participation.</p></section>
    <section><h3>Explore</h3><p><a href="programs.php">Programs</a></p><p><a href="index.php">Home</a></p></section>
    <section><h3>Get Connected</h3><p><a href="login.php?action=register">Register to participate</a></p><p><a href="login.php">Log in</a></p></section>
  </section>
  <section class="container copyright">&copy; <span data-year></span> Green Urban Farms. All rights reserved.</section>
</footer>
<script src="../js/script.js" defer></script>
</body>
</html>
