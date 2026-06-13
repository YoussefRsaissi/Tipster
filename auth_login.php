<?php
session_start();
header('Content-Type: application/json');
require 'db.php';

try {
  $username = trim($_POST['username'] ?? '');
  $password = trim($_POST['password'] ?? '');

  if ($username === '' || $password === '') {
    echo json_encode(['ok'=>false,'message'=>'Vyplňte všechna pole.']); exit;
  }

  $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
  $stmt->execute([$username, $username]);
  $user = $stmt->fetch();

  if (!$user || !password_verify($password, $user['password'])) {
    echo json_encode(['ok'=>false,'message'=>'Špatné jméno nebo heslo.']); exit;
  }

  if (isset($user['is_verified']) && (int)$user['is_verified'] !== 1) {
    echo json_encode(['ok'=>false,'message'=>'Nejdříve ověřte svůj email.']); exit;
  }

  $_SESSION['user'] = $user['username'];
  $_SESSION['user_id'] = $user['id'];
  echo json_encode(['ok'=>true]);

} catch (Throwable $e) {
  echo json_encode(['ok'=>false,'message'=>'Server error: '.$e->getMessage()]);
}
