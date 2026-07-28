<?php
$config = require __DIR__ . '/config.php';
date_default_timezone_set($config['timezone']);

$dsn = sprintf(
    'mysql:host=%s;dbname=%s;charset=%s',
    $config['db_host'],
    $config['db_name'],
    $config['db_charset']
);

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

try {
    $pdo = new PDO($dsn, $config['db_user'], $config['db_pass'], $options);
} catch (PDOException $e) {
    http_response_code(500);
    exit('Αποτυχία σύνδεσης με τη βάση: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8'));
}
