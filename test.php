<?php
die();
include_once("connection-pdo.php");

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
        $my_arr = array('status' => 0, 'msg' => 'Registration Error.. ');
        echo json_encode($my_arr);
    } else {
        $json = json_decode($response, true);
        $access_token = $json['access_token'];
        echo "Access Token: " . $access_token . "\n";
    }
    curl_close($ch);
}
