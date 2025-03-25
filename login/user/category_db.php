<?php
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



switch ($action) {

    case 'save':
        $success = 0;
        $msg = $link = '';

        $category = $_POST['category'];
        $category_low = strtolower($category);

        $sql = "SELECT `name`, `slug` FROM `categories`";
        $query = $pdoconn->prepare($sql);
        $query->execute();
        $my_arr = $query->fetchAll(PDO::FETCH_ASSOC);

        $existingSlugs = [];
        foreach ($my_arr as $row) {
            $existingSlugs[] = $row['slug'];
            $name = strtolower($row['name']);

            if ($name === $category_low) {
                $response = ['status' => 0, 'msg' => 'Category Already Exists.'];
                echo json_encode($response);
                exit();
            }
        }

        $baseSlug = createSlug($category_low);
        $uniqueSlug = $baseSlug;

        $counter = 1;
        while (in_array($uniqueSlug, $existingSlugs)) {
            $uniqueSlug = $baseSlug . '-' . $counter;
            $counter++;
        }


        $sql="INSERT INTO `categories` (`name`,`slug`) VALUES ('$category','$uniqueSlug')";
        $query = $pdoconn->prepare($sql);
        $query->execute();
        $lastInsertId = $pdoconn->lastInsertId();


        if (isset($_FILES['featuredImage']) && $_FILES['featuredImage']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['featuredImage']['tmp_name'];
            $fileName = $_FILES['featuredImage']['name'];
            $fileSize = $_FILES['featuredImage']['size'];
            $fileType = $_FILES['featuredImage']['type'];

            $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);

            $newFileName = $lastInsertId . '.jpg';

            $uploadDir = '../../category_img/';
            $destination = $uploadDir . $newFileName;

            if (move_uploaded_file($fileTmpPath, $destination)) {
                $my_arr = array('status' => 1, 'msg' => 'Saved Successfully.');
                echo json_encode($my_arr);
            } else {
                $my_arr = array('status' => 0, 'msg' => 'Image Upload Error.');
                echo json_encode($my_arr);

                $sql="DELETE FROM `categories` WHERE `id`='$lastInsertId'";
                $query = $pdoconn->prepare($sql);
                $query->execute();
            }
        } else {
            $my_arr = array('status' => 0, 'msg' => 'Image Upload Error');
            echo json_encode($my_arr);

            $sql="DELETE FROM `categories` WHERE `id`='$lastInsertId'";
            $query = $pdoconn->prepare($sql);
            $query->execute();
        }
        break;

    case 'load_data':
        $html='';

        $sql="SELECT `id`,`name`,`del_flg` FROM `categories` ORDER BY `del_flg` ASC,`name` ASC";
        $query = $pdoconn->prepare($sql);
        $query->execute();
        $my_arr = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach ($my_arr as $val) {
            $id=$val['id'];
            $name=$val['name'];
            $del_flg=$val['del_flg'];

            if($del_flg==1){
                $clr='style="background-color: #fffbbc !important;"';
            }else{
                $clr='';
            }

            $filepath = '../../category_img/'.$id.'.jpg';

            if (file_exists($filepath)) {
               $img='<img src="'.$filepath.'" class="or_img" alt="Category">';
            } else {
                $img='';
            }


            $html.='<tr>
                        <td '.$clr.'>'.$name.'</td>
                        <td '.$clr.'>'.$img.'</td>
                        <td '.$clr.'>
                            <button class="btn btn-sm btn-dark mx-1 p-1 px-2" onclick="status_change('.$del_flg.','.$id.')"><i class="fa-solid fa-rotate"></i></button>
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
            $sql="UPDATE `categories` SET `del_flg`='0' WHERE `id`='$id'";
        }else if($del==0){
            $sql="UPDATE `categories` SET `del_flg`='1' WHERE `id`='$id'";
        }
        $query = $pdoconn->prepare($sql);
        $query->execute();


        $my_arr = array('status' => 1);
        echo json_encode($my_arr);
        break;
}


function createSlug($text) {
    $slug = strtolower($text);
    $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);
    $slug = trim($slug, '-');
    return $slug;
}