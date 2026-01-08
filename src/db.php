<?php
$host = 'db';
$dbname = 'yakiniku_db';
$username = 'root';
$password = 'root';

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4'
        ]
    );
} catch (PDOException $e) {
    echo "DB接続失敗: " . $e->getMessage();
    exit();
}
?>