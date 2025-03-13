<?php
include_once ('header.php');

if($is_saller!=1){
    header('Location: home.php');
}
?>




<div class="container-fluid mb-3 mt-3 col-12 d-flex justify-content-center flex-row flex-wrap g-5">
    <button class="btn btn-primary mx-2 mt-2 mt-sm-0 col-sm-5 btn1 top_page_button" onclick="showDiv(1)">Sell Details</button>
    <button class="btn btn-outline-primary mx-2 mt-2 mt-sm-0 col-sm-5 btn2 top_page_button" onclick="showDiv(2)">Payment Details</button>
</div>

<div id="newProductDiv" class="full-screen d-flex text-white d-none px-2 col-12 justify-content-between flex-wrap">
    <div class="container-fluid pt-4 pb-4 text-dark rounded">
        <div class="table-responsive">
            <table class="table table-bordered table-hover table_center" id="sell_table">
                <thead class="table-dark">
                <tr>
                    <th>Date & Time</th>
                    <th>Product</th>
                    <th>Buyer</th>
                    <th>Image</th>
                    <th>INR</th>
                    <th>USD</th>
                    <th>Payment ID</th>
                </tr>
                </thead>
                <tbody id="sell_data">



                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="productListDiv" class="full-screen d-flex text-white d-none px-2 col-12 justify-content-between flex-wrap">
    <div class="container-fluid pt-4 text-dark rounded">
        <div class="table-responsive">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table_center">
                    <thead class="table-dark">
                    <tr>
                        <th colspan="2">Total Sell</th>
                        <th colspan="2">Platform Charge(15%)</th>
                        <th colspan="2">Total Earn</th>
                        <th colspan="2">Total Pay</th>
                        <th colspan="2">Pending</th>
                    </tr>
                    <tr>
                        <th>INR</th>
                        <th>USD</th>
                        <th>INR</th>
                        <th>USD</th>
                        <th>INR</th>
                        <th>USD</th>
                        <th>INR</th>
                        <th>USD</th>
                        <th>INR</th>
                        <th>USD</th>
                    </tr>
                    </thead>
                    <tbody id="payment_summary">


                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="container-fluid pt-4 pb-4 text-dark rounded">
        <div class="table-responsive">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-dark">
                    <tr>
                        <th>Entry On</th>
                        <th>Paid On</th>
                        <th>User</th>
                        <th>Payment ID</th>
                        <th>Description</th>
                        <th>INR</th>
                        <th>USD</th>
                    </tr>
                    </thead>
                    <tbody id="payment_history">


                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>





<script src="<?php echo $site_url; ?>login/user/js/sell.js?v=<?php echo $ver; ?>"></script>


<?php
include_once ('footer.php');

?>








