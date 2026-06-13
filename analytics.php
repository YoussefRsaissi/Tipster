<?php
session_start();
include 'db.php';

// Kontrola admina
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header("Location: login.php");
    exit;
}

// Načti data
$totalVisitors = $pdo->query("SELECT COUNT(*) FROM analytics")->fetchColumn();
$uniqueUsers   = $pdo->query("SELECT COUNT(DISTINCT user_id) FROM analytics")->fetchColumn();

// Připravíme data pro graf (posledních 7 dní)
$stmt = $pdo->query("SELECT DATE(created_at) as day, COUNT(*) as visits 
                     FROM analytics 
                     GROUP BY day 
                     ORDER BY day DESC 
                     LIMIT 7");
$rows = $stmt->fetchAll();

$days = [];
$visits = [];
foreach(array_reverse($rows) as $r){
    $days[] = $r['day'];
    $visits[] = $r['visits'];
}
?>
<!DOCTYPE html>
<html lang="cs">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Analytics - Admin Panel</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
body{font-family:Poppins,sans-serif;background:#f3f4f6;margin:0;}
.main{margin-left:260px;padding:100px 20px;}
h2{margin-bottom:20px;}
.card{background:#fff;padding:20px;border-radius:14px;box-shadow:0 4px 12px rgba(0,0,0,0.08);margin-bottom:20px;}
.kpi{display:flex;gap:20px;flex-wrap:wrap;margin-bottom:20px;}
.kpi div{flex:1;padding:20px;border-radius:14px;text-align:center;color:#fff;font-weight:600;font-size:18px;}
.kpi div:nth-child(1){background:linear-gradient(135deg,#3b82f6,#6366f1);}
.kpi div:nth-child(2){background:linear-gradient(135deg,#10b981,#059669);}
.chart-container{width:100%;max-width:600px;height:300px;margin:0 auto;}
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
    <h2>Analytics</h2>

    <div class="kpi">
        <div>👥 Návštěvy: <?= $totalVisitors ?></div>
        <div>🧑‍💻 Unikátní uživatelé: <?= $uniqueUsers ?></div>
    </div>

    <div class="card">
        <h3>Návštěvy za posledních 7 dní</h3>
        <canvas id="visitsChart" class="chart-container"></canvas>
    </div>
</div>

<script>
const ctx = document.getElementById('visitsChart').getContext('2d');
new Chart(ctx,{
    type:'line',
    data:{
        labels: <?= json_encode($days) ?>,
        datasets:[{
            label:'Počet návštěv',
            data: <?= json_encode($visits) ?>,
            borderColor:'#3b82f6',
            backgroundColor:'rgba(59,130,246,0.2)',
            fill:true,
            tension:0.3
        }]
    },
    options:{responsive:true,plugins:{legend:{display:false}},scales:{y:{beginAtZero:true}}}
});
</script>
</body>
</html>
