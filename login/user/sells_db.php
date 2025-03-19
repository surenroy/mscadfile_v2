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


        $sql="SELECT `currency`,`amount` FROM `sales`";
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


        $sql="SELECT IFNULL(SUM(`amount_inr`),0) AS inr,IFNULL(SUM(`amount_usd`),0) AS usd FROM `seller_payment`";
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
        FROM `seller_payment` ORDER BY `created_at` ASC";
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


    case 'user_payment':
        $payment_summary = '';


        $sql="SELECT `id`,`name` FROM `users` ORDER BY `name` ASC";
        $query = $pdoconn->prepare($sql);
        $query->execute();
        $my_arr = $query->fetchAll(PDO::FETCH_ASSOC);

        $users=array();
        foreach ($my_arr as $val) {
            $id = $val['id'];
            $name = $val['name'];

            $users[$id]['id']=$id;
            $users[$id]['name']=$name;

            $sql="SELECT `currency`,IFNULL(SUM(`amount`),0) as amount FROM `sales` WHERE `seller_id`='$id' GROUP BY `currency`";
            $query = $pdoconn->prepare($sql);
            $query->execute();
            $my_arrc = $query->fetchAll(PDO::FETCH_ASSOC);
            foreach ($my_arrc as $valc) {
                $currency=$valc['currency'];
                $amount=$valc['amount'];

                if($currency==1){
                    $users[$id]['inr']=$amount;
                }else{
                    $users[$id]['usd']=$amount;
                }
            }


            $sql="SELECT IFNULL(SUM(`amount_inr`),0) AS inr,IFNULL(SUM(`amount_usd`),0) AS usd FROM `seller_payment` WHERE `seller`='$id'";
            $query = $pdoconn->prepare($sql);
            $query->execute();
            $my_arrp = $query->fetchAll(PDO::FETCH_ASSOC);
            foreach ($my_arrp as $valp) {
                $inr=$valp['inr'];
                $usd=$valp['usd'];

                $users[$id]['inrp']=$inr;
                $users[$id]['usdp']=$usd;
            }
        }





        foreach ($users as $usr) {
            $usr_id=$usr['id'];
            $usr_name=$usr['name'];

            if(isset($usr['inr'])){
                $usr_inr=$usr['inr'];
            }else{
                $usr_inr=0;
            }

            if(isset($usr['usd'])){
                $usr_usd=$usr['usd'];
            }else{
                $usr_usd=0;
            }

            $usr_inrp=$usr['inrp'];
            $usr_usdp=$usr['usdp'];




            if($usr_inr>0 && $usr_usd>0){
                $liminr=round(($usr_inr*0.85),2)-$usr_inrp;
                $limusd=round(($usr_usd*0.85),2)-$usr_usdp;

                $payment_summary.= '<tr>
                        <td>'.$usr_name.'</td>
                        <td>#'.$usr_id.'</td>
                        <td>'.$usr_inr.'</td>
                        <td>'.$usr_usd.'</td>
                        <td>' . round(($usr_inr*0.15),2) . '</td>
                        <td>' . round(($usr_usd*0.15),2) . '</td>
                        <td>' . round(($usr_inr*0.85),2) . '</td>
                        <td>' . round(($usr_usd*0.85),2) . '</td>
                        <td>'.$usr_inrp.'</td>
                        <td>'.$usr_usdp.'</td>
                        <td>' . (round(($usr_inr*0.85),2)-$usr_inrp). '</td>
                        <td>' . (round(($usr_usd*0.85),2)-$usr_usdp) . '</td>
                        <td>
                            <button class="btn btn-sm btn-danger mx-1 p-1 px-2"  onclick="open_modal('.$usr_id.',1,'.$liminr.')"><i class="fa-solid fa-indian-rupee-sign"></i></button>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-dark mx-1 p-1 px-2" onclick="open_modal('.$usr_id.',2,'.$limusd.')"><i class="fa-solid fa-usd"></i></button>
                        </td>
                    </tr>';
            }



        }




        $my_arr = array('status' => 1, 'msg' => '', 'user_payment' => $payment_summary);
        echo json_encode($my_arr);
        break;



    case 'submit_payment':
        $html='';
        $usrid=$_POST["usrid"];
        $type=$_POST["type"];
        $date=$_POST["date"];
        $amount=$_POST["amount"];
        $note=$_POST["note"];
        $payid=$_POST["payid"];

        $date_exp=explode('-',$date);

        $pay_date=$date_exp[2].'-'.$date_exp[1].'-'.$date_exp[0];



        if($type==1){
            $inr=$amount;
            $usd=0;
        }else{
            $inr=0;
            $usd=$amount;
        }



        $sql="INSERT INTO `seller_payment` (`seller`,`amount_inr`,`amount_usd`,`paid_at`,`payment_details`,
                              `created_at`,`unique_id`) VALUES 
        ('$usrid','$inr','$usd','$pay_date','$note',NOW(),'$payid')";
        $query = $pdoconn->prepare($sql);
        $query->execute();


        $my_arr = array('status' => 1);
        echo json_encode($my_arr);
        break;

}




