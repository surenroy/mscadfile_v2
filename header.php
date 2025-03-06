<?php
include_once("connection-pdo.php");
ob_start();
if (!isset($_SESSION)) session_start();


if (!isset($_COOKIE['usd'])) {
    $sql = "SELECT `usd` FROM `currency_conversion` WHERE `id` = 1";
    $query = $pdoconn->prepare($sql);
    $query->execute();
    $my_arr = $query->fetchAll(PDO::FETCH_ASSOC);
    $usd = $my_arr[0]['usd'];
    setcookie("usd", $usd, time() + 3600, "/");
} else {
    $usd = $_COOKIE['usd'];
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
    <link href="<?php echo $site_url; ?>css/style.css?v=<?php echo $ver; ?>" rel="stylesheet">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400..700;1,400..700&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo $site_url; ?>css/jquery-confirm.min.css?v=<?php echo $ver; ?>">


</head>
<body>

<script>
    var site_url='<?php echo $site_url;?>';
</script>

<div id="google_translate_element" style="position: absolute; top: 3px; left: 5px; z-index: -5;"></div>







<header class="top-header">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-md-6 p-0">
                <a class="fw-bold store-welcome" href="<?php echo $site_url; ?>index.php">Welcome to MSCAD Store</a>
            </div>

            <div class="col-md-6 p-0">
                <div class="d-flex flex-wrap align-items-center gap-3 justify-content-center justify-content-md-end">
                    <div class="nav-item dropdown notranslate" data-no-translate="true">
                        <a class="nav-link dropdown-toggle currency-toggle" href="#" id="currencyDropdown" role="button"
                           data-bs-toggle="dropdown" aria-expanded="false">
                            <img id="selectedCurrencyFlag" src="https://flagcdn.com/w40/in.png" width="18" height="14" class="me-1">
                            <span id="selectedCurrency">INR (₹)</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-custom">
                            <li><a class="dropdown-item d-flex align-items-center" href="#" onclick="changeCurrency('INR(₹)', 'https://flagcdn.com/w40/in.png',1)">
                                    <img src="https://flagcdn.com/w40/in.png" data-type="1" width="18" height="14" class="me-1"> INR (₹)
                                </a>
                            </li>
                            <li><a class="dropdown-item d-flex align-items-center" href="#" onclick="changeCurrency('USD($)', 'https://flagcdn.com/w40/us.png',2)">
                                    <img src="https://flagcdn.com/w40/us.png" data-type="2" width="18" height="14" class="me-1"> USD ($)
                                </a>
                            </li>
                        </ul>
                    </div>




                    <?php
                        if(isset($_SESSION['user_name'])){
                            $user_type=$_SESSION["user_type"];
                            $is_sallar=$_SESSION["is_saller"];

                            if($user_type==0 && $is_sallar==1){
                                echo '<div class="nav-item">
                                    <a href="'.$site_url.'login/user/product.php" class="nav-link user-link">
                                        <i class="fa fa-upload"></i> Upload
                                    </a>
                                </div>
                                <div class="nav-item">
                                    <a href="'.$site_url.'login/user/index.php" class="nav-link user-link">
                                        <i class="fa fa-user"></i> Account
                                    </a>
                                </div>';
                            }

                            if($user_type==0 && $is_sallar==0){
                                echo '<div class="nav-item">
                                    <a href="'.$site_url.'login/user/index.php" class="nav-link user-link">
                                        <i class="fa fa-user"></i> Account
                                    </a>
                                </div>';
                            }

                            if($user_type==9){
                                echo '<div class="nav-item">
                                    <a href="'.$site_url.'login/admin/index.php" class="nav-link user-link">
                                        <i class="fa fa-user"></i> Account
                                    </a>
                                </div>';
                            }

                            if($user_type==2){
                                echo '<div class="nav-item">
                                    <a href="'.$site_url.'login/admin_user/index.php" class="nav-link user-link">
                                        <i class="fa fa-user"></i> Account
                                    </a>
                                </div>';
                            }


                            echo '<div class="nav-item">
                                    <a href="'.$site_url.'logout.php" class="nav-link user-link">
                                        <i class="fa fa-sign-in-alt"></i> Logout
                                    </a>
                                </div>';
                        }else{
                            echo '<div class="nav-item">
                                    <button class="nav-link user-link" data-bs-toggle="modal" data-bs-target="#loginModal">
                                        <i class="fa fa-sign-in-alt"></i> Login
                                    </button>
                                </div>';
                        }
                    ?>

                </div>
            </div>
        </div>
    </div>
</header>


<div class="container-fluid py-2" style="background: #D4C742;">
    <div class="col-12 d-flex flex-row flex-wrap justify-content-between" >
        <div class="col-12 col-sm-7">
            <div class="input-group input-group-sm">
                <input class="input-group-text form-control col-9 form-control-sm" id="top_search" type="search" placeholder="Type Search" aria-label="Search" name="search_query">
                <button class="btn btn-dark col-3 text-white fw-bold px-3 btn-sm" id="top_search_btn" onclick="all_search()">Search</button>
            </div>
        </div>

        <a href="<?php echo $site_url; ?>wishlist.php" class="col-4 col-sm-2 mt-2 mt-sm-0 btn btn-dark fw-bold px-3 btn-sm">
            <i class="fa-solid fa-heart"></i> <span class="cart text-capitalize">Wishlist </span>(<span id="wishlist_cnt">0</span>)
        </a>
        <a href="<?php echo $site_url; ?>cart.php" class="col-4 col-sm-2 mt-2 mt-sm-0 btn btn-dark fw-bold px-3 btn-sm">
            <i class="fa fa-shopping-cart"></i> <span class="cart text-capitalize">Cart </span>(<span id="cart_cnt">0</span>)
        </a>



    </div>
</div>


<nav class="navbar navbar-expand-lg shadow-sm" style="background: linear-gradient(90deg, #000000 30%, #ebb120 100%); padding: 4px 0;">
    <div class="container-fluid">
        <a class="navbar-brand d-flex align-items-start justify-content-start" href="<?php echo $site_url; ?>index.php">
            <img src="<?php echo $site_url; ?>images/logo.webp" alt="mscadfile logo" width="100"  style="max-height: 40px; display: block !important;">
            <span class="fw-bold store-welcome-mobile" href="<?php echo $site_url; ?>index.php">MSCAD Store</span>
        </a>

        <button class="navbar-toggler bg-light p-1" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon" style="width: 20px; height: 20px;"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-between" id="navbarNav">
            <ul class="navbar-nav flex-grow-0 me-auto mb-0">

                <li class="nav-item"><a class="nav-link text-white fw-bold px-2 py-1" href="<?php echo $site_url; ?>index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link text-white fw-bold px-2 py-1" href="<?php echo $site_url; ?>product.php">Products</a></li>
                <li class="nav-item"><a class="nav-link text-white fw-bold px-2 py-1" href="<?php echo $site_url; ?>category.php">Categories</a></li>
                <li class="nav-item"><a class="nav-link text-white fw-bold px-2 py-1" href="<?php echo $site_url; ?>blog.php">Blog</a></li>
                <li class="nav-item"><a class="nav-link text-white fw-bold px-2 py-1" href="<?php echo $site_url; ?>featured.php">Featured</a></li>
                <li class="nav-item"><a class="nav-link text-white fw-bold px-2 py-1" href="<?php echo $site_url; ?>new_arrivals.php">New Arrivals</a></li>
            </ul>
        </div>
    </div>
</nav>



<script src="<?php echo $site_url; ?>js/jquery-3.6.0.min.js?v=<?php echo $ver; ?>"></script>