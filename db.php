<?php
$host = "localhost"; // Veritabanı sunucusu
$dbname = "BiletUygulamasi"; // Veritabanı adı
$username = "root"; // Kullanıcı adı
$password = ""; // Şifre

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Veritabanı bağlantı hatası: " . $e->getMessage());
}
?>
