<?php
#include_once("../connection-pdo.php");
include_once('/home/arindam.co.in/mscad/connection-pdo.php');

$timeMinusOneHour = date('Y-m-d H:i:s', strtotime('-1 hour'));

$sql = "SELECT `id`,`file_url` FROM `download_files` WHERE DATE_FORMAT(`created_at`,'%Y-%m-%d %H:%i:%s')<='$timeMinusOneHour'";
$query = $pdoconn->prepare($sql);
$query->execute();
$my_arr = $query->fetchAll(PDO::FETCH_ASSOC);
foreach ($my_arr as $val) {
    $dnl_id = $val['id'];
    $file_url = $val['file_url'];


    $filePath = '/home/arindam.co.in/mscad/file_download/' . $file_url;
    if (file_exists($filePath)) {
        unlink($filePath);
    }

    $sql = "DELETE FROM `download_files` WHERE `id`='$dnl_id'";
    $query = $pdoconn->prepare($sql);
    $query->execute();
}




$sql="UPDATE products JOIN ( SELECT id, FLOOR(20 + (RAND() * 41)) AS random_view_increase,
           FLOOR(5 + (RAND() * 12)) * (IF(RAND() > 0.5, 1, -1)) AS random_wishlist_change
    FROM products ORDER BY RAND() LIMIT 70) AS random_values
ON products.id = random_values.id SET 
    products.view = products.view + random_values.random_view_increase,
    products.wish = GREATEST(products.wish + random_values.random_wishlist_change, 0)";
$query = $pdoconn->prepare($sql);
$query->execute();
?>
