<?php
include_once ('header.php');

?>







<div id="sentMessage" class="full-screen d-flex text-white px-2 col-12 justify-content-between flex-wrap">
    <p class="text-center col-12 pt-2 text-dark">To Download files for a product, start processing by click <button class="btn btn-sm btn-dark mx-1 p-1 px-2"><i class="fa-solid fa-file-export"></i></button> button. It will process your file which are ready to download. Then after some time you will get links while you click <button class="btn btn-sm btn-warning mx-1 p-1 px-2"><i class="fa-solid fa-eye"></i></button></p>

    <div class="container-fluid pt-2 pb-4 text-dark rounded">
        <div class="table-responsive">
            <table class="table table-bordered table-hover table_center" id="order_table">
                <thead class="table-dark">
                <tr>
                    <th>Date & Time</th>
                    <th>Product</th>
                    <th>Seller</th>
                    <th>Image</th>
                    <th>Files</th>
                    <th>Payment ID</th>
                    <th>Paid</th>
                </tr>
                </thead>
                <tbody id="order_data">

                </tbody>
            </table>
        </div>
    </div>
</div>



<div class="modal fade" id="productModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Product Files</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table_center" id="product_table">
                        <thead class="table-dark">
                        <tr>
                            <th>Name</th>
                            <th>Size (Mb)</th>
                            <th>Link</th>
                        </tr>
                        </thead>
                        <tbody id="modal_body_product">

                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Close</button>
            </div>
        </div>
    </div>
</div>



<script src="<?php echo $site_url; ?>login/user/js/order.js?v=<?php echo $ver; ?>"></script>



<?php
include_once ('footer.php');

?>








