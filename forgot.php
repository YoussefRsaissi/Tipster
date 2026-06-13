<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    if ($email === '') {
        $error = "Zadejte e-mail.";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user) {
            $token = bin2hex(random_bytes(16));
            $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));
            $upd = $pdo->prepare("UPDATE users SET reset_token=?, reset_expires=? WHERE id=?");
            $upd->execute([$token, $expires, $user['id']]);

            // Odkaz pro reset
            $resetLink = "https://www.tipsterai.cz/Tipster/reset.php?token=" . $token;

            // PHPMailer
            require 'lib/PHPMailer-master/src/Exception.php';
            require 'lib/PHPMailer-master/src/PHPMailer.php';
            require 'lib/PHPMailer-master/src/SMTP.php';
            use PHPMailer\PHPMailer\PHPMailer;

            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host = 'smtp.forpsi.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'info@tipsterai.cz'; // tvůj email
            $mail->Password = 'gT8qG@thd6';         // tvé heslo
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;
            $mail->CharSet = 'UTF-8';

            $mail->setFrom('info@tipsterai.cz', 'tipsterAi');
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = "🔑 Reset hesla - tipsterAi";
            $mail->Body = "
                <div style='font-family:Arial,sans-serif;max-width:600px;margin:auto;background:#fff;border-radius:10px;padding:24px;box-shadow:0 10px 30px rgba(0,0,0,.1)'>
                  <h2 style='color:#3b82f6;margin:0 0 10px'>Obnova hesla</h2>
                  <p>Odkaz platí 60 minut. Pokračuj zde:</p>
                  <p><a href='{$resetLink}' style='display:inline-block;background:#3b82f6;color:#fff;padding:12px 18px;border-radius:10px;text-decoration:none;font-weight:700'>Resetovat heslo</a></p>
                  <p style='color:#6b7280;font-size:12px;margin-top:24px'>Pokud jsi o reset nežádal, ignoruj tento e-mail.</p>
                </div>";
            $mail->send();

            $success = "Pokud email existuje, poslali jsme odkaz na reset hesla.";
        } else {
            $success = "Pokud email existuje, poslali jsme odkaz na reset hesla.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="cs">
<head>
  <meta charset="UTF-8">
  <title>Zapomenuté heslo - TipsterAi</title>
  <link rel="stylesheet" href="assets/css/style.css">
  <style>
    body {font-family:'Poppins',sans-serif;background:#f3f4f6;}
    .forgot-box {
        max-width:400px;
        margin:60px auto;
        padding:24px;
        background:#fff;
        border-radius:14px;
        box-shadow:0 6px 18px rgba(0,0,0,0.1);
    }
    .forgot-box h2 {text-align:center;margin-bottom:20px;}
    .forgot-box input {
        width:100%;padding:12px;margin:8px 0;border-radius:8px;border:1px solid #ccc;
    }
    .forgot-box button {
        width:100%;padding:12px;background:#3b82f6;color:#fff;font-weight:700;border:none;border-radius:8px;cursor:pointer;
    }
    .forgot-box p {margin-top:10px;text-align:center;}
    .msg {text-align:center;margin-top:10px;font-size:14px;}
    .msg.error {color:#b91c1c;}
    .msg.success {color:#059669;}
  </style>
</head>
<body>
  <div class="forgot-box">
    <h2>Zapomenuté heslo</h2>
    <?php if (!empty($error)): ?>
      <p class="msg error"><?= $error ?></p>
    <?php elseif (!empty($success)): ?>
      <p class="msg success"><?= $success ?></p>
    <?php endif; ?>
    <form method="POST">
      <input type="email" name="email" placeholder="Váš email" required>
      <button type="submit">Poslat odkaz</button>
    </form>
    <p><a href="index.php">← Zpět</a></p>
  </div>
</body>
</html>
