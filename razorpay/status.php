<?php
include_once('../header.php');
require '../vendor/autoload.php';

use Razorpay\Api\Api;

$keyId = 'rzp_test_hGZvoGcbQyHopS';
$keySecret = 'qcu2ghpOjNU9Lh7LMbUHbZr6';

$api = new Api($keyId, $keySecret);

$paymentStatus = '';
$paymentID = '';

if (isset($_GET['razorpay_payment_id']) && isset($_GET['razorpay_payment_link_id'])) {
    $paymentID = $_GET['razorpay_payment_id'];
    $paymentLinkId = $_GET['razorpay_payment_link_id'];
    try {
        $payment = $api->payment->fetch($paymentID);
        $status = $payment->status;

        if ($status === 'captured') {
            $razorpay_payment_id = $_GET['razorpay_payment_id'];
            $razorpay_payment_link_id = $_GET['razorpay_payment_link_id'];

            $user_id = $_SESSION["user_id"];
            $user_currency = $_SESSION["user_currency"];
            $purchased_products = $_SESSION["purchased_products"];
            $prices_products = $_SESSION["prices_products"];
            $total_cart_price = $_SESSION["total_cart_price"];

            $product_ids = explode(',', $purchased_products);
            $product_prices = explode(',', $prices_products);


            $data = [
                "pay_id" => $razorpay_payment_id,
                "link_id" => $razorpay_payment_link_id
            ];

            $json_data = json_encode($data, JSON_PRETTY_PRINT);



            $sql="SELECT `id` FROM `sales_payment` WHERE `unique_id`='$razorpay_payment_id' AND `currency_type`=1";
            $query = $pdoconn->prepare($sql);
            $query->execute();
            $chq_arr = $query->fetchAll(PDO::FETCH_ASSOC);
            if(count($chq_arr)==0){
                // Deleting from wishlist_cart
                $sql = "DELETE FROM `wishlist_cart` WHERE `user_id`='$user_id' AND `cart` IN ($purchased_products)";
                $query = $pdoconn->prepare($sql);
                $query->execute();

                // Inserting into sales_payment
                $sql = "INSERT INTO `sales_payment` (`sales_amount`,`currency_type`,`transction_details`,`unique_id`) VALUES ('$total_cart_price','$user_currency','$json_data','$razorpay_payment_id')";
                $query = $pdoconn->prepare($sql);
                $query->execute();
                $lastInsertId = $pdoconn->lastInsertId();

                // Inserting into sales
                $sql = "SELECT `id`,`user_id` FROM `products` WHERE `id` IN ($purchased_products)";
                $query = $pdoconn->prepare($sql);
                $query->execute();
                $my_arr = $query->fetchAll(PDO::FETCH_ASSOC);
                $i=0;
                foreach ($my_arr as $val) {
                    $id = $val["id"];
                    $seller = $val["user_id"];
                    $product_price=$product_prices[$i];

                    $sql = "INSERT INTO `sales` (`product_id`,`seller_id`,`buyer_id`,`payment_id`,`created_at`,`currency`,`amount`) VALUES ('$id','$seller','$user_id','$lastInsertId',NOW(),'$user_currency','$product_price')";
                    $query = $pdoconn->prepare($sql);
                    $query->execute();

                    $i=$i+1;
                }
            }




            $paymentStatus = 'success';
        } else {
            $paymentStatus = 'failed';
        }
    } catch (Exception $e) {
        die("Error: " . $e->getMessage());
    }
} else {
    die("Invalid request.");
}
?>

<div class="container text-center mb-5 mt-5" style="min-height: 50vh;">
    <?php if ($paymentStatus === 'success'): ?>
        <div class="text-success">
            <i class="status-icon fas fa-check-circle" style="font-size: 70pt; margin-bottom: 15px;"></i>
            <h2>Payment Successful!</h2>
            <p>Your payment ID is: <strong><?php echo htmlspecialchars($paymentID); ?></strong></p>
        </div>
    <?php else: ?>
        <div class="text-danger">
            <i class="status-icon fas fa-times-circle" style="font-size: 70pt; margin-bottom: 15px;"></i>
            <h2>Payment Failed!</h2>
            <p>Your payment ID is: <strong><?php echo htmlspecialchars($paymentID); ?></strong></p>
        </div>
    <?php endif; ?>
</div>

<?php
include_once('../footer.php');

?>
