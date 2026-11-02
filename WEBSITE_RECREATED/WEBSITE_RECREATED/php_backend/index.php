<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
require_once __DIR__ . '/DB.php';
$registerError = ''; $registerSuccess = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['form_action'] ?? '') === 'register') {
  $name = trim($_POST['name'] ?? ''); $email = strtolower(trim($_POST['email'] ?? '')); $password = $_POST['password'] ?? '';
  if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 8) $registerError = 'Enter your name, a valid email, and a password of at least 8 characters.';
  else { $q = $pdo->prepare('SELECT id FROM users WHERE email = ?'); $q->execute([$email]);
    if ($q->fetch()) $registerError = 'An account with that email already exists. Please log in.';
    else { $q = $pdo->prepare("INSERT INTO users (name,email,password,role) VALUES (?,?,?,'user')"); $q->execute([$name,$email,password_hash($password, PASSWORD_DEFAULT)]); $registerSuccess = 'Your account was created. You can now log in.'; }
  }
}
?>

<!DOCTYPE html>
<!-- This code  is written for the Home page for green urban farms -->
<!-- Written by Alfred Favourson Chalwe SID => 2511350 -->
<html lang="en">
      <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Home | Green Urban Farms</title>
            <link rel="stylesheet" href="../css/style.css">
      </head>
      <body>
            <?php include __DIR__ . '/header.php'; ?>
            
            <main>
                  <section class="hero hero-landing">
                        <section class="container hero-grid">
                              <section>
                                    <span class="badge">SUSTAINABLE URBAN AGRICULTURE</span>
                                    <h1>Grow Fresh. Grow Together.</h1>
                                    <p class="lead">
                                          Green Urban Farms empowers urban communities to grow fresh, healthy food through
                                          sustainable farming education, community participation and accessible urban agriculture initiatives.
                                    </p>
                                    <section class="actions">
                                          <a class="btn btn-primary" href="programs.php">Explore Programs</a>
                                          <a class="btn btn-secondary" href="get-involved.php">Join a Program</a>
                                    </section>
                              </section>
                              <section class="hero-card hero-card-fresh">
                                    <h2>Small spaces. Big harvests.</h2>
                                    <p>
                                          Learn practical ways to grow vegetables and herbs using containers, 
                                          community gardens and other space-efficient methods.
                                    </p>
                              </section>
                        </section>
                  </section>
                  <?php if (empty($_SESSION['user_id'])): ?>
                  <section class="section alt" id="register">
                        <section class="container container-narrow">
                              <h2>Join Green Urban Farms</h2>
                              <p>Create an account to take part in our community.</p>
                              <?php if ($registerError): ?>
                                    <p role="alert">
                                          <?= htmlspecialchars($registerError)
                                          ?>
                                    </p>
                              <?php endif; ?>
                              <?php if ($registerSuccess): ?>
                                    <p role="status">
                                          <?= htmlspecialchars($registerSuccess)
                                          ?> 
                                          <a href="login.php">Log in</a>
                                    </p>
                              <?php endif; ?>
                              <form class="form" method="post" action="index.php#register">
                                    <input type="hidden" name="form_action" value="register">
                                    <label for="reg-name">Full name</label>
                                    <input id="reg-name" name="name" required maxlength="150">
                                    <label for="reg-email">Email</label>
                                    <input id="reg-email" type="email" name="email" required maxlength="190">
                                    <label for="reg-password">Password (at least 8 characters)</label>
                                    <input id="reg-password" type="password" name="password" minlength="8" required>
                                    <button class="btn btn-primary" type="submit">Create Account</button>
                              </form>
                              <p>Already registered? <a href="login.php">Log in here</a>.</p>
                        </section>
                  </section>
                  <?php endif; ?>
                  <section class="section">
                        <section class="container">
                              <section class="section-head">
                                    <h2>What We Offer</h2>
                                    <p class="lead">
                                          Resources and activities designed for
                                          urban residents at every experience level.
                                    </p>
                              </section>
                              <section class="cards">
                                    <section class="card">
                                          <section class="icon">🌿</section>
                                          <h3>Programs</h3>
                                          <p>Training, youth activities and community gardens that turn learning into practical action.</p>
                                          <a class="btn btn-outline" href="programs.php">View Programs</a>
                                    </section>
                                    <section class="card">
                                          <section class="icon">📚</section>
                                          <h3>Resources</h3>
                                          <p>Beginner guides, growing tips and answers to common urban farming questions.</p>
                                          <a class="btn btn-outline" href="resources.php">Browse Resources</a>
                                    </section>
                                    <section class="card">
                                          <section class="icon">🤝</section>
                                          <h3>Get Involved</h3>
                                          <p>Volunteer, join a programme or participate in community events and workshops.</p>
                                          <a class="btn btn-outline" href="get-involved.php">Get Involved</a>
                                    </section>
                              </section>
                        </section>
                  </section>
                  <section class="section alt">
                        <section class="container">
                              <section class="section-head">
                                    <h2>Our Approach</h2>
                                    <p class="lead">
                                          We combine practical education, community participation
                                          and responsible farming practices.
                                    </p>
                              </section>
                              <section class="cards">
                                    <section class="card">
                                          <h3>Learn</h3>
                                          <p>Build knowledge through accessible training and resources.</p>
                                    </section>
                                    <section class="card">
                                          <h3>Grow</h3>
                                          <p>Apply techniques in homes, containers and shared gardens.</p>
                                    </section>
                                    <section class="card">
                                          <h3>Share</h3>
                                          <p>Work with neighbours and strengthen local food-growing communities.</p>
                                    </section>
                              </section>
                        </section>
                  </section>
            </main>
            
            <footer class="site-footer">
                  <section class="container footer-grid">
                        <section>
                              <h3>Green Urban Farms</h3>
                              <p>
                                    Empowering urban communities to grow fresh, healthy food through 
                                    sustainable farming education and community participation.
                              </p>
                        </section>
                        <section>
                              <h3>Explore</h3>
                              <p><a href="programs.php">Programs</a></p>
                              <p><a href="resources.php">Resources</a>
                              </p><p><a href="events.php">Events</a></p>
                        </section>
                        <section>
                              <h3>Connect</h3>
                              <p><a href="get-involved.php">Get Involved</a></p>
                              <p><a href="contact.php">Contact Us</a></p>
                              <p>Ndola, Copperbelt Province, Zambia</p>
                        </section>
                  </section>
                  <section class="container copyright">© 
                        <span data-year></span> Green Urban Farms. All rights reserved.
                  </section>
          </footer>
          
          <script src="../js/script.js" defer></script>
    </body>
</html>