<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
require_once __DIR__ . '/DB.php';
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
                                          <a class="btn btn-primary" href="login.php?action=register">Explore Programs</a>
                                          <a class="btn btn-secondary" href="login.php?action=register">Join a Program</a>
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