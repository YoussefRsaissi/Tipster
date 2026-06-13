<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
header('Content-Type: application/json');
require 'db.php';

// PHPMailer (ZIP verze)
require 'lib/PHPMailer-master/src/Exception.php';
require 'lib/PHPMailer-master/src/PHPMailer.php';
require 'lib/PHPMailer-master/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

try {
  $username = trim($_POST['username'] ?? '');
  $email    = trim($_POST['email'] ?? '');
  $password = trim($_POST['password'] ?? '');

  if ($username === '' || $email === '' || $password === '') {
    echo json_encode(['ok'=>false,'message'=>'Vyplňte všechna pole.']); exit;
  }

  // duplicity
  $check = $pdo->prepare("SELECT id FROM users WHERE username=? OR email=?");
  $check->execute([$username, $email]);
  if ($check->rowCount() > 0) {
    echo json_encode(['ok'=>false,'message'=>'Uživatel nebo email už existuje.']); exit;
  }

  $hash  = password_hash($password, PASSWORD_DEFAULT);
  $token = bin2hex(random_bytes(16));

  $stmt = $pdo->prepare("INSERT INTO users (username,email,password,email_verification_token,is_verified,created_at) VALUES (?,?,?,?,0,NOW())");
  $stmt->execute([$username,$email,$hash,$token]);

  // Poslat ověřovací email
  $baseUrl = 'https://www.tipsterai.cz/Tipster'; // ⬅️ UPRAV dle reálné cesty
  $verifyLink = $baseUrl . "/verify.php?token=" . $token;

  $mail = new PHPMailer(true);
  $mail->isSMTP();
  $mail->Host = 'smtp.forpsi.com';
  $mail->SMTPAuth = true;
  $mail->Username = 'info@tipsterai.cz';  // ⬅️ UPRAV
  $mail->Password = 'gT8qG@thd6';          // ⬅️ UPRAV
  $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
  $mail->Port = 587;
  $mail->CharSet = 'UTF-8';
  $mail->Encoding = 'base64';

  $mail->setFrom('info@tipsterai.cz','tipsterAi');
  $mail->addAddress($email,$username);
  $mail->isHTML(true);
  $mail->Subject = "✔ Ověření účtu - tipsterAi";
  $mail->Body = "
  <div style='font-family:Arial,sans-serif;max-width:600px;margin:auto;background:#fff;border-radius:10px;padding:24px;box-shadow:0 10px 30px rgba(0,0,0,.1)'>
    <h2 style='color:#3b82f6;margin:0 0 10px'>Vítej, {$username} 👋</h2>
    <p>Pro aktivaci účtu klikni na tlačítko:</p>
    <p><a href='{$verifyLink}' style='display:inline-block;background:#3b82f6;color:#fff;padding:12px 18px;border-radius:10px;text-decoration:none;font-weight:700'>Ověřit účet</a></p>
    <p style='color:#6b7280;font-size:12px;margin-top:24px'>© ".date('Y')." tipsterAi</p>
  </div>";

  $mail->send();

  echo json_encode(['ok'=>true,'message'=>'Registrace OK. Zkontrolujte email a ověřte účet.']);

} catch (Throwable $e) {
  echo json_encode(['ok'=>false,'message'=>'Server error: '.$e->getMessage()]);
}
