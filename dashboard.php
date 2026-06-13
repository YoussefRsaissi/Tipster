<?php
session_start();
require 'db.php';

/* DEBUG – ať místo 500 vidíš chybu */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$debugMsg = null;

/* Bezpečné defaulty */
$successRate = 0;
$newTipsToday = 0;
$topSport = "Žádný";
$topSportCount = 0;
$chartData = [];

try {
    // 🎯 Úspěšnost tipů (počítá 'win')
    $sql = "SELECT 
              COUNT(*) AS total, 
              SUM(CASE WHEN result='win' THEN 1 ELSE 0 END) AS wins
            FROM tips";
    $row = $pdo->query($sql)->fetch(PDO::FETCH_ASSOC);
    $total = (int)($row['total'] ?? 0);
    $wins  = (int)($row['wins'] ?? 0);
    $successRate = $total > 0 ? round(($wins / $total) * 100, 1) : 0;

    // ⚡ Nové tipy dnes (bere created_at nebo date)
    $sql = "SELECT COUNT(*) 
            FROM tips 
            WHERE DATE(COALESCE(created_at, `date`)) = CURDATE()";
    $newTipsToday = (int)$pdo->query($sql)->fetchColumn();

    // 🏆 Top sport týdne
    $sql = "SELECT sport, COUNT(*) AS cnt
            FROM tips
            WHERE YEAR(COALESCE(created_at, `date`)) = YEAR(CURDATE())
              AND WEEK(COALESCE(created_at, `date`), 1) = WEEK(CURDATE(), 1)
            GROUP BY sport
            ORDER BY cnt DESC
            LIMIT 1";
    $row = $pdo->query($sql)->fetch(PDO::FETCH_ASSOC);
    if ($row && !empty($row['sport'])) {
        $topSport = $row['sport'];
        $topSportCount = (int)$row['cnt'];
    }

    // 📈 Graf – posledních 7 dní (wins vs loses)
    $sql = "SELECT 
              DATE(COALESCE(created_at, `date`)) AS d,
              SUM(CASE WHEN result='win'  THEN 1 ELSE 0 END) AS wins,
              SUM(CASE WHEN result='lose' THEN 1 ELSE 0 END) AS loses
            FROM tips
            WHERE COALESCE(created_at, `date`) >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
            GROUP BY d
            ORDER BY d ASC";
    $chartData = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);

} catch (Throwable $e) {
    // Nezabij celou stránku – ukaž chybu nahoře
    $debugMsg = $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="cs">
<head>
  <meta charset="UTF-8">
  <title>Dashboard - tipsterAi</title>
  <link rel="stylesheet" href="dashboard.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
  <!-- Sidebar -->
  <aside class="sidebar">
    <h2 class="logo">tipsterAi</h2>
    <nav>
      <a href="dashboard.php">🏠 Dashboard</a>
      <a href="tips.php">📋 Moje tipy</a>
      <a href="chat.php">💬 Chat s botem</a>
      <a href="profile.php">👤 Profil</a>
      <a href="settings.php">⚙️ Nastavení</a>
      <a href="logout.php">🚪 Odhlásit se</a>
    </nav>
  </aside>

  <!-- Hlavní obsah -->
  <main class="main">
    <?php if ($debugMsg): ?>
      <div style="background:#fee2e2;border:1px solid #ef4444;color:#991b1b;padding:10px 12px;border-radius:8px;margin-bottom:16px;">
        <strong>Chyba:</strong> <?= htmlspecialchars($debugMsg) ?>
      </div>
    <?php endif; ?>

    <h1>📊 Můj dashboard</h1>

    <!-- KPI boxy -->
    <div class="kpi">
      <div class="kpi-box success">
        <h3>Úspěšnost tipů</h3>
        <p><?= $successRate ?> %</p>
      </div>
      <div class="kpi-box info">
        <h3>Nové tipy dnes</h3>
        <p><?= $newTipsToday ?></p>
      </div>
      <div class="kpi-box highlight">
        <h3>Top sport týdne</h3>
        <p><?= htmlspecialchars($topSport) ?> (<?= (int)$topSportCount ?>)</p>
      </div>
    </div>

    <!-- Graf -->
    <section class="analytics">
      <h2>📈 Výsledky za posledních 7 dní</h2>
      <canvas id="tipsChart"></canvas>
    </section>

    <!-- Poslední tipy -->
    <section class="tips">
      <h2>📌 Poslední tipy</h2>
      <table>
        <tr>
          <th>ID</th>
          <th>Sport</th>
          <th>Tip</th>
          <th>Výsledek</th>
          <th>Datum</th>
        </tr>
        <?php
        try {
          $sql = "SELECT id, sport, tip, result, COALESCE(created_at, `date`) AS dt
                  FROM tips
                  ORDER BY COALESCE(created_at, `date`) DESC
                  LIMIT 5";
          $q = $pdo->query($sql);
          while ($row = $q->fetch(PDO::FETCH_ASSOC)):
        ?>
        <tr>
          <td><?= (int)$row['id'] ?></td>
          <td><?= htmlspecialchars($row['sport'] ?? '—') ?></td>
          <td><?= htmlspecialchars($row['tip'] ?? '—') ?></td>
          <td><?= $row['result'] ? htmlspecialchars($row['result']) : 'Čeká' ?></td>
          <td><?= htmlspecialchars($row['dt'] ?? '') ?></td>
        </tr>
        <?php
          endwhile;
        } catch (Throwable $e) {
          echo '<tr><td colspan="5">Nelze načíst tipy: '.htmlspecialchars($e->getMessage()).'</td></tr>';
        }
        ?>
      </table>
    </section>
  </main>

  <script>
  (function(){
    const labels = <?= json_encode(array_column($chartData, 'd')) ?: '[]' ?>;
    const wins   = <?= json_encode(array_map('intval', array_column($chartData, 'wins'))) ?: '[]' ?>;
    const loses  = <?= json_encode(array_map('intval', array_column($chartData, 'loses'))) ?: '[]' ?>;

    const ctx = document.getElementById('tipsChart');
    if (!ctx) return;

    new Chart(ctx.getContext('2d'), {
      type: 'line',
      data: {
        labels,
        datasets: [
          { label: 'Výhry', data: wins,  borderColor:'#10b981', backgroundColor:'rgba(16,185,129,0.2)', borderWidth:2, tension:.3, fill:true },
          { label: 'Prohry',data: loses, borderColor:'#ef4444', backgroundColor:'rgba(239,68,68,0.2)', borderWidth:2, tension:.3, fill:true }
        ]
      },
      options: {
        responsive:true,
        plugins:{ legend:{ position:'top' } },
        scales:{ y:{ beginAtZero:true, precision:0 } }
      }
    });
  })();
  </script>
</body>
</html>
