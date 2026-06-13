<?php
session_start();
include 'db.php';

// Kontrola přihlášení
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Načteme oznámení
$announcements = $pdo->query("SELECT * FROM announcements ORDER BY created_at DESC LIMIT 10")->fetchAll();
?>
<!DOCTYPE html>
<html lang="cs">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Oznámení</title>
<link rel="stylesheet" href="dashboard.css">
<style>
.card {
    background:#fff;
    padding:20px;
    border-radius:14px;
    box-shadow:0 6px 18px rgba(0,0,0,0.1);
    margin-bottom:20px;
}
.card h3 {
    margin-bottom:10px;
    color:#3b82f6;
}
.card p {
    margin:0 0 10px;
}
.date {
    font-size:12px;
    color:#6b7280;
}
</style>
</head>
<body>
<div class="main">
    <h2>Oznámení od admina</h2>
    <?php if ($announcements): ?>
        <?php foreach ($announcements as $a): ?>
        <div class="card">
            <h3><?= htmlspecialchars($a['title']) ?></h3>
            <p><?= nl2br(htmlspecialchars($a['message'])) ?></p>
            <p class="date">📅 <?= $a['created_at'] ?></p>
        </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Momentálně nejsou žádná oznámení.</p>
    <?php endif; ?>
</div>
</body>
</html>
