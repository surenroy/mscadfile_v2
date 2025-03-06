<?php
ob_start();
if (!isset($_SESSION)) session_start();
include_once("connection-pdo.php");

if (isset($_REQUEST['action']))
    $action = $_REQUEST['action'];
else {
    $action = 'NF';
    $coockie_value = "You Dont Have The Permission To View This Page. ";
    setcookie('error_msg', $coockie_value, time() + (3600), "/");
    header('Location: error/error-msg.php');
}





switch ($action) {

    case 'login_user':
        $success = 0;
        $msg = $link = '';

        $login_password = strip_tags($_POST['login_password']);
        $login_email = strip_tags($_POST['login_email']);
        $login_email = strtolower($login_email);

        $sql="SELECT `id`,`name`,`password`,`is_saller`,`user_type`,`folder_id` FROM `users` WHERE `del_flg`=0 AND `email`='$login_email'";
        $query = $pdoconn->prepare($sql);
        $query->execute();
        $my_arr = $query->fetchAll(PDO::FETCH_ASSOC);

        if (count($my_arr) != 1) {
            $my_arr = array('status' => 0, 'msg' => 'User Not Found Or Credientials Mismatched.');
            echo json_encode($my_arr);
            exit();
        } else {
            $user_id = $my_arr[0]['id'];
            $user_name = $my_arr[0]['name'];
            $password = $my_arr[0]['password'];
            $is_saller = $my_arr[0]['is_saller'];
            $user_type = $my_arr[0]['user_type'];
            $folder_id = $my_arr[0]['folder_id'];

            if (password_verify($login_password, $password)) {
                $_SESSION["user_id"] = $user_id;
                $_SESSION["user_name"] = $user_name;
                $_SESSION["user_type"] = $user_type;
                $_SESSION["is_saller"] = $is_saller;
                $_SESSION["folder_id"] = $folder_id;


                switch ($user_type) {
                    case '9':
                        $link=$site_url.'login/admin/dashboard/dashboard.php';
                        break;

                    case '2':
                        $link=$site_url.'login/admin_user/dashboard/dashboard.php';
                        break;

                    case '0':
                        $link=$site_url.'login/user/dashboard/dashboard.php';
                        break;
                }




                $my_arr = array('status' => 1, 'link' => $link);
                echo json_encode($my_arr);
            } else {
                $my_arr = array('status' => 0, 'msg' => 'User Not Found Or Credientials Mismatched.');
                echo json_encode($my_arr);
                exit();
            }
        }


        break;

    case 'register_otp':
        $success = 0;
        $msg = '';

        $register_email = strip_tags($_POST['register_email']);
        $register_email = strtolower($register_email);

        $sql="DELETE FROM `register_user` WHERE `email`='$register_email'";
        $query = $pdoconn->prepare($sql);
        $query->execute();

        $otp=rand(100000, 999999);
        $_SESSION["otp"] = $otp;

        $sql="INSERT INTO `register_user` (`email`,`otp`) VALUES ('$register_email','$otp')";
        $query = $pdoconn->prepare($sql);
        $query->execute();

        //send email with otp //pending

        $my_arr = array('status' => 1, 'msg' => '');
        echo json_encode($my_arr);
        break;



    case 'register_user':
        $success = 0;
        $msg = $link = '';

        $register_email = strip_tags($_POST['register_email']);
        $register_email = strtolower($register_email);
        $register_name = strip_tags($_POST['register_name']);
        $register_otp = strip_tags($_POST['register_otp']);
        $register_mobile = strip_tags($_POST['register_mobile']);
        $register_whatsapp = strip_tags($_POST['register_whatsapp']);
        $register_password = strip_tags($_POST['register_password']);
        $register_seller = strip_tags($_POST['register_seller']);


        $sql="SELECT `otp` FROM `register_user` WHERE `email`='$register_email'";
        $query = $pdoconn->prepare($sql);
        $query->execute();
        $my_arr = $query->fetchAll(PDO::FETCH_ASSOC);

        if (count($my_arr) != 1) {
            $my_arr = array('status' => 0, 'msg' => 'Some Error Found. Start registration from Beginning.');
            echo json_encode($my_arr);
            exit();
        } else {
            $otp=$_SESSION["otp"];

            if($register_otp!=$otp){
                $my_arr = array('status' => 0, 'msg' => 'Email OTP Mismatch. Start registration from Beginning.');
                echo json_encode($my_arr);
                exit();
            }

            $sql="DELETE FROM `register_user` WHERE `email`='$register_email'";
            $query = $pdoconn->prepare($sql);
            $query->execute();

            $sql="SELECT `id` FROM `users` WHERE `email`='$register_email'";
            $query = $pdoconn->prepare($sql);
            $query->execute();
            $my_arr = $query->fetchAll(PDO::FETCH_ASSOC);

            if (count($my_arr) != 0) {
                $my_arr = array('status' => 0, 'msg' => 'Email ID Already Registered. Start registration from Beginning.');
                echo json_encode($my_arr);
                exit();
            }


            $hashedPassword = password_hash($register_password, PASSWORD_BCRYPT);

            $sql="INSERT INTO `users` (`name`,`email`,`mobile_no`,`whatsapp_no`,`password`,`is_saller`) VALUES 
                    ('$register_name','$register_email','$register_mobile','$register_whatsapp','$hashedPassword','$register_seller')";
            $query = $pdoconn->prepare($sql);
            $query->execute();
            $lastInsertId = $pdoconn->lastInsertId();



            if ($register_seller==1){
                $sql="SELECT `refresh_token`,`access_token`,`api_key`,`client_id`,
                `client_secret`,`auth_code`,`parent_folder`,
                UNIX_TIMESTAMP(`created_at`) AS created_at FROM `manage_api_tokens`";
                $query = $pdoconn->prepare($sql);
                $query->execute();
                $my_arr = $query->fetchAll(PDO::FETCH_ASSOC);

                $refresh_token=$my_arr[0]['refresh_token'];
                $access_token=$my_arr[0]['access_token'];
                $api_key=$my_arr[0]['api_key'];
                $client_id=$my_arr[0]['client_id'];
                $client_secret=$my_arr[0]['client_secret'];
                $auth_code=$my_arr[0]['auth_code'];
                $parent_folder=$my_arr[0]['parent_folder'];
                $created_at=$my_arr[0]['created_at'];


                $currentTimestamp = time();
                $timeDifference = $currentTimestamp - $created_at;

                if($timeDifference > 2400){
                    $token_url = 'https://oauth2.googleapis.com/token';
                    $data = array(
                        'client_id' => $client_id,
                        'client_secret' => $client_secret,
                        'refresh_token' => $refresh_token,
                        'grant_type' => 'refresh_token'
                    );

                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $token_url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_POST, true);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));

                    $response = curl_exec($ch);
                    if(curl_errno($ch)) {
                        $sql="DELETE FROM `users` WHERE `id`='$lastInsertId'";
                        $query = $pdoconn->prepare($sql);
                        $query->execute();

                        $my_arr = array('status' => 0, 'msg' => 'Registration Error.. ');
                        echo json_encode($my_arr);
                        exit();
                    } else {
                        $json = json_decode($response, true);
                        $access_token = $json['access_token'];

                        $sql="UPDATE `manage_api_tokens` SET `access_token`='$access_token',`created_at`=NOW()";
                        $query = $pdoconn->prepare($sql);
                        $query->execute();

                    }
                    curl_close($ch);




                    $api_url = 'https://www.googleapis.com/drive/v3/files?key='.$api_key;

                    $data = array(
                        'mimeType' => 'application/vnd.google-apps.folder',
                        'name' => 'Folder_'.$lastInsertId,
                        'parents' => array($parent_folder)
                    );

                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $api_url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_POST, true);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data)); // Convert array to JSON
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                        'Authorization: Bearer ' . $access_token,
                        'Content-Type: application/json',
                        'Accept: application/json'
                    ));

                    $response = curl_exec($ch);
                    if(curl_errno($ch)) {
                        $sql="DELETE FROM `users` WHERE `id`='$lastInsertId'";
                        $query = $pdoconn->prepare($sql);
                        $query->execute();

                        $my_arr = array('status' => 0, 'msg' => 'Registration Error.. ');
                        echo json_encode($my_arr);
                        exit();
                    } else {
                        $json = json_decode($response, true);
                        $user_folder_id=$json['id'];

                        $sql="UPDATE `users` SET `folder_id`='$user_folder_id' WHERE `id`='$lastInsertId'";
                        $query = $pdoconn->prepare($sql);
                        $query->execute();
                    }

                    curl_close($ch);
                }
            }


            $my_arr = array('status' => 1, 'msg' => 'Registered Successful. Go to Login.');
            echo json_encode($my_arr);
        }
        break;


    case 'forget_otp':
        $success = 0;
        $msg = '';

        $forget_email = strip_tags($_POST['forget_email']);
        $forget_email = strtolower($forget_email);

        $otp=rand(100000, 999999);
        $_SESSION["otp"] = $otp;

        $sql="SELECT `id` FROM `users` WHERE `email`='$forget_email'";
        $query = $pdoconn->prepare($sql);
        $query->execute();
        $my_arr = $query->fetchAll(PDO::FETCH_ASSOC);

        if (count($my_arr) != 1) {
            $my_arr = array('status' => 0, 'msg' => 'User Not Found Or Credientials Mismatched.');
            echo json_encode($my_arr);
            exit();
        } else {
            $sql="UPDATE `users` SET `otp`='$otp' WHERE `email`='$forget_email'";
            $query = $pdoconn->prepare($sql);
            $query->execute();


            //send email with otp //pending

            $my_arr = array('status' => 1, 'msg' => '');
            echo json_encode($my_arr);
        }



        break;

    case 'forget_password':
        $success = 0;
        $msg = $link = '';


        $forget_email = strip_tags($_POST['forget_email']);
        $forget_email = strtolower($forget_email);
        $forget_otp = strip_tags($_POST['forget_otp']);
        $forget_password = strip_tags($_POST['forget_password']);
        $forget_password_verify = strip_tags($_POST['forget_password_verify']);


        $sql="SELECT `otp` FROM `users` WHERE `email`='$forget_email'";
        $query = $pdoconn->prepare($sql);
        $query->execute();
        $my_arr = $query->fetchAll(PDO::FETCH_ASSOC);

        if (count($my_arr) != 1) {
            $my_arr = array('status' => 0, 'msg' => 'Some Error Found. Start from Beginning.');
            echo json_encode($my_arr);
            exit();
        } else {
            $otp=$_SESSION["otp"];

            if($forget_otp!=$otp){
                $my_arr = array('status' => 0, 'msg' => 'Email OTP Mismatch. Start from Beginning.');
                echo json_encode($my_arr);
                exit();
            }


            $hashedPassword = password_hash($forget_password, PASSWORD_BCRYPT);

            $sql="UPDATE `users` SET `password`='$hashedPassword', `otp`=NULL WHERE `email`='$forget_email' AND `otp`='$otp'";
            $query = $pdoconn->prepare($sql);
            $query->execute();

            $my_arr = array('status' => 1, 'msg' => 'Updated Successful. Go to Login.');
            echo json_encode($my_arr);
        }
        break;

    case 'sendMessage':
        $success = 0;
        $msg = $link = '';
        $result=1;

        $subject = strip_tags($_POST['subject']);
        $message = strip_tags($_POST['message']);

        $user_id=$_SESSION["user_id"];

        $sql="INSERT INTO `message` (`from`,`to`,`create_on`,`subject`) VALUES ('$user_id','1',NOW(),'$subject')";
        $query = $pdoconn->prepare($sql);
        $query->execute();
        $lastInsertId = $pdoconn->lastInsertId();


        $data = [
            'subject' => $subject,
            'message' => $message
        ];

        $folderPath = 'login/message/';
        $filePath = $folderPath . $lastInsertId . '.json';

        if (!file_exists($folderPath)) {
            mkdir($folderPath, 0777, true);
        }

        $jsonData = json_encode($data, JSON_PRETTY_PRINT);
        if ($jsonData === false) {
            $result=0;
        }

        if (file_put_contents($filePath, $jsonData) === false) {
            $result=0;
        }


        if($result==0){
            $sql="DELETE FROM `message` WHERE `id`='$lastInsertId'";
            $query = $pdoconn->prepare($sql);
            $query->execute();

            $my_arr = array('status' => 0, 'msg' => 'Some Error Found.');
            echo json_encode($my_arr);
        }else{
            $my_arr = array('status' => 1, 'msg' => 'Sent Successfully.');
            echo json_encode($my_arr);
        }
        break;



    case 'loadProducts':
        $html='';
        $limit=$_POST["limit"];
        $offset=$_POST["offset"];



        $sql="SELECT `id`,`name`,`slug` FROM `categories`";
        $query = $pdoconn->prepare($sql);
        $query->execute();
        $my_arr = $query->fetchAll(PDO::FETCH_ASSOC);
        $categoryArray = [];
        foreach ($my_arr as $category) {
            $categoryArray[$category['id']]['name'] = $category['name'];
            $categoryArray[$category['id']]['slug'] = $category['slug'];
        }


        $sql="SELECT COUNT(`id`) AS cnt FROM `products` WHERE `active`=1";
        $query = $pdoconn->prepare($sql);
        $query->execute();
        $my_arr = $query->fetchAll(PDO::FETCH_ASSOC);
        $total_products=$my_arr[0]['cnt'];
        $total_pages = ceil($total_products / $limit);



        $sql="SELECT `id`,`name`,`slug`,`currency`,`price`,`offer`,`featured_image`,`category_id`,`view`,`wish` FROM `products` WHERE `active`=1 ORDER BY `created_at` DESC,`view` DESC,`wish` DESC LIMIT $limit OFFSET $offset";
        $query = $pdoconn->prepare($sql);
        $query->execute();
        $my_arr = $query->fetchAll(PDO::FETCH_ASSOC);
        foreach($my_arr as $val){
            $id=$val['id'];
            $name=$val['name'];
            $slug=$val['slug'];
            $currency=$val['currency'];
            $price=$val['price'];
            $offer=$val['offer'];
            $featured_image=$val['featured_image'];
            $category_id=$val['category_id'];
            $view=$val['view'];
            $wish=$val['wish'];

            $filepath = $site_url.'product_images/'.$featured_image;

            if(isset($categoryArray[$category_id])){
                $category_name=$categoryArray[$category_id]['name'];
                $category_slug=$categoryArray[$category_id]['slug'];
            }else{
                $category_name=$category_slug='';
            }



            $usd_rate = $_COOKIE["usd"];
            if($currency==1){
                $inr_price=$price;
                $inr_offer=$offer;
                $usd_price=round(($price*$usd_rate),2);
                $usd_offer=round(($offer*$usd_rate),2);
            }else{
                $inr_price=round(($price/$usd_rate),2);
                $inr_offer=round(($offer/$usd_rate),2);
                $usd_price=$price;
                $usd_offer=$offer;
            }

            $html.='<div class="item-item-big-index col-lg-3 col-12 mt-3 mx-1 p-0">
                <span class="badge badge_love bg-danger position-absolute top-0 end-0 px-2 py-2">
                    <i class="fa-regular fa-heart wishlist'.$id.'" onclick="add_wish('.$id.')"></i>
                </span>

                <img src="'.$filepath.'" alt="'.$slug.'">
                <div class="item-name-index mt-2 fw-bold px-1 col-12 text-start">
                    <p class="mb-1">'.$name.'</p>
                    <p class="text-start text-black-50 mb-2">
                        <small><i class="fa-solid fa-eye" id="view_'.$id.'"></i> '.$view.'</small>&nbsp;&nbsp;
                        <small><i class="fa-solid fa-heart" id="wish_'.$id.'"></i> '.$wish.'</small>&nbsp;&nbsp;
                        <a class="text-decoration-none text-black-50" href="'.$site_url.'category/category.php?slug='.$category_slug.'"><small><i class="fa-solid fa-list"></i> '.$category_name.'</small></a>
                    </p>
                </div>

                
                <div class="price-tag text-start mb-2 px-1">
                    <i class="fa-solid fa-tag text-success"></i> 
                        <del class="text-black-50 inr_price">₹'.$inr_price.'</del> <span class="text-danger fw-bold inr_price">₹'.$inr_offer.'</span>
                        <del class="text-black-50 usd_price">$'.$usd_price.'</del> <span class="text-danger fw-bold usd_price">$'.$usd_offer.'</span>
                </div>

                <div class="col-12 px-1 pb-2 d-flex flex-row justify-content-between flex-wrap">
                    <a href="'.$site_url.'product/product.php?slug='.$slug.'" class="btn btn-sm btn-primary col-sm-5 text-decoration-none">View Details</a>
                    <button class="btn btn-sm btn-dark col-sm-5 addcart add_cart_'.$id.'" onclick="add_cart('.$id.')">Add To Cart</button>
                    <button class="btn btn-sm btn-warning d-none col-sm-5 removecart remove_cart_'.$id.'" onclick="remove_cart('.$id.')">In Cart</button>
                </div>
            </div>';
        }


        $my_arr = array('status' => 1, 'html'=> $html);
        echo json_encode($my_arr);
        break;

    case 'loadBanner':
        $html='';

        $sql="SELECT * FROM ((SELECT `id`, `name`, `slug`, `feature`, `created_at`,`featured_image` FROM `products`
                    WHERE `active` = 1 AND `feature` = 1 ORDER BY `created_at` DESC LIMIT 3) UNION ALL
                                    (SELECT `id`, `name`, `slug`, `feature`, `created_at`,`featured_image` FROM `products`
                    WHERE `active` = 1 AND `feature` = 0 ORDER BY `created_at` DESC LIMIT 10)) AS combined_results ORDER BY RAND()";
        $query = $pdoconn->prepare($sql);
        $query->execute();
        $my_arr = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach ($my_arr as $val) {
            $id = $val['id'];
            $name = $val['name'];
            $slug = $val['slug'];
            $feature = $val['feature'];
            $created_at = $val['created_at'];
            $featured_image=$val['featured_image'];

            if($feature==1){
                $feature='Featured';
            }else{
                $feature='New';
            }

            $filepath = $site_url.'product_images/'.$featured_image;

            $url=$site_url.'product/product.php?slug='.$slug;

            $html .= '<div class="item-item-big col-md-2 col-5 mx-2 mt-2" onclick="openInNewTab(\'' . $url . '\')">
                <div class="ribbon_banner">' . $feature . '</div>

                <span class="badge badge_love bg-danger position-absolute top-0 end-0">
                    <i class="fa-regular fa-heart wish_' . $id . '" onclick="add_wish(' . $id . '); event.stopPropagation();"></i>
                </span>

                <div class="item-name_banner">' . shortenString($name, 12) . '</div>
                <img src="' . $filepath . '" alt="' . $slug . '">
            </div>';


        }


        $my_arr = array('status' => 1, 'html'=> $html);
        echo json_encode($my_arr);
        break;


    case 'loadProductsFeatured':
        $html='';
        $limit=$_POST["limit"];
        $offset=$_POST["offset"];



        $sql="SELECT `id`,`name`,`slug` FROM `categories`";
        $query = $pdoconn->prepare($sql);
        $query->execute();
        $my_arr = $query->fetchAll(PDO::FETCH_ASSOC);
        $categoryArray = [];
        foreach ($my_arr as $category) {
            $categoryArray[$category['id']]['name'] = $category['name'];
            $categoryArray[$category['id']]['slug'] = $category['slug'];
        }


        $sql="SELECT COUNT(`id`) AS cnt FROM `products` WHERE `active`=1 AND `feature`=1";
        $query = $pdoconn->prepare($sql);
        $query->execute();
        $my_arr = $query->fetchAll(PDO::FETCH_ASSOC);
        $total_products=$my_arr[0]['cnt'];
        $total_pages = ceil($total_products / $limit);



        $sql="SELECT `id`,`name`,`slug`,`currency`,`price`,`offer`,`featured_image`,`category_id`,`view`,`wish` FROM `products` 
        WHERE `active`=1 AND `feature`=1 ORDER BY `created_at` DESC,`view` DESC,`wish` DESC LIMIT $limit OFFSET $offset";
        $query = $pdoconn->prepare($sql);
        $query->execute();
        $my_arr = $query->fetchAll(PDO::FETCH_ASSOC);
        foreach($my_arr as $val){
            $id=$val['id'];
            $name=$val['name'];
            $slug=$val['slug'];
            $currency=$val['currency'];
            $price=$val['price'];
            $offer=$val['offer'];
            $featured_image=$val['featured_image'];
            $category_id=$val['category_id'];
            $view=$val['view'];
            $wish=$val['wish'];

            $filepath = $site_url.'product_images/'.$featured_image;

            if(isset($categoryArray[$category_id])){
                $category_name=$categoryArray[$category_id]['name'];
                $category_slug=$categoryArray[$category_id]['slug'];
            }else{
                $category_name=$category_slug='';
            }



            $usd_rate = $_COOKIE["usd"];
            if($currency==1){
                $inr_price=$price;
                $inr_offer=$offer;
                $usd_price=round(($price*$usd_rate),2);
                $usd_offer=round(($offer*$usd_rate),2);
            }else{
                $inr_price=round(($price/$usd_rate),2);
                $inr_offer=round(($offer/$usd_rate),2);
                $usd_price=$price;
                $usd_offer=$offer;
            }

            $html.='<div class="item-item-big-index col-lg-3 col-12 mt-3 mx-1 p-0">
                <span class="badge badge_love bg-danger position-absolute top-0 end-0 px-2 py-2">
                    <i class="fa-regular fa-heart wishlist'.$id.'" onclick="add_wish('.$id.')"></i>
                </span>
                <div class="ribbon_banner">Feature</div>

                <img src="'.$filepath.'" alt="'.$slug.'">
                <div class="item-name-index mt-2 fw-bold px-1 col-12 text-start">
                    <p class="mb-1">'.$name.'</p>
                    <p class="text-start text-black-50 mb-2">
                        <small><i class="fa-solid fa-eye" id="view_'.$id.'"></i> '.$view.'</small>&nbsp;&nbsp;
                        <small><i class="fa-solid fa-heart" id="wish_'.$id.'"></i> '.$wish.'</small>&nbsp;&nbsp;
                        <a class="text-decoration-none text-black-50" href="'.$site_url.'category/category.php?slug='.$category_slug.'"><small><i class="fa-solid fa-list"></i> '.$category_name.'</small></a>
                    </p>
                </div>

                
                <div class="price-tag text-start mb-2 px-1">
                    <i class="fa-solid fa-tag text-success"></i> 
                        <del class="text-black-50 inr_price">₹'.$inr_price.'</del> <span class="text-danger fw-bold inr_price">₹'.$inr_offer.'</span>
                        <del class="text-black-50 usd_price">$'.$usd_price.'</del> <span class="text-danger fw-bold usd_price">$'.$usd_offer.'</span>
                </div>

                <div class="col-12 px-1 pb-2 d-flex flex-row justify-content-between flex-wrap">
                    <a href="'.$site_url.'product/product.php?slug='.$slug.'" class="btn btn-sm btn-primary col-sm-5 text-decoration-none">View Details</a>
                    <button class="btn btn-sm btn-dark col-sm-5 addcart add_cart_'.$id.'" onclick="add_cart('.$id.')">Add To Cart</button>
                    <button class="btn btn-sm btn-warning d-none col-sm-5 removecart remove_cart_'.$id.'" onclick="remove_cart('.$id.')">In Cart</button>
                </div>
            </div>';
        }


        $my_arr = array('status' => 1, 'html'=> $html);
        echo json_encode($my_arr);
        break;

    case 'loadNewProducts':
        $html='';
        $limit=$_POST["limit"];
        $offset=$_POST["offset"];



        $sql="SELECT `id`,`name`,`slug` FROM `categories`";
        $query = $pdoconn->prepare($sql);
        $query->execute();
        $my_arr = $query->fetchAll(PDO::FETCH_ASSOC);
        $categoryArray = [];
        foreach ($my_arr as $category) {
            $categoryArray[$category['id']]['name'] = $category['name'];
            $categoryArray[$category['id']]['slug'] = $category['slug'];
        }


        $sql="SELECT COUNT(`id`) AS cnt FROM `products` WHERE `active`=1";
        $query = $pdoconn->prepare($sql);
        $query->execute();
        $my_arr = $query->fetchAll(PDO::FETCH_ASSOC);
        $total_products=$my_arr[0]['cnt'];
        $total_pages = ceil($total_products / $limit);



        $sql="SELECT `id`,`name`,`slug`,`currency`,`price`,`offer`,`featured_image`,`category_id`,`view`,`wish` FROM `products` 
        WHERE `active`=1 ORDER BY `created_at` DESC,`view` DESC,`wish` DESC LIMIT $limit OFFSET $offset";
        $query = $pdoconn->prepare($sql);
        $query->execute();
        $my_arr = $query->fetchAll(PDO::FETCH_ASSOC);
        foreach($my_arr as $val){
            $id=$val['id'];
            $name=$val['name'];
            $slug=$val['slug'];
            $currency=$val['currency'];
            $price=$val['price'];
            $offer=$val['offer'];
            $featured_image=$val['featured_image'];
            $category_id=$val['category_id'];
            $view=$val['view'];
            $wish=$val['wish'];

            $filepath = $site_url.'product_images/'.$featured_image;

            if(isset($categoryArray[$category_id])){
                $category_name=$categoryArray[$category_id]['name'];
                $category_slug=$categoryArray[$category_id]['slug'];
            }else{
                $category_name=$category_slug='';
            }



            $usd_rate = $_COOKIE["usd"];
            if($currency==1){
                $inr_price=$price;
                $inr_offer=$offer;
                $usd_price=round(($price*$usd_rate),2);
                $usd_offer=round(($offer*$usd_rate),2);
            }else{
                $inr_price=round(($price/$usd_rate),2);
                $inr_offer=round(($offer/$usd_rate),2);
                $usd_price=$price;
                $usd_offer=$offer;
            }

            $html.='<div class="item-item-big-index col-lg-3 col-12 mt-3 mx-1 p-0">
                <span class="badge badge_love bg-danger position-absolute top-0 end-0 px-2 py-2">
                    <i class="fa-regular fa-heart wishlist'.$id.' wishlist_heart" onclick="add_wish('.$id.')"></i>
                </span>
                <div class="ribbon_banner">New</div>

                <img src="'.$filepath.'" alt="'.$slug.'">
                <div class="item-name-index mt-2 fw-bold px-1 col-12 text-start">
                    <p class="mb-1">'.$name.'</p>
                    <p class="text-start text-black-50 mb-2">
                        <small><i class="fa-solid fa-eye" id="view_'.$id.'"></i> '.$view.'</small>&nbsp;&nbsp;
                        <small><i class="fa-solid fa-heart" id="wish_'.$id.'"></i> '.$wish.'</small>&nbsp;&nbsp;
                        <a class="text-decoration-none text-black-50" href="'.$site_url.'category/category.php?slug='.$category_slug.'"><small><i class="fa-solid fa-list"></i> '.$category_name.'</small></a>
                    </p>
                </div>

                
                <div class="price-tag text-start mb-2 px-1">
                    <i class="fa-solid fa-tag text-success"></i> 
                        <del class="text-black-50 inr_price">₹'.$inr_price.'</del> <span class="text-danger fw-bold inr_price">₹'.$inr_offer.'</span>
                        <del class="text-black-50 usd_price">$'.$usd_price.'</del> <span class="text-danger fw-bold usd_price">$'.$usd_offer.'</span>
                </div>

                <div class="col-12 px-1 pb-2 d-flex flex-row justify-content-between flex-wrap">
                    <a href="'.$site_url.'product/product.php?slug='.$slug.'" class="btn btn-sm btn-primary col-sm-5 text-decoration-none">View Details</a>
                    <button class="btn btn-sm btn-dark col-sm-5 addcart add_cart_'.$id.'" onclick="add_cart('.$id.')">Add To Cart</button>
                    <button class="btn btn-sm btn-warning d-none col-sm-5 removecart remove_cart_'.$id.'" onclick="remove_cart('.$id.')">In Cart</button>
                </div>
            </div>';
        }


        $my_arr = array('status' => 1, 'html'=> $html);
        echo json_encode($my_arr);
        break;


    case 'loadProductsCategory':
        $html='';
        $limit=$_POST["limit"];
        $offset=$_POST["offset"];
        $cat=$_POST["cat"];


        $sql="SELECT `id`,`name`,`slug` FROM `categories` WHERE `id`='$cat'";
        $query = $pdoconn->prepare($sql);
        $query->execute();
        $my_arr = $query->fetchAll(PDO::FETCH_ASSOC);
        $categoryArray = [];
        foreach ($my_arr as $category) {
            $categoryArray[$category['id']]['name'] = $category['name'];
            $categoryArray[$category['id']]['slug'] = $category['slug'];
        }


        $sql="SELECT COUNT(`id`) AS cnt FROM `products` WHERE `active`=1 AND `category_id`='$cat'";
        $query = $pdoconn->prepare($sql);
        $query->execute();
        $my_arr = $query->fetchAll(PDO::FETCH_ASSOC);
        $total_products=$my_arr[0]['cnt'];
        $total_pages = ceil($total_products / $limit);



        $sql="SELECT `id`,`name`,`slug`,`currency`,`price`,`offer`,`featured_image`,`category_id`,`view`,`wish` FROM `products` 
        WHERE `active`=1 AND `category_id`='$cat' ORDER BY `created_at` DESC,`view` DESC,`wish` DESC LIMIT $limit OFFSET $offset";
        $query = $pdoconn->prepare($sql);
        $query->execute();
        $my_arr = $query->fetchAll(PDO::FETCH_ASSOC);
        foreach($my_arr as $val){
            $id=$val['id'];
            $name=$val['name'];
            $slug=$val['slug'];
            $currency=$val['currency'];
            $price=$val['price'];
            $offer=$val['offer'];
            $featured_image=$val['featured_image'];
            $category_id=$val['category_id'];
            $view=$val['view'];
            $wish=$val['wish'];

            $filepath = $site_url.'product_images/'.$featured_image;

            if(isset($categoryArray[$category_id])){
                $category_name=$categoryArray[$category_id]['name'];
                $category_slug=$categoryArray[$category_id]['slug'];
            }else{
                $category_name=$category_slug='';
            }



            $usd_rate = $_COOKIE["usd"];
            if($currency==1){
                $inr_price=$price;
                $inr_offer=$offer;
                $usd_price=round(($price*$usd_rate),2);
                $usd_offer=round(($offer*$usd_rate),2);
            }else{
                $inr_price=round(($price/$usd_rate),2);
                $inr_offer=round(($offer/$usd_rate),2);
                $usd_price=$price;
                $usd_offer=$offer;
            }

            $html.='<div class="item-item-big-index col-lg-3 col-12 mt-3 mx-1 p-0">
                <span class="badge badge_love bg-danger position-absolute top-0 end-0 px-2 py-2">
                    <i class="fa-regular fa-heart wishlist'.$id.'" onclick="add_wish('.$id.')"></i>
                </span>

                <img src="'.$filepath.'" alt="'.$slug.'">
                <div class="item-name-index mt-2 fw-bold px-1 col-12 text-start">
                    <p class="mb-1">'.$name.'</p>
                    <p class="text-start text-black-50 mb-2">
                        <small><i class="fa-solid fa-eye" id="view_'.$id.'"></i> '.$view.'</small>&nbsp;&nbsp;
                        <small><i class="fa-solid fa-heart" id="wish_'.$id.'"></i> '.$wish.'</small>&nbsp;&nbsp;
                        <a class="text-decoration-none text-black-50" href="'.$site_url.'category/category.php?slug='.$category_slug.'"><small><i class="fa-solid fa-list"></i> '.$category_name.'</small></a>
                    </p>
                </div>

                
                <div class="price-tag text-start mb-2 px-1">
                    <i class="fa-solid fa-tag text-success"></i> 
                        <del class="text-black-50 inr_price">₹'.$inr_price.'</del> <span class="text-danger fw-bold inr_price">₹'.$inr_offer.'</span>
                        <del class="text-black-50 usd_price">$'.$usd_price.'</del> <span class="text-danger fw-bold usd_price">$'.$usd_offer.'</span>
                </div>

                <div class="col-12 px-1 pb-2 d-flex flex-row justify-content-between flex-wrap">
                    <a href="'.$site_url.'product/product.php?slug='.$slug.'" class="btn btn-sm btn-primary col-sm-5 text-decoration-none">View Details</a>
                    <button class="btn btn-sm btn-dark col-sm-5 addcart add_cart_'.$id.'" onclick="add_cart('.$id.')">Add To Cart</button>
                    <button class="btn btn-sm btn-warning d-none col-sm-5 removecart remove_cart_'.$id.'" onclick="remove_cart('.$id.')">In Cart</button>
                </div>
            </div>';
        }


        $my_arr = array('status' => 1, 'html'=> $html);
        echo json_encode($my_arr);
        break;


    case 'loadProductsSeller':
        $html='';
        $limit=$_POST["limit"];
        $offset=$_POST["offset"];
        $seller=$_POST["seller"];


        $sql="SELECT `id`,`name`,`slug` FROM `categories`";
        $query = $pdoconn->prepare($sql);
        $query->execute();
        $my_arr = $query->fetchAll(PDO::FETCH_ASSOC);
        $categoryArray = [];
        foreach ($my_arr as $category) {
            $categoryArray[$category['id']]['name'] = $category['name'];
            $categoryArray[$category['id']]['slug'] = $category['slug'];
        }


        $sql="SELECT COUNT(`id`) AS cnt FROM `products` WHERE `active`=1 AND `user_id`='$seller'";
        $query = $pdoconn->prepare($sql);
        $query->execute();
        $my_arr = $query->fetchAll(PDO::FETCH_ASSOC);
        $total_products=$my_arr[0]['cnt'];
        $total_pages = ceil($total_products / $limit);



        $sql="SELECT `id`,`name`,`slug`,`currency`,`price`,`offer`,`featured_image`,`category_id`,`view`,`wish` FROM `products` 
        WHERE `active`=1 AND `user_id`='$seller' ORDER BY `created_at` DESC,`view` DESC,`wish` DESC LIMIT $limit OFFSET $offset";
        $query = $pdoconn->prepare($sql);
        $query->execute();
        $my_arr = $query->fetchAll(PDO::FETCH_ASSOC);
        foreach($my_arr as $val){
            $id=$val['id'];
            $name=$val['name'];
            $slug=$val['slug'];
            $currency=$val['currency'];
            $price=$val['price'];
            $offer=$val['offer'];
            $featured_image=$val['featured_image'];
            $category_id=$val['category_id'];
            $view=$val['view'];
            $wish=$val['wish'];

            $filepath = $site_url.'product_images/'.$featured_image;

            if(isset($categoryArray[$category_id])){
                $category_name=$categoryArray[$category_id]['name'];
                $category_slug=$categoryArray[$category_id]['slug'];
            }else{
                $category_name=$category_slug='';
            }



            $usd_rate = $_COOKIE["usd"];
            if($currency==1){
                $inr_price=$price;
                $inr_offer=$offer;
                $usd_price=round(($price*$usd_rate),2);
                $usd_offer=round(($offer*$usd_rate),2);
            }else{
                $inr_price=round(($price/$usd_rate),2);
                $inr_offer=round(($offer/$usd_rate),2);
                $usd_price=$price;
                $usd_offer=$offer;
            }

            $html.='<div class="item-item-big-index col-lg-3 col-12 mt-3 mx-1 p-0">
                <span class="badge badge_love bg-danger position-absolute top-0 end-0 px-2 py-2">
                    <i class="fa-regular fa-heart wishlist'.$id.'" onclick="add_wish('.$id.')"></i>
                </span>

                <img src="'.$filepath.'" alt="'.$slug.'">
                <div class="item-name-index mt-2 fw-bold px-1 col-12 text-start">
                    <p class="mb-1">'.$name.'</p>
                    <p class="text-start text-black-50 mb-2">
                        <small><i class="fa-solid fa-eye" id="view_'.$id.'"></i> '.$view.'</small>&nbsp;&nbsp;
                        <small><i class="fa-solid fa-heart" id="wish_'.$id.'"></i> '.$wish.'</small>&nbsp;&nbsp;
                        <a class="text-decoration-none text-black-50" href="'.$site_url.'category/category.php?slug='.$category_slug.'"><small><i class="fa-solid fa-list"></i> '.$category_name.'</small></a>
                    </p>
                </div>

                
                <div class="price-tag text-start mb-2 px-1">
                    <i class="fa-solid fa-tag text-success"></i> 
                        <del class="text-black-50 inr_price">₹'.$inr_price.'</del> <span class="text-danger fw-bold inr_price">₹'.$inr_offer.'</span>
                        <del class="text-black-50 usd_price">$'.$usd_price.'</del> <span class="text-danger fw-bold usd_price">$'.$usd_offer.'</span>
                </div>

                <div class="col-12 px-1 pb-2 d-flex flex-row justify-content-between flex-wrap">
                    <a href="'.$site_url.'product/product.php?slug='.$slug.'" class="btn btn-sm btn-primary col-sm-5 text-decoration-none">View Details</a>
                    <button class="btn btn-sm btn-dark col-sm-5 addcart add_cart_'.$id.'" onclick="add_cart('.$id.')">Add To Cart</button>
                    <button class="btn btn-sm btn-warning d-none col-sm-5 removecart remove_cart_'.$id.'" onclick="remove_cart('.$id.')">In Cart</button>
                </div>
            </div>';
        }


        $my_arr = array('status' => 1, 'html'=> $html);
        echo json_encode($my_arr);
        break;


    case 'loadWishList':
        $html='<tr>
                <td colspan="5" style="text-align: center;">Login First</td>
            </tr>';

        if(isset($_SESSION["user_id"])) {
            $html='';
            $user_id = $_SESSION["user_id"];

            $sql="SELECT `wishlist` FROM `wishlist_cart` WHERE `user_id`='$user_id' AND `wishlist` IS NOT NULL";
            $query = $pdoconn->prepare($sql);
            $query->execute();
            $my_arr = $query->fetchAll(PDO::FETCH_ASSOC);
            $wishlist=array();
            foreach($my_arr as $val){
                array_push($wishlist,$val['wishlist']);
            }

            $final_wish=implode(',',$wishlist);

            $sql="SELECT `id`,`name` FROM `categories`";
            $query = $pdoconn->prepare($sql);
            $query->execute();
            $my_arr = $query->fetchAll(PDO::FETCH_ASSOC);
            $categoryArray = [];
            foreach ($my_arr as $category) {
                $categoryArray[$category['id']] = $category['name'];
            }

            $sql="SELECT `id`,`name`,`slug`,`currency`,`price`,`offer`,`category_id`,`featured_image`,
        DATE_FORMAT(`created_at`,'%d-%m-%Y %H:%i') AS created_at FROM `products`
         WHERE `active`=1 AND `pending`=0 AND `drive_pending`=0 
           AND `id` IN ($final_wish) ORDER BY `active` DESC, `created_at` DESC";
            $query = $pdoconn->prepare($sql);
            $query->execute();
            $my_arr = $query->fetchAll(PDO::FETCH_ASSOC);

            $i=1;
            foreach ($my_arr as $val) {
                $id=$val['id'];
                $name=$val['name'];
                $slug=$val['slug'];
                $currency=$val['currency'];
                $price=$val['price'];
                $offer=$val['offer'];
                $category_id=$val['category_id'];
                $created_at=$val['created_at'];
                $feature_image=$val['featured_image'];

                $usd_rate = $_COOKIE["usd"];
                if($currency==1){
                    $inr_price=$price;
                    $inr_offer=$offer;
                    $usd_price=round(($price*$usd_rate),2);
                    $usd_offer=round(($offer*$usd_rate),2);
                }else{
                    $inr_price=round(($price/$usd_rate),2);
                    $inr_offer=round(($offer/$usd_rate),2);
                    $usd_price=$price;
                    $usd_offer=$offer;
                }



                $html .= '<tr>
                <td>'.$name.'</td>
                <td><img src="product_images/'.$feature_image.'" class="pr_img" alt="'.$slug.'"></td>
                <td>
                    <del class="text-black-50 inr_price"><small>₹'.$inr_price.'</small></del> <span class="text-danger fw-bold inr_price">₹'.$inr_offer.'</span>
                    <del class="text-black-50 usd_price"><small>$'.$usd_price.'</small></del> <span class="text-danger fw-bold usd_price">$'.$usd_offer.'</span>
                </td>
                <td>'.$categoryArray[$category_id].'</td>
                <td><button class="btn btn-sm btn-danger" onclick="remove_wish('.$id.')"><i class="fa-solid fa-trash"></i></button></td>
            </tr>';

            }
        }




        $my_arr = array('status' => 1, 'msg' => '', 'html'=> $html);
        echo json_encode($my_arr);
        break;


    case 'loadCartList':
        $html='<tr>
                <td colspan="6" style="text-align: center;">Login First</td>
            </tr>';

        if(isset($_SESSION["user_id"])) {
            $html='';
            $user_id = $_SESSION["user_id"];

            $sql="SELECT `cart` FROM `wishlist_cart` WHERE `user_id`='$user_id' AND `cart` IS NOT NULL";
            $query = $pdoconn->prepare($sql);
            $query->execute();
            $my_arr = $query->fetchAll(PDO::FETCH_ASSOC);
            $cartlist=array();
            foreach($my_arr as $val){
                array_push($cartlist,$val['cart']);
            }

            $final_cart=implode(',',$cartlist);

            $sql="SELECT `id`,`name` FROM `categories`";
            $query = $pdoconn->prepare($sql);
            $query->execute();
            $my_arr = $query->fetchAll(PDO::FETCH_ASSOC);
            $categoryArray = [];
            foreach ($my_arr as $category) {
                $categoryArray[$category['id']] = $category['name'];
            }

            $sql="SELECT `id`,`name`,`slug`,`currency`,`price`,`offer`,`category_id`,`featured_image`,
        DATE_FORMAT(`created_at`,'%d-%m-%Y %H:%i') AS created_at FROM `products`
         WHERE `active`=1 AND `pending`=0 AND `drive_pending`=0 
           AND `id` IN ($final_cart) ORDER BY `active` DESC, `created_at` DESC";
            $query = $pdoconn->prepare($sql);
            $query->execute();
            $my_arr = $query->fetchAll(PDO::FETCH_ASSOC);

            $non_delete_cart=array();
            $i=1;

            $usd_price_total=$inr_price_total=0;
            $usd_offer_total=$inr_offer_total=0;
            foreach ($my_arr as $val) {
                $id=$val['id'];
                array_push($non_delete_cart,$id);
                $name=$val['name'];
                $slug=$val['slug'];
                $currency=$val['currency'];
                $price=$val['price'];
                $offer=$val['offer'];
                $category_id=$val['category_id'];
                $created_at=$val['created_at'];
                $feature_image=$val['featured_image'];
                $usd_rate = $_COOKIE["usd"];
                if($currency==1){
                    $inr_price=$price;
                    $inr_offer=$offer;
                    $usd_price=round(($price*$usd_rate),2);
                    $usd_offer=round(($offer*$usd_rate),2);
                }else{
                    $inr_price=round(($price/$usd_rate),2);
                    $inr_offer=round(($offer/$usd_rate),2);
                    $usd_price=$price;
                    $usd_offer=$offer;
                }


                $usd_price_total=$usd_price_total+$usd_price;
                $inr_price_total=$inr_price_total+$inr_price;
                $usd_offer_total=$usd_offer_total+$usd_offer;
                $inr_offer_total=$inr_offer_total+$inr_offer;

                $html .= '<tr>
                <td>'.$name.'</td>
                <td><img src="product_images/'.$feature_image.'" class="pr_img" alt="'.$slug.'"></td>
                <td>
                    <del class="text-black-50 inr_price"><small>₹'.$inr_price.'</small></del> <del class="text-black-50 usd_price"><small>$'.$usd_price.'</small></del>
                </td>
                <td>
                    <span class="text-danger fw-bold inr_price">₹'.$inr_offer.'</span> <span class="text-danger fw-bold usd_price">$'.$usd_offer.'</span>
                </td>
                <td>'.$categoryArray[$category_id].'</td>
                <td><button class="btn btn-sm btn-danger" onclick="remove_wish('.$id.')"><i class="fa-solid fa-trash"></i></button></td>
            </tr>';

            }

            if(count($my_arr)>0){
                $savings_percentage = (($inr_price_total - $inr_offer_total) / $inr_price_total) * 100;
                $savings_percentage=round($savings_percentage,0);


                $html .= '<tr>
                <td colspan="2" class="bg-light fw-bold">Total:</td>
                <td class="bg-light fw-bold">
                    <del class="text-black-50 inr_price"><small>₹'.$inr_price_total.'</small></del> <del class="text-black-50 usd_price"><small>$'.$usd_price_total.'</small></del>
                </td>
                <td class="bg-light fw-bold text-warning">
                    <span class="text-danger fw-bold inr_price">₹'.$inr_offer_total.'</span> <span class="text-danger fw-bold usd_price">$'.$usd_offer_total.'</span>
                </td>
                <td class="bg-success fw-bold text-white">Save: '.$savings_percentage.'%</td>
                <td class="bg-dark fw-bold text-white" style="cursor: pointer;" onclick="buy_now()">Buy Now</td>
            </tr>';
            }

            if(count($non_delete_cart)>0){
                $delete_cart=implode(',',$non_delete_cart);
                $sql="DELETE FROM `wishlist_cart` WHERE `user_id`='$user_id' AND `cart` NOT IN ($delete_cart)";
                $query = $pdoconn->prepare($sql);
                $query->execute();
            }

        }




        $my_arr = array('status' => 1, 'msg' => '', 'html'=> $html);
        echo json_encode($my_arr);
        break;



    case 'wish_cart':
        $wishlistItems = array();
        $cartlistItems = array();
        $cart_cnt=$wish_cnt=0;

        if(isset($_SESSION["user_id"])){
            $user_id=$_SESSION["user_id"];
            $sql="SELECT `wishlist`,`cart` FROM `wishlist_cart` WHERE `user_id`='$user_id'";
            $query = $pdoconn->prepare($sql);
            $query->execute();
            $my_arr = $query->fetchAll(PDO::FETCH_ASSOC);
            foreach ($my_arr as $item) {
                $wishlist=$item['wishlist'];
                $cart=$item['cart'];

                if($wishlist==''){
                    array_push($cartlistItems, $cart);
                }else{
                    array_push($wishlistItems, $wishlist);
                }
            }

            $cart_cnt=count($cartlistItems);
            $wish_cnt=count($wishlistItems);
        }


        $my_arr = array('status' => 1, 'wish'=> $wishlistItems, 'cart'=> $cartlistItems, 'cart_cnt'=>$cart_cnt, 'wish_cnt'=>$wish_cnt);
        echo json_encode($my_arr);
        break;

    case 'add_wish':
        $id=$_POST["id"];
        $rmv=$_POST["rmv"];
        $status=0;

        if(isset($_SESSION["user_id"])){
            $user_id=$_SESSION["user_id"];

            if($rmv==0){
                $sql="INSERT INTO `wishlist_cart` (`user_id`,`wishlist`) VALUES ('$user_id','$id')";
                $query = $pdoconn->prepare($sql);
                $query->execute();

                $sql="DELETE FROM wishlist_cart WHERE id NOT IN (SELECT * FROM (SELECT MIN(id) FROM wishlist_cart GROUP BY user_id, wishlist, cart) AS temp)";
                $query = $pdoconn->prepare($sql);
                $query->execute();
            }else{
                $sql="DELETE FROM wishlist_cart WHERE user_id='$user_id' AND `wishlist`='$id'";
                $query = $pdoconn->prepare($sql);
                $query->execute();
            }
            $status=1;
        }


        $my_arr = array('status' => $status);
        echo json_encode($my_arr);
        break;


    case 'remove_wish':
        $id=$_POST["id"];
        $status=0;

        if(isset($_SESSION["user_id"])){
            $user_id=$_SESSION["user_id"];

            $sql="DELETE FROM wishlist_cart WHERE user_id='$user_id' AND `wishlist`='$id'";
            $query = $pdoconn->prepare($sql);
            $query->execute();
            $status=1;
        }


        $my_arr = array('status' => $status);
        echo json_encode($my_arr);
        break;



    case 'add_cart':
        $id=$_POST["id"];
        $status=0;

        if(isset($_SESSION["user_id"])){
            $user_id=$_SESSION["user_id"];

            $sql="INSERT INTO `wishlist_cart` (`user_id`,`cart`) VALUES ('$user_id','$id')";
            $query = $pdoconn->prepare($sql);
            $query->execute();

            $sql="DELETE FROM wishlist_cart WHERE id NOT IN (SELECT * FROM (SELECT MIN(id) FROM wishlist_cart GROUP BY user_id, wishlist, cart) AS temp)";
            $query = $pdoconn->prepare($sql);
            $query->execute();
            $status=1;
        }


        $my_arr = array('status' => $status);
        echo json_encode($my_arr);
        break;


    case 'remove_cart':
        $id=$_POST["id"];
        $status=0;

        if(isset($_SESSION["user_id"])){
            $user_id=$_SESSION["user_id"];

            $sql="DELETE FROM `wishlist_cart` WHERE `user_id`='$user_id' AND `cart`='$id'";
            $query = $pdoconn->prepare($sql);
            $query->execute();
            $status=1;
        }


        $my_arr = array('status' => $status);
        echo json_encode($my_arr);
        break;

    case 'newsletter':
        $newsletter_email=$_POST["newsletter_email"];

        if (filter_var($newsletter_email, FILTER_VALIDATE_EMAIL)) {
            $sql="INSERT INTO `newsletters` (`email`) VALUES ('$newsletter_email')";
            $query = $pdoconn->prepare($sql);
            $query->execute();

            $sql="DELETE FROM newsletters WHERE id NOT IN (SELECT * FROM (SELECT MIN(id) FROM newsletters GROUP BY email) AS temp)";
            $query = $pdoconn->prepare($sql);
            $query->execute();
            $status=1;
        } else {
            $status=0;
        }

        $my_arr = array('status' => $status);
        echo json_encode($my_arr);
        break;


    case 'sendMessage':
        $success = 0;
        $msg = $link = '';
        $result=1;

        $subject = strip_tags($_POST['subject']);
        $message = strip_tags($_POST['message']);

        if(isset($_SESSION["user_id"])) {
            $user_id = $_SESSION["user_id"];

            $sql="INSERT INTO `message` (`from`,`to`,`create_on`,`subject`) VALUES ('$user_id','1',NOW(),'$subject')";
            $query = $pdoconn->prepare($sql);
            $query->execute();
            $lastInsertId = $pdoconn->lastInsertId();


            $data = [
                'subject' => $subject,
                'message' => $message
            ];

            $folderPath = 'login/message/';
            $filePath = $folderPath . $lastInsertId . '.json';

            if (!file_exists($folderPath)) {
                mkdir($folderPath, 0777, true);
            }

            $jsonData = json_encode($data, JSON_PRETTY_PRINT);
            if ($jsonData === false) {
                $result=0;
            }

            if (file_put_contents($filePath, $jsonData) === false) {
                $result=0;
            }


            if($result==0){
                $sql="DELETE FROM `message` WHERE `id`='$lastInsertId'";
                $query = $pdoconn->prepare($sql);
                $query->execute();

                $my_arr = array('status' => 0, 'msg' => 'Some Error Found.');
                echo json_encode($my_arr);
            }else{
                $my_arr = array('status' => 1, 'msg' => 'Sent Successfully.');
                echo json_encode($my_arr);
            }
        }else{
            $my_arr = array('status' => 0, 'msg' => 'Login First.');
            echo json_encode($my_arr);
        }


        break;
}

function shortenString($string, $length) {
    if (strlen($string) > $length) {
        return substr($string, 0, $length) . '...';
    }
    return $string;
}