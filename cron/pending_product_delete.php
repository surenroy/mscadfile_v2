<?php
include_once("../connection-pdo.php");
$yesterday = date('Y-m-d', strtotime('-2 day'));

$sql = "SELECT `id`, `user_id` FROM `products` WHERE `pending`=1 AND `drive_pending`=0 AND DATE_FORMAT(`created_at`,'%Y-%m-%d')<='$yesterday'";
$query = $pdoconn->prepare($sql);
$query->execute();
$my_arr = $query->fetchAll(PDO::FETCH_ASSOC);
foreach ($my_arr as $val) {
    $product_id = $val['id'];

    $sql = "SELECT `file_image` FROM `products_files` WHERE `product_id`='$product_id'";
    $query = $pdoconn->prepare($sql);
    $query->execute();
    $my_arr2 = $query->fetchAll(PDO::FETCH_ASSOC);
    foreach ($my_arr2 as $val) {
        $file_image = $val['file_image'];

        $filePath = '../product_files_temp/' . $file_image;
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }

    $sql = "DELETE FROM `products_files` WHERE `product_id`='$product_id'";
    $query = $pdoconn->prepare($sql);
    $query->execute();
}
?>
