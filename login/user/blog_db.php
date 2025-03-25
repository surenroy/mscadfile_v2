<?php
set_time_limit(1800);
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


if($user_type!=0){
    header('Location: ../../logout.php');
    exit(0);
}


$server_storage_limit=30; //always deduct 5gb from available server space
$server_storage_limit=0.05;

switch ($action) {


    case 'submit_blog':
        if (isset($_FILES['file'])) {
            $file = $_FILES['file'];
            $title = strip_tags($_POST['title']);
            $short_description = strip_tags($_POST['short_description']);
            $description = $_POST['description'];

            $uploadDir = '../../blog_data/';

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            $actual_name =basename($file['name']);
            $fileExtension = strtolower(pathinfo($actual_name, PATHINFO_EXTENSION));

            $uniqueId = uniqid('', true);
            $uniqueIdWithUnderscore = str_replace('.', '_', $uniqueId);

            $fileName = $user_id.time().$uniqueIdWithUnderscore . '.'.$fileExtension;
            $uploadFile = $uploadDir . $fileName;

            $maxSizeInBytes = 300 * 1024; // 300kb
            if ($file['size'] > $maxSizeInBytes) {
                $my_arr = array('status' => 0, 'msg' => 'Max File Size 300kb');
                echo json_encode($my_arr);
                exit;
            }


            if (move_uploaded_file($file['tmp_name'], $uploadFile)) {
                $uniqueSlug = generateUniqueSlug($title);

                $sql="INSERT INTO `blog` (`title`,`slug`,`date_time`,`short_text`,`image`) VALUES ('$title','$uniqueSlug',NOW(),'$short_description','$fileName')";
                $query = $pdoconn->prepare($sql);
                $query->execute();
                $lastInsertId = $pdoconn->lastInsertId();


                $data = [
                    'description' => $description
                ];

                $json_data = json_encode($data, JSON_PRETTY_PRINT);

                $folder = '../../blog_data';
                $filename = $lastInsertId.'.json';

                if (!is_dir($folder)) {
                    mkdir($folder, 0777, true);
                }

                $file_path = $folder . '/' . $filename;

                if (file_put_contents($file_path, $json_data)) {
                    $my_arr = array('status' => 1, 'msg' => 'Saved Successfully');
                    echo json_encode($my_arr);
                } else {
                    $sql="DELETE FROM `blog` WHERE `id`='$lastInsertId'";
                    $query = $pdoconn->prepare($sql);
                    $query->execute();

                    $my_arr = array('status' => 0, 'msg' => 'Some Error Occured..');
                    echo json_encode($my_arr);
                }
            } else {
                $my_arr = array('status' => 0, 'msg' => 'Failed to Move File.');
                echo json_encode($my_arr);
            }
        } else {
            $my_arr = array('status' => 0, 'msg' => 'No File Uploaded');
            echo json_encode($my_arr);
        }

        break;


    case 'load_blog':
        $html='';

        $sql="SELECT `id`,`title`,DATE_FORMAT(`date_time`,'%d-%m-%Y') AS date_time,`del_flg` FROM `blog`";
        $query = $pdoconn->prepare($sql);
        $query->execute();
        $my_arr = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach ($my_arr as $val) {
            $id=$val['id'];
            $title=$val['title'];
            $date_time=$val['date_time'];
            $del_flg=$val['del_flg'];

            if($del_flg==1){
                $clr='style="background-color: #fffbbc !important;"';
            }else{
                $clr='';
            }

            $html.='<tr>
                    <td '.$clr.'>'.$val['date_time'].'</td>
                    <td '.$clr.'>'.$val['title'].'</td>
                    <td '.$clr.'>
                        <button class="btn btn-sm btn-danger mx-1 p-1 px-2" onclick="status_change('.$val['id'].','.$del_flg.')"><i class="fa-solid fa-trash"></i></button>
                    </td>
                </tr>';
        }


        $my_arr = array('status' => 1, 'msg' => '', 'html'=> $html);
        echo json_encode($my_arr);
        break;


    case 'status_change':
        $html='';
        $id=$_POST["id"];
        $del=$_POST["del"];

        if($del==1){
            $sql="UPDATE `blog` SET `del_flg`='0' WHERE `id`='$id'";
        }else if($del==0){
            $sql="UPDATE `blog` SET `del_flg`='1' WHERE `id`='$id'";
        }
        $query = $pdoconn->prepare($sql);
        $query->execute();


        $my_arr = array('status' => 1);
        echo json_encode($my_arr);
        break;
}



function generateUniqueSlug($title) {
    // Convert title to slug format
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title), '-'));

    // Fetch all existing slugs
    $sql = "SELECT `slug` FROM `blog`";
    $query = $pdoconn->prepare($sql);
    $query->execute();
    $existingSlugs = $query->fetchAll(PDO::FETCH_COLUMN); // Fetch as a simple array

    // Check uniqueness and append number if needed
    $originalSlug = $slug;
    $counter = 1;

    while (in_array($slug, $existingSlugs)) {
        $slug = $originalSlug . '-' . $counter;
        $counter++;
    }

    return $slug;
}






