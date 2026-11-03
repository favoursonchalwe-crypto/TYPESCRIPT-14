<?php
require_once __DIR__ . '/recovery-common.php';
$error=''; $validUser=0; $resetRow=0;
$token=trim($_GET['token']??''); $ticket=trim($_GET['ticket']??'');
if ($token !== '') {
 $q=$pdo->prepare("SELECT id,user_id FROM password_resets WHERE method='email' AND secret_hash=? AND used_at IS NULL AND expires_at>NOW() LIMIT 1"); $q->execute([hash('sha256',$token)]); $r=$q->fetch(); if($r){$validUser=(int)$r['user_id'];$resetRow=(int)$r['id'];}
} elseif ($ticket !== '' && !empty($_SESSION['verified_reset_user']) && !empty($_SESSION['verified_reset_ticket']) && hash_equals($_SESSION['verified_reset_ticket'],$ticket) && ($_SESSION['verified_reset_expires']??0)>=time()) {
 $validUser=(int)$_SESSION['verified_reset_user'];
}
if ($_SERVER['REQUEST_METHOD']==='POST') {
 if (!recovery_check_csrf()) $error='Session expired. Refresh and try again.';
 else {
  // Revalidate credentials from POST rather than trusting a posted user id.
  $token=trim($_POST['token']??''); $ticket=trim($_POST['ticket']??''); $validUser=0; $resetRow=0;
  if($token!==''){$q=$pdo->prepare("SELECT id,user_id FROM password_resets WHERE method='email' AND secret_hash=? AND used_at IS NULL AND expires_at>NOW() LIMIT 1");$q->execute([hash('sha256',$token)]);$r=$q->fetch();if($r){$validUser=(int)$r['user_id'];$resetRow=(int)$r['id'];}}
  elseif($ticket!=='' && !empty($_SESSION['verified_reset_user']) && hash_equals($_SESSION['verified_reset_ticket']??'', $ticket) && ($_SESSION['verified_reset_expires']??0)>=time()) $validUser=(int)$_SESSION['verified_reset_user'];
  $pw=$_POST['password']??''; $confirm=$_POST['confirm_password']??'';
  if(!$validUser)$error='This reset link or verification session is invalid or expired. Start again.';
  elseif(strlen($pw)<8)$error='Your new password must be at least 8 characters.';
  elseif($pw!==$confirm)$error='The passwords do not match.';
  else{$pdo->prepare('UPDATE users SET password=? WHERE id=?')->execute([password_hash($pw,PASSWORD_DEFAULT),$validUser]);if($resetRow)$pdo->prepare('UPDATE password_resets SET used_at=NOW() WHERE id=?')->execute([$resetRow]);else $pdo->prepare("UPDATE password_resets SET used_at=NOW() WHERE user_id=? AND method='sms' AND used_at IS NULL")->execute([$validUser]);unset($_SESSION['verified_reset_user'],$_SESSION['verified_reset_ticket'],$_SESSION['verified_reset_expires']);header('Location: login.php?reset=success');exit;}
 }
}
if(!$validUser && $_SERVER['REQUEST_METHOD']!=='POST') $error='This reset link or verification session is invalid or expired. Start again.';
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Set New Password | Green Urban Farms</title><link rel="stylesheet" href="../css/style.css"></head><body><?php include __DIR__.'/header.php';?><main><section class="page-hero page-banner page-banner-login"><section class="container"><h1>Set a new password</h1><p class="lead">Choose a strong password for your Green Urban Farms account.</p></section></section><section class="section section-soft"><section class="container"><section class="form-card auth-card"><h2>New Password</h2><?php if($error):?><p role="alert"><?=h($error)?></p><?php endif;?><?php if($validUser):?><form method="post" class="form"><input type="hidden" name="csrf" value="<?=h(recovery_csrf())?>"><input type="hidden" name="token" value="<?=h($token)?>"><input type="hidden" name="ticket" value="<?=h($ticket)?>"><section class="field"><label for="password">New password</label><input id="password" type="password" name="password" minlength="8" autocomplete="new-password" required></section><section class="field"><label for="confirm_password">Confirm new password</label><input id="confirm_password" type="password" name="confirm_password" minlength="8" autocomplete="new-password" required></section><button class="btn btn-primary" type="submit">Update Password</button></form><?php else:?><p><a href="forgot-password.php">Request a new recovery link or code</a></p><?php endif;?></section></section></section></main><?php include __DIR__.'/footer.php';?></body></html>
