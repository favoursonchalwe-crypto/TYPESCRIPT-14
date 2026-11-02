<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
function h($value): string { return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8'); }
function require_login(): void { if (empty($_SESSION['user_id'])) { header('Location: login.php'); exit; } }
function require_admin(): void { require_login(); if (($_SESSION['user_role'] ?? '') !== 'admin') { http_response_code(403); exit('Access denied. Administrator access is required.'); } }
