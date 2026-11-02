<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
$script = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '/php_backend/session-status.php');
$root = rtrim(dirname(dirname($script)), '/');
echo json_encode(['loggedIn' => !empty($_SESSION['user_id']), 'role' => $_SESSION['user_role'] ?? null, 'rootPath' => $root, 'registerUrl' => $root . '/php_backend/login.php?action=register', 'loginUrl' => $root . '/php_backend/login.php'], JSON_UNESCAPED_SLASHES);
