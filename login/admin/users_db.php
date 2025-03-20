<?php
ob_start();
if (!isset($_SESSION)) session_start();
include_once("../../connection-pdo.php");

$user_id=$_SESSION["user_id"];
$user_name=$_SESSION["user_name"];
$user_type=$_SESSION["user_type"];
$is_saller=$_SESSION["is_saller"];

if (isset($_REQUEST['action']))
    $action = $_REQUEST['action'];
else {
    $action = 'NF';
    $coockie_value = "You Dont Have The Permission To View This Page. ";
    setcookie('error_msg', $coockie_value, time() + (3600), "/");
    header('Location: error/error-msg.php');
}


if($user_type!=9){
    header('Location: ../../logout.php');
    exit(0);
}



switch ($action) {

    case 'load_data':
        $html='';

        $sql="SELECT `id`,`name` FROM `countries`";
        $query = $pdoconn->prepare($sql);
        $query->execute();
        $my_arr = $query->fetchAll(PDO::FETCH_ASSOC);
        $countryArray = [];
        foreach ($my_arr as $country) {
            $countryArray[$country['id']] = $country['name'];
        }


        $sql="SELECT IFNULL(SUM(`total_space`),0) AS total_usage,`user_id` FROM `products` GROUP BY `user_id`";
        $query = $pdoconn->prepare($sql);
        $query->execute();
        $my_arr = $query->fetchAll(PDO::FETCH_ASSOC);
        $usageArray = [];
        foreach ($my_arr as $usage) {
            $usageArray[$usage['user_id']] = $usage['total_usage'];
        }

        $sql="SELECT `id`,`name`,`email`,`mobile_no`,`whatsapp_no`,`is_saller`,`country`,`del_flg`,
       DATE_FORMAT(`created_at`,'%d-%m-%Y %H:%i') AS created_at,`payment_id`,`user_type`,
       IFNULL(`currency`,0) AS currency,`pincode` FROM `users` ORDER BY `del_flg` ASC,`name` ASC";
        $query = $pdoconn->prepare($sql);
        $query->execute();
        $my_arr = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach ($my_arr as $val) {
            $id=$val['id'];
            $name=$val['name'];
            $email=$val['email'];
            $mobile_no=$val['mobile_no'];
            $whatsapp_no=$val['whatsapp_no'];
            $is_saller=$val['is_saller'];
            $country=$val['country'];
            $del_flg=$val['del_flg'];
            $created_at=$val['created_at'];
            $payment_id=$val['payment_id'];
            $user_type=$val['user_type'];
            $currency=$val['currency'];
            $pincode=$val['pincode'];

            $saller='';
            $seller_hide='';
            if($user_type==0){
                if($is_saller==0){
                    $saller='Buyer';
                }else{
                    $saller='Seller';
                }
                $user_type='User';
            }else if($user_type==2){
                $user_type='Admin_User';
                $seller_hide='d-none';
            }else if($user_type==9){
                $user_type='Admin';
                $seller_hide='d-none';
            }

            if($currency==1){
                $currency='INR';
            }else if($currency==2){
                $currency='USD';
            }else if($currency==0){
                $currency='';
            }

            if($del_flg==1){
                $clr='style="background-color: #fffbbc !important;"';
            }else{
                $clr='';
            }

            $cntry='';
            if(isset($countryArray[$country])){
                $cntry=$countryArray[$country];
            }

            if(isset($usageArray[$id])){
                $usage=$usageArray[$id];
            }else{
                $usage='';
            }

            $html.='<tr>
                        <td '.$clr.'>'.$name.'</td>
                        <td '.$clr.'>'.$email.'</td>
                        <td '.$clr.'>'.$mobile_no.'</td>
                        <td '.$clr.'>'.$whatsapp_no.'</td>
                        <td '.$clr.'>'.$saller.'</td>
                        <td '.$clr.'>'.$cntry.'</td>
                        <td '.$clr.'>'.$created_at.'</td>
                        <td '.$clr.'>'.$payment_id.'</td>
                        <td '.$clr.'>'.$user_type.'</td>
                        <td '.$clr.'>'.$currency.'</td>
                        <td '.$clr.'>'.$pincode.'</td>
                        <td '.$clr.'>
                            <button class="btn btn-sm btn-dark mx-1 p-1 px-2" onclick="status_change('.$del_flg.','.$id.')"><i class="fa-solid fa-rotate"></i></button>
                        </td>
                        <td '.$clr.'>'.$usage.'</td>
                </tr>';
        }


        $my_arr = array('status' => 1, 'msg' => '', 'html'=> $html);
        echo json_encode($my_arr);
        break;




    case 'status_change':
        $html='';
        $id=$_POST["id"];
        $del=$_POST["del"];

        if($del==1){
            $sql="UPDATE `users` SET `del_flg`='0' WHERE `id`='$id'";
        }else if($del==0){
            $sql="UPDATE `users` SET `del_flg`='1' WHERE `id`='$id'";
        }
        $query = $pdoconn->prepare($sql);
        $query->execute();


        $my_arr = array('status' => 1);
        echo json_encode($my_arr);
        break;
}
