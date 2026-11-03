<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/recovery-config.php';
function h($v) { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }
function recovery_csrf() {
    if (empty($_SESSION['recovery_csrf'])) $_SESSION['recovery_csrf'] = bin2hex(random_bytes(32));
    return $_SESSION['recovery_csrf'];
}
function recovery_check_csrf() {
    return isset($_POST['csrf'], $_SESSION['recovery_csrf']) && hash_equals($_SESSION['recovery_csrf'], $_POST['csrf']);
}
function recovery_base_url() {
    if (GUf_BASE_URL !== '') return rtrim(GUf_BASE_URL, '/');
    $https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
    $scheme = $https ? 'https' : 'http';
    return $scheme . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost') . rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
}
function recovery_rate_limited(PDO $pdo, string $ip): bool {
    $q = $pdo->prepare("SELECT COUNT(*) FROM password_resets WHERE request_ip = ? AND created_at > (NOW() - INTERVAL 15 MINUTE)");
    $q->execute([$ip]);
    return (int)$q->fetchColumn() >= 5;
}
function recovery_save(PDO $pdo, int $userId, string $method, string $secret, string $ip): void {
    $pdo->prepare('DELETE FROM password_resets WHERE user_id = ? OR expires_at < NOW() OR used_at IS NOT NULL')->execute([$userId]);
    $q = $pdo->prepare('INSERT INTO password_resets (user_id, method, secret_hash, expires_at, request_ip) VALUES (?, ?, ?, DATE_ADD(NOW(), INTERVAL 30 MINUTE), ?)');
    $q->execute([$userId, $method, hash('sha256', $secret), $ip]);
}
function recovery_send_sms(string $phone, string $message): bool {
    if (!function_exists('curl_init') || AT_API_KEY === 'PASTE_SANDBOX_API_KEY_HERE' || AT_API_KEY === '') return false;
    $data = ['username' => AT_USERNAME, 'to' => $phone, 'message' => $message];
    if (AT_SENDER_ID !== '') $data['from'] = AT_SENDER_ID;
    $ch = curl_init('https://api.sandbox.africastalking.com/version1/messaging');
    curl_setopt_array($ch, [CURLOPT_POST => true, CURLOPT_POSTFIELDS => http_build_query($data), CURLOPT_RETURNTRANSFER => true, CURLOPT_HTTPHEADER => ['apiKey: ' . AT_API_KEY, 'Accept: application/json', 'Content-Type: application/x-www-form-urlencoded'], CURLOPT_TIMEOUT => 20]);
    $body = curl_exec($ch); $status = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE); curl_close($ch);
    if ($body === false || $status < 200 || $status >= 300) return false;
    $json = json_decode($body, true);
    return isset($json['SMSMessageData']['Recipients'][0]['statusCode']) && (int)$json['SMSMessageData']['Recipients'][0]['statusCode'] === 101;
}
