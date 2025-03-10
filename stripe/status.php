<?php
require '../vendor/autoload.php';
include_once("../connection-pdo.php");

\Stripe\Stripe::setApiKey('sk_test_51MevDXSAzhaecUmMTkH6My0R7HILCpusmUcWT7VazsyrCYZmo65phpmMH9rD202fscfdJkXVgCKWJ4qeTp21wOKn00zGZLJjJD'); // Replace with your actual key

$status = $_GET['status'] ?? 'failed';
$session_id = $_GET['session_id'] ?? '';

if (empty($session_id)) {
    die("<h2>Invalid request: No session ID provided.</h2>");
}

try {
    // Retrieve the Checkout Session
    $session = \Stripe\Checkout\Session::retrieve($session_id);

    // Debugging: Print session details
    echo "<pre>";
    print_r($session);
    echo "</pre>";

    // Retrieve Payment Intent
    if (!empty($session->payment_intent)) {
        $payment_intent = \Stripe\PaymentIntent::retrieve($session->payment_intent);

        if ($payment_intent->status === 'succeeded') {
            echo "<h2>Payment Successful!</h2>";
            echo "<p>Transaction ID: " . $payment_intent->id . "</p>";
        } else {
            echo "<h2>Payment Failed!</h2>";
        }
    } else {
        echo "<h2>Error: No payment intent found.</h2>";
    }
} catch (\Stripe\Exception\ApiErrorException $e) {
    echo "<h2>Stripe API Error:</h2> " . $e->getMessage();
} catch (Exception $e) {
    echo "<h2>Error:</h2> " . $e->getMessage();
}
?>
