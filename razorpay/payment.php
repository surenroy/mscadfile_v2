<?php
require '../vendor/autoload.php'; 
include_once("../connection-pdo.php");

use Razorpay\Api\Api;

// Razorpay API Key
$keyId = 'rzp_test_hGZvoGcbQyHopS';
$keySecret = 'qcu2ghpOjNU9Lh7LMbUHbZr6';

$api = new Api($keyId, $keySecret);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $amount = isset($_POST['amount']) ? $_POST['amount'] : 0;
    $currency = isset($_POST['currency']) ? $_POST['currency'] : 'INR';
    $user_id = isset($_POST['user_id']) ? $_POST['user_id'] : '';

    $callback_url=$site_url.'razorpay/status/';

    if (!$amount || !$user_id) {
        die("Invalid request parameters");
    }

    $amountInPaise = round($amount * 100); // Convert INR to paise

    try {
        $paymentLink = $api->paymentLink->create([
            'amount' => $amountInPaise,
            'currency' => $currency,
            'description' => 'Payment for Order',
            'customer' => [
                'name' => "User $user_id",
                'email' => "user$user_id@example.com",
                'contact' => "9876543210"
            ],
            'callback_url' => $callback_url,
            'callback_method' => 'get'
        ]);

        // #######  Store payment details in the database ########


        // Redirect to the Razorpay payment page
        header("Location: " . $paymentLink['short_url']);
        exit;
    } catch (Exception $e) {
        die("Error: " . $e->getMessage());
    }
}
?>
