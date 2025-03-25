<?php
ob_start();
set_time_limit(3600);
include_once("/home/arindam.co.in/mscad/connection-pdo.php");

$sql = "SELECT `id`, `user_id` FROM `products` WHERE `pending`=1 AND `drive_pending`=1";
$query = $pdoconn->prepare($sql);
$query->execute();
$my_arr = $query->fetchAll(PDO::FETCH_ASSOC);
foreach ($my_arr as $val) {
    $product_id = $val['id'];
    $user_id = $val['user_id'];

    $sql = "SELECT `folder_id` FROM `users` WHERE `id`='$user_id'";
    $query = $pdoconn->prepare($sql);
    $query->execute();
    $my_arr1 = $query->fetchAll(PDO::FETCH_ASSOC);
    $folderId = $my_arr1[0]['folder_id'];

    $sql = "SELECT `id`, `file_image` FROM `products_files` WHERE `type`=1 AND `product_id`='$product_id' AND `drive_link` IS NULL";
    $query = $pdoconn->prepare($sql);
    $query->execute();
    $my_arr2 = $query->fetchAll(PDO::FETCH_ASSOC);
    $count_pending_upload = count($my_arr2);

    foreach ($my_arr2 as $val2) {
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

        $id = $val2['id'];
        $filePath = '/home/arindam.co.in/mscad/product_files_temp/' . $val2['file_image'];
        $fileName = basename($filePath);
        $fileSize = filesize($filePath);




        $url = "https://www.googleapis.com/drive/v3/about?fields=storageQuota";

        // Initialize cURL
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer $access_token",
            "Content-Type: application/json"
        ]);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            echo 'cURL error: ' . curl_error($ch);
            curl_close($ch);
            return;
        }

        curl_close($ch);
        $data = json_decode($response, true);

        // Check if storageQuota is set
        if (isset($data['storageQuota'])) {
            $limit = $data['storageQuota']['limit']; // Total storage limit in bytes
            $usage = $data['storageQuota']['usage']; // Used storage in bytes

            // Calculate free space
            $freeSpace = $limit - $usage;
            if($fileSize>$freeSpace){
                echo "Drive Storage Limit Exhausted.\n";
                die();
            }

        } else {
            echo "Failed to retrieve storage quota information.\n";

            die();
        }





















        // Prepare metadata
        $uploadUrl = "https://www.googleapis.com/upload/drive/v3/files?uploadType=multipart";
        $metadata = [
            'name' => $fileName,
            'parents' => [$folderId],
        ];
        $metadataJson = json_encode($metadata);
        $boundary = uniqid();
        $body = "--$boundary\r\n";
        $body .= "Content-Type: application/json; charset=UTF-8\r\n\r\n";
        $body .= $metadataJson . "\r\n";
        $body .= "--$boundary\r\n";
        $body .= "Content-Type: application/octet-stream\r\n\r\n";

        // Read file in 100 MB chunks
        $handle = fopen($filePath, 'rb');
        if ($handle === false) {
            die('Failed to open file: ' . $filePath);
        }

        // Define chunk size (100 MB)
        $chunkSize = 100 * 1024 * 1024; // 100 MB

        while (!feof($handle)) {
            $chunk = fread($handle, $chunkSize);
            if ($chunk === false) {
                die('Error reading file.');
            }
            $body .= $chunk;
        }
        fclose($handle);

        // Close the multipart body
        $body .= "\r\n--$boundary--";

        // Upload to Google Drive
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $uploadUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer $access_token",
            "Content-Type: multipart/related; boundary=$boundary",
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);

        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            $my_arr = array('status' => 0, 'msg' => 'Upload Error: ' . curl_error($ch));
            echo json_encode($my_arr);
            exit();
        } else {
            $json = json_decode($response, true);

            if(isset($json['id'])){
                $file_id = $json['id'];

                $sql = "UPDATE `products_files` SET `drive_link`='$file_id' WHERE `id`='$id' AND `product_id`='$product_id'";
                $query = $pdoconn->prepare($sql);
                $query->execute();
            }
        }
        curl_close($ch);

        unset($body, $metadataJson, $handle, $chunk);
        gc_collect_cycles();
    }

    if ($count_pending_upload > 0) {
        $sql = "SELECT `file_image` FROM `products_files` WHERE `type`=1 AND `product_id`='$product_id' AND `drive_link` IS NULL";
        $query = $pdoconn->prepare($sql);
        $query->execute();
        $my_arr3 = $query->fetchAll(PDO::FETCH_ASSOC);
        $count_pending = count($my_arr3);

        if ($count_pending == 0) {
            $sql = "SELECT `file_image`,`size`,`type` FROM `products_files` WHERE `product_id`='$product_id'";
            $query = $pdoconn->prepare($sql);
            $query->execute();
            $my_arr3 = $query->fetchAll(PDO::FETCH_ASSOC);

            $size_total=0;
            $f=0;
            $i=0;
            foreach ($my_arr3 as $val3) {
                $file_image_delete = $val3['file_image'];
                $size = $val3['size'];
                $type = $val3['type'];

                if($type==1){
                    $filePath = '/home/arindam.co.in/mscad/product_files_temp/' . $file_image_delete;
                    if (file_exists($filePath)) {
                        unlink($filePath);
                    }
                    $f=$f+1;
                }else{
                    $i=$i+1;
                }

                $size_total=$size_total+$size;
            }

            $total_files=$f.'/'.$i;
            $size_total=round(($size_total/1024),2);
            $sql = "UPDATE `products` SET `pending`=0, `drive_pending`=0,`total_space`='$size_total',`total_files`='$total_files' WHERE `id`='$product_id'";
            $query = $pdoconn->prepare($sql);
            $query->execute();


            echo $product_id . ' is Published.<br>';
        } else {
            echo $product_id . ' is Pending.<br>';
        }

        ob_flush();
        flush();
    }
}

ob_end_flush();
?>
