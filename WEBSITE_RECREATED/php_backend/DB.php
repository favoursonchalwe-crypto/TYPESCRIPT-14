<?php
declare(strict_types=1);

$host = 'localhost';
$dbname = 'green_urban';
$username = 'root';
$password = ''; 

try {
    $pdo = new PDO(
        "mysql:host={$host};dbname={$dbname};charset=utf8mb4",
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );
} catch (PDOException $exception) {
    error_log('Green Urban Farms database connection failed: ' . $exception->getMessage());
    http_response_code(500);
    exit('Unable to connect to the Green Urban Farms database. Check phpMyAdmin/XAMPP and DB.php settings.');
}
