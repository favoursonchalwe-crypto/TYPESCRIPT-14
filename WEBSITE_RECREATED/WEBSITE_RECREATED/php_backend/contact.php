<?php
require_once __DIR__ . '/DB.php';
$error=''; $success='';
if ($_SERVER['REQUEST_METHOD']==='POST') {
 $name=trim($_POST['name']??''); $email=trim($_POST['email']??''); $subject=trim($_POST['subject']??''); $message=trim($_POST['message']??'');
 if ($name==='' || !filter_var($email,FILTER_VALIDATE_EMAIL) || $message==='') $error='Please enter your name, a valid email, and a message.';
 else { $q=$pdo->prepare('INSERT INTO contact_messages (name,email,subject,message) VALUES (?,?,?,?)'); $q->execute([$name,$email,$subject?:null,$message]); $success='Thank you. Your message has been received.'; }
}
?>
<!DOCTYPE html>
<html lang="en">

      <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Contact Us | Green Urban Farms</title>
            <link rel="stylesheet" href="../css/style.css">
      </head>
      
      <body>
            <?php include __DIR__ . '/header.php'; ?>
<!-- shared header included above -->
<!--<header>
                  <section class="container navbar">
                        <a class="logo" href="index.php"><img src="Logo.png"></a>
                        <a class="logo" href="index.php">GREEN <span>URBAN FARMS</span></a>
                        <button class="menu-btn" aria-label="Open menu">☰</button>
                        <nav>
                              <ul class="nav-links">
                                    <li><a href="index.php">Home</a></li>
                                    <li><a href="about.html">About Us</a></li>
                                    <li><a href="programs.php">Programs</a></li>
                                    <li><a href="resources.php">Resources</a></li>
                                    <li><a href="events.php">Events</a></li>
                                    <li><a href="get-involved.php">Get Involved</a></li>
                                    <li><a href="contact.php">Contact Us</a></li>
                              </ul>
                        </nav>
                  </section>
            </header>-->
            
            <main>
                  <section class="page-hero">
                        <section class="container">
                              <h1>Contact Us</h1>
                              <p class="lead">Have a question, idea or collaboration proposal? Send us a message.</p>
                        </section>
                  </section>
                  <section class="section">
                        <section class="container grid-2">
                              <section>
                                    <h2>Green Urban Farms</h2>
                                    <p>Ndola, Copperbelt Province, Zambia</p>
                                    <p class="mt-10">Email: info@greenurbanfarms.org</p>
                                    <p>Phone: +260 076 751 915</p>
                                    <a href="tel:+260976751915">Click me to call us</a>
                                    <section class="callout">
                                          <h3>Office Hours</h3>
                                          <p>Monday–Friday: 08:00–17:00</p>
                                    </section>
                              </section>
                              <form class="form" method="post" action="contact.php">
                                    <h2>Send a Message</h2>
                                    <?php if($error): ?><p class="error" role="alert"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p><?php endif; ?>
                                    <?php if($success): ?><p class="success" role="status"><?= htmlspecialchars($success, ENT_QUOTES, 'UTF-8') ?></p><?php endif; ?>
                                    <label for="name">Name</label>
                                    <input id="name" name="name" maxlength="150" required>
                                    <label for="email">Email</label>
                                    <input id="email" name="email" type="email" maxlength="190" required>
                                    <label for="subject">Subject</label><input id="subject" name="subject" maxlength="200">
                                    <label for="message">Message</label>
                                    <textarea id="message" name="message" required></textarea>
                                    <button class="btn btn-primary" type="submit">Send Message</button>
                                    
                              </form>
                        </section>
                  </section>
            </main>
            
            <footer class="site-footer">
                  <section class="container footer-grid">
                        <section>
                              <h3>Green Urban Farms</h3>
                              <p>Empowering urban communities to grow fresh, healthy food through sustainable farming
                              education and community participation.</p>
                        </section>
                        <section>
                              <h3>Explore</h3>
                              <p><a href="programs.php">Programs</a></p>
                              <p><a href="resources.php">Resources</a></p>
                              <p><a href="events.php">Events</a></p>
                        </section>
                        <section>
                              <h3>Connect</h3>
                              <p><a href="get-involved.php">Get Involved</a></p>
                              <p><a href="contact.php">Contact Us</a></p>
                              <p>Ndola, Copperbelt Province, Zambia</p>
                        </section>
                  </section>
                  <section class="container copyright">
                        © <span data-year></span> Green Urban Farms. All rights reserved.
                  </section>
            </footer>
            
            <script src="../js/script.js" defer></script>
      </body>
</html>