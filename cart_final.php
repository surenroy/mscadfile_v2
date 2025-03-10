<?php
ob_start();
if (!isset($_SESSION)) session_start();
include_once("connection-pdo.php");


if (isset($_SESSION['user_id']))
    $user_id = $_SESSION['user_id'];
else {
    header('Location: index.php');
    exit();
}


if (!isset($_COOKIE['usd'])) {
    $sql = "SELECT `usd` FROM `currency_conversion` WHERE `id` = 1";
    $query = $pdoconn->prepare($sql);
    $query->execute();
    $my_arr = $query->fetchAll(PDO::FETCH_ASSOC);
    $usd = $my_arr[0]['usd'];
    setcookie("usd", $usd, time() + 3600, "/");
} else {
    $usd = $_COOKIE['usd'];
}






$user_currency=$_COOKIE['user_currency'];
if($user_currency==1){
    $user_currency_txt='INR';
}else{
    $user_currency_txt='USD';
}


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title> Payment Gateway</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    
<style>
    .gateway-container {
        display: flex;
        justify-content: center;
        gap: 20px;
        flex-wrap: wrap;
    }
    .gateway-card {
        border: 2px solid #ddd;
        border-radius: 8px;
        padding: 15px;
        cursor: pointer;
        text-align: center;
        transition: 0.3s;
        width: 180px;
        position: relative;
    }
    .gateway-card:hover {
        border-color: #007bff;
    }
    .gateway-card img {
        width: 100px;
        height: auto;
        margin-bottom: 8px;
    }
    .form-check-input {
        display: none;
    }
    .tick-mark {
        display: none;
        position: absolute;
        top: 10px;
        right: 10px;
        font-size: 24px;
        color: green;
    }
    .form-check-input:checked + .gateway-card {
        border-color: #007bff;
        background-color: #f8f9fa;
    }
    .form-check-input:checked + .gateway-card .tick-mark {
        display: block;
    }
</style>

</head>
<body>

<?php
$sql="SELECT `id`,`currency`,`price`,`offer` FROM `products`
WHERE `active`=1 AND `pending`=0 AND `drive_pending`=0 AND `id` IN 
(SELECT `cart` FROM `wishlist_cart` WHERE `user_id`='$user_id' AND `cart` IS NOT NULL)";
$query = $pdoconn->prepare($sql);
$query->execute();
$my_arr = $query->fetchAll(PDO::FETCH_ASSOC);

$product_ids=array();
$total_cart_price=0;
foreach ($my_arr as $val) {
    $id=$val['id'];
    $currency=$val['currency'];
    $price=$val['price'];
    $offer=$val['offer'];
    array_push($product_ids,$id);

    $usd_rate = $_COOKIE["usd"];
    if($currency==1){
        $inr_offer=$offer;
        $usd_offer=round(($offer*$usd_rate),2);
    }else{
        $inr_offer=round(($offer/$usd_rate),2);
        $usd_offer=$offer;
    }


    if($user_currency==1){
        $item_price_as_user=$inr_offer;
    }else{
        $item_price_as_user=$usd_offer;
    }
    $total_cart_price=$total_cart_price+$item_price_as_user;

}




$purchased_products=implode(",",$product_ids);




echo '<h3>Cart product Details:</h3>
<p><strong>Currency: </strong>'.$user_currency_txt.'</p>
<p><strong>Total payable: </strong>'.$total_cart_price.'</p>
<p><strong>Product IDS: </strong>'.$purchased_products.'</p>';

?>


<?php $default_gateway = ($user_currency_txt == 'INR') ? "razorpay" : "stripe"; ?>
<form id="paymentForm" method="post" action="<?php echo ($user_currency_txt == 'INR') ? './razorpay/' : './stripe/'; ?>">
    <div class="gateway-container d-flex justify-content-center gap-3">
        <?php if ($user_currency_txt == 'INR') { ?>
            <label>
                <input type="hidden" name="amount" value="<?=$total_cart_price?>">
                <input type="hidden" name="currency" value="<?=$user_currency_txt?>">
                <input type="hidden" name="user_id" value="<?=$user_id?>">            
                <input class="form-check-input" type="radio" name="payment_gateway" id="razorpay" value="razorpay" required <?php echo ($default_gateway == 'razorpay') ? 'checked' : ''; ?>>
                <div class="gateway-card">
                    <span class="tick-mark">✔️</span>
                    <img src="./images/razorpay.png" alt="Razorpay">
                    <p>Pay using Razorpay</p>
                </div>
            </label>
        <?php } ?>

        <?php if ($user_currency_txt != 'INR') { ?>
            <label>
                <input type="hidden" name="amount" value="<?=$total_cart_price?>">
                <input type="hidden" name="currency" value="<?=$user_currency_txt?>">
                <input type="hidden" name="user_id" value="<?=$user_id?>"> 
                <input class="form-check-input" type="radio" name="payment_gateway" id="stripe" value="stripe" required <?php echo ($default_gateway == 'stripe') ? 'checked' : ''; ?>>
                <div class="gateway-card">
                    <span class="tick-mark">✔️</span>
                    <img src="./images/stripe.jpg" alt="Stripe">
                    <p>Pay using Stripe</p>
                </div>
            </label>
        <?php } ?>
    </div>

    <div class="d-flex justify-content-center mt-3">
        <button type="submit" id="payment-button" class="btn btn-primary btn-sm">Proceed to Pay</button>
    </div>
</form>






<?php
echo '<h3 style="margin-top: 50px;">After Successful Payment:</h3>
<p>DELETE FROM `wishlist_cart` WHERE `user_id`="$user_id" AND `cart` IN ($purchased_products)   //delete from cart</p>
<p>Transction Details Data in JSON         $data = [
    "Transaction ID" => $any_Data,
    "Payment ID" => $any_Data,
    "Order ID" => $any_Data,
    "Payable Amount" => $any_Data,
    "Amount With Tax" => $any_Data,
    "User Details" => $any_Data,
    "Product Wise Price" => [
        "product_id_1" => $price_1,
        "product_id_2" => $price_2,
    ]
];
            $transction_details = json_encode($data, JSON_PRETTY_PRINT);</p>
<p>INSERT INTO `sales_payment` (`sales_amount`,`currency_type`,`transction_details`,`unique_id`) VALUES ("$total_cart_price","$user_currency","Transction Details in JSON","Payment Unique ID") //get the last insert id $lastInsertId = $pdoconn->lastInsertId();</p>
<p>
$sql="SELECT `id`,`user_id` FROM `products` WHERE `id` IN ($purchased_products)";
foreach ($my_arr as $val) {
    $id = $val["id"];
    $seller = $val["user_id"];

    $sql="INSERT INTO `sales` (`product_id`,`seller_id`,`buyer_id`,`payment_id`,`created_at`) VALUES ("$id","$seller","$user_id","$lastInsertId",NOW())";
}</p>';




?>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

