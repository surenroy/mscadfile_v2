<?php
include_once ('header.php');


$sql = "SELECT `id`,`country_nsme`,`code` FROM `country`";
$query = $pdoconn->prepare($sql);
$query->execute();
$my_countryarr = $query->fetchAll(PDO::FETCH_ASSOC);




$sql="SELECT `name`,`email`,`mobile_no`,`whatsapp_no`,`pincode`,`payment_id`,`country_mob`,`country_wp` FROM `users` WHERE `id`='$user_id'";
$query = $pdoconn->prepare($sql);
$query->execute();
$my_arr = $query->fetchAll(PDO::FETCH_ASSOC);


$country_mob=$my_arr[0]['country_mob'];
$country_wp=$my_arr[0]['country_wp'];
?>

<div class="container mt-5 mb-5">
    <div class="row col-sm-12 d-flex justify-content-between flex-row flex-wrap g-2">
        <div class="mb-3 col-sm-6">
            <label for="name" class="form-label fw-bold">Name</label>
            <input type="text" class="form-control form-control-sm col-12 w-100" id="name" value="<?php echo $my_arr[0]['name'] ?>">
        </div>
        <div class="mb-3 col-sm-6">
            <label for="email" class="form-label fw-bold">Email</label>
            <input type="email" class="form-control form-control-sm col-12 w-100" id="email" value="<?php echo $my_arr[0]['email'] ?>" readonly>
        </div>




        <div class="mb-3 col-sm-6">
            <label for="mobile" class="form-label fw-bold">Mobile</label>
            <div class="input-group">
                <select class="form-control form-control-sm" id="mobile_country" style="max-width: 150px;">
                    <option value="0" disabled <?php if ($country_mob == 0) echo 'selected'; ?>>Select Country</option>
                    <?php foreach ($my_countryarr as $country): ?>
                        <option value="<?php echo $country['id']; ?>" <?php if ($country['id'] == $country_mob) echo 'selected'; ?>>
                            <?php echo $country['country_nsme'].' ('.$country['code'].')'; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <input type="text" class="form-control form-control-sm" id="mobile" placeholder="Mobile Number" value="<?php echo $my_arr[0]['mobile_no'] ?>" style="flex: 1;">
            </div>
        </div>


        <div class="mb-3 col-sm-6">
            <label for="whatsapp" class="form-label fw-bold">Whatsapp</label>
            <div class="input-group">
                <select class="form-control form-control-sm" id="whatsapp_country" style="max-width: 150px;">
                    <option value="0" disabled <?php if ($country_wp == 0) echo 'selected'; ?>>Select Country</option>
                    <?php foreach ($my_countryarr as $country): ?>
                        <option value="<?php echo $country['id']; ?>" <?php if ($country['id'] == $country_wp) echo 'selected'; ?>>
                            <?php echo $country['country_nsme'].' ('.$country['code'].')'; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <input type="text" class="form-control form-control-sm" id="whatsapp" placeholder="Whatsapp Number" value="<?php echo $my_arr[0]['whatsapp_no'] ?>" style="flex: 1;">
            </div>
        </div>


        <div class="mb-3 col-sm-6">
            <label for="pincode" class="form-label fw-bold">Pincode</label>
            <input type="text" class="form-control form-control-sm col-12 w-100" id="pincode" maxlength="6" value="<?php echo $my_arr[0]['pincode'] ?>">
        </div>

        <?php
            if($is_saller==1){
                echo '<div class="mb-3 col-sm-6">
                    <label for="upi" class="form-label fw-bold">UPI/PayPal ID</label>
                    <input type="text" class="form-control form-control-sm col-12 w-100" id="upi" value="'.$my_arr[0]['payment_id'].'">
                </div>';
            }else{
                echo '<div class="mb-3 col-sm-6 d-none">
                    <label for="upi" class="form-label fw-bold">UPI/PayPal ID</label>
                    <input type="text" class="form-control form-control-sm col-12 w-100" id="upi" value="">
                </div>';
            }
        ?>

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
