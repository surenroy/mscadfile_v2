<?php
include_once ('../header.php');

if (isset($_GET['slug'])) {
    $slug = $_GET['slug'];


    $sql="SELECT `id`,`name`,`slug`,`currency`,`price`,`offer`,`feature`,`category_id`,`user_id`,`created_at`,`view`,`wish`,
    `attr_size`,`attr_size_unit`,`attr_weight`,`attr_weight_unit`,`attr_purity`,`attr_purity_unit`,`attr_volume`,
    `attr_volume_unit`,`files`,`featured_image`,`total_space`,`total_files` FROM `products` WHERE `active`=1 
    AND `pending`=0 AND `drive_pending`=0 AND `slug`='$slug'";
    $query = $pdoconn->prepare($sql);
    $query->execute();
    $my_arr = $query->fetchAll(PDO::FETCH_ASSOC);

    if(count($my_arr)==0){
       header('Location: ../index.php');
       exit();
    }

    $sql="SELECT `id`,`name`,`slug` FROM `categories`";
    $query = $pdoconn->prepare($sql);
    $query->execute();
    $my_arr3 = $query->fetchAll(PDO::FETCH_ASSOC);
    $categoryArray = [];
    foreach ($my_arr3 as $category) {
        $categoryArray[$category['id']]['name'] = $category['name'];
        $categoryArray[$category['id']]['slug'] = $category['slug'];
    }




    $id=$my_arr[0]['id'];
    $name=$my_arr[0]['name'];
    $slug=$my_arr[0]['slug'];
    $currency=$my_arr[0]['currency'];
    $price=$my_arr[0]['price'];
    $offer=$my_arr[0]['offer'];
    $category_id=$my_arr[0]['category_id'];
    $category_name=$categoryArray[$category_id]['name'];
    $category_slug=$categoryArray[$category_id]['slug'];
    $created_at=$my_arr[0]['created_at'];
    $view=$my_arr[0]['view'];
    $wish=$my_arr[0]['wish'];
    $files=$my_arr[0]['files'];
    $seller=$my_arr[0]['user_id'];
    $feature_image=$my_arr[0]['featured_image'];
    $total_space=$my_arr[0]['total_space'];
    $total_files=$my_arr[0]['total_files'];
    $total_files_exp=explode("/",$total_files);
    $total_files=$total_files_exp[1];
    $attr_size=$my_arr[0]['attr_size'];
    $attr_size_unit=$my_arr[0]['attr_size_unit'];
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
        $attr_size_txt='<span>Size:</span> '.$attr_size.' '.$sizeUnit;
    }
    $attr_weight=$my_arr[0]['attr_weight'];
    $attr_weight_unit=$my_arr[0]['attr_weight_unit'];
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
        $attr_weight_txt='<span>Weight:</span> '.$attr_weight.' '.$sizeUnit;
    }
    $attr_purity=$my_arr[0]['attr_purity'];
    $attr_purity_unit=$my_arr[0]['attr_purity_unit'];
    if($attr_purity!=0 && $attr_purity!=''){
        $sizeUnit = '';
        switch ($attr_purity) {
            case 1:
                $attr_purity_c = '24K995';
                break;
            case 2:
                $attr_purity_c = '23K958';
                break;
            case 3:
                $attr_purity_c = '22K916';
                break;
            case 4:
                $attr_purity_c = '20K833';
                break;
            case 5:
                $attr_purity_c = '18K750';
                break;
            case 6:
                $attr_purity_c = '14K585';
                break;
            default:
                $attr_purity_c = '';
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
        $attr_purity_txt='<span>Purity:</span> '.$attr_purity_c.' '.$attr_purity_unit;
    }
    $attr_volume=$my_arr[0]['attr_volume'];
    $attr_volume_unit=$my_arr[0]['attr_volume_unit'];
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
        $attr_volume_txt='<span>Volume:</span> '.$attr_volume.' '.$sizeUnit;
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

    $savings_percentage = (($price - $offer) / $price) * 100;
    $savings_percentage=round($savings_percentage,0);



    $usd_rate = $_COOKIE["usd"];
    if($currency==1){
        $inr_price=$price;
        $inr_offer=$offer;
        $usd_price=round(($price*$usd_rate),2);
        $usd_offer=round(($offer*$usd_rate),2);
    }else{
        $inr_price=round(($price/$usd_rate),2);
        $inr_offer=round(($offer/$usd_rate),2);
        $usd_price=$price;
        $usd_offer=$offer;
    }



    $sql="UPDATE `products` SET `view`=`view`+1 WHERE `id`='$id'";
    $query = $pdoconn->prepare($sql);
    $query->execute();

} else {
    header('Location: ../index.php');
    exit();
}


$jsonFilePath = $site_url.'description/'.$id.'.json';
$jsonData = file_get_contents($jsonFilePath);
$data = json_decode($jsonData, true);


$description=$data['description'];
$meta_description=$data['seo_description'];
$meta_keywords=$data['meta_keyword'];
$meta_title=$data['meta_title'];
?>



<script>
    function updateMetaTags() {
        var keywords='<?php echo $meta_keywords; ?>';
        var title='<?php echo $meta_title; ?>';
        var description='<?php echo $meta_description; ?>';

        document.title = title;
        let metaKeywords = document.querySelector('meta[name="keywords"]');
        if (metaKeywords) {
            metaKeywords.setAttribute('content', keywords);
        } else {
            metaKeywords = document.createElement('meta');
            metaKeywords.name = "keywords";
            metaKeywords.content = keywords;
            document.head.appendChild(metaKeywords);
        }

        let metaDescription = document.querySelector('meta[name="description"]');
        if (metaDescription) {
            metaDescription.setAttribute('content', description);
        } else {
            metaDescription = document.createElement('meta');
            metaDescription.name = "description";
            metaDescription.content = description;
            document.head.appendChild(metaDescription);
        }

        let metaTitle = document.querySelector('meta[name="title"]');
        if (metaTitle) {
            metaTitle.setAttribute('content', title);
        } else {
            metaTitle = document.createElement('meta');
            metaTitle.name = "title";
            metaTitle.content = title;
            document.head.appendChild(metaTitle);
        }
    }

    $( document ).ready(function() {
        updateMetaTags();
    });


</script>







<section class="container-fluid py-2">
    <div class="row">
        <div class="col-md-6">
            <div class="position-relative">
                <div class="product-main-image">
                    <img id="mainImage" src="<?php echo $site_url; ?>product_images/<?php echo $feature_image; ?>" class="img-fluid magnify" alt="<?php echo $slug; ?>">
                </div>

                <a href="#" class="prev-arrow"><i class="fa-solid fa-angle-left"></i></a>
                <a href="#" class="next-arrow"><i class="fa-solid fa-angle-right"></i></a>

                <p class="text-muted small text-center mt-2">Mouse move on Image to zoom</p>
            </div>

            <div class="d-flex justify-content-center mt-3">
                <?php
                    $sql="SELECT `file_image` FROM `products_files` WHERE `product_id`='$id' AND `type`=0 ORDER BY `id` ASC";
                    $query = $pdoconn->prepare($sql);
                    $query->execute();
                    $my_arr2 = $query->fetchAll(PDO::FETCH_ASSOC);
                    $i=1;
                    foreach ($my_arr2 as $val2) {
                        $file_image=$val2['file_image'];
                        if($i==1){
                            echo '<img src="'.$site_url.'product_images/'.$file_image.'" class="thumb-img me-2 active" onclick="changeImage(this)">';
                        }else{
                            echo '<img src="'.$site_url.'product_images/'.$file_image.'" class="thumb-img me-2" onclick="changeImage(this)">';
                        }
                        $i=$i+1;
                    }
                ?>
            </div>
        </div>

        <div class="col-md-6 mt-md-0 mt-3">
            <h2 class="fw-bold"><?php echo $name; ?></h2>

            <p>
                <span class="badge bg-success position-static">Save: <?php echo $savings_percentage; ?>%</span>
                <span class="text-muted"><del>Old price: <span class="inr_price">₹<?php echo $inr_price; ?></span><span class="usd_price">$<?php echo $usd_price; ?></span></del></span>
            </p>
            <h3 class="text-success fw-bold"><span class="inr_price">₹<?php echo $inr_offer; ?></span><span class="usd_price">$<?php echo $usd_offer; ?></h3>

            <div class="product-specs mt-3">
                <div class="d-flex flex-wrap">
                    <?php
                        if($attr_size!=''){
                            echo '<span class="spec-tag">'.$attr_size_txt.'</span>';
                        }
                        if($attr_weight!=''){
                            echo '<span class="spec-tag">'.$attr_weight_txt.'</span>';
                        }
                        if($attr_purity!=0 && $attr_purity!=''){
                            echo '<span class="spec-tag">'.$attr_purity_txt.'</span>';
                        }
                        if($attr_volume!=''){
                            echo '<span class="spec-tag">'.$attr_volume_txt.'</span>';
                        }
                    ?>
                </div>

                <p class="mt-2"><strong>File includes:</strong> <span class="text-dark"><?php echo $files; ?></span></p>
                <p class="mt-2"><strong>Category:</strong> <a class="text-decoration-none" href="<?php echo $site_url; ?>'category/category.php?slug=<?php echo $category_slug; ?>"><small><?php echo $category_name; ?></small></a></p>

            </div>

            <div class="col-12 p-0 d-flex flex-row flex-wrap justify-content-between">
                <button class="btn btn-warning fw-bold col-12 col-sm-8 mt-2 mt-sm-0 addcart add_cart_<?php echo $id; ?>" onclick="add_cart(<?php echo $id; ?>)">
                    <i class="fa fa-cart-plus"></i> ADD TO CART
                </button>
                <button class="btn btn-dark fw-bold col-12 col-sm-8 mt-2 mt-sm-0 d-none removecart remove_cart_<?php echo $id; ?>" onclick="remove_cart(<?php echo $id; ?>)">
                    <i class="fa fa-shopping-cart"></i> IN CART
                </button>

                <button class="btn btn-dark fw-bold col-12 col-sm-3 mt-2 mt-sm-0" onclick="add_wish(<?php echo $id; ?>)">
                    <i class="fa-regular fa-heart wishlist<?php echo $id; ?>"></i> Wishlist
                </button>
            </div>

            <div class="mt-3">
                <span class="fw-bold">Share:</span>
                <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $site_url; ?>product/product.php?slug=<?php echo $slug; ?>" target="_blank" class="btn btn-outline-primary btn-sm"><i class="fab fa-facebook"></i></a>
                <a href="whatsapp://send?text=<?php echo $site_url; ?>product/product.php?slug=<?php echo $slug; ?>" class="btn btn-outline-success btn-sm"><i class="fab fa-whatsapp"></i></a>
                <a href="https://telegram.me/share/url?url=<?php echo $site_url; ?>product/product.php?slug=<?php echo $slug; ?>" class="btn btn-outline-info btn-sm"><i class="fab fa-telegram"></i></a>
            </div>

            <div class="mt-4 d-flex justify-content-between">
                <p><strong>Seller:</strong> #<?php echo $seller; ?></p>
                <a target="_blank" href="<?php echo $site_url; ?>seller/seller.php?id=<?php echo $seller; ?>" class="text-primary no-underline">View Seller Profile</a>
            </div>

            <table class="table mt-3">
                <thead>
                <tr>
                    <th>Total file(s)</th>
                    <th>Size (GB)</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td><?php echo $total_files;?></td>
                    <td><?php echo $total_space;?></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</section>












<section id="balaji-ring" class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2 class="text-primary text-center"><?php echo $name; ?></h2>
            </div>
        </div>
        <div class="row align-items-center">
            <div class="col-md-12">
                <?php echo $description; ?>
            </div>
        </div>
    </div>
</section>


<section id="related-products" class="py-5 bg-light">
    <div class="container-fluid">
        <h2 class="text-center text-primary mb-4">Related Products</h2>
        <p class="text-center text-dark">Explore our exquisite collection of handcrafted jewelry, designed to perfection for every occasion.</p>

        <div class="row justify-content-center g-3 mt-3">

            <?php
            $keywordsArray = explode(',', $meta_keywords);
            $processedKeywords = array_map(function($keyword) {
                $cleanedKeyword = preg_replace('/\d/', '', $keyword);
                return trim($cleanedKeyword);
            }, $keywordsArray);


            $processedKeywords = array_filter($processedKeywords);
            $searchConditions = [];
            foreach ($processedKeywords as $keyword) {
                $searchConditions[] = "`name` LIKE '%" . $keyword . "%'";
            }
            $searchQuery = implode(' OR ', $searchConditions);

                $sql = "SELECT `id`, `name`, `slug`, `currency`, `price`, `offer`, `featured_image`, `category_id`, `view`, `wish` 
            FROM `products` WHERE `active` = 1 AND (" . $searchQuery . ") ORDER BY `view` DESC LIMIT 10";
                $query = $pdoconn->prepare($sql);
                $query->execute();
                $my_arr = $query->fetchAll(PDO::FETCH_ASSOC);
                $i=0;
                foreach($my_arr as $val){
                    $i=$i+1;
                    $id=$val['id'];
                    $name=$val['name'];
                    $slug=$val['slug'];
                    $currency=$val['currency'];
                    $price=$val['price'];
                    $offer=$val['offer'];
                    $featured_image=$val['featured_image'];
                    $category_id=$val['category_id'];
                    $view=$val['view'];
                    $wish=$val['wish'];

                    $filepath = $site_url.'product_images/'.$featured_image;

                    if(isset($categoryArray[$category_id])){
                        $category_name=$categoryArray[$category_id]['name'];
                        $category_slug=$categoryArray[$category_id]['slug'];
                    }else{
                        $category_name=$category_slug='';
                    }



                    $usd_rate = $_COOKIE["usd"];
                    if($currency==1){
                        $inr_price=$price;
                        $inr_offer=$offer;
                        $usd_price=round(($price*$usd_rate),2);
                        $usd_offer=round(($offer*$usd_rate),2);
                    }else{
                        $inr_price=round(($price/$usd_rate),2);
                        $inr_offer=round(($offer/$usd_rate),2);
                        $usd_price=$price;
                        $usd_offer=$offer;
                    }

                    echo '<div class="item-item-big-index col-lg-3 col-12 mt-3 mx-2 p-0">
                    <span class="badge badge_love bg-danger position-absolute top-0 end-0 px-2 py-2">
                        <i class="fa-regular fa-heart wishlist'.$id.' wishlist_heart" onclick="add_wish('.$id.')"></i>
                    </span>
    
                    <img src="'.$filepath.'" alt="'.$slug.'">
                    <div class="item-name-index mt-2 fw-bold px-1 col-12 text-start">
                        <p class="mb-1">'.$name.'</p>
                        <p class="text-start text-black-50 mb-2">
                            <small><i class="fa-solid fa-eye" id="view_'.$id.'"></i> '.$view.'</small>&nbsp;&nbsp;
                            <small><i class="fa-solid fa-heart" id="wish_'.$id.'"></i> '.$wish.'</small>&nbsp;&nbsp;
                            <a class="text-decoration-none text-black-50" href="'.$site_url.'category/category.php?slug='.$category_slug.'"><small><i class="fa-solid fa-list"></i> '.$category_name.'</small></a>
                        </p>
                    </div>
    
                    
                    <div class="price-tag text-start mb-2 px-1">
                        <i class="fa-solid fa-tag text-success"></i> 
                            <del class="text-black-50 inr_price">₹'.$inr_price.'</del> <span class="text-danger fw-bold inr_price">₹'.$inr_offer.'</span>
                            <del class="text-black-50 usd_price">$'.$usd_price.'</del> <span class="text-danger fw-bold usd_price">$'.$usd_offer.'</span>
                    </div>
    
                    <div class="col-12 px-1 pb-2 d-flex flex-row justify-content-between flex-wrap">
                        <a href="'.$site_url.'product/product.php?slug='.$slug.'" class="btn btn-sm btn-primary col-sm-5 text-decoration-none">View Details</a>
                        <button class="btn btn-sm btn-dark col-sm-5 addcart add_cart_'.$id.'" onclick="add_cart('.$id.')">Add To Cart</button>
                        <button class="btn btn-sm btn-warning d-none col-sm-5 removecart remove_cart_'.$id.'" onclick="remove_cart('.$id.')">In Cart</button>
                    </div>
                </div>';
                }

            if ($i == 0) {
                    echo '<script>
                        document.getElementById("related-products").classList.add("d-none");
                    </script>';
                }


            ?>


        </div>
    </div>
</section>



<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.touchswipe/1.6.19/jquery.touchSwipe.min.js"></script>

<?php
include_once ('../footer.php');
?>








