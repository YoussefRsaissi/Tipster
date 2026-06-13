<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include 'db.php';

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username=? LIMIT 1");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user) {
        if (password_verify($password, $user['password'])) {
            // uložíme session
            $_SESSION['user'] = $user['username'];
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];

            // přesměrování podle role
            if ($user['role'] === 'admin') {
                header("Location: admin.php");
            } else {
                header("Location: dashboard.php");
            }
            exit;
        } else {
            $error = "❌ Špatné heslo";
        }
    } else {
        $error = "❌ Uživatel nenalezen";
    }
}
?>

<!DOCTYPE html>
<html lang="cs">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login - tipsterAi</title>
<link rel="stylesheet" href="assets/css/style.css">
<style>
body { font-family:'Poppins',sans-serif; background:#f3f4f6; }
form{max-width:400px;margin:80px auto;padding:24px;background:#fff;border-radius:14px;box-shadow:0 6px 18px rgba(0,0,0,0.1);}
form h2{text-align:center;margin-bottom:20px;}
form input{width:100%;padding:12px;margin:8px 0;border-radius:8px;border:1px solid #ccc;}
form button{width:100%;padding:12px;background:#3b82f6;color:#fff;font-weight:700;border:none;border-radius:8px;cursor:pointer;}
form button:hover{background:#2563eb;}
form p{color:red;text-align:center;margin-top:10px;}
</style>
</head>
<body>
<form method="POST" action="">
    <h2>Přihlášení</h2>
    <input type="text" name="username" placeholder="Uživatelské jméno" required>
    <input type="password" name="password" placeholder="Heslo" required>
    <button type="submit">Login</button>
    <?php if($error) echo "<p>$error</p>"; ?>
</form>
</body>
</html>
