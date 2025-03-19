<?php
set_time_limit(1800);
ob_start();
if (!isset($_SESSION)) session_start();
include_once("../../connection-pdo.php");

$user_id = $_SESSION["user_id"];
$user_name = $_SESSION["user_name"];
$user_type = $_SESSION["user_type"];
$is_saller = $_SESSION["is_saller"];

if (isset($_REQUEST['action']))
    $action = $_REQUEST['action'];
else {
    $action = 'NF';
    $coockie_value = "You Dont Have The Permission To View This Page. ";
    setcookie('error_msg', $coockie_value, time() + (3600), "/");
    header('Location: error/error-msg.php');
    exit();
}

if (!isset($_SESSION["user_id"])) {
    header('Location: ../../logout.php');
    exit();
}


if ($user_type != 0) {
    header('Location: ../../logout.php');
    exit(0);
}


switch ($action) {
    case 'load_sell':
        $html = '';


        $sql="SELECT sales.product_id,
       sales.seller_id,
       DATE_FORMAT(sales.created_at,'%d-%m-%Y %H:%i') AS created_at,
       sales.currency,
       sales.amount,
       sales.buyer_id,
       sales_payment.unique_id,
       products.name,
       products.slug,
       products.featured_image
FROM (sales sales
      LEFT OUTER JOIN products products
         ON (sales.product_id = products.id))
     LEFT OUTER JOIN sales_payment sales_payment
        ON (sales.payment_id = sales_payment.id)
WHERE (sales.seller_id = $user_id)
ORDER BY sales.created_at DESC";
        $query = $pdoconn->prepare($sql);
        $query->execute();
        $my_arr = $query->fetchAll(PDO::FETCH_ASSOC);

        $i = 1;
        foreach ($my_arr as $val) {
            $id = $val['product_id'];
            $buyer_id = $val['buyer_id'];
            $currency = $val['currency'];
            $amount = $val['amount'];
            $name = $val['name'];
            $slug = $val['slug'];
            $unique_id = $val['unique_id'];
            $created_at = $val['created_at'];
            $featured_image = $val['featured_image'];

            $inr=$usd='';
            if ($currency == 1) {
                $inr = '₹'.$amount;
            } else {
                $usd = '$'.$amount;
            }


            $html .= '<tr>
                    <td>' . $created_at . '</td>
                    <td><a target="_blank" href="'.$site_url.'product/?name='.$slug.'" class="text-dark text-decoration-none">' . $name . '</a></td>
                    <td>#' . $buyer_id . '</td>
                    <td><img src="' . $site_url . 'product_images/' . $featured_image . '" class="pr_img" alt="' . $slug . '"></td>
                    <td>' . $inr . '</td>
                    <td>' . $usd . '</td>                    
                    <td>' . $unique_id . '</td>
                    
                </tr>';

            $i = $i + 1;


        }


        $my_arr = array('status' => 1, 'msg' => '', 'html' => $html);
        echo json_encode($my_arr);
        break;









    case 'load_payment':
        $payment_summary = '';


        $sql="SELECT `currency`,`amount` FROM `sales` WHERE `seller_id`='$user_id'";
        $query = $pdoconn->prepare($sql);
        $query->execute();
        $my_arr = $query->fetchAll(PDO::FETCH_ASSOC);

        $usd_sells=$inr_sells=0;
        foreach ($my_arr as $val) {
            $currency = $val['currency'];
            $amount = $val['amount'];

            if($currency==1){
                $inr_sells=$inr_sells+$amount;
            }else{
                $usd_sells=$usd_sells+$amount;
            }
        }


        $sql="SELECT IFNULL(SUM(`amount_inr`),0) AS inr,IFNULL(SUM(`amount_usd`),0) AS usd FROM `seller_payment` WHERE `seller`='$user_id'";
        $query = $pdoconn->prepare($sql);
        $query->execute();
        $my_arr = $query->fetchAll(PDO::FETCH_ASSOC);
        $inr_paid=$my_arr[0]['inr'];
        $usd_paid=$my_arr[0]['usd'];

        $payment_summary .= '<tr>
                    <td>' . $inr_sells . '</td>
                    <td>' . $usd_sells . '</td>
                   <td>' . round(($inr_sells*0.15),2) . '</td>
                    <td>' . round(($usd_sells*0.15),2) . '</td>
                    <td>' . round(($inr_sells*0.85),2) . '</td>
                    <td>' . round(($usd_sells*0.85),2) . '</td>
                     <td>' . $inr_paid . '</td>
                    <td>' . $usd_paid . '</td>
                   <td>' . (round(($inr_sells*0.85),2)-$inr_paid). '</td>
                    <td>' . (round(($usd_sells*0.85),2)-$usd_paid) . '</td>
                </tr>';





        $sql="SELECT `seller`,`amount_inr`,`amount_usd`,
       DATE_FORMAT(`paid_at`,'%d-%m-%Y') AS paid_at,
        DATE_FORMAT(`created_at`,'%d-%m-%Y %H:%i') AS created_at,`payment_details`,`unique_id`
        FROM `seller_payment` WHERE `seller`='$user_id' ORDER BY `created_at` ASC";
        $query = $pdoconn->prepare($sql);
        $query->execute();
        $my_arr = $query->fetchAll(PDO::FETCH_ASSOC);
        $payment_history='';
        foreach ($my_arr as $val) {
            $usd=$val['amount_usd'];
            $inr=$val['amount_inr'];
            if($inr==0){
                $inr='';
            }
            if($usd==0){
                $usd='';
            }

            $payment_history .= '<tr>
                    <td>' . $val['created_at'] . '</td>
                    <td>' . $val['paid_at'] . '</td>
                    <td>#' . $val['seller'] . '</td>
                    <td>' . $val['unique_id'] . '</td>
                    <td>' . $val['payment_details'] . '</td>
                    <td>' . $inr . '</td>
                    <td>' . $usd . '</td>
                    
                </tr>';
        }








        $my_arr = array('status' => 1, 'msg' => '', 'payment_summary' => $payment_summary, 'payment_history'=>$payment_history);
        echo json_encode($my_arr);
        break;



}




