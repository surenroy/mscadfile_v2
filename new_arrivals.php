<?php
include_once ('header.php');

?>


<section class="container-fluid py-2">
    <section class="py-5">
        <div class="container-fluid">
            <div class="col-12 d-flex justify-content-around flex-row flex-wrap" id="product_list">

                <?php
                $sql="SELECT COUNT(`id`) AS cnt FROM `products` WHERE `active`=1";
                $query = $pdoconn->prepare($sql);
                $query->execute();
                $my_arr = $query->fetchAll(PDO::FETCH_ASSOC);
                $total_products=$my_arr[0]['cnt'];
                $limit = 20;
                $page = 1;
                $total_pages = ceil($total_products / $limit);



                ?>




            </div>
        </div>



        <div class="container mt-5">
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center">
                    <li class="page-item">
                        <button class="page-link" onclick="loadNewProducts(1,<?php echo $limit; ?>)" aria-label="First">
                            <span aria-hidden="true">&laquo;&laquo;</span>
                        </button>
                    </li>
                    <li class="page-item">
                        <button class="page-link" onclick="loadNewProducts(<?= max(1, $page - 1) ?>,<?php echo $limit; ?>)" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </button>
                    </li>
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <li class="page<?php echo $i; ?> page-item <?= $i == $page ? 'active' : '' ?>">
                            <button class="page-link" onclick="loadNewProducts(<?= $i ?>,<?php echo $limit; ?>)"><?= $i ?></button>
                        </li>
                    <?php endfor; ?>
                    <li class="page-item">
                        <button class="page-link" onclick="loadNewProducts(<?= min($total_pages, $page + 1) ?>,<?php echo $limit; ?>)" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </button>
                    </li>
                    <li class="page-item">
                        <button class="page-link" onclick="loadNewProducts(<?= $total_pages ?>,<?php echo $limit; ?>)" aria-label="Last">
                            <span aria-hidden="true">&raquo;&raquo;</span>
                        </button>
                    </li>
                </ul>
            </nav>
        </div>


    </section>
</section>






<script>
    $(document).ready(function() {
        loadNewProducts(1,20);
        loadBanner();
    });
</script>










<?php
include_once ('banner.php');
include_once ('footer.php');

?>








