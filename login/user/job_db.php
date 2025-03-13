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




    case 'submit_job':
        if (isset($_FILES['file'])) {
            $file = $_FILES['file'];
            $description = strip_tags($_POST['description']);

            $uploadDir = '../job/';

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
                $sql="INSERT INTO `jobs` (`image`,`created_at`,`create_by`,`type`) VALUES ('$fileName',NOW(),'$user_id','$description')";
                $query = $pdoconn->prepare($sql);
                $query->execute();

                $my_arr = array('status' => 1, 'msg' => 'Saved Successfully');
                echo json_encode($my_arr);
            } else {
                $my_arr = array('status' => 0, 'msg' => 'Failed to Move File.');
                echo json_encode($my_arr);
            }
        } else {
            $my_arr = array('status' => 0, 'msg' => 'No File Uploaded');
            echo json_encode($my_arr);
        }

        break;





    case 'load_my':
        $html='';

        $sql="SELECT `id`,`image`,DATE_FORMAT(`created_at`,'%d-%m-%Y %H:%i') AS date_time,`del_flg`,`type` FROM `jobs` WHERE `create_by`='$user_id'";
        $query = $pdoconn->prepare($sql);
        $query->execute();
        $my_arr = $query->fetchAll(PDO::FETCH_ASSOC);

        $ids=array();
        foreach ($my_arr as $val) {
            $id=$val['id'];
            $image=$val['image'];
            $date_time=$val['date_time'];
            $del_flg=$val['del_flg'];
            $type=$val['type'];

            switch ($type) {
                case '1':
                    $selectedDescriptionMessage = 'You selected Description 1';
                    break;
                case '2':
                    $selectedDescriptionMessage = 'You selected Description 2';
                    break;
                case '3':
                    $selectedDescriptionMessage = 'You selected Description 3';
                    break;
                case '4':
                    $selectedDescriptionMessage = 'You selected Description 4';
                    break;
                default:
                    $selectedDescriptionMessage = 'Please select a description';
            }

            array_push($ids,$id);

            if($del_flg==1){
                $clr='';
            }else{
                $clr='<button class="btn btn-sm btn-dark col-sm-5 mt-2 hide'.$id.'" onclick="hide_job('.$id.')">Hide</button>';
            }

            $html.='<div class="card p-3 col-12 mb-2 card'.$id.'">
            <div class="row">
                <div class="col-md-2 d-flex flex-row justify-content-center flex-wrap">
                    <img src="'.$site_url.'login/job/'.$image.'" class="col-12 img-fluid rounded job_image w-100" alt="JobImage">
                </div>
                <div class="col-md-4 mt-2 mt-sm-0 d-flex flex-row justify-content-center flex-wrap">
                    <div class="col-12 p-0 m-0 job_desc"><strong>Posted At: '.$date_time.'</strong><br>'.$selectedDescriptionMessage.'</div>
                    <div class="col-12 d-flex flex-row align-items-center justify-content-between">
                        '.$clr.'
                        <button class="btn btn-sm btn-danger col-sm-5 mt-2" onclick="del_job('.$id.')">Delete</button>
                    </div>
                </div>
                <div class="col-md-6 mt-2 mt-sm-0 mt-sm-0">
                    <table class="table table-sm table-responsive">
                        <thead class="table-dark">
                        <tr>
                            <th>User</th>
                            <th>Date</th>
                            <th>Product</th>
                        </tr>
                        </thead>
                        <tbody id="table_my_'.$id.'">
                        
                        </tbody>
                    </table>
                </div>
            </div>
        </div>';
        }


        $my_arr = array('status' => 1, 'msg' => '', 'html'=> $html,'ids'=>$ids);
        echo json_encode($my_arr);
        break;



    case 'load_table':
        $html='';
        $id=$_POST['id'];

        $sql="SELECT job_data.id,
       job_data.user_id,
       job_data.description,
       DATE_FORMAT(job_data.created_at,'%d-%m-%Y %H:%i') as created_at,
       users.id as usrid
        FROM job_data job_data
             LEFT OUTER JOIN users users ON (job_data.user_id = users.id)
        WHERE (job_data.job_id = $id)
        ORDER BY job_data.created_at DESC";
        $query = $pdoconn->prepare($sql);
        $query->execute();
        $my_arr = $query->fetchAll(PDO::FETCH_ASSOC);

        $ids=array();
        foreach ($my_arr as $val) {
            $id=$val['id'];
            $usrid=$val['usrid'];
            $date_time=$val['created_at'];
            $description=$val['description'];


            $html.='<tr>
                            <td>'.$usrid.'</td>
                            <td>'.$date_time.'</td>
                            <td><button class="btn btn-sm btn-primary" data-ids="'.$description.'" onclick="show_product('.$id.')">View Product</button></td>
                        </tr>';
        }


        $my_arr = array('status' => 1, 'msg' => '', 'html'=> $html);
        echo json_encode($my_arr);
        break;



    case 'load_other':
        $html='';

        $sql="SELECT `id`,`image`,DATE_FORMAT(`created_at`,'%d-%m-%Y %H:%i') AS date_time,`del_flg`,`type` FROM `jobs` WHERE `create_by`!='$user_id'";
        $query = $pdoconn->prepare($sql);
        $query->execute();
        $my_arr = $query->fetchAll(PDO::FETCH_ASSOC);

        $ids=array();
        foreach ($my_arr as $val) {
            $id=$val['id'];
            $image=$val['image'];
            $date_time=$val['date_time'];
            $del_flg=$val['del_flg'];
            $type=$val['type'];

            switch ($type) {
                case '1':
                    $selectedDescriptionMessage = 'You selected Description 1';
                    break;
                case '2':
                    $selectedDescriptionMessage = 'You selected Description 2';
                    break;
                case '3':
                    $selectedDescriptionMessage = 'You selected Description 3';
                    break;
                case '4':
                    $selectedDescriptionMessage = 'You selected Description 4';
                    break;
                default:
                    $selectedDescriptionMessage = 'Please select a description';
            }

            array_push($ids,$id);

            $html.='<div class="card p-3 col-12 mb-2">
            <div class="row">
                <div class="col-md-2 d-flex flex-row justify-content-center flex-wrap">
                    <img src="'.$site_url.'login/job/'.$image.'" class="col-12 img-fluid rounded job_image w-100" alt="Job Image">
                    <div class="col-12 d-flex flex-row align-items-center justify-content-center">
                        <a href="'.$site_url.'login/job/'.$image.'" target="_blank" download class="btn btn-sm btn-dark col-12 mt-2">Download</a>
                    </div>
                </div>
                <div class="col-md-4 mt-2 mt-sm-0 d-flex flex-row justify-content-center flex-wrap">
                    <div class="col-12 p-0 m-0 job_desc"><strong>Posted At: '.$date_time.'</strong><br>'.$selectedDescriptionMessage.'</div>
                    <div class="col-12 d-flex flex-row align-items-center justify-content-center">
                        <button class="btn btn-sm btn-primary col-12 mt-2" onclick="reply_product_modal('.$id.')">Reply With Product</button>
                    </div>
                </div>
                <div class="col-md-6 mt-2 mt-sm-0 mt-sm-0">
                    <table class="table table-sm table-responsive">
                        <thead class="table-dark">
                        <tr>
                            <th>User</th>
                            <th>Date</th>
                            <th>Product</th>
                        </tr>
                        </thead>
                        <tbody id="table_oth_'.$id.'">
                        
                        
                        </tbody>
                    </table>
                </div>
            </div>
        </div>';
        }


        $my_arr = array('status' => 1, 'msg' => '', 'html'=> $html,'ids'=>$ids);
        echo json_encode($my_arr);
        break;


    case 'load_table_oth':
        $htmlo='';
        $id=$_POST['id'];

        $sql="SELECT job_data.id,
       job_data.user_id,
       job_data.description,
       DATE_FORMAT(job_data.created_at,'%d-%m-%Y %H:%i') as created_at,
       users.id as usrid
        FROM job_data job_data
             LEFT OUTER JOIN users users ON (job_data.user_id = users.id)
        WHERE (job_data.job_id = $id)
        ORDER BY job_data.created_at DESC";
        $query = $pdoconn->prepare($sql);
        $query->execute();
        $my_arr = $query->fetchAll(PDO::FETCH_ASSOC);

        $ids=array();
        foreach ($my_arr as $val) {
            $id=$val['id'];
            $usrid=$val['usrid'];
            $date_time=$val['created_at'];
            $description=$val['description'];


            $htmlo.='<tr>
                            <td>'.$usrid.'</td>
                            <td>'.$date_time.'</td>
                            <td><button class="btn btn-sm btn-dark" data-ids="'.$description.'" onclick="show_product('.$id.')">Show Product</button></td>
                        </tr>';
        }


        $my_arr = array('status' => 1, 'msg' => '', 'html'=> $htmlo);
        echo json_encode($my_arr);
        break;


    case 'hide_job':
        $html='';
        $id=$_POST["id"];

        $sql="UPDATE `jobs` SET `del_flg`='1' WHERE `id`='$id'";
        $query = $pdoconn->prepare($sql);
        $query->execute();


        $my_arr = array('status' => 1);
        echo json_encode($my_arr);
        break;


    case 'del_job':
        $html='';
        $id=$_POST["id"];

        $sql="SELECT `image` FROM `jobs` WHERE `id`='$id'";
        $query = $pdoconn->prepare($sql);
        $query->execute();
        $my_arr = $query->fetchAll(PDO::FETCH_ASSOC);
        $image=$my_arr[0]['image'];

        $filePath = '../job/'.$image;

        if (file_exists($filePath)) {
            unlink($filePath);
        }

        $filePath = '../job/'.$id.'.json';

        if (file_exists($filePath)) {
            unlink($filePath);
        }


        $sql="DELETE FROM `jobs` WHERE `id`='$id'";
        $query = $pdoconn->prepare($sql);
        $query->execute();

        $sql="DELETE FROM `job_data` WHERE `job_id`='$id'";
        $query = $pdoconn->prepare($sql);
        $query->execute();


        $my_arr = array('status' => 1);
        echo json_encode($my_arr);
        break;


    case 'reply_product_modal':
        $html='';

        $sql="SELECT `id`,`name` FROM `categories`";
        $query = $pdoconn->prepare($sql);
        $query->execute();
        $my_arr = $query->fetchAll(PDO::FETCH_ASSOC);
        $categoryArray = [];
        foreach ($my_arr as $category) {
            $categoryArray[$category['id']] = $category['name'];
        }

        $sql="SELECT `id`,`name`,`slug`,`currency`,`price`,`offer`,`category_id`,`featured_image`,
        DATE_FORMAT(`created_at`,'%d-%m-%Y %H:%i') AS created_at FROM `products`
         WHERE `user_id`='$user_id' AND `active`=1 AND `pending`=0 AND `drive_pending`=0 ORDER BY `active` DESC, `created_at` DESC";
        $query = $pdoconn->prepare($sql);
        $query->execute();
        $my_arr = $query->fetchAll(PDO::FETCH_ASSOC);

        $i=1;
        foreach ($my_arr as $val) {
            $id=$val['id'];
            $name=$val['name'];
            $slug=$val['slug'];
            $currency=$val['currency'];
            $price=$val['price'];
            $offer=$val['offer'];
            $category_id=$val['category_id'];
            $created_at=$val['created_at'];
            $feature_image=$val['featured_image'];
            if ($currency == 1) {
                $currencySymbol = '₹';
            } else {
                $currencySymbol = '$';
            }

            $html .= '<tr>
                <td><input type="checkbox" name="product_ids[]" value="'.$id.'"></td>
                <td>'.$name.'</td>
                <td>'.$slug.'</td>
                <td><img src="../../product_images/'.$feature_image.'" class="pr_img" alt="'.$slug.'"></td>
                <td><small class="text-decoration-line-through">'.$currencySymbol.$price.'</small> <span class="fw-bold">'.$currencySymbol.$offer.'</span></td>
                <td>'.$categoryArray[$category_id].'</td>
                <td>'.$created_at.'</td>
            </tr>';

        }


        $my_arr = array('status' => 1, 'msg' => '', 'html'=> $html);
        echo json_encode($my_arr);
        break;


    case 'reply_with_product':
        $job=$_POST['job'];
        $checked_value=$_POST['checked_value'];


        $sql="INSERT INTO `job_data` (`job_id`,`user_id`,`description`,`created_at`) VALUES ('$job','$user_id','$checked_value',NOW())";
        $query = $pdoconn->prepare($sql);
        if($query->execute()){
            $my_arr = array('status' => 1, 'msg' => 'Successfully Posted.');
            echo json_encode($my_arr);
        }else{
            $my_arr = array('status' => 0, 'msg' => 'Failed to Add.');
            echo json_encode($my_arr);
        }




        break;


    case 'show_product':
        $html='';
        $id=$_POST["id"];

        $sql="SELECT `description` FROM `job_data` WHERE `id`='$id'";
        $query = $pdoconn->prepare($sql);
        $query->execute();
        $my_arr = $query->fetchAll(PDO::FETCH_ASSOC);
        $description=$my_arr[0]['description'];


        $sql="SELECT `id`,`name` FROM `categories`";
        $query = $pdoconn->prepare($sql);
        $query->execute();
        $my_arr = $query->fetchAll(PDO::FETCH_ASSOC);
        $categoryArray = [];
        foreach ($my_arr as $category) {
            $categoryArray[$category['id']] = $category['name'];
        }

        $sql="SELECT `id`,`name`,`slug`,`currency`,`price`,`offer`,`category_id`,`featured_image`,
        DATE_FORMAT(`created_at`,'%d-%m-%Y %H:%i') AS created_at FROM `products`
         WHERE `user_id`='$user_id' AND `active`=1 AND `pending`=0 AND `drive_pending`=0 
           AND `id` IN ($description) ORDER BY `active` DESC, `created_at` DESC";
        $query = $pdoconn->prepare($sql);
        $query->execute();
        $my_arr = $query->fetchAll(PDO::FETCH_ASSOC);

        $i=1;
        foreach ($my_arr as $val) {
            $id=$val['id'];
            $name=$val['name'];
            $slug=$val['slug'];
            $currency=$val['currency'];
            $price=$val['price'];
            $offer=$val['offer'];
            $category_id=$val['category_id'];
            $created_at=$val['created_at'];
            $feature_image=$val['featured_image'];
            if ($currency == 1) {
                $currencySymbol = '₹';
            } else {
                $currencySymbol = '$';
            }

            $html .= '<tr>
                <td>'.$name.'</td>
                <td>'.$slug.'</td>
                <td><img src="../../product_images/'.$feature_image.'" class="pr_img" alt="'.$slug.'"></td>
                <td><small class="text-decoration-line-through">'.$currencySymbol.$price.'</small> <span class="fw-bold">'.$currencySymbol.$offer.'</span></td>
                <td>'.$categoryArray[$category_id].'</td>
                <td>'.$created_at.'</td>
            </tr>';

        }


        $my_arr = array('status' => 1, 'msg' => '', 'html'=> $html);
        echo json_encode($my_arr);
        break;

}





