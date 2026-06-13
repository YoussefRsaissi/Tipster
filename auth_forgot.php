<?php
session_start();
header('Content-Type: application/json');
require 'db.php';

// PHPMailer (ZIP verze)
require 'lib/PHPMailer-master/src/Exception.php';
require 'lib/PHPMailer-master/src/PHPMailer.php';
require 'lib/PHPMailer-master/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;

try {
  $email = trim($_POST['email'] ?? '');
  if ($email === '') { echo json_encode(['ok'=>false,'message'=>'Zadejte email.']); exit; }

  $stmt = $pdo->prepare("SELECT id,username FROM users WHERE email=?");
  $stmt->execute([$email]);
  $user = $stmt->fetch();

  if (!$user) { echo json_encode(['ok'=>true,'message'=>'Pokud email existuje, posíláme odkaz.']); exit; } // bezpečnost

  $token = bin2hex(random_bytes(16));
  $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

  $upd = $pdo->prepare("UPDATE users SET reset_token=?, reset_expires=? WHERE id=?");
  $upd->execute([$token,$expires,$user['id']]);

  $baseUrl = 'https://www.tipsterai.cz/Tipster'; // ⬅️ UPRAV dle cesty
  $resetLink = $baseUrl . "/reset.php?token=" . $token;

  $mail = new PHPMailer(true);
  $mail->isSMTP();
  $mail->Host = 'smtp.forpsi.com';
  $mail->SMTPAuth = true;
  $mail->Username = 'info@tipsterai.cz';   // ⬅️ UPRAV
  $mail->Password = 'gT8qG@thd6';           // ⬅️ UPRAV
  $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
  $mail->Port = 587;
  $mail->CharSet = 'UTF-8';
  $mail->Encoding = 'base64';

  $mail->setFrom('info@tipsterai.cz','tipsterAi');
  $mail->addAddress($email,$user['username']);
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

  echo json_encode(['ok'=>true,'message'=>'Pokud email existuje, posíláme odkaz na reset.']);

} catch (Throwable $e) {
  echo json_encode(['ok'=>false,'message'=>'Server error: '.$e->getMessage()]);
}
