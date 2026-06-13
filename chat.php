<?php
session_start();
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

require 'db.php'; // připojení k DB

$user_id = $_SESSION['user_id'];

// Funkce jednoduchého bota
function botReply($message){
    $message = strtolower($message);
    if(strpos($message, 'ahoj') !== false){
        return "Ahoj! Jak vám mohu pomoci s tipy?";
    } elseif(strpos($message, 'tip') !== false){
        return "Zde je můj tip pro dnešek: Sledujte aktuální zápasy a statistiky!";
    } else {
        return "Díky za zprávu! Brzy se vám ozvu.";
    }
}

// Odeslání nové zprávy uživatele a odpovědi bota
if(isset($_POST['message']) && !empty(trim($_POST['message']))) {
    $msg = trim($_POST['message']);
    
    // Uloží zprávu uživatele
    $stmt = $pdo->prepare("INSERT INTO chat (user_id, message, sender) VALUES (?, ?, 'user')");
    $stmt->execute([$user_id, $msg]);

    // Generování odpovědi bota
    $reply = botReply($msg);
    $stmt = $pdo->prepare("INSERT INTO chat (user_id, message, sender) VALUES (?, ?, 'bot')");
    $stmt->execute([$user_id, $reply]);

    header("Location: chat.php");
    exit();
}

// Načtení chat historie
$stmt = $pdo->prepare("SELECT * FROM chat WHERE user_id = ? ORDER BY created_at ASC");
$stmt->execute([$user_id]);
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!doctype html>
<html lang="cs">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Chat — Klient Dashboard</title>
<link rel="stylesheet" href="dashboard.css">
<style>
.chat-box {
    background: #fff;
    padding: 20px;
    border-radius: 14px;
    box-shadow: 0 6px 18px rgba(0,0,0,0.1);
    max-height: 400px;
    overflow-y: auto;
    margin-bottom: 20px;
}
.chat-msg {
    margin-bottom: 12px;
    padding: 12px 16px;
    border-radius: 12px;
    max-width: 75%;
    word-wrap: break-word;
}
.chat-msg.user { background: #3b82f6; color: #fff; margin-left: auto; }
.chat-msg.bot { background: #f3f4f6; color: #111827; margin-right: auto; }
.chat-form { display: flex; gap: 10px; }
.chat-form input { flex: 1; padding: 10px 14px; border-radius: 12px; border: 1px solid #ddd; }
.chat-form button { padding: 10px 20px; border-radius: 12px; border: none; background: #7c3aed; color: #fff; cursor: pointer; }
</style>
</head>
<body>
<?php include 'header.php'; ?>
<?php include 'sidebar.php'; ?>

<div class="main">
    <h2>Chat s botem</h2>

    <div class="chat-box">
        <?php if(count($messages) > 0): ?>
            <?php foreach($messages as $msg): ?>
                <div class="chat-msg <?php echo $msg['sender']; ?>">
                    <?php echo htmlspecialchars($msg['message']); ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p style="text-align:center; color:#6b7280;">Žádné zprávy zatím</p>
        <?php endif; ?>
    </div>

    <form class="chat-form" method="post" action="chat.php">
        <input type="text" name="message" placeholder="Napište zprávu..." required>
        <button type="submit">Odeslat</button>
    </form>
</div>

</body>
</html>
