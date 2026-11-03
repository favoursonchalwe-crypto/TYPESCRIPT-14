<?php
    if (session_status() !== PHP_SESSION_ACTIVE) session_start();
    require_once __DIR__ . '/db.php';
    $error=''; $success='';
    if ($_SERVER['REQUEST_METHOD']==='POST') {
        $name=trim($_POST['full_name']??''); $email=strtolower(trim($_POST['email']??'')); $phone=trim($_POST['phone']??''); $type=$_POST['participation_type']??'volunteer';
        $allowed=['volunteer','community_member','partner','donor','other']; $interests=trim($_POST['interests']??''); $availability=trim($_POST['availability']??''); $experience=trim($_POST['experience']??''); $message=trim($_POST['message']??'');
        if($name==='' || !filter_var($email,FILTER_VALIDATE_EMAIL) || !in_array($type,$allowed,true)) $error='Please enter your name, valid email, and participation type.';
            else { 
                $uid=isset($_SESSION['user_id'])?(int)$_SESSION['user_id']:null;
            
                $q=$pdo->prepare("
                INSERT INTO volunteer_applications(name,email,phone,interests,experience,availability,status) 
                VALUES
                (?,?,?,?,?,?, 'pending')
                ");

                $q->execute([
                $name,
                $email,
                $phone ?: null,
                $interests ?: null,
                $experience ?: null,
                $availability ?: null,
                ]); 
                
                $success='Thank you. Your application has been submitted.'; 
            }
}
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Get Involved | Green Urban Farms</title><link rel="stylesheet" href="../css/style.css"><script src="../js/script.js" defer></script></head><body>
<?php include __DIR__ . '/header.php'; ?><main><section class="page-hero page-banner page-banner-involved"><section class="container"><p class="page-kicker">BE PART OF THE CHANGE</p><h1>Grow something good with us.</h1><p class="lead">Volunteer, participate, partner or share your skills. Every contribution helps build greener, healthier communities.</p><section class="actions"><a class="btn btn-primary" href="#application">Apply to Get Involved</a><a class="btn btn-outline" href="contact.php">Talk to Our Team</a></section></section></section>
<section class="section" id="application"><section class="container split"><section><h2>There is a place for you</h2><img class="section-photo inline-feature-photo" src="../images/Get-Intouch.jpg" alt="People connecting and getting involved in community growing"><p>Whether you have growing experience or are just getting started, you can contribute time, ideas and energy to community food-growing initiatives.</p><section class="cards involvement-options"><article class="card"><section class="icon">🌱</section><h3>Volunteer</h3><p>Support garden activities and community events.</p></article><article class="card"><section class="icon">🤝</section><h3>Partner</h3><p>Work with us to strengthen local growing projects.</p></article></section></section>
<form class="form-card" method="post"><h2>Submit an Application</h2><?php if($error): ?><p role="alert"><?= htmlspecialchars($error) ?></p><?php endif; ?><?php if($success): ?><p role="status"><?= htmlspecialchars($success) ?></p><?php endif; ?><section class="form-grid"><section class="field"><label for="full_name">Full name</label><input id="full_name" name="full_name" maxlength="150" required></section><section class="field"><label for="email">Email address</label><input id="email" name="email" type="email" maxlength="190" required></section><section class="field"><label for="phone">Phone</label><input id="phone" name="phone" maxlength="40"></section><section class="field"><label for="participation_type">How would you like to participate?</label><select id="participation_type" name="participation_type"><option value="volunteer">Volunteer</option><option value="community_member">Community member</option><option value="partner">Partner</option><option value="donor">Donor</option><option value="other">Other</option></select></section><section class="field field-full"><label for="interests">Areas of interest</label><textarea id="interests" name="interests"></textarea></section><section class="field"><label for="experience">Relevant experience</label><input id="experience" name="experience" maxlength="500"></section><section class="field"><label for="availability">Availability</label><input id="availability" name="availability" maxlength="150"></section><section class="field field-full"><label for="message">Additional message</label><textarea id="message" name="message"></textarea></section><section class="field field-full"><button class="btn btn-primary" type="submit">Submit Application</button></section></section></form></section></section></main><?php include __DIR__ . '/footer.php'; ?></body></html>