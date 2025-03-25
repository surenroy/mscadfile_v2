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
    exit();
}

if(!isset($_SESSION["user_id"])){
    header('Location: ../../logout.php');
    exit();
}

if($user_type!=9){
    header('Location: ../../logout.php');
    exit(0);
}



switch ($action) {


    case 'update_data':
        $success = 0;
        $msg = $link = '';

        $name = strip_tags($_POST['name']);
        $mobile = strip_tags($_POST['mobile']);
        $whatsapp = strip_tags($_POST['whatsapp']);
        $pincode = strip_tags($_POST['pincode']);
        $whatsapp_country = strip_tags($_POST['whatsapp_country']);
        $upi = strip_tags($_POST['upi']);
        $mobile_country = strip_tags($_POST['mobile_country']);

        $sql="UPDATE `users` SET `name`='$name',`mobile_no`='$mobile',`whatsapp_no`='$whatsapp',`country_mob`='$mobile_country',
                   `payment_id`='$upi',`country_wp`='$whatsapp_country',`pincode`='$pincode' WHERE `id`='$user_id'";
        $query = $pdoconn->prepare($sql);
        if($query->execute()){
            $my_arr = array('status' => 1, 'msg' => 'Successfully Updated');
            echo json_encode($my_arr);
        }else{
            $my_arr = array('status' => 0, 'msg' => 'Some Error Found');
            echo json_encode($my_arr);
        }
        break;


    case 'update_password':
        $success = 0;
        $msg = $link = '';

        $old_pass = strip_tags($_POST['old_pass']);
        $new_pass = strip_tags($_POST['new_pass']);

        $sql="SELECT `password` FROM `users` WHERE `id`='$user_id'";
        $query = $pdoconn->prepare($sql);
        $query->execute();
        $my_arr = $query->fetchAll(PDO::FETCH_ASSOC);
        $password = $my_arr[0]['password'];

        if (password_verify($old_pass, $password)) {
            $hashedPassword = password_hash($new_pass, PASSWORD_BCRYPT);

            $sql="UPDATE `users` SET `password`='$hashedPassword' WHERE `id`='$user_id'";
            $query = $pdoconn->prepare($sql);
            if($query->execute()){
                $my_arr = array('status' => 1, 'msg' => 'Successfully Updated');
                echo json_encode($my_arr);
            }else{
                $my_arr = array('status' => 0, 'msg' => 'Some Error Found');
                echo json_encode($my_arr);
            }
        }else{
            $my_arr = array('status' => 0, 'msg' => 'Old password Mismatched');
            echo json_encode($my_arr);
        }


        break;
}