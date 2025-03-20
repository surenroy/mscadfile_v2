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

    case 'sendMessage':
        $success = 0;
        $msg = $link = '';
        $result=1;

        $subject = strip_tags($_POST['subject']);
        $message = strip_tags($_POST['message']);

        $user_id=$_SESSION["user_id"];

        $sql="INSERT INTO `message` (`from`,`to`,`create_on`,`subject`) VALUES ('$user_id','1',NOW(),'$subject')";
        $query = $pdoconn->prepare($sql);
        $query->execute();
        $lastInsertId = $pdoconn->lastInsertId();


        $data = [
            'subject' => $subject,
            'message' => $message
        ];

        $folderPath = '../message/';
        $filePath = $folderPath . $lastInsertId . '.json';

        if (!file_exists($folderPath)) {
            mkdir($folderPath, 0777, true);
        }

        $jsonData = json_encode($data, JSON_PRETTY_PRINT);
        if ($jsonData === false) {
            $result=0;
        }

        if (file_put_contents($filePath, $jsonData) === false) {
            $result=0;
        }


        if($result==0){
            $sql="DELETE FROM `message` WHERE `id`='$lastInsertId'";
            $query = $pdoconn->prepare($sql);
            $query->execute();

            $my_arr = array('status' => 0, 'msg' => 'Some Error Found.');
            echo json_encode($my_arr);
        }else{
            $my_arr = array('status' => 1, 'msg' => 'Sent Successfully.');
            echo json_encode($my_arr);
        }
        break;

    case 'load_inbox':
        $html='';
        $user_id=$_SESSION["user_id"];

        $sql="SELECT message.id, message.open, message.subject,DATE_FORMAT(`create_on`, '%d-%m-%Y %H:%i') AS date_time,
           message.open,users.name FROM message message
             LEFT OUTER JOIN users users ON (message.`from` = users.id)
        WHERE message.`to` = $user_id ORDER BY message.create_on DESC";
        $query = $pdoconn->prepare($sql);
        $query->execute();
        $my_arr = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach ($my_arr as $val) {
            $open=$val['open'];
            if($open==0){
                $open='text-danger';
            }else{
                $open='';
            }

            $html.='<tr>
                    <td id="msg_'.$val['id'].'" class="'.$open.' fw-bold">'.$val['date_time'].'</td>
                    <td>'.$val['subject'].'</td>
                    <td>'.$val['name'].'</td>
                    <td>
                        <button class="btn btn-sm btn-dark mx-1 p-1 px-2" onclick="show_msg('.$val['id'].',1,'.$val['open'].')"><i class="fa-solid fa-eye"></i></button>
                    </td>
                </tr>';
        }


        $my_arr = array('status' => 1, 'msg' => '', 'html'=> $html);
        echo json_encode($my_arr);
        break;

    case 'load_outbox':
        $html='';
        $user_id=$_SESSION["user_id"];

        $sql="SELECT message.id, message.open, message.subject,DATE_FORMAT(`create_on`, '%d-%m-%Y %H:%i') AS date_time,
           message.open,users.name FROM message message
             LEFT OUTER JOIN users users ON (message.`to` = users.id)
        WHERE message.`from` = $user_id ORDER BY message.create_on DESC";
        $query = $pdoconn->prepare($sql);
        $query->execute();
        $my_arr = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach ($my_arr as $val) {
            $open=$val['open'];
            if($open==0){
                $open='text-danger';
            }else{
                $open='';
            }
            $html.='<tr>
                    <td id="msg_'.$val['id'].'" class="'.$open.' fw-bold">'.$val['date_time'].'</td>
                    <td>'.$val['subject'].'</td>
                    <td>'.$val['name'].'</td>
                    <td>
                        <button class="btn btn-sm btn-dark mx-1 p-1 px-2" onclick="show_msg('.$val['id'].',0,'.$val['open'].')"><i class="fa-solid fa-eye"></i></button>
                    </td>
                </tr>';
        }


        $my_arr = array('status' => 1, 'msg' => '', 'html'=> $html);
        echo json_encode($my_arr);
        break;

    case 'show_msg':
        $html='';
        $id=$_POST["id"];
        $type=$_POST["type"];
        $open=$_POST["open"];

        if($type==1 && $open==0){
            $sql="UPDATE `message` SET `open`=1 WHERE `id`='$id'";
            $query = $pdoconn->prepare($sql);
            $query->execute();
        }

        $jsonFilePath = '../message/'.$id.'.json';
        if (file_exists($jsonFilePath)) {
            $jsonData = file_get_contents($jsonFilePath);
            $dataArray = json_decode($jsonData, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                if (isset($dataArray['message'])) {
                    $html = $dataArray['message'];
                }
            }
        }


        $sql="SELECT `reply` FROM `message` WHERE `id`='$id'";
        $query = $pdoconn->prepare($sql);
        $query->execute();
        $my_arr = $query->fetchAll(PDO::FETCH_ASSOC);
        $reply=$my_arr[0]['reply'];



        $jsonFilePath = '../message/'.$reply.'.json';
        if (file_exists($jsonFilePath)) {
            $jsonData = file_get_contents($jsonFilePath);
            $dataArray = json_decode($jsonData, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                if (isset($dataArray['message'])) {
                    $html.='<hr><h6 class="col-12 text-danger fw-bold mt-3 mb-3" >Reply Message From Admin:</h6>';
                    $html.= $dataArray['message'];
                }
            }
        }



        $my_arr = array('status' => 1, 'msg' => '', 'html'=> $html);
        echo json_encode($my_arr);
        break;
}
