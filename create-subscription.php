<?php
require 'vendor/autoload.php';
include 'stripe_config.php';

\Stripe\Stripe::setApiKey(STRIPE_SECRET_KEY);

$data = json_decode(file_get_contents("php://input"), true);

try {
  $subscription = \Stripe\Subscription::create([
    "customer" => "cus_xxxxxxxx", // TODO: vytvořit zákazníka při registraci
    "items" => [["price" => $data['plan']]],
    "trial_period_days" => 3
  ]);

  echo json_encode(["success" => true, "id" => $subscription->id]);
} catch(Exception $e) {
  echo json_encode(["success" => false, "error" => $e->getMessage()]);
}
