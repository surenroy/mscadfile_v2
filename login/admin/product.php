<?php
include_once('header.php');


?>
<style>
    .progress {
        height: 10px;
    }

    .progress-bar {
        font-size: 10px;
        background-color: rgba(18, 255, 0, 0.83);
        color: black;
    }

    .main-container {
        font-family: 'Lato';
    }

    .ck-content {
        font-family: 'Lato';
        line-height: 1.6;
        word-break: break-word;
    }

    .editor-container_classic-editor .editor-container__editor {
        width: 100%;
    }

    .ck-editor__editable {
        min-height: 20vh;
    }

    .ck-powered-by-balloon {
        display: none !important;
    }
</style>


<div class="container-fluid mb-3 mt-3 col-12 d-flex justify-content-center flex-row flex-wrap g-5">
    <button class="btn btn-outline-primary mx-2 mt-2 mt-sm-0 col-sm-5 btn2 top_page_button" onclick="showDiv(2)">Products List</button>
</div>


<div id="productListDiv" class="full-screen d-flex text-white px-2 col-12 justify-content-between flex-wrap">
    <div class="container-fluid pt-4 pb-4 text-dark rounded">
        <div class="table-responsive">
            <table class="table table-bordered table-hover table_center" id="product_table">
                <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Slug</th>
                    <th>Image</th>
                    <th>Price</th>
                    <th>Feature</th>
                    <th>Category</th>
                    <th>Date</th>
                    <th>View</th>
                    <th>Wishlist</th>
                    <th>Pending</th>
                    <th>Size</th>
                    <th>Weight</th>
                    <th>Purity</th>
                    <th>Volume</th>
                    <th>File Types</th>
                    <th>Space (GB)</th>
                    <th>Image/Files</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody id="product_data">

                </tbody>
            </table>
        </div>
    </div>
</div>





<div class="modal fade" id="cropModal" tabindex="-1" aria-labelledby="cropModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-dark" id="cropModalLabel">Crop Image</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0 col-12">
                <img id="cropImage" src="#" alt="Image for cropping" style="max-width: 100%;">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="cropAndUpload()">Crop & Upload</button>
            </div>
        </div>
    </div>
</div>


<script src="<?php echo $site_url; ?>login/admin/js/product.js?v=<?php echo $ver; ?>"></script>


<?php
include_once('footer.php');

?>








