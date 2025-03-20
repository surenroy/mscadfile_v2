<?php
include_once ('header.php');

?>

<div class="container-fluid mb-3 mt-3 col-12 d-flex justify-content-center flex-row flex-wrap g-5">
    <button class="btn btn-primary mx-2 mt-2 mt-sm-0 col-sm-5 btn1 top_page_button" onclick="showDiv(1)">Add New</button>
    <button class="btn btn-outline-primary mx-2 mt-2 mt-sm-0 col-sm-5 btn2 top_page_button" onclick="showDiv(2)">View</button>
</div>

<div id="newMessage" class="full-screen d-flex text-white px-2 col-12 justify-content-between flex-wrap">
    <div class="container pt-4 pb-4 text-dark rounded">
        <form class="col-12 p-0 m-0 d-flex justify-content-center flex-wrap flex-row">
            <div class="row g-2 mb-3 col-sm-6 p-0 m-0 d-flex justify-content-between flex-wrap w-100">
                <div class="col-md-6">
                    <label class="form-label col-12 w-100 fw-bold">Category</label>
                    <input type="text" class="form-control form-control-sm w-100 atz" id="category" required>
                </div>
            </div>

            <div class="row g-2 mb-3 col-sm-6 p-0 m-0 d-flex justify-content-between flex-wrap w-100">
                <div class="col-md-6">
                    <label class="form-label col-12 w-100 fw-bold">Image (jpg,jpeg) <200kb (300hx300w)</label>
                    <input type="file" id="featuredImage" accept=".jpg,.jpeg" class="form-control form-control-sm col-12 mb-2 w-100" onchange="previewImage(this, 'featuredPreview', 'featuredImage')">
                    <img id="featuredPreview" src="#" class="img-thumbnail mb-2 d-none col-12" style="max-width: 300px;">

                    <div class="col-12">
                        <button type="button" class="btn btn-sm btn-danger" id="remove_btn" onclick="removeImage('featuredImage', 'featuredPreview')">Remove</button>
                    </div>
                </div>
            </div>





            <div class="col-12 text-center">
                <button type="button" class="btn btn-danger col-12 top_page_button text-center" id="submit_btn" onclick="submit_form()">Submit</button>
            </div>
        </form>
    </div>
</div>

<div id="productListDiv" class="full-screen d-flex text-white d-none px-2 col-12 justify-content-between flex-wrap">
    <div class="container-fluid pt-4 pb-4 text-dark rounded">
        <div class="table-responsive">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="table_data">
                    <thead class="table-dark">
                    <tr>
                        <th>Name</th>
                        <th>Image</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody id="data_data">


                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>





<script src="<?php echo $site_url; ?>login/admin/js/category.js?v=<?php echo $ver; ?>"></script>


<?php
include_once ('footer.php');

?>








