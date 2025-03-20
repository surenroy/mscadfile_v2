<?php
include_once ('header.php');

?>




<div class="container-fluid mb-3 mt-3 col-12 d-flex justify-content-center flex-row flex-wrap p-0">
    <button class="btn btn-primary mx-2 mt-2 mt-sm-0 col-sm-3 btn1 top_page_button" onclick="showDiv(1)">Post Job</button>
    <button class="btn btn-outline-primary mx-2 mt-2 mt-sm-0 col-sm-3 btn2 top_page_button" onclick="showDiv(2)">Other Jobs</button>
    <button class="btn btn-outline-primary mx-2 mt-2 mt-sm-0 col-sm-3 btn3 top_page_button" onclick="showDiv(3)">My Jobs</button>
</div>


<div id="newMessage" class="full-screen d-flex text-white d-none px-2 col-12 justify-content-between flex-wrap">
    <div class="container pt-4 pb-4 text-dark rounded">
        <div class="col-12 p-0 m-0 d-flex justify-content-between flex-wrap flex-row">
            <div class="row g-2 mb-3 col-sm-4 p-0 m-0 d-flex justify-content-between flex-wrap">
                <div class="col-md-12">
                    <label class="form-label col-12 w-100 fw-bold">Job Image</label>
                    <input type="file" accept=".jpg,.jpeg,.png" class="form-control form-control-sm col-12 mb-2 w-100" id="featuredImage" onchange="previewImage(this, 'featuredPreview', 'image1')">
                    <img id="featuredPreview" src="#" class="img-thumbnail mb-2 d-none" style="max-width: 300px;">
                    <button type="button" class="btn btn-sm btn-danger" onclick="removeImage('featuredImage', 'featuredPreview')">Remove</button>
                </div>
            </div>

            <div class="row g-2 mb-3 col-sm-8 w-100 p-0 m-0 d-flex justify-content-between flex-wrap">
                <div class="col-12 w-100">
                    <label class="form-label col-12 w-100 fw-bold">Description</label>
                    <select class="form-select form-select-sm col-12 w-100" id="description">
                        <option value="0">Select a description</option>
                        <option value="1">Description 1</option>
                        <option value="2">Description 2</option>
                        <option value="3">Description 3</option>
                        <option value="4">Description 4</option>
                    </select>
                </div>
            </div>

            <div class="col-12 text-center">
                <button onclick="submit_job()" id="blog_btn" class="btn btn-danger col-12 top_page_button text-center">Submit</button>
            </div>
        </div>
    </div>
</div>


<div id="inboxMessage" class="full-screen d-flex text-white d-none px-2 col-12 justify-content-between flex-wrap">
    <div class="container-fluid pt-4 pb-4 text-dark rounded col-12" id="other_job">




    </div>
</div>



<div id="sentMessage" class="full-screen d-flex text-white d-none px-2 col-12 justify-content-between flex-wrap">
    <div class="container-fluid pt-4 pb-4 text-dark rounded col-12" id="my_job">







    </div>
</div>



<div class="modal fade" id="productModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Product Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table_center" id="product_table">
                        <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Slug</th>
                            <th>Image</th>
                            <th>Price</th>
                            <th>Category</th>
                            <th>Date</th>
                        </tr>
                        </thead>
                        <tbody id="modal_body_product">

                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" data-id="0" id="reply_btn" onclick="reply_with_product()">Add to Reply</button>
            </div>
        </div>
    </div>
</div>



<div class="modal fade" id="productModal_show" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Product Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table_center" id="product_table">
                        <thead class="table-dark">
                        <tr>
                            <th>Name</th>
                            <th>Slug</th>
                            <th>Image</th>
                            <th>Price</th>
                            <th>Category</th>
                            <th>Date</th>
                        </tr>
                        </thead>
                        <tbody id="modal_body_product_show">

                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>




<script src="<?php echo $site_url; ?>login/user/js/job.js?v=<?php echo $ver; ?>"></script>

<?php
include_once ('footer.php');

?>








