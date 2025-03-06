<?php
ob_start();
if (!isset($_SESSION)) session_start();
include_once("../connection-pdo.php");

if (isset($_REQUEST['action']))
    $action = $_REQUEST['action'];
else {
    $action = 'NF';
    $coockie_value = "You Dont Have The Permission To View This Page. ";
    setcookie('error_msg', $coockie_value, time() + (3600), "/");
    header('Location: ../error/error-msg.php');
}





switch ($action) {

    case 'loadSearchProducts':
        $html='';
        $limit=$_POST["limit"];
        $offset=$_POST["offset"];
        $search=$_SESSION['search'];


        $sql="SELECT `id`,`name`,`slug` FROM `categories`";
        $query = $pdoconn->prepare($sql);
        $query->execute();
        $my_arr = $query->fetchAll(PDO::FETCH_ASSOC);
        $categoryArray = [];
        foreach ($my_arr as $category) {
            $categoryArray[$category['id']]['name'] = $category['name'];
            $categoryArray[$category['id']]['slug'] = $category['slug'];
        }


        $sql = "SELECT COUNT(`id`) AS cnt FROM `products` WHERE `active`=1 AND `name` LIKE '%$search%'";
        $query = $pdoconn->prepare($sql);
        $query->execute();
        $my_arr = $query->fetchAll(PDO::FETCH_ASSOC);
        $total_products=$my_arr[0]['cnt'];
        $total_pages = ceil($total_products / $limit);



        $sql="SELECT `id`,`name`,`slug`,`currency`,`price`,`offer`,`featured_image`,`category_id`,`view`,`wish` FROM `products` 
        WHERE `active`=1 AND `name` LIKE '%$search%' ORDER BY `created_at` DESC,`view` DESC,`wish` DESC LIMIT $limit OFFSET $offset";
        $query = $pdoconn->prepare($sql);
        $query->execute();
        $my_arr = $query->fetchAll(PDO::FETCH_ASSOC);
        $i=0;
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

            $i=$i+1;
        }


        $my_arr = array('status' => 1, 'html'=> $html, 'cnt'=>$i);
        echo json_encode($my_arr);
        break;

}

