<?php
include_once ('header.php');

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

<div class="container-fluid mb-3 mt-3 col-12 d-flex justify-content-center flex-row flex-wrap">
    <button class="btn btn-primary mx-2 mt-2 mt-sm-0 col-sm-3 btn1 top_page_button" onclick="showDiv(1)">New Blog</button>
    <button class="btn btn-outline-primary mx-2 mt-2 mt-sm-0 col-sm-3 btn2 top_page_button" onclick="showDiv(2)">My Blogs</button>
</div>

<div id="newMessage" class="full-screen d-flex d-none text-white px-2 col-12 justify-content-between flex-wrap">
    <div class="container pt-4 pb-4 text-dark rounded">
        <div class="col-12 p-0 m-0 d-flex justify-content-between flex-wrap flex-row">
            <div class="row g-2 mb-3 col-sm-12 p-0 m-0 d-flex justify-content-between flex-wrap">
                <div class="col-md-6">
                    <label class="form-label col-12 w-100 fw-bold">Image <300kb</label>
                    <input type="file" accept=".jpg,.jpeg,.png" class="form-control form-control-sm col-12 mb-2 w-100" id="featuredImage" onchange="previewImage(this, 'featuredPreview', 'image1')">
                    <img id="featuredPreview" src="#" class="img-thumbnail mb-2 d-none" style="max-width: 300px;">
                    <button type="button" class="btn btn-sm btn-danger" onclick="removeImage('featuredImage', 'featuredPreview')">Remove</button>
                </div>
            </div>

            <div class="row g-2 mb-3 col-12 p-0 m-0 d-flex justify-content-between flex-wrap">
                <div class="col-md-6">
                    <label class="form-label col-12 w-100 fw-bold" for="subject">Title</label>
                    <input type="text" class="form-control form-control-sm w-100" id="subject" maxlength="100" required>
                </div>
            </div>

            <div class="row g-2 mb-3 col-sm-8 w-100 p-0 m-0 d-flex justify-content-between flex-wrap">
                <div class="col-12 w-100">
                    <label class="form-label col-12 w-100 fw-bold">Short Description</label>
                    <textarea class="form-control form-select-sm col-12 w-100" rows="2" maxlength="250" id="short_description"></textarea>
                </div>
            </div>

            <div class="row g-2 mb-3 col-12 p-0 m-0 d-flex justify-content-between flex-wrap">
                <label for="editor" class="form-label col-12 w-100 fw-bold">Description</label>
                <div class="editor-container editor-container_classic-editor col-12" id="editor-container">
                    <div class="editor-container__editor">
                        <div id="editor"></div>
                    </div>
                </div>
            </div>

            <div class="col-12 text-center">
                <button onclick="submit_blog()" id="blog_btn" class="btn btn-danger col-12 top_page_button text-center">Submit</button>
            </div>
        </div>
    </div>
</div>



<div id="inboxMessage" class="full-screen d-flex text-white d-none px-2 col-12 justify-content-between flex-wrap">
    <div class="container-fluid pt-4 pb-4 text-dark rounded">
        <div class="table-responsive">
            <table class="table table-bordered table-hover table_center" id="table_inbox">
                <thead class="table-dark">
                <tr>
                    <th>Date & Time</th>
                    <th>Title</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody id="inbox_data">

                </tbody>
            </table>
        </div>
    </div>
</div>



<script type="importmap">
    {
        "imports": {
            "ckeditor5": "https://cdn.ckeditor.com/ckeditor5/43.0.0/ckeditor5.js",
            "ckeditor5/": "https://cdn.ckeditor.com/ckeditor5/43.0.0/"
        }
    }
</script>

<script type="module" src="<?php echo $site_url; ?>ckeditor/ckeditor_update.js?v=<?php echo $ver; ?>"></script>



<script src="<?php echo $site_url; ?>login/user/js/blog.js?v=<?php echo $ver; ?>"></script>


<?php
include_once ('footer.php');

?>








