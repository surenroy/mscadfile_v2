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
    case 'load_order':
        $html = '';


        $sql = "SELECT products.name,
       products.slug,
       sales.product_id,
       sales.seller_id,
       sales_payment.unique_id,
       DATE_FORMAT(sales.created_at,'%d-%m-%Y %H:%i') AS created_at,
       sales.currency,
       sales.amount,
       sales.buyer_id,
       products.featured_image
    FROM (sales sales
          LEFT OUTER JOIN products products
             ON (sales.product_id = products.id))
         LEFT OUTER JOIN sales_payment sales_payment
            ON (sales.payment_id = sales_payment.id)
    WHERE (sales.buyer_id = $user_id)
    ORDER BY sales.created_at DESC";
        $query = $pdoconn->prepare($sql);
        $query->execute();
        $my_arr = $query->fetchAll(PDO::FETCH_ASSOC);

        $i = 1;
        foreach ($my_arr as $val) {
            $id = $val['product_id'];
            $name = $val['name'];
            $slug = $val['slug'];
            $unique_id = $val['unique_id'];
            $currency = $val['currency'];
            $amount = $val['amount'];
            $created_at = $val['created_at'];
            $seller_id = $val['seller_id'];
            $featured_image = $val['featured_image'];


            if ($currency == 1) {
                $currencySymbol = '₹';
            } else {
                $currencySymbol = '$';
            }


            $html .= '<tr>
                    <td>' . $created_at . '</td>
                    <td>' . $name . '</td>
                    <td><a target="_blank" href="' . $site_url . 'seller/?id=' . $seller_id . '" class="text-dark no-underline">#' . $seller_id . '</a></td>
                    <td><img src="' . $site_url . 'product_images/' . $featured_image . '" class="pr_img" alt="' . $slug . '"></td>
                    <td>
                        <button class="btn btn-sm btn-warning mx-1 p-1 px-2" onclick="load_files('.$id.')"><i class="fa-solid fa-eye"></i></button>
                    </td>
                    <td>' . $unique_id . '</td>
                    <td>' . $currencySymbol . $amount . '</td>
                    
                </tr>';

            $i = $i + 1;


        }


        $my_arr = array('status' => 1, 'msg' => '', 'html' => $html);
        echo json_encode($my_arr);
        break;








    case 'load_files':
        $html='';
        $id=$_POST['id'];

        $sql="SELECT `id` FROM `sales` WHERE `product_id`='$id' AND `buyer_id`='$user_id'";
        $query = $pdoconn->prepare($sql);
        $query->execute();
        $my_arr = $query->fetchAll(PDO::FETCH_ASSOC);
       if(count($my_arr)==0){
           $my_arr = array('status' => 0, 'msg' => 'Not Allowed...', 'html'=> $html);
           echo json_encode($my_arr);
           exit();
       }

        $sql="SELECT `file_image`,`drive_link`,`size` FROM `products_files` WHERE `type`=1 AND `product_id`='$id'";
        $query = $pdoconn->prepare($sql);
        $query->execute();
        $my_arr = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach ($my_arr as $val) {
            $name=$val['file_image'];
            $drive_link=$val['drive_link'];
            $size=$val['size'];

            $html .= '<tr>
                <td>'.$name.'</td>
                <td>'.$size.'</td>
                <td>
                    <a target="_blank" href="https://drive.google.com/uc?export=download&id='.$drive_link.'" download><i class="fa-solid fa-download"></i></a>
                </td>
            </tr>';

        }


        $my_arr = array('status' => 1, 'msg' => '', 'html'=> $html);
        echo json_encode($my_arr);
        break;




}




