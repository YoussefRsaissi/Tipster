<?php
session_start();
include 'db.php';

// Kontrola admina
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header("Location: login.php");
    exit;
}

$id = $_SESSION['user_id'];
$msg = "";

// Uložení změn
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first = $_POST['first_name'] ?? '';
    $last = $_POST['last_name'] ?? '';
    $phone = $_POST['phone'] ?? '';
    
    // Upload profilovky
    $profile_pic = null;
    if (!empty($_FILES['profile_pic']['name'])) {
        $fileName = "admin_" . time() . "_" . basename($_FILES['profile_pic']['name']);
        $targetPath = "uploads/" . $fileName;
        if (move_uploaded_file($_FILES['profile_pic']['tmp_name'], $targetPath)) {
            $profile_pic = $targetPath;
        }
    }

    $sql = "UPDATE users SET first_name=?, last_name=?, phone=?";
    $params = [$first, $last, $phone];

    if ($profile_pic) {
        $sql .= ", profile_pic=?";
        $params[] = $profile_pic;
    }

    $sql .= " WHERE id=?";
    $params[] = $id;

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    $msg = "✅ Profil admina byl úspěšně aktualizován.";
}

// Načti data admina
$stmt = $pdo->prepare("SELECT * FROM users WHERE id=?");
$stmt->execute([$id]);
$admin = $stmt->fetch();
?>
<!DOCTYPE html>
<html lang="cs">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Profil admina</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
body{font-family:Poppins,sans-serif;background:#f3f4f6;margin:0;}
.main{margin-left:260px;padding:100px 20px;}
.card{background:#fff;padding:20px;border-radius:14px;box-shadow:0 4px 12px rgba(0,0,0,0.08);margin-bottom:20px;max-width:500px;}
form{display:flex;flex-direction:column;gap:12px;}
input{padding:10px;border:1px solid #ccc;border-radius:8px;}
button{padding:10px 16px;border:none;border-radius:8px;background:#3b82f6;color:#fff;font-weight:600;cursor:pointer;}
button:hover{opacity:0.9;}
.msg{margin-bottom:20px;font-weight:600;color:#10b981;}
.profile-pic{width:100px;height:100px;border-radius:50%;object-fit:cover;margin-bottom:10px;border:3px solid #3b82f6;}
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
    <h2>Profil admina</h2>
    <?php if($msg): ?><p class="msg"><?= $msg ?></p><?php endif; ?>
    <div class="card">
        <?php if($admin['profile_pic']): ?>
            <img src="<?= $admin['profile_pic'] ?>" alt="Profilová fotka" class="profile-pic">
        <?php else: ?>
            <img src="https://via.placeholder.com/100" alt="Profilová fotka" class="profile-pic">
        <?php endif; ?>

        <form method="post" enctype="multipart/form-data">
            <input type="text" name="first_name" value="<?= htmlspecialchars($admin['first_name'] ?? '') ?>" placeholder="Jméno">
            <input type="text" name="last_name" value="<?= htmlspecialchars($admin['last_name'] ?? '') ?>" placeholder="Příjmení">
            <input type="text" name="phone" value="<?= htmlspecialchars($admin['phone'] ?? '') ?>" placeholder="Telefon">
            <input type="file" name="profile_pic">
            <button type="submit">Uložit změny</button>
        </form>
    </div>
</div>
</body>
</html>
