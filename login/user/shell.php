<?php
set_time_limit(3600);
include_once("../../connection-pdo.php");


$product_id = $argv[1];








// Get access token if expired
$sql = "SELECT `refresh_token`, `access_token`, `api_key`, `client_id`, `client_secret`, `auth_code`, UNIX_TIMESTAMP(`created_at`) AS created_at FROM `manage_api_tokens`";
$query = $pdoconn->prepare($sql);
$query->execute();
$my_arr = $query->fetchAll(PDO::FETCH_ASSOC);

$refresh_token = $my_arr[0]['refresh_token'];
$access_token = $my_arr[0]['access_token'];
$client_id = $my_arr[0]['client_id'];
$client_secret = $my_arr[0]['client_secret'];
$created_at = $my_arr[0]['created_at'];

$currentTimestamp = time();
$timeDifference = $currentTimestamp - $created_at;

if ($timeDifference > 2400) {
    // Refresh access token
    $token_url = 'https://oauth2.googleapis.com/token';
    $data = [
        'client_id' => $client_id,
        'client_secret' => $client_secret,
        'refresh_token' => $refresh_token,
        'grant_type' => 'refresh_token'
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $token_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));

    $response = curl_exec($ch);
    if (curl_errno($ch)) {
        exit();
    } else {
        $json = json_decode($response, true);
        $access_token = $json['access_token'];

        $sql = "UPDATE `manage_api_tokens` SET `access_token`='$access_token', `created_at`=NOW()";
        $query = $pdoconn->prepare($sql);
        $query->execute();
    }
    curl_close($ch);
}














$sql="SELECT `drive_link`,`file_image` FROM `products_files` WHERE `type`=1 AND `product_id`='$product_id'";
$query = $pdoconn->prepare($sql);
$query->execute();
$my_arr = $query->fetchAll(PDO::FETCH_ASSOC);
foreach ($my_arr as $val) {
    $file_id=$val['drive_link'];
    $file_image=$val['file_image'];
    $save_path='../../file_download/'.$file_image;



    $url = "https://www.googleapis.com/drive/v3/files/{$file_id}?alt=media";
    $headers = ["Authorization: Bearer $access_token"];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $data = curl_exec($ch);
    curl_close($ch);

    if ($data) {
        file_put_contents($save_path, $data);
        echo 'File Done:'.$file_image;

        $sql="INSERT INTO `download_files` (`file_url`,`complete`) VALUES ('$file_image','1')";
        $query = $pdoconn->prepare($sql);
        $query->execute();
    }

}
