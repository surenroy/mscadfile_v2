<?php
include_once ('header.php');

?>











<div class="container mt-5 p-4 shadow-md rounded bg-white">

    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Product</th>
                <th>Price</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>1</td>
                <td>LOXMI MURTI</td>
                <td>$25.99</td>
            </tr>
            <tr>
                <td>2</td>
                <td>LOXMI MURTI</td>
                <td>$26.99</td>
            </tr>
            </tbody>
        </table>
    </div>


    <div class="row justify-content-end">
        <div class="col-sm-4">
            <div class="table-responsive">
                <table class="table table-bordered mt-4">
                    <tbody>
                    <tr>
                        <td>Subtotal</td>
                        <td>$51.99</td>
                    </tr>
                    <tr>
                        <td>Tax</td>
                        <td>$2.00</td>
                    </tr>
                    <tr class="table-dark text-white">
                        <td><strong>Total Payable</strong></td>
                        <td><strong>$53.99</strong></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>




    <h5 class="mt-5">Customer Details</h5>

    <form class="mt-2 row g-3 col-12">
        <div class="col-md-6">
            <label class="form-label">Name</label>
            <input type="text" class="form-control form-control-sm col-12 w-100" required>
        </div>
        <div class="col-md-6">
            <label class="form-label">Address</label>
            <input type="text" class="form-control form-control-sm col-12 w-100" required>
        </div>
        <div class="col-md-4">
            <label class="form-label">Email</label>
            <input type="email" class="form-control form-control-sm col-12 w-100" required>
        </div>
        <div class="col-md-4">
            <label class="form-label">Mobile</label>
            <input type="tel" class="form-control form-control-sm col-12 w-100" required>
        </div>
        <div class="col-md-4">
            <label class="form-label">Country</label>
            <select class="form-select" required>
                <option selected disabled>Select Country</option>
                <option>USA</option>
                <option>India</option>
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label">State</label>
            <select class="form-select" required>
                <option selected disabled>Select State</option>
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label">City</label>
            <input type="text" class="form-control form-control-sm col-12 w-100" required>
        </div>
        <div class="col-md-4">
            <label class="form-label">Pincode</label>
            <input type="text" class="form-control form-control-sm col-12 w-100" required>
        </div>


        <h5 class="mt-5">Choose Payment Method</h5>
        <div class="d-flex gap-3">
            <div class="form-check d-flex align-items-center gap-2">
                <input class="form-check-input mt-0" type="radio" name="paymentMethod" id="razorpay" required>
                <label class="form-check-label d-flex align-items-center gap-2" for="razorpay">
                    <img src="https://www.mscadfile.com/images/razorpay.png" alt="Razorpay Logo" width="90" height="50">
                </label>
            </div>
            <div class="form-check d-flex align-items-center gap-2">
                <input class="form-check-input mt-0" type="radio" name="paymentMethod" id="stripe" required>
                <label class="form-check-label d-flex align-items-center gap-2" for="stripe">
                    <img src="https://banner2.cleanpng.com/20180419/ogw/avfzzgn3a.webp" alt="Stripe Logo" width="80" height="40">
                </label>
            </div>
        </div>


        <button type="submit" class="btn btn-primary mt-4 col-md-6 mx-auto">Proceed to Payment</button>
    </form>
</div>


<?php
include_once ('footer.php');

?>








