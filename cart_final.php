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



