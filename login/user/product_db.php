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
    case 'load_product':
        $html='';

        $sql="SELECT `id`,`name` FROM `categories`";
        $query = $pdoconn->prepare($sql);
        $query->execute();
        $my_arr = $query->fetchAll(PDO::FETCH_ASSOC);
        $categoryArray = [];
        foreach ($my_arr as $category) {
            $categoryArray[$category['id']] = $category['name'];
        }

        $sql="SELECT `id`,`name`,`slug`,`currency`,`price`,`offer`,`feature`,`active`,`category_id`,
        DATE_FORMAT(`created_at`,'%d-%m-%Y %H:%i') AS created_at,`view`,`wish`,`pending`,`drive_pending`,
        `attr_size`,`attr_size_unit`,`attr_weight`,`attr_weight_unit`,`attr_purity`,`attr_purity_unit`,
        `attr_volume`,`attr_volume_unit`,`files`,`featured_image`,`total_space`,`total_files` FROM `products`
         WHERE `user_id`='$user_id' ORDER BY `active` DESC, `created_at` DESC";
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
           $feature=$val['feature'];
           $active=$val['active'];
           $category_id=$val['category_id'];
           $created_at=$val['created_at'];
           $view=$val['view'];
           $wish=$val['wish'];
           $pending=$val['pending'];
           $drive_pending=$val['drive_pending'];
           $files=$val['files'];
           $feature_image=$val['featured_image'];
           $total_space=$val['total_space'];
           $total_files=$val['total_files'];


            if($pending==1 && $drive_pending==0){
                $html='';
            }else{
                $attr_size=$val['attr_size'];
                $attr_size_unit=$val['attr_size_unit'];
                if($attr_size!=''){
                    $sizeUnit = '';
                    switch ($attr_size_unit) {
                        case 1:
                            $sizeUnit = 'cm';
                            break;
                        case 2:
                            $sizeUnit = 'mm';
                            break;
                        case 3:
                            $sizeUnit = 'inch';
                            break;
                        default:
                            $sizeUnit = '';
                    }
                    $attr_size=$attr_size.' '.$sizeUnit;
                }

                $attr_weight=$val['attr_weight'];
                $attr_weight_unit=$val['attr_weight_unit'];
                if($attr_weight!=''){
                    $sizeUnit = '';
                    switch ($attr_weight_unit) {
                        case 1:
                            $sizeUnit = 'gram';
                            break;
                        case 2:
                            $sizeUnit = 'pound';
                            break;
                        default:
                            $sizeUnit = '';
                    }
                    $attr_weight=$attr_weight.' '.$sizeUnit;
                }
                $attr_purity=$val['attr_purity'];
                $attr_purity_unit=$val['attr_purity_unit'];
                if($attr_purity!=0 && $attr_purity!=''){
                    $sizeUnit = '';
                    switch ($attr_purity) {
                        case 1:
                            $attr_purity = '24K995';
                            break;
                        case 2:
                            $attr_purity = '23K958';
                            break;
                        case 3:
                            $attr_purity = '22K916';
                            break;
                        case 4:
                            $attr_purity = '20K833';
                            break;
                        case 5:
                            $attr_purity = '18K750';
                            break;
                        case 6:
                            $attr_purity = '14K585';
                            break;
                        default:
                            $attr_purity = '';
                    }
                    switch ($attr_purity_unit) {
                        case 1:
                            $attr_purity_unit = 'Gold';
                            break;
                        case 2:
                            $attr_purity_unit = 'Silver';
                            break;
                        case 3:
                            $attr_purity_unit = 'Imitation';
                            break;
                        default:
                            $attr_purity_unit = '';
                    }
                    $attr_purity=$attr_purity.' '.$attr_purity_unit;
                }
                $attr_volume=$val['attr_volume'];
                $attr_volume_unit=$val['attr_volume_unit'];
                if($attr_volume!=''){
                    $sizeUnit = '';
                    switch ($attr_volume_unit) {
                        case 1:
                            $sizeUnit = 'Rhino';
                            break;
                        case 2:
                            $sizeUnit = 'Matrix';
                            break;
                        case 3:
                            $sizeUnit = 'ZBrush';
                            break;
                        case 4:
                            $sizeUnit = 'Magices';
                            break;
                        case 5:
                            $sizeUnit = 'Others';
                            break;
                        default:
                            $sizeUnit = '';
                    }
                    $attr_volume=$attr_volume.' '.$sizeUnit;
                }

                if ($currency == 1) {
                    $currencySymbol = '₹';
                } else {
                    $currencySymbol = '$';
                }

                if($active==0){
                    $clr='style="background-color: #fffbbc !important;"';
                }else{
                    $clr='';
                }


                $fileTypes = ['3DM', 'STL', 'MGX', 'OBJ'];
                $fileArray = explode(',', $files);
                $output = [];

                foreach ($fileArray as $index => $value) {
                    if ($value == 1) {
                        $output[] = $fileTypes[$index];
                    } else {
                        $output[] = '';
                    }
                }
                $files = implode(', ', array_filter($output));




                if($pending==1){
                    $pending='<i class="fa-solid fa-hourglass-half"></i>';
                }else{
                    $pending='';
                }

                if($feature==1){
                    $featured='<i class="fa-solid fa-star"></i> <button class="btn btn-sm btn-dark mx-1 p-1 px-2" onclick="change_feature('.$id.','.$feature.')"><i class="fa-solid fa-rotate"></i></button>';
                }else{
                    $featured='<button class="btn btn-sm btn-dark mx-1 p-1 px-2" onclick="change_feature('.$id.','.$feature.')"><i class="fa-solid fa-rotate"></i></button>';
                }

                $html.='<tr>
                    <td '.$clr.'>'.$i.'</td>
                    <td '.$clr.'>'.$name.'</td>
                    <td '.$clr.'>'.$slug.'</td>
                    <td '.$clr.'><img src="../../product_images/'.$feature_image.'" class="pr_img" alt="'.$slug.'"></td>
                    <td '.$clr.'><small class="text-decoration-line-through">'.$currencySymbol.$price.'</small> <span class="fw-bold">'.$currencySymbol.$offer.'</span></td>
                    <td '.$clr.'>'.$featured.'</td>
                    <td '.$clr.'>'.$categoryArray[$category_id].'</td>
                    <td '.$clr.'>'.$created_at.'</td>
                    <td '.$clr.'>'.$view.'</td>
                    <td '.$clr.'>'.$wish.'</td>
                    <td '.$clr.'>'.$pending.'</td>
                    <td '.$clr.'>'.$attr_size.'</td>
                    <td '.$clr.'>'.$attr_weight.'</td>
                    <td '.$clr.'>'.$attr_purity.'</td>
                    <td '.$clr.'>'.$attr_volume.'</td>
                    <td '.$clr.'>'.$files.'</td>
                    <td '.$clr.'>'.$total_space.'</td>
                    <td '.$clr.'>'.$total_files.'</td>
                    <td>
                        <a class="btn btn-sm btn-dark mx-1 p-1 px-2" href="" target="_blank"><i class="fa-solid fa-eye"></i></a>
                        <button class="btn btn-sm btn-danger mx-1 p-1 px-2" onclick="change_status('.$id.','.$active.')"><i class="fa-solid fa-rotate"></i></button>
                    </td>
                </tr>';

                $i=$i+1;
            }



        }


        $my_arr = array('status' => 1, 'msg' => '', 'html'=> $html);
        echo json_encode($my_arr);
        break;



    case 'add_product':
        $success = 0;
        $msg = $link = '';


        $sql="SELECT SUM(`feature`) AS feature  FROM `products` WHERE `user_id`='$user_id'";
        $query = $pdoconn->prepare($sql);
        $query->execute();
        $my_arr = $query->fetchAll(PDO::FETCH_ASSOC);
        $already_feature=$my_arr[0]['feature'];


        $product = strip_tags($_POST['product']);
        $category = strip_tags($_POST['category']);
        $currency = strip_tags($_POST['currency']);
        $price = strip_tags($_POST['price']);
        $offer = strip_tags($_POST['offer']);
        $active = strip_tags($_POST['active']);
        $feature = strip_tags($_POST['feature']);
        if($already_feature>=4){
            $feature=0;
        }

        $size = strip_tags($_POST['size']);
        $size_unit = strip_tags($_POST['size_unit']);
        $weight = strip_tags($_POST['weight']);
        $weight_unit = strip_tags($_POST['weight_unit']);
        $purity = strip_tags($_POST['purity']);
        $purity_unit = strip_tags($_POST['purity_unit']);
        $volume = strip_tags($_POST['volume']);
        $volume_unit = strip_tags($_POST['volume_unit']);
        $file_3dm = strip_tags($_POST['file_3dm']);
        $file_stl = strip_tags($_POST['file_stl']);
        $file_mgx = strip_tags($_POST['file_mgx']);
        $file_obj = strip_tags($_POST['file_obj']);

        $description = $_POST['description'];
        $meta_title = strip_tags($_POST['meta_title']);
        $meta_keyword = strip_tags($_POST['meta_keyword']);
        $meta_description = strip_tags($_POST['meta_description']);
        $seo_description = $_POST['seo_description'];

        $file=$file_3dm.','.$file_stl.','.$file_mgx.','.$file_obj;

        $sql = "SELECT `slug` FROM `products`";
        $query = $pdoconn->prepare($sql);
        $query->execute();
        $existing_slugs = $query->fetchAll(PDO::FETCH_ASSOC);

        $unique_slug = makeUniqueSlug($product, $existing_slugs);

        $sql="INSERT INTO `products` (`name`,`slug`,`currency`,`price`,`offer`,`feature`,`active`,`category_id`,
            `user_id`,`created_at`,`attr_size`,`attr_size_unit`,`attr_weight`,`attr_weight_unit`,`attr_purity`,
            `attr_purity_unit`,`attr_volume`,`attr_volume_unit`,`files`) VALUES 
            ('$product','$unique_slug','$currency','$price','$offer','$feature','$active','$category',
             '$user_id',NOW(),'$size','$size_unit','$weight','$weight_unit','$purity',
             '$purity_unit','$volume','$volume_unit','$file')";
        $query = $pdoconn->prepare($sql);
        if($query->execute()){
            $lastInsertId = $pdoconn->lastInsertId();

            $_SESSION['lastproduct_id'] = $lastInsertId;
            $data = [
                'description' => $description,
                'meta_title' => $meta_title,
                'meta_keyword' => $meta_keyword,
                'meta_description' => $meta_description,
                'seo_description' => $seo_description,
            ];

            $json_data = json_encode($data, JSON_PRETTY_PRINT);

            $folder = '../../description';
            $filename = $lastInsertId.'.json';

            if (!is_dir($folder)) {
                mkdir($folder, 0777, true);
            }

            $file_path = $folder . '/' . $filename;

            if (file_put_contents($file_path, $json_data)) {
                $my_arr = array('status' => 1, 'msg' => 'Saved Successfully');
                echo json_encode($my_arr);
            } else {
                $my_arr = array('status' => 0, 'msg' => 'Some Error Occured..');
                echo json_encode($my_arr);
            }
        }else{
            $my_arr = array('status' => 0, 'msg' => 'Some Error Occured');
            echo json_encode($my_arr);
        }
        break;




    case 'upload_img':
        $lastproduct_id=$_SESSION['lastproduct_id'];

        if (isset($_FILES['file'])) {
            $uploadDir = '../../product_images/';
            $actual_name=$_POST['fileName'];
            $fileExtension = strtolower(pathinfo($actual_name, PATHINFO_EXTENSION));

            $fileType = strtolower($_FILES['file']['type']);

            $allowedExtensions = ['jpg', 'jpeg', 'png'];
            $allowedTypes = ['image/jpeg', 'image/png'];

            if (!in_array($fileType, $allowedTypes)) {
                echo json_encode(['success' => false, 'message' => 'Invalid file type']);
                exit;
            }

            $fileSize = $_FILES['file']['size'];
            $maxFileSize = 1024 * 1024;

            if ($fileSize > $maxFileSize) {
                echo json_encode(['success' => false, 'message' => 'File size exceeds the 1 mb limit']);
                exit;
            }

            $uniqueId = uniqid('', true);
            $uniqueIdWithUnderscore = str_replace('.', '_', $uniqueId);

            $fileName = $lastproduct_id.$user_id.$uniqueIdWithUnderscore . '.'.$fileExtension;
            $filePath = $uploadDir . $fileName;

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            if (move_uploaded_file($_FILES['file']['tmp_name'], $filePath)) {
                $absoluteFilePath = $site_url . 'product_images/' . $filePath;

                $fileSizeInMB = $fileSize / 1048576;
                $fileSizeInMB=round($fileSizeInMB, 2);

                $sql="INSERT INTO `products_files` (`product_id`,`file_image`,`file_name`,`size`) VALUES ('$lastproduct_id','$fileName','$actual_name','$fileSizeInMB')";
                $query = $pdoconn->prepare($sql);
                $query->execute();

                echo json_encode(['success' => true, 'filePath' => $absoluteFilePath]);
            } else {
                echo json_encode(['success' => false, 'message' => 'File upload failed']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'No file uploaded']);
        }

        break;

    case 'load_img_table':
        $html='';
        $lastproduct_id=$_SESSION['lastproduct_id'];
        $sql="SELECT `id`,`file_image`,`file_name` FROM `products_files` WHERE `type`=0 AND `product_id`='$lastproduct_id'";
        $query = $pdoconn->prepare($sql);
        $query->execute();
        $my_arr = $query->fetchAll(PDO::FETCH_ASSOC);

        $i=1;
        foreach ($my_arr as $val) {

            $html.='<tr>
                    <td>'.$i.'</td>
                    <td>'.$val['file_name'].'</td>
                    <td>
                        <img src="../../product_images/' . $val['file_image'] . '" alt="' .$val['file_name'] . '" style="max-width: 50px; max-height: auto;" />
                    </td>
                    <td>
                        <button class="btn btn-sm btn-danger del_btn mx-1 p-1 px-2" onclick="delete_img('.$val['id'].')"><i class="fa-solid fa-trash"></i></button>
                    </td>
                </tr>';

            $i=$i+1;
        }

        $imageCounter=$i-1;
        if($imageCounter<0){
            $imageCounter=0;
        }
        $my_arr = array('status' => 1, 'msg' => '', 'html'=> $html, 'imageCounter'=>$imageCounter);
        echo json_encode($my_arr);
        break;

    case 'delete_img':
        $html='';
        $lastproduct_id=$_SESSION['lastproduct_id'];
        $id=$_POST['id'];

        $sql="SELECT `file_image` FROM `products_files` WHERE `id`='$id' AND `product_id`='$lastproduct_id' AND `type`=0";
        $query = $pdoconn->prepare($sql);
        $query->execute();
        $my_arr = $query->fetchAll(PDO::FETCH_ASSOC);
        $file_image=$my_arr[0]['file_image'];

        $sql="DELETE FROM `products_files` WHERE `id`='$id' AND `product_id`='$lastproduct_id'";
        $query = $pdoconn->prepare($sql);
        $query->execute();

        $filePath = '../../product_images/' . $file_image;

        if (file_exists($filePath)) {
            unlink($filePath);
        }


        $my_arr = array('status' => 1, 'msg' => '');
        echo json_encode($my_arr);
        break;




    case 'upload_file':
        $lastproduct_id=$_SESSION['lastproduct_id'];

        if (isset($_FILES['file'])) {
            $file = $_FILES['file'];

            if ($file['error'] !== UPLOAD_ERR_OK) {
                $response = ['success' => false, 'message' => 'File upload error.'];
                echo json_encode($response);
                exit;
            }

            $uploadDir = '../../product_files_temp/';

            $totalSpace = getDirectorySize($uploadDir);
            $totalSpaceGB = round($totalSpace / (1024 * 1024 * 1024), 2);

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            $actual_name =basename($file['name']);
            $fileExtension = strtolower(pathinfo($actual_name, PATHINFO_EXTENSION));

            $uniqueId = uniqid('', true);
            $uniqueIdWithUnderscore = str_replace('.', '_', $uniqueId);

            $fileName = $lastproduct_id.$user_id.time().$uniqueIdWithUnderscore . '.'.$fileExtension;
            $uploadFile = $uploadDir . $fileName;

            $maxSizeInBytes = 1.5 * 1024 * 1024 * 1024; // 3GB
            if ($file['size'] > $maxSizeInBytes) {
                $response = ['success' => false, 'message' => 'File size exceeds 1.5GB limit.'];
                echo json_encode($response);
                exit;
            }

            $fileSize=$file['size'];
            $fileSize = round($fileSize / (1024 * 1024 * 1024), 2);
            if(($server_storage_limit-$totalSpaceGB)<$fileSize){
                $response = ['success' => false, 'message' => 'Server is in Low Storage.. Try Later.'];
                echo json_encode($response);
                exit;
            }


            if (move_uploaded_file($file['tmp_name'], $uploadFile)) {
                $response = ['success' => true, 'message' => 'File uploaded successfully.'];
                $fileSize=$file['size'];
                $fileSizeInMB = $fileSize / 1048576;
                $fileSizeInMB=round($fileSizeInMB, 2);

                $sql="INSERT INTO `products_files` (`product_id`,`file_image`,`file_name`,`type`,`size`) VALUES ('$lastproduct_id','$fileName','$actual_name','1','$fileSizeInMB')";
                $query = $pdoconn->prepare($sql);
                $query->execute();

            } else {
                $response = ['success' => false, 'message' => 'Failed to move uploaded file.'];
            }
        } else {
            $response = ['success' => false, 'message' => 'No file uploaded.'];
        }

        echo json_encode($response);

        break;



    case 'load_file_table':
        $html='';
        $lastproduct_id=$_SESSION['lastproduct_id'];
        $sql="SELECT `id`,`file_image`,`file_name` FROM `products_files` WHERE `type`=1 AND `product_id`='$lastproduct_id'";
        $query = $pdoconn->prepare($sql);
        $query->execute();
        $my_arr = $query->fetchAll(PDO::FETCH_ASSOC);

        $i=1;
        foreach ($my_arr as $val) {

            $html.='<tr>
                    <td>'.$i.'</td>
                    <td>'.$val['file_name'].'</td>                   
                    <td>
                        <button class="btn btn-sm btn-danger del_btn mx-1 p-1 px-2" onclick="delete_file('.$val['id'].')"><i class="fa-solid fa-trash"></i></button>
                    </td>
                </tr>';

            $i=$i+1;
        }

        $fileCounter=$i-1;
        if($fileCounter<0){
            $fileCounter=0;
        }
        $my_arr = array('status' => 1, 'msg' => '', 'html'=> $html, 'fileCounter'=>$fileCounter);
        echo json_encode($my_arr);
        break;

    case 'delete_file':
        $html='';
        $lastproduct_id=$_SESSION['lastproduct_id'];
        $id=$_POST['id'];

        $sql="SELECT `file_image` FROM `products_files` WHERE `id`='$id' AND `product_id`='$lastproduct_id' AND `type`=1";
        $query = $pdoconn->prepare($sql);
        $query->execute();
        $my_arr = $query->fetchAll(PDO::FETCH_ASSOC);
        $file_image=$my_arr[0]['file_image'];

        $sql="DELETE FROM `products_files` WHERE `id`='$id' AND `product_id`='$lastproduct_id'";
        $query = $pdoconn->prepare($sql);
        $query->execute();

        $filePath = '../../product_files_temp/' . $file_image;

        if (file_exists($filePath)) {
            unlink($filePath);
        }


        $my_arr = array('status' => 1, 'msg' => '');
        echo json_encode($my_arr);
        break;


    case 'final_product':
        $html='';
        $success=0;
        $lastproduct_id=$_SESSION['lastproduct_id'];

        $sql="SELECT `file_image` FROM `products_files` WHERE `product_id`='$lastproduct_id' AND `type`=0";
        $query = $pdoconn->prepare($sql);
        $query->execute();
        $my_arr = $query->fetchAll(PDO::FETCH_ASSOC);
        if(count($my_arr)==0){
            $my_arr = array('status' => 0, 'msg' => 'Upload Atlease One Image of the product.');
            echo json_encode($my_arr);
            exit();
        }
        $file_image=$my_arr[0]['file_image'];


        $sql="SELECT `file_image` FROM `products_files` WHERE `product_id`='$lastproduct_id' AND `type`=1";
        $query = $pdoconn->prepare($sql);
        $query->execute();
        $my_arr = $query->fetchAll(PDO::FETCH_ASSOC);
        if(count($my_arr)==0){
            $my_arr = array('status' => 0, 'msg' => 'Upload Atlease One File of the product.');
            echo json_encode($my_arr);
            exit();
        }

        $sql="UPDATE `products` SET `drive_pending`=1,`featured_image`='$file_image' WHERE `id`='$lastproduct_id'";
        $query = $pdoconn->prepare($sql);
        $query->execute();

        $my_arr = array('status' => 1, 'msg' => '');
        echo json_encode($my_arr);
        break;



    case 'change_status':
        $html='';
        $id=$_POST["id"];
        $active=$_POST["active"];

        if($active==1){
            $sql="UPDATE `products` SET `active`='0' WHERE `id`='$id' AND `user_id`='$user_id' AND `admin_stop`='0'";
        }else if($active==0){
            $sql="UPDATE `products` SET `active`='1' WHERE `id`='$id' AND `user_id`='$user_id'";
        }
        $query = $pdoconn->prepare($sql);
        $query->execute();


        $my_arr = array('status' => 1);
        echo json_encode($my_arr);
        break;


    case 'change_feature':
        $html='';
        $id=$_POST["id"];
        $feature=$_POST["feature"];

        $sql="SELECT SUM(`feature`) AS feature  FROM `products` WHERE `user_id`='$user_id'";
        $query = $pdoconn->prepare($sql);
        $query->execute();
        $my_arr = $query->fetchAll(PDO::FETCH_ASSOC);
        $already_feature=$my_arr[0]['feature'];

        if($already_feature<=4){
            if($feature==1){
                $sql="UPDATE `products` SET `feature`='0' WHERE `id`='$id' AND `user_id`='$user_id'";
            }else if($feature==0){
                $sql="UPDATE `products` SET `feature`='1' WHERE `id`='$id' AND `user_id`='$user_id'";
            }
            $query = $pdoconn->prepare($sql);
            $query->execute();

            $my_arr = array('status' => 1);
        }else{
            $my_arr = array('status' => 0, 'msg'=>'Max Featured Limit Reached.');
        }

        echo json_encode($my_arr);
        break;
}


function generateSlug($product_name) {
    $product_name=strtolower($product_name);
    $slug = trim(preg_replace('/[^a-z0-9\s-]+/', '', $product_name));
    $slug = preg_replace('/\s+/', '-', $slug);
    $slug = ltrim($slug, '-');
    $slug = rtrim($slug, '-');
    return $slug;
}

function makeUniqueSlug($product_name, $existing_slugs) {
    $slug = generateSlug($product_name);
    $original_slug = $slug;
    $counter = 1;
    while (in_array(['slug' => $slug], $existing_slugs)) {
        $slug = $original_slug . '-' . $counter;
        $counter++;
    }
    return $slug;
}

function getDirectorySize($dir) {
    $size = 0;
    if (is_dir($dir)) {
        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
        foreach ($files as $file) {
            if (!$file->isDir()) {
                $size += $file->getSize();
            }
        }
    }
    return $size;
}



