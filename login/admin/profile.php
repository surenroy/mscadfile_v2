<?php
include_once ('header.php');

?>

<div class="container mt-5 mb-5">
    <div class="row col-sm-12 d-flex justify-content-between flex-row flex-wrap g-2">
        <div class="mb-3 col-sm-6">
            <label for="name" class="form-label fw-bold">Name</label>
            <input type="text" class="form-control form-control-sm col-12 w-100" id="name">
        </div>
        <div class="mb-3 col-sm-6">
            <label for="email" class="form-label fw-bold">Email</label>
            <input type="email" class="form-control form-control-sm col-12 w-100" id="email" readonly>
        </div>
        <div class="mb-3 col-sm-6">
            <label for="mobile" class="form-label fw-bold">Mobile</label>
            <input type="text" class="form-control form-control-sm col-12 w-100" id="mobile">
        </div>
        <div class="mb-3 col-sm-6">
            <label for="whatsapp" class="form-label fw-bold">Whatsapp</label>
            <input type="text" class="form-control form-control-sm col-12 w-100" id="whatsapp">
        </div>
        <div class="mb-3 col-sm-6">
            <label for="pincode" class="form-label fw-bold">Pincode</label>
            <input type="text" class="form-control form-control-sm col-12 w-100" id="pincode" maxlength="6">
        </div>
        <div class="mb-3 col-sm-6">
            <label for="currency" class="form-label fw-bold">Currency</label>
            <select class="form-select form-select-sm col-12 w-100" id="currency">
                <option selected value="0">Select Currency</option>
                <option value="1">INR</option>
                <option value="2">USD</option>
            </select>
        </div>
        <?php
            if($is_saller==1){
                echo '<div class="mb-3 col-sm-6">
                    <label for="upi" class="form-label fw-bold">UPI/PayPal ID</label>
                    <input type="text" class="form-control form-control-sm col-12 w-100" id="upi">
                </div>';
            }else{
                echo '<div class="mb-3 col-sm-6 d-none">
                    <label for="upi" class="form-label fw-bold">UPI/PayPal ID</label>
                    <input type="text" class="form-control form-control-sm col-12 w-100" id="upi">
                </div>';
            }

        ?>


        <div class="mb-3 col-sm-6">
            <label for="country" class="form-label fw-bold">Country</label>
            <select class="form-select form-select-sm col-12 w-100" id="country">
                <option selected value="0">Select Country</option>
                <?php
                    $sql="SELECT `id`,`name` FROM `countries` ORDER BY `name` ASC";
                    $query = $pdoconn->prepare($sql);
                    $query->execute();
                    $my_arr = $query->fetchAll(PDO::FETCH_ASSOC);
                    foreach($my_arr as $val){
                        $id=$val['id'];
                        $name=$val['name'];
                        echo "<option value='$id'>$name</option>";
                    }
                ?>
            </select>
        </div>
        <div class="col-12 text-center">
            <button type="button" id="submit_btn" onclick="update_data()" class="btn btn-danger col-12 top_page_button text-center">Submit</button>
        </div>
    </div>
</div>


<div class="container mb-5">
    <form class="row col-sm-12 d-flex justify-content-center flex-row flex-wrap g-2">
        <div class="mb-3 col-sm-4">
            <label for="old_pass" class="form-label fw-bold">Old Password</label>
            <input type="email" class="form-control form-control-sm col-12 w-100" id="old_pass">
        </div>
        <div class="mb-3 col-sm-4">
            <label for="new_pass" class="form-label fw-bold">New Password</label>
            <input type="text" class="form-control form-control-sm col-12 w-100" id="new_pass">
        </div>
        <div class="mb-3 col-sm-4">
            <label for="verify_pass" class="form-label fw-bold">Verify Password</label>
            <input type="text" class="form-control form-control-sm col-12 w-100" id="verify_pass">
        </div>

        <div class="col-12 text-center">
            <button type="button" id="submit_pass_btn" onclick="update_password()" class="btn btn-dark col-12 top_page_button text-center">Submit</button>
        </div>
    </form>
</div>



<script src="<?php echo $site_url; ?>login/admin/js/profile.js?v=<?php echo $ver; ?>"></script>

<?php
include_once ('footer.php');

?>
