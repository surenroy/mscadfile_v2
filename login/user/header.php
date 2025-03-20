<?php
ob_start();
if (!isset($_SESSION)) session_start();
include_once("../../connection-pdo.php");

$user_id=$_SESSION["user_id"];
$user_name=$_SESSION["user_name"];
$user_type=$_SESSION["user_type"];
$is_saller=$_SESSION["is_saller"];


if($user_type!=0){
    header('Location: ../../logout.php');
    exit(0);
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title id="title">mscadfile.com | Jewelry CAD Files & 3D Models</title>
    <link rel="icon" type="image/x-icon" href="<?php echo $site_url; ?>images/logo.ico">
    <meta name="keywords" content="Jewelry CAD files, 3D jewelry models, Jewelry design, CAD resources">
    <meta name="description" content="High-quality 3D CAD files and models for jewelry designers. Access a vast collection of downloadable jewelry CAD files for professional use.">
    <meta name="author" content="mscadfile.com">
    <link rel="canonical" href="https://mscadfile.com">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="<?php echo $site_url; ?>css/bootstrap.min.css?v=<?php echo $ver; ?>" rel="stylesheet">
    <link href="<?php echo $site_url; ?>login/user/css/style.css?v=<?php echo $ver; ?>" rel="stylesheet">
    <link href="<?php echo $site_url; ?>css/dataTables.bootstrap5.css?v=<?php echo $ver; ?>" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo $site_url; ?>css/jquery-confirm.min.css?v=<?php echo $ver; ?>">
    <script src="<?php echo $site_url; ?>js/jquery-3.6.0.min.js?v=<?php echo $ver; ?>"></script>
</head>
<body style="padding-top: 58px;">



<script>
    var site_url='<?php echo $site_url;?>';
</script>




<nav class="navbar navbar-expand-lg shadow-sm position-fixed fixed-top" style="background: linear-gradient(90deg, #000000 30%, #ebb120 100%); padding: 4px 0;">
    <div class="container-fluid">
        <a class="navbar-brand d-flex align-items-start justify-content-start" href="<?php echo $site_url; ?>home.php">
            <img src="<?php echo $site_url; ?>images/logo.webp" alt="mscadfile logo" width="100"  style="max-height: 40px; display: block !important;">
            <span class="fw-bold store-welcome-mobile" href="<?php echo $site_url; ?>home.php">MSCAD Store</span>
        </a>

        <button class="navbar-toggler bg-light p-1" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon" style="width: 20px; height: 20px;"></span>
        </button>



        <div class="collapse navbar-collapse justify-content-between" id="navbarNav">
            <ul class="navbar-nav flex-grow-0 me-auto mb-0">

                <li class="nav-item"><a class="nav-link text-white fw-bold px-2 py-1" href="<?php echo $site_url; ?>login/user/index.php">Dashboard</a></li>
                <?php
                if($is_saller==1){
                    echo '<li class="nav-item"><a class="nav-link text-white fw-bold px-2 py-1" href="'.$site_url.'login/user/product.php">Product</a></li>';
                }
                ?>
                <li class="nav-item"><a class="nav-link text-white fw-bold px-2 py-1" href="<?php echo $site_url; ?>login/user/message.php">Message</a></li>
                <li class="nav-item"><a class="nav-link text-white fw-bold px-2 py-1" href="<?php echo $site_url; ?>login/user/order.php">Order</a></li>
                <?php
                if($is_saller==1){
                    echo '<li class="nav-item"><a class="nav-link text-white fw-bold px-2 py-1" href="'.$site_url.'login/user/sell.php">Sell</a></li>';
                }
                ?>
                <li class="nav-item"><a class="nav-link text-white fw-bold px-2 py-1" href="<?php echo $site_url; ?>login/user/job.php">Jobs</a></li>
                <li class="nav-item"><a class="nav-link text-white fw-bold px-2 py-1" href="<?php echo $site_url; ?>login/user/profile.php">Profile</a></li>
                <li class="nav-item"><a class="nav-link text-white fw-bold px-2 py-1" href="<?php echo $site_url; ?>logout.php">Logout</a></li>


            </ul>
        </div>
    </div>
</nav>

