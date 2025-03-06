<?php
include_once('header.php');

if ($is_saller != 1) {
    header('Location: index.php');
}


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

<link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/43.0.0/ckeditor5.css">
<link href="https://cdn.jsdelivr.net/npm/cropperjs@1.5.12/dist/cropper.min.css" rel="stylesheet"/>


<div class="container-fluid mb-3 mt-3 col-12 d-flex justify-content-center flex-row flex-wrap g-5">
    <button class="btn btn-primary mx-2 mt-2 mt-sm-0 col-sm-5 btn1 top_page_button" onclick="showDiv(1)">New Product</button>
    <button class="btn btn-outline-primary mx-2 mt-2 mt-sm-0 col-sm-5 btn2 top_page_button" onclick="showDiv(2)">Products List</button>
</div>

<div id="newProductDiv" class="full-screen d-flex text-white d-none px-2 col-12 justify-content-between flex-wrap">
    <div class="container-fluid pt-4 pb-4 text-dark rounded">
        <div class="col-12 p-0 m-0">
            <div class="row g-2 mb-3 col-12 p-0 m-0 d-flex justify-content-between flex-wrap">
                <div class="col-md-8">
                    <label class="form-label col-12 w-100 fw-bold" for="product">Product Name</label>
                    <input type="text" class="form-control form-control-sm w-100" required id="product">
                </div>
                <div class="col-md-4">
                    <label class="form-label col-12 w-100 fw-bold" for="category">Category</label>
                    <select class="form-select form-select-sm w-100" id="category">
                        <option value="0">Select Category</option>

                        <?php
                        $sql = "SELECT `id`,`name` FROM `categories` WHERE `del_flg`=0 ORDER BY `name` ASC";
                        $query = $pdoconn->prepare($sql);
                        $query->execute();
                        $my_arr = $query->fetchAll(PDO::FETCH_ASSOC);

                        foreach ($my_arr as $row) {
                            echo '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
                        }
                        ?>
                    </select>
                </div>


                <div class="row g-2 mb-3 col-12 p-0 m-0 d-flex justify-content-between flex-wrap">
                    <label for="editor" class="form-label col-12 w-100 fw-bold">Description</label>
                    <div class="editor-container editor-container_classic-editor col-12" id="editor-container">
                        <div class="editor-container__editor">
                            <div id="editor"></div>
                        </div>
                    </div>
                </div>


                <div class="col-12 text-center mb-3 mt-3">
                    <button type="button" id="seo_btn" class="btn btn-dark col-12 top_page_button text-center" onclick="generateSEOContent()">Generate SEO Data</button>
                </div>

                <div class="row g-2 mb-3 col-12 p-0 m-0 d-flex justify-content-between flex-wrap">
                    <div class="col-md-6">
                        <label class="form-label col-12 w-100 fw-bold">Meta Title</label>
                        <input type="text" class="form-control form-control-sm col-12 w-100" id="meta_title" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label col-12 w-100 fw-bold">Meta Keywords</label>
                        <input type="text" class="form-control form-control-sm col-12 w-100" id="meta_keyword" readonly>
                    </div>
                </div>

                <div class="row g-2 mb-3 col-12 p-0 m-0 d-flex justify-content-between flex-wrap">
                    <div class="col-12">
                        <label class="form-label col-12 w-100 fw-bold">Meta Description</label>
                        <textarea class="form-control form-control-sm col-12 w-100" rows="3" id="meta_description" readonly></textarea>
                    </div>
                </div>

                <div class="row g-2 mb-3 col-12 p-0 m-0 d-flex justify-content-between flex-wrap">
                    <div class="col-12">
                        <label class="form-label col-12 w-100 fw-bold">SEO Description</label>
                        <textarea class="form-control form-control-sm col-12 w-100" rows="3" id="seo_description" readonly></textarea>
                    </div>
                </div>


                <hr>

                <div class="row g-2 mb-3 col-12 p-0 m-0 d-flex justify-content-between flex-wrap">
                    <div class="col-md-2">
                        <label class="form-label col-12 w-100 fw-bold" for="currency">Currency</label>
                        <select class="form-select form-select-sm col-12 w-100" id="currency">
                            <option value="0">Select</option>
                            <option value="1">INR</option>
                            <option value="2">USD</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label col-12 w-100 fw-bold" for="price">Original Price</label>
                        <input type="text" class="form-control form-control-sm col-12 w-100" id="price" oninput="twoDigit(this)">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label col-12 w-100 fw-bold" for="offer">Sell Price</label>
                        <input type="text" class="form-control form-control-sm col-12 w-100" id="offer" oninput="twoDigit(this)">
                    </div>
                    <div class="col-md-2 d-flex align-items-end justify-content-start flex-wrap">
                        <label class="form-label col-12 w-100 fw-bold col-12 p-0 m-0" for="attr">Attribute</label>
                        <div class="form-check me-3">
                            <input type="checkbox" class="form-check-input" checked id="active">
                            <label class="form-check-label">Active</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="feature">
                            <label class="form-check-label">Feature</label>
                        </div>
                    </div>
                    <div class="col-md-3 d-flex flex-wrap justify-content-start">
                        <label class="form-label col-12 w-100 fw-bold col-12 p-0 m-0">Files Included</label>
                        <div class="form-check me-3">
                            <input type="checkbox" class="form-check-input" id="3dm">
                            <label class="form-check-label">3DM</label>
                        </div>
                        <div class="form-check me-3">
                            <input type="checkbox" class="form-check-input" id="stl">
                            <label class="form-check-label">STL</label>
                        </div>
                        <div class="form-check me-3">
                            <input type="checkbox" class="form-check-input" id="mgx">
                            <label class="form-check-label">MGX</label>
                        </div>
                        <div class="form-check me-3">
                            <input type="checkbox" class="form-check-input" id="obj">
                            <label class="form-check-label">OBJ</label>
                        </div>
                    </div>
                </div>

                <hr>
                <div class="row g-2 mb-3 col-12 p-0 m-0 d-flex justify-content-between flex-wrap">
                    <div class="col-md-2 d-flex justify-content-between flex-wrap">
                        <label class="form-label col-12 w-100 fw-bold">Size</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control form-control-sm col-12 w-100" id="size" oninput="threeDigit(this)">
                        </div>
                        <div class="col-sm-6">
                            <select class="form-select form-select-sm col-12 w-100" id="size_unit">
                                <option value="0">Select</option>
                                <option value="1">cm</option>
                                <option value="2">mm</option>
                                <option value="3">inch</option>
                            </select>
                        </div>
                    </div>


                    <div class="col-md-2 d-flex flex-row justify-content-between flex-wrap">
                        <label class="form-label col-12 w-100 fw-bold">Weight</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control form-control-sm col-sm-12 w-100" id="weight" oninput="threeDigit(this)">
                        </div>
                        <div class="col-sm-6">
                            <select class="form-select form-select-sm col-12 w-100" id="weight_unit">
                                <option value="0">Select</option>
                                <option value="1">Gram</option>
                                <option value="2">Pound</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-2 d-flex flex-row justify-content-between flex-wrap">
                        <label class="form-label col-12 w-100 fw-bold">Purity</label>
                        <div class="col-sm-6">
                            <select class="form-select form-select-sm col-12 w-100" id="purity">
                                <option value="0">Select</option>
                                <option value="1">24K995</option>
                                <option value="2">23K958</option>
                                <option value="3">22K916</option>
                                <option value="4">20K833</option>
                                <option value="5">18K750</option>
                                <option value="6">14K585</option>
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <select class="form-select form-select-sm col-12 w-100" id="purity_unit">
                                <option value="0">Select</option>
                                <option value="1">Gold</option>
                                <option value="2">Silver</option>
                                <option value="3">Imitation</option>
                            </select>
                        </div>
                    </div>


                    <div class="col-md-2 d-flex justify-content-center flex-wrap">
                        <div class="col-sm-6 p-0">
                            <label class="form-label col-12 w-100 fw-bold">Volume</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control form-control-sm col-12 w-100" id="volume" oninput="threeDigit(this)">
                            </div>
                        </div>
                        <div class="col-sm-6 p-0">
                            <label class="form-label col-12 w-100 fw-bold">Software</label>
                            <div class="col-sm-12">
                                <select class="form-select form-select-sm col-12 w-100" id="volume_unit">
                                    <option value="0">Select</option>
                                    <option value="1">Rhino</option>
                                    <option value="2">Matrix</option>
                                    <option value="3">ZBrush</option>
                                    <option value="4">Magices</option>
                                    <option value="5">Others</option>
                                </select>
                            </div>
                        </div>

                    </div>


                </div>

                <div class="col-12 text-center mt-3 mb-3">
                    <button type="button" class="btn btn-primary col-12 top_page_button text-center" id="add_btn" onclick="add_product()">Add Images & Files</button>
                </div>


                <div class="d-none col-12 d-flex flex-row justify-content-between flex-wrap p-0" id="submit_div">


                    <div class="row g-2 mb-3 col-5 p-0 m-0 d-flex flex-row flex-wrap justify-content-start">
                        <div class="col-sm-12" id="image1">
                            <label class="form-label col-12 w-100 fw-bold">Image Upload (.jpg/.jpeg/.png) <300kb</label>
                            <small>1st Image will be the Featured Image</small>
                            <input type="file" accept=".jpg,.jpeg,.png" class="form-control form-control-sm col-12 mb-2 w-100" id="input1" onchange="previewImage(this, 'preview1', '1', 'progressBar1')">
                            <div class="progress mt-2 d-none" id="progressBar1Container">
                                <div class="progress-bar progress-bar-striped progress-bar-animated" id="progressBarImg" style="width: 0%;">0%</div>
                            </div>
                        </div>

                        <table id="imageDetailsTable" class="table mt-3">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Image Name</th>
                                <th>Icon</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody id="imageTableBody">

                            </tbody>
                        </table>


                    </div>

                    <hr>

                    <div class="row g-2 mb-3 col-5 p-0 m-0 d-flex flex-row flex-wrap justify-content-start">
                        <div class="col-sm-12" id="fileUpload">
                            <label class="form-label col-12 w-100 fw-bold">File Upload (All file types) <1.5GB</label>
                            <input type="file" accept="*" class="form-control form-control-sm col-12 mb-2 w-100" id="inputFile">
                            <button class="btn btn-primary mt-2" id="uploadButton" onclick="uploadFile()">Upload</button>
                            <div class="progress mt-2 d-none" id="progressBarFileContainer">
                                <div class="progress-bar progress-bar-striped progress-bar-animated" id="progressBarFile" style="width: 0%;">0%</div>
                            </div>
                        </div>

                        <table id="fileDetailsTable" class="table mt-3">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>File Name</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody id="fileTableBody">

                            </tbody>
                        </table>


                    </div>

                    <hr>


                    <div class="col-12 text-center">
                        <button type="button" id="final_product" class="btn btn-danger col-12 top_page_button text-center" onclick="final_product()">Add Product</button>
                    </div>

                </div>

            </div>
        </div>
    </div>


</div>


<div id="productListDiv" class="full-screen d-flex text-white d-none px-2 col-12 justify-content-between flex-wrap">
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


<script src="https://cdn.jsdelivr.net/npm/cropperjs@1.5.12/dist/cropper.min.js"></script>

<script src="<?php echo $site_url; ?>login/user/js/product.js?v=<?php echo $ver; ?>"></script>

<script type="importmap">
    {
        "imports": {
            "ckeditor5": "https://cdn.ckeditor.com/ckeditor5/43.0.0/ckeditor5.js",
            "ckeditor5/": "https://cdn.ckeditor.com/ckeditor5/43.0.0/"
        }
    }
</script>

<script type="module" src="<?php echo $site_url; ?>ckeditor/ckeditor_update.js?v=<?php echo $ver; ?>"></script>


<?php
include_once('footer.php');

?>








