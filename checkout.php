<?php
require 'vendor/autoload.php'; // Stripe PHP knihovna

\Stripe\Stripe::setApiKey('sk_test_51S7glB48...blablabla'); // ⚡ SECRET key

$plan = $_GET['plan'] ?? 'premium'; //  plán z tlačítka
$prices = [
    'start'   => 'price_1234567890', // Stripe ID ceny Start
    'premium' => 'price_0987654321', // Stripe ID ceny Premium
    'yearly'  => 'price_1122334455'  // Stripe ID ceny Roční
];

if (!isset($prices[$plan])) {
    die("❌ Neplatný plán");
}

header('Content-Type: application/json');

$session = \Stripe\Checkout\Session::create([
    'payment_method_types' => ['card'],
    'mode' => 'subscription',
    'line_items' => [[
        'price' => $prices[$plan],
        'quantity' => 1,
    ]],
    'subscription_data' => [
        'trial_period_days' => 3, // ⚡ 3 dny zdarma
    ],
    'success_url' => 'https://www.tipsterai.cz/success.php?session_id={CHECKOUT_SESSION_ID}',
    'cancel_url' => 'https://www.tipsterai.cz/cancel.php',
]);

echo json_encode(['id' => $session->id]);
