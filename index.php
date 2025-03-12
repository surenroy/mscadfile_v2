<?php
include_once ('header.php');

?>









<section class="hero-section text-center text-lg-start position-relative">
    <div class="hero-overlay"></div>
    <div class="container">
        <div class="row align-items-center hero-content">
            <div class="col-lg-5 col-md-6 col-12 text-center">
                <div id="imageCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <img src="<?php echo $site_url; ?>images/banner-1.png" class="d-block w-100 mx-auto img-fluid hero_img" alt="3D CAD Jewelry 1">
                        </div>
                        <div class="carousel-item">
                            <img src="<?php echo $site_url; ?>images/banner-2.png" class="d-block w-100 mx-auto img-fluid hero_img" alt="3D CAD Jewelry 2">
                        </div>
                        <div class="carousel-item">
                            <img src="<?php echo $site_url; ?>images/banner-3.png" class="d-block w-100 mx-auto img-fluid hero_img" alt="3D CAD Jewelry 3">
                        </div>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#imageCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#imageCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
            </div>


            <div class="col-lg-7 col-md-6 col-12">
                <h2 class="mb-3 mt-4 mt-sm-3">Latest 3D CAD Designs</h2>
                <p class="lead">Find the perfect 3D CAD jewelry design for your next project. Our vast selection includes pendants, rings, bracelets, jhumkas, earrings, bangles, and necklaces in a variety of styles.</p>
                <a href="#" class="btn hero-btn mt-3">Buy 3D CAD Designs</a>
            </div>
        </div>
    </div>
</section>


<div class="carousel-container">
    <div class="carousel-wrapper">
        <?php
        $sql="SELECT `id`,`name`,`slug` FROM `categories` WHERE `del_flg`=0 ORDER BY `name` ASC";
        $query = $pdoconn->prepare($sql);
        $query->execute();
        $my_arr = $query->fetchAll(PDO::FETCH_ASSOC);

        if (count($my_arr) > 0) {
            $animation_duration = 210 + (count($my_arr) * 23);

            echo '<style>
                .carousel-wrapper {
                    display: flex;
                    gap: 20px;
                    position: relative;
                    width: max-content;
                    animation: scroll ' . $animation_duration . 's linear infinite;
                }
                .carousel-container:hover .carousel-wrapper {
                    animation-play-state: paused;
                }
                @keyframes scroll {
                    from { transform: translateX(0); }
                    to { transform: translateX(-50%); }
                }
            </style>';
        }



        foreach($my_arr as $val){
            $id=$val['id'];
            $name=$val['name'];
            $slug=$val['slug'];

            $url = $site_url . 'category/category.php?slug=' . $slug;

            echo '<div class="category-item"  onclick="openCategoryUrl(this)" data-url="' . $url . '">
                <img src="' . $site_url . 'category_img/' . $id . '.jpg" alt="Category">
                <div class="category-name">' . $name . '</div>
            </div>';
        }
        ?>


    </div>
</div>




<section class="py-5">
    <div class="container-fluid">
        <div class="col-12 d-flex justify-content-around flex-row flex-wrap" id="product_list">

            <?php
                $sql="SELECT COUNT(`id`) AS cnt FROM `products` WHERE `active`=1 AND `pending`=0 AND `drive_pending`=0";
                $query = $pdoconn->prepare($sql);
                $query->execute();
                $my_arr = $query->fetchAll(PDO::FETCH_ASSOC);
                $total_products=$my_arr[0]['cnt'];
                $limit = 8;
                $page = 1;
                $total_pages = ceil($total_products / $limit);





            ?>




        </div>
    </div>



    <div class="container mt-5">
        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center">
                <li class="page-item">
                    <button class="page-link" onclick="loadProducts(1,<?php echo $limit; ?>)" aria-label="First">
                        <span aria-hidden="true">&laquo;&laquo;</span>
                    </button>
                </li>
                <li class="page-item">
                    <button class="page-link" onclick="loadProducts(<?= max(1, $page - 1) ?>,<?php echo $limit; ?>)" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </button>
                </li>
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <li class="page<?php echo $i; ?> page-item <?= $i == $page ? 'active' : '' ?>">
                        <button class="page-link" onclick="loadProducts(<?= $i ?>,<?php echo $limit; ?>)"><?= $i ?></button>
                    </li>
                <?php endfor; ?>
                <li class="page-item">
                    <button class="page-link" onclick="loadProducts(<?= min($total_pages, $page + 1) ?>,<?php echo $limit; ?>)" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </button>
                </li>
                <li class="page-item">
                    <button class="page-link" onclick="loadProducts(<?= $total_pages ?>,<?php echo $limit; ?>)" aria-label="Last">
                        <span aria-hidden="true">&raquo;&raquo;</span>
                    </button>
                </li>
            </ul>
        </nav>
    </div>


</section>






<?php
if(isset($_SESSION['user_name'])){
    $user_type=$_SESSION["user_type"];

    if($user_type==0){
        echo '<button class="btn btn-primary fixed-btn_footer btn-sm" onclick="toggleModal()"><i class="fa-solid fa-comments"></i></button>
        <div class="custom-modal_footer" id="popupForm">
            <div class="modal-header_footer">
                <h5 class="modal-title">Send a Message</h5>
                <button type="button" class="btn-close" onclick="toggleModal()"></button>
            </div>
            <div class="modal-body mt-3">
                <div class="mb-2 w-100">
                    <input type="text" class="form-control w-100 form-control-sm" id="subject" placeholder="Enter subject">
                </div>
                <div class="mb-2 w-100">
                    <textarea class="form-control w-100 form-control-sm" id="message" rows="4" placeholder="Write your message"></textarea>
                </div>
                <button class="btn btn-success w-100" onclick="sendMessage()" id="msg_btn">Send</button>
            </div>
        </div>';
    }
}

?>











<script>
    $(document).ready(function() {
        setTimeout(function() {
            loadProducts(1,8);
            loadBanner();
        }, 1000);
    });
</script>



<?php
include_once ('banner.php');
include_once ('footer.php');

?>








