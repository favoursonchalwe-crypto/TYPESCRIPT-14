<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
require_once __DIR__.'/DB.php';
if (!empty($_SESSION['user_id'])) { header('Location: '.(($_SESSION['user_role']??'')==='admin'?'admin.php':'index.php')); exit; }
$error=''; $notice=''; $mode=((($_POST['action']??'')==='register') || (($_GET['action']??'')==='register') || (isset($_GET['register'])))?'register':'login';
if ($_SERVER['REQUEST_METHOD']==='POST') {
 $email=strtolower(trim($_POST['email']??'')); $password=$_POST['password']??'';
 if (!filter_var($email,FILTER_VALIDATE_EMAIL) || $password==='') $error='Enter a valid email and password.';
 elseif ($mode==='register') {
  $name=trim($_POST['name']??'');
  if ($name==='') $error='Enter your name.'; elseif (strlen($password)<8) $error='Password must be at least 8 characters.';
  else { $q=$pdo->prepare('SELECT id FROM users WHERE email=?'); $q->execute([$email]);
   if ($q->fetch()) { $error='That email already has an account.'; $mode='login'; }
   else { $q=$pdo->prepare('INSERT INTO users (name,email,password,role) VALUES (?,?,?,?)'); $q->execute([$name,$email,password_hash($password,PASSWORD_DEFAULT),'user']); $notice='Account created. Please log in.'; $mode='login'; }
  }
 } else {
  $q=$pdo->prepare('SELECT id,name,email,password,role FROM users WHERE email=? LIMIT 1'); $q->execute([$email]); $u=$q->fetch();
 if ($u && password_verify($password,$u['password'])) { session_regenerate_id(true); $_SESSION['user_id']=(int)$u['id']; $_SESSION['user_name']=$u['name']; $_SESSION['user_email']=$u['email']; $_SESSION['user_role']=$u['role']; header('Location: '.($u['role']==='admin'?'admin.php':'index.php')); exit; }
  $error='Email or password is incorrect.';
 }
}
?>
<!doctype html>
<html lang="en">

  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Login / Register | Green Urban Farms</title>
    <link rel="stylesheet" href="../css/style.css">
  </head>
  
  <body>
    <main class="auth">
      <a href="index.php">← Home</a>
      <h1><?= $mode==='register'?'Create account':'Welcome back' ?></h1>
      <?php if($error):?><p class="msg"><?=htmlspecialchars($error)?></p>
      <?php endif;?>
      <?php if($notice):?><p class="msg"><?=htmlspecialchars($notice)?></p>
      <?php endif;?><form method="post"><input type="hidden" name="action" value="<?= $mode==='register'?'register':'login' ?>">
      <?php if($mode==='register'):?><label>Full name</label><input name="name" required>
      <?php endif;?><label>Email</label><input type="email" name="email" required><label>Password</label><input type="password" name="password" minlength="8" required><button><?= $mode==='register'?'Register':'Log in' ?></button></form>
      <p>
        <?php if($mode==='register'):?>Already registered? <a href="login.php">Log in</a><?php else:?>New here? <a href="login.php?action=register">Register</a>
        <?php endif;?>
      </p>
    </main>
    <footer class="site-footer">
      <section class="container footer-grid">
        <section>
          <h3>Green Urban Farms</h3>
          <p>Growing fresh food, sharing knowledge and building greener communities.</p>
        </section>
        <section>
          <h4>Explore</h4>
          <ul class="footer-links">
            <li><a href="programs.php">Programs</a></li>
            <li><a href="../resources/index.html">Resources</a></li>
            <li><a href="../events/index.html">Events</a></li>
          </ul>
        </section>
        <section>
          <h4>Connect</h4>
          <ul class="footer-links">
            <li><a href="../get-involved/index.html">Get Involved</a></li>
            <li><a href="../contact/index.html">Contact Us</a></li>
          </ul>
        </section>
      </section>
      <section class="container copyright">© <span data-year></span> Green Urban Farms. All rights reserved.</section>
    </footer>
    <script src="../js/script.js" defer></script>
  </body>
</html>
