<?php
require_once __DIR__ . '/recovery-common.php';
$error=''; $ok=false;
if ($_SERVER['REQUEST_METHOD']==='POST') {
 if (!recovery_check_csrf()) $error='Session expired. Refresh and try again.';
 else {
  $phone=trim($_POST['phone']??''); $code=trim($_POST['code']??'');
  if (!preg_match('/^[0-9]{6}$/',$code)) $error='Enter the six-digit code.';
  else {
   $q=$pdo->prepare("SELECT pr.id,pr.user_id FROM password_resets pr JOIN users u ON u.id=pr.user_id WHERE pr.method='sms' AND pr.secret_hash=? AND u.phone=? AND pr.used_at IS NULL AND pr.expires_at>NOW() ORDER BY pr.id DESC LIMIT 1");
   $q->execute([hash('sha256',$code),$phone]); $row=$q->fetch();
   if ($row) { $ticket=bin2hex(random_bytes(32)); $_SESSION['verified_reset_user']=(int)$row['user_id']; $_SESSION['verified_reset_ticket']=$ticket; $_SESSION['verified_reset_expires']=time()+600; $pdo->prepare('UPDATE password_resets SET used_at=NOW() WHERE id=?')->execute([$row['id']]); header('Location: reset-password.php?ticket='.urlencode($ticket)); exit; }
   $error='That code is invalid or has expired. Request a new code.';
  }
 }
}
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Verify SMS Code | Green Urban Farms</title><link rel="stylesheet" href="../css/style.css"></head><body><?php include __DIR__.'/header.php'; ?><main><section class="page-hero page-banner page-banner-login"><section class="container"><h1>Verify your phone</h1><p class="lead">Enter the code sent to your registered mobile number.</p></section></section><section class="section section-soft"><section class="container"><section class="form-card auth-card"><h2>SMS Verification</h2><?php if($error):?><p role="alert"><?=h($error)?></p><?php endif;?><form method="post" class="form"><input type="hidden" name="csrf" value="<?=h(recovery_csrf())?>"><section class="field"><label for="phone">Registered phone (+260...)</label><input id="phone" name="phone" required></section><section class="field"><label for="code">Six-digit code</label><input id="code" name="code" inputmode="numeric" pattern="[0-9]{6}" maxlength="6" required></section><button class="btn btn-primary" type="submit">Verify Code</button></form></section></section></section></main><?php include __DIR__.'/footer.php';?></body></html>
