<?php
include_once ('header.php');

?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">



<div class="container-fluid mb-3 mt-3 col-12 d-flex justify-content-center flex-row flex-wrap">
    <button class="btn btn-primary mx-2 mt-2 mt-sm-0 col-sm-3 btn1 top_page_button" onclick="showDiv(1)">Sell</button>
    <button class="btn btn-outline-primary mx-2 mt-2 mt-sm-0 col-sm-3 btn2 top_page_button" onclick="showDiv(2)">Payment</button>
    <button class="btn btn-outline-primary mx-2 mt-2 mt-sm-0 col-sm-3 btn3 top_page_button" onclick="showDiv(3)">Balance</button>
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




<div id="userListDiv" class="full-screen d-flex text-white d-none px-2 col-12 justify-content-between flex-wrap">
    <div class="container-fluid pt-4 text-dark rounded">
        <div class="table-responsive">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-dark">
                    <tr>
                        <th colspan="2">User</th>
                        <th colspan="2">Total Sell</th>
                        <th colspan="2">Platform Charge(15%)</th>
                        <th colspan="2">Total Earn</th>
                        <th colspan="2">Total Pay</th>
                        <th colspan="2">Pending</th>
                        <th colspan="2">Payment</th>
                    </tr>
                    <tr>
                        <th>Name</th>
                        <th>ID</th>
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
                        <th>INR</th>
                        <th>USD</th>
                    </tr>
                    </thead>
                    <tbody id="user_payment">


                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>



<!-- Modal -->
<div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="paymentModalLabel"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="paymentForm" class=" col-12">
                    <div class="mb-3 col-12">
                        <label for="paymentDate" class="form-label">Payment Date</label>
                        <input type="text" class="form-control datepicker form-control-sm col-12 w-100" id="paymentDate" placeholder="Select Date">
                    </div>
                    <div class="mb-3 col-12">
                        <label for="amount" class="form-label">Amount</label>
                        <input type="number" class="form-control form-control-sm col-12 w-100" id="amount" placeholder="Enter Amount">
                    </div>
                    <div class="mb-3 col-12">
                        <label for="reAmount" class="form-label">Re-enter Amount</label>
                        <input type="number" class="form-control form-control-sm col-12 w-100" id="reAmount" placeholder="Re-enter Amount">
                    </div>
                    <div class="mb-3 col-12">
                        <label for="paymentID" class="form-label">Payment ID</label>
                        <input type="text" class="form-control form-control-sm col-12 w-100" id="paymentID" placeholder="Enter a note">
                    </div>
                    <div class="mb-3 col-12">
                        <label for="note" class="form-label">Note</label>
                        <input type="text" class="form-control form-control-sm col-12 w-100" id="note" placeholder="Enter a note">
                    </div>

                    <button id="submit_btn" class="btn btn-primary btn-sm col-12" onclick="submit_payment()">Submit</button>
                </div>
            </div>
        </div>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<script src="<?php echo $site_url; ?>login/user/js/sells.js?v=<?php echo $ver; ?>"></script>

<?php
include_once ('footer.php');

?>








