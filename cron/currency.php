<?php
include_once('/home/arindam.co.in/mscad/connection-pdo.php');



$url = "https://open.er-api.com/v6/latest/INR";
$response = file_get_contents($url);
if ($response !== false) {
    $data = json_decode($response, true);
    $usd_value = round($data['rates']['USD'],5);

    $sql = "UPDATE `currency_conversion` SET `usd`='$usd_value' WHERE `id`=1";
    $query = $pdoconn->prepare($sql);
    $query->execute();
}




?>
