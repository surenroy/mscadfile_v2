<?php
require '../vendor/autoload.php';
include_once("../connection-pdo.php");

use Razorpay\Api\Api;

// Razorpay API Key
$keyId = 'rzp_test_hGZvoGcbQyHopS';
$keySecret = 'qcu2ghpOjNU9Lh7LMbUHbZr6';

$api = new Api($keyId, $keySecret);

// Get Razorpay response
if (isset($_GET['razorpay_payment_id']) && isset($_GET['razorpay_payment_link_id'])) {
    $paymentId = $_GET['razorpay_payment_id'];
    $paymentLinkId = $_GET['razorpay_payment_link_id'];
    echo '<pre>';
    print_r($_GET);
    echo '<pre>';
    
    try {
        // Fetch payment details
        $payment = $api->payment->fetch($paymentId);

        $status = $payment->status; // Can be "captured", "failed", etc.

        // Update payment status in the database
        

        if ($status === 'captured') {
            echo "Payment successful. Thank you!";
        } else {
            echo "Payment failed or pending.";
        }
    } catch (Exception $e) {
        die("Error: " . $e->getMessage());
    }
} else {
    die("Invalid request.");
}
?>
