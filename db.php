<?php
$host = "localhost";              // DB host
$dbname = "root";          // název DB 
$username = "name";            // uživatel DB
$password = "pass";       // heslo do DB

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("❌ Připojení k databázi selhalo: " . $e->getMessage());
}
