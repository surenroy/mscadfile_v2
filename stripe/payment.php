<?php
require '../vendor/autoload.php'; // Load Stripe SDK
include_once("../connection-pdo.php");

\Stripe\Stripe::setApiKey('sk_test_51MevDXSAzhaecUmMTkH6My0R7HILCpusmUcWT7VazsyrCYZmo65phpmMH9rD202fscfdJkXVgCKWJ4qeTp21wOKn00zGZLJjJD'); // Replace with your Secret Key


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $amount = $_POST['amount']; // Amount in INR
    $currency = $_POST['currency']; // Currency (INR)
    $user_id = $_POST['user_id']; // User ID
    $payment_gateway = $_POST['payment_gateway']; // Stripe

    $callback_url=$site_url.'stripe/status/';

    try {
        // Convert amount to the smallest currency unit (INR has 2 decimal places)
        $amount_cents = round($amount * 100);

        // Create a Checkout Session
        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => $currency,
                    'product_data' => [
                        'name' => "Payment for User ID: $user_id",
                    ],
                    'unit_amount' => $amount_cents, // Convert to cents
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => $callback_url.'?status=success&session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => $callback_url.'?status=failed',
        ]);

        // Redirect to Stripe Payment Page
        header("Location: " . $session->url);
        exit();
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}
