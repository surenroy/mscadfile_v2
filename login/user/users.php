<?php
include_once ('header.php');

?>




<div id="productListDiv" class="full-screen d-flex text-white px-2 col-12 justify-content-between flex-wrap">
    <div class="container-fluid pt-4 pb-4 text-dark rounded">
        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="table_users">
                <thead class="table-dark">
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Mobile</th>
                    <th>Whatsapp</th>
                    <th>Type</th>
                    <th>Country</th>
                    <th>Registered</th>
                    <th>Payment ID</th>
                    <th>Type</th>
                    <th>Currency</th>
                    <th>Pincode</th>
                    <th>Action</th>
                    <th>Usage (GB)</th>
                </tr>
                </thead>
                <tbody id="users_data">


                </tbody>
            </table>
        </div>
    </div>
</div>








<script src="<?php echo $site_url; ?>login/user/js/users.js?v=<?php echo $ver; ?>"></script>

<?php
include_once ('footer.php');

?>








