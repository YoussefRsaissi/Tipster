<?php
session_start();
require 'db.php';

// Ověření admina
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sport = $_POST['sport'] ?? '';
    $tip = $_POST['tip'] ?? '';
    $odds = $_POST['odds'] ?? null;
    $result = $_POST['result'] ?? null;

    if ($sport && $tip) {
        $stmt = $pdo->prepare("INSERT INTO tips (sport, tip, odds, result) VALUES (?, ?, ?, ?)");
        $stmt->execute([$sport, $tip, $odds, $result]);
        $success = "✅ Tip byl úspěšně přidán!";
    } else {
        $error = "❌ Vyplň alespoň sport a tip.";
    }
}
?>
<!DOCTYPE html>
<html lang="cs">
<head>
  <meta charset="UTF-8">
  <title>Přidat tip - tipsterAi</title>
  <link rel="stylesheet" href="dashboard.css">
  <style>
    form {
      max-width: 500px;
      margin: 20px auto;
      background: #fff;
      padding: 20px;
      border-radius: 14px;
      box-shadow: 0 6px 18px rgba(0,0,0,0.1);
    }
    form label { display: block; margin-top: 10px; font-weight: 600; }
    form input, form select {
      width: 100%;
      padding: 10px;
      margin-top: 6px;
      border: 1px solid #ccc;
      border-radius: 8px;
    }
    form button {
      margin-top: 15px;
      padding: 12px;
      width: 100%;
      border: none;
      border-radius: 10px;
      background: #3b82f6;
      color: #fff;
      font-weight: 600;
      cursor: pointer;
      transition: 0.2s;
    }
    form button:hover { background: #2563eb; }
    .msg { text-align: center; margin: 10px 0; font-weight: 600; }
    .msg.success { color: #10b981; }
    .msg.error { color: #ef4444; }
  </style>
</head>
<body>
  <!-- Sidebar -->
  <aside class="sidebar">
    <h2 class="logo">tipsterAi</h2>
    <nav>
      <a href="dashboard.php">🏠 Dashboard</a>
      <a href="tips.php">📋 Tipy</a>
      <a href="chat.php">💬 Chat s botem</a>
      <a href="profile.php">👤 Profil</a>
      <a href="settings.php">⚙️ Nastavení</a>
      <a href="logout.php">🚪 Odhlásit se</a>
    </nav>
  </aside>

  <!-- Main -->
  <main class="main">
    <h1>➕ Přidat tip</h1>

    <?php if (!empty($success)): ?>
      <p class="msg success"><?= $success ?></p>
    <?php elseif (!empty($error)): ?>
      <p class="msg error"><?= $error ?></p>
    <?php endif; ?>

    <form method="post">
      <label>Sport</label>
      <select name="sport" required>
        <option value="">-- Vyber sport --</option>
        <option value="Fotbal">Fotbal</option>
        <option value="Hokej">Hokej</option>
        <option value="Tenis">Tenis</option>
        <option value="Basketbal">Basketbal</option>
      </select>

      <label>Zápas / Tip</label>
      <input type="text" name="tip" placeholder="Např. Barcelona vs Real Madrid - Výhra Barcelona" required>

      <label>Kurz</label>
      <input type="number" step="0.01" name="odds" placeholder="Např. 1.85">

      <label>Výsledek</label>
      <select name="result">
        <option value="">⏳ Čeká</option>
        <option value="win">✅ Výhra</option>
        <option value="lose">❌ Prohra</option>
      </select>

      <button type="submit">💾 Uložit tip</button>
    </form>
  </main>
</body>
</html>
