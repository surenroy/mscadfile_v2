<?php
include_once('header.php');

?>

<style>
    .gateway-container {
        display: flex;
        justify-content: center;
        gap: 20px;
        flex-wrap: wrap;
    }
    .gateway-card {
        border: 2px solid #ddd;
        border-radius: 8px;
        padding: 15px;
        cursor: pointer;
        text-align: center;
        transition: 0.3s;
        width: max-content;
        position: relative;
    }
    .gateway-card:hover {
        border-color: #007bff;
    }
    .gateway-card img {
        width: 100px;
        height: auto;
        margin-bottom: 8px;
    }
    .form-check-input {
        display: none;
    }
    .tick-mark {
        display: none;
        position: absolute;
        top: 10px;
        right: 10px;
        font-size: 24px;
        color: green;
    }
    .form-check-input:checked + .gateway-card {
        border-color: #007bff;
        background-color: #f8f9fa;
    }
    .form-check-input:checked + .gateway-card .tick-mark {
        display: block;
    }
</style>


<section class="container py-2" style="min-height: 70vh;">
    <div class="col-12 p-0 m-0 mt-1 mb-2 d-flex flex-row flex-wrap g-2 justify-content-center">

        <div class="col-12 p-0 m-0 table-responsive">

            <table class="table table-bordered table-hover table_center" id="product_table">
                <thead class="table-dark">
                <tr>
                    <th>Name</th>
                    <th>Image</th>
                    <th>Price</th>
                    <th>Offer</th>
                    <th>Category</th>
                    <th>Remove</th>
                </tr>
                </thead>
                <tbody id="product_show_cart">

                <?php
                $html = '<tr>
                <td colspan="6" style="text-align: center;">Login First</td>
            </tr>';

                if (isset($_SESSION["user_id"])) {

                    $user_currency = $_COOKIE['user_currency'];
                    $user_currency_txt = ($user_currency == 1) ? 'INR' : 'USD';


                    $html = '';
                    $user_id = $_SESSION["user_id"];

                    $sql = "SELECT `cart` FROM `wishlist_cart` WHERE `user_id`='$user_id' AND `cart` IS NOT NULL";
                    $query = $pdoconn->prepare($sql);
                    $query->execute();
                    $my_arr = $query->fetchAll(PDO::FETCH_ASSOC);
                    $cartlist = array();
                    foreach ($my_arr as $val) {
                        array_push($cartlist, $val['cart']);
                    }

                    if(count($cartlist)>0){
                        $final_cart = implode(',', $cartlist);

                        $sql = "SELECT `id`,`name` FROM `categories`";
                        $query = $pdoconn->prepare($sql);
                        $query->execute();
                        $my_arr = $query->fetchAll(PDO::FETCH_ASSOC);
                        $categoryArray = [];
                        foreach ($my_arr as $category) {
                            $categoryArray[$category['id']] = $category['name'];
                        }

                        $sql = "SELECT `id`,`name`,`slug`,`currency`,`price`,`offer`,`category_id`,`featured_image`,
                    DATE_FORMAT(`created_at`,'%d-%m-%Y %H:%i') AS created_at FROM `products`
                     WHERE `active`=1 AND `pending`=0 AND `drive_pending`=0 
                    AND `id` IN ($final_cart) ORDER BY `active` DESC, `created_at` DESC";
                        $query = $pdoconn->prepare($sql);
                        $query->execute();
                        $my_arr = $query->fetchAll(PDO::FETCH_ASSOC);

                        $product_ids = array();
                        $product_prices = array();
                        $total_cart_price = 0;
                        $non_delete_cart = array();
                        $i = 1;

                        $usd_price_total = $inr_price_total = 0;
                        $usd_offer_total = $inr_offer_total = 0;
                        foreach ($my_arr as $val) {
                            $id = $val['id'];
                            array_push($non_delete_cart, $id);
                            array_push($product_ids, $id);
                            $name = $val['name'];
                            $slug = $val['slug'];
                            $currency = $val['currency'];
                            $price = $val['price'];
                            $offer = $val['offer'];
                            $category_id = $val['category_id'];
                            $created_at = $val['created_at'];
                            $feature_image = $val['featured_image'];
                            $usd_rate = $_COOKIE["usd"];
                            if ($currency == 1) {
                                $inr_price = $price;
                                $inr_offer = $offer;
                                $usd_price = round(($price * $usd_rate), 2);
                                $usd_offer = round(($offer * $usd_rate), 2);
                            } else {
                                $inr_price = round(($price / $usd_rate), 2);
                                $inr_offer = round(($offer / $usd_rate), 2);
                                $usd_price = $price;
                                $usd_offer = $offer;
                            }


                            if($user_currency==1){
                                array_push($product_prices, $inr_offer);
                            }else{
                                array_push($product_prices, $usd_offer);
                            }





                            $usd_price_total = $usd_price_total + $usd_price;
                            $inr_price_total = $inr_price_total + $inr_price;
                            $usd_offer_total = $usd_offer_total + $usd_offer;
                            $inr_offer_total = $inr_offer_total + $inr_offer;

                            $html .= '<tr>
                <td><a href="'.$site_url.'product/product.php?slug='.$slug.'" class="text-decoration-none fw-bold text-dark">'.$name.'</a></td>
                <td><img src="product_images/' . $feature_image . '" class="pr_img" alt="' . $slug . '"></td>
                <td>
                    <del class="text-black-50 inr_price"><small>₹' . $inr_price . '</small></del> <del class="text-black-50 usd_price"><small>$' . $usd_price . '</small></del>
                </td>
                <td>
                    <span class="text-danger fw-bold inr_price">₹' . $inr_offer . '</span> <span class="text-danger fw-bold usd_price">$' . $usd_offer . '</span>
                </td>
                <td>' . $categoryArray[$category_id] . '</td>
                <td><button class="btn btn-sm btn-danger" onclick="remove_cart(' . $id . ')"><i class="fa-solid fa-trash"></i></button></td>
            </tr>';

                        }

                        if (count($my_arr) > 0) {
                            $savings_percentage = (($inr_price_total - $inr_offer_total) / $inr_price_total) * 100;
                            $savings_percentage = round($savings_percentage, 0);


                            $html .= '<tr>
                <td colspan="2" class="bg-light fw-bold">Total:</td>
                <td class="bg-light fw-bold">
                    <del class="text-black-50 inr_price"><small>₹' . $inr_price_total . '</small></del> <del class="text-black-50 usd_price"><small>$' . $usd_price_total . '</small></del>
                </td>
                <td class="bg-light fw-bold text-warning">
                    <span class="text-danger fw-bold inr_price">₹' . $inr_offer_total . '</span> <span class="text-danger fw-bold usd_price">$' . $usd_offer_total . '</span>
                </td>
                <td colspan="2" class="bg-success fw-bold text-white">Save: ' . $savings_percentage . '%</td>
            </tr>';
                        }

                        if (count($non_delete_cart) > 0) {
                            $delete_cart = implode(',', $non_delete_cart);
                            $sql = "DELETE FROM `wishlist_cart` WHERE `user_id`='$user_id' AND `cart` NOT IN ($delete_cart)";
                            $query = $pdoconn->prepare($sql);
                            $query->execute();
                        }




                        if($user_currency==1){
                            $total_cart_price=$inr_offer_total;
                        }else{
                            $total_cart_price=$usd_offer_total;
                        }


                        $purchased_products=implode(",",$product_ids);
                        $prices_products=implode(",",$product_prices);


                        $_SESSION['user_currency']=$user_currency;
                        $_SESSION['purchased_products']=$purchased_products;
                        $_SESSION['prices_products']=$prices_products;
                        $_SESSION['total_cart_price']=$total_cart_price;
                    }else{
                        $html = '<tr>
                <td colspan="6" style="text-align: center;">Cart is Empty</td>
            </tr>';
                    }


                }


                echo $html;

                ?>

                </tbody>
            </table>

        </div>

        <div class="col-12 mt-3 d-flex justify-content-center flex-wrap">
            <?php
            if (isset($_SESSION["user_id"]) && count($cartlist) > 0) {
                if($user_currency==1){
                    $action='./razorpay/';
                }else{
                    $action='./stripe/';
                }

                echo '<form id="paymentForm" method="post" action="'.$action.'">
                    <div class="gateway-container">';

                            if($user_currency==1){
                                echo '<label>
                            <input type="hidden" name="amount" value="'.$total_cart_price.'">
                            <input type="hidden" name="currency" value="'.$user_currency_txt.'">
                            <input type="hidden" name="user_id" value="'.$user_id.'">
                            <input class="form-check-input" type="radio" name="payment_gateway" id="razorpay" value="razorpay" checked>
                            <div class="gateway-card">
                                <img src="./images/razorpay.png" alt="Razorpay">
                                <p class="m-0">Pay using Razorpay</p>
                            </div>
                        </label>';
                            }else{
                                echo '<label>
                           <input type="hidden" name="amount" value="'.$total_cart_price.'">
                            <input type="hidden" name="currency" value="'.$user_currency_txt.'">
                            <input type="hidden" name="user_id" value="'.$user_id.'">
                            <input class="form-check-input" type="radio" name="payment_gateway" id="stripe" value="stripe" checked>
                            <div class="gateway-card">
                                <img src="./images/stripe.jpg" alt="Stripe">
                                <p class="m-0">Pay using Stripe</p>
                            </div>
                        </label>';
                            }


                    echo '</div>
                    <div class="d-flex justify-content-center mt-3">
                        <button type="submit" id="payment-button" class="btn btn-primary btn-sm">Proceed to Pay</button>
                    </div>
                </form>';

            }
            ?>

        </div>


    </div>
</section>


<script>

</script>

<?php
include_once('footer.php');

?>








