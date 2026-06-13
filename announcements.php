<?php
session_start();
include 'db.php';

// Kontrola admina
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header("Location: login.php");
    exit;
}

// Přidání oznámení
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $message = $_POST['message'] ?? '';
    if ($title && $message) {
        $stmt = $pdo->prepare("INSERT INTO announcements (title, message) VALUES (?, ?)");
        $stmt->execute([$title, $message]);
    }
}

// Mazání oznámení
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM announcements WHERE id=?");
    $stmt->execute([$id]);
}

$announcements = $pdo->query("SELECT * FROM announcements ORDER BY created_at DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="cs">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Oznámení - Admin</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
body{font-family:Poppins,sans-serif;background:#f3f4f6;margin:0;}
.main{margin-left:260px;padding:100px 20px;}
.card{background:#fff;padding:20px;border-radius:14px;box-shadow:0 4px 12px rgba(0,0,0,0.08);margin-bottom:20px;}
form{display:flex;flex-direction:column;gap:10px;}
input,textarea{padding:10px;border:1px solid #ccc;border-radius:8px;}
button{padding:10px;border:none;border-radius:8px;background:#3b82f6;color:#fff;font-weight:600;cursor:pointer;}
button:hover{opacity:0.9;}
table{width:100%;border-collapse:collapse;margin-top:20px;}
th,td{padding:12px;text-align:left;border-bottom:1px solid #eee;}
th{background:#3b82f6;color:#fff;}
a.delete{color:red;text-decoration:none;}
</style>
</head>
<body>
<div class="main">
    <h2>Oznámení</h2>
    <div class="card">
        <h3>Přidat nové oznámení</h3>
        <form method="post">
            <input type="text" name="title" placeholder="Nadpis" required>
            <textarea name="message" placeholder="Zpráva" rows="4" required></textarea>
            <button type="submit">Přidat</button>
        </form>
    </div>

    <div class="card">
        <h3>Seznam oznámení</h3>
        <table>
            <tr><th>ID</th><th>Nadpis</th><th>Zpráva</th><th>Datum</th><th>Akce</th></tr>
            <?php foreach($announcements as $a): ?>
            <tr>
                <td><?= $a['id'] ?></td>
                <td><?= htmlspecialchars($a['title']) ?></td>
                <td><?= htmlspecialchars($a['message']) ?></td>
                <td><?= $a['created_at'] ?></td>
                <td><a href="?delete=<?= $a['id'] ?>" class="delete">Smazat</a></td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
</div>
</body>
</html>
