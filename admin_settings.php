<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include 'db.php';

// Kontrola admina
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header("Location: login.php");
    exit;
}

$msg = "";

// Změna hesla admina
if (isset($_POST['change_password'])) {
    $newPass = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("UPDATE users SET password=? WHERE id=?");
    $stmt->execute([$newPass, $_SESSION['user_id']]);
    $msg = "✅ Heslo bylo změněno.";
}

// Změna kontaktního emailu
if (isset($_POST['change_email'])) {
    $email = $_POST['contact_email'];
    $stmt = $pdo->prepare("UPDATE settings SET value=? WHERE name='contact_email'");
    $stmt->execute([$email]);
    $msg = "✅ Kontaktní e-mail byl uložen.";
}

// Přepnutí režimu údržby
if (isset($_POST['toggle_maintenance'])) {
    $mode = $_POST['maintenance'] === "on" ? "on" : "off";
    $stmt = $pdo->prepare("UPDATE settings SET value=? WHERE name='maintenance_mode'");
    $stmt->execute([$mode]);
    $msg = "✅ Režim údržby změněn na: $mode";
}

// Načtení aktuálních hodnot
$settings = $pdo->query("SELECT name,value FROM settings")->fetchAll(PDO::FETCH_KEY_PAIR);
?>
<!DOCTYPE html>
<html lang="cs">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Nastavení admina - Admin Panel</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
body{font-family:Poppins,sans-serif;background:#f3f4f6;margin:0;}
.main{margin-left:260px;padding:100px 20px;}
h2{margin-bottom:20px;}
.card{background:#fff;padding:20px;border-radius:14px;box-shadow:0 4px 12px rgba(0,0,0,0.08);margin-bottom:20px;}
form{display:flex;flex-direction:column;gap:10px;}
input,select{padding:10px;border:1px solid #ccc;border-radius:8px;}
button{padding:10px 16px;border:none;border-radius:8px;background:#3b82f6;color:#fff;font-weight:600;cursor:pointer;}
button:hover{opacity:0.9;}
.msg{margin-bottom:20px;font-weight:600;color:#10b981;}
.btn-back {
    display:inline-flex;
    align-items:center;
    background:#3b82f6;
    color:#fff;
    padding:10px 16px;
    border-radius:8px;
    text-decoration:none;
    font-weight:600;
    margin-bottom:20px;
}
.btn-back i {margin-right:8px;}
.btn-back:hover {opacity:0.9;}
</style>
</head>
<body>
<div class="main">
    <a href="admin.php" class="btn-back"><i class="fas fa-arrow-left"></i> Zpět na admin panel</a>
    <h2>Nastavení admina</h2>
    <?php if($msg): ?><p class="msg"><?= $msg ?></p><?php endif; ?>

    <div class="card">
        <h3>Změna hesla</h3>
        <form method="post">
            <input type="password" name="new_password" placeholder="Nové heslo" required>
            <button type="submit" name="change_password">Uložit</button>
        </form>
    </div>

    <div class="card">
        <h3>Kontaktní e-mail</h3>
        <form method="post">
            <input type="email" name="contact_email" value="<?= htmlspecialchars($settings['contact_email'] ?? '') ?>" required>
            <button type="submit" name="change_email">Uložit</button>
        </form>
    </div>

    <div class="card">
        <h3>Režim údržby</h3>
        <form method="post">
            <select name="maintenance">
                <option value="off" <?= ($settings['maintenance_mode'] ?? 'off')==='off'?'selected':'' ?>>Vypnuto</option>
                <option value="on" <?= ($settings['maintenance_mode'] ?? 'off')==='on'?'selected':'' ?>>Zapnuto</option>
            </select>
            <button type="submit" name="toggle_maintenance">Uložit</button>
        </form>
    </div>
</div>
</body>
</html>
