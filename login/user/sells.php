<?php
include_once ('header.php');

?>




<div class="container-fluid mb-3 mt-3 col-12 d-flex justify-content-center flex-row flex-wrap">
    <button class="btn btn-primary mx-2 mt-2 mt-sm-0 col-sm-3 btn1 top_page_button" onclick="showDiv(1)">Sell</button>
    <button class="btn btn-outline-primary mx-2 mt-2 mt-sm-0 col-sm-3 btn2 top_page_button" onclick="showDiv(2)">Payment</button>
    <button class="btn btn-outline-primary mx-2 mt-2 mt-sm-0 col-sm-3 btn3 top_page_button" onclick="showDiv(3)">Balance</button>
</div>

<div id="newProductDiv" class="full-screen d-flex text-white d-none px-2 col-12 justify-content-between flex-wrap">
    <div class="container-fluid pt-4 pb-4 text-dark rounded">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                <tr>
                    <th>Date & Time</th>
                    <th>Order ID</th>
                    <th>Product</th>
                    <th>Buyer</th>
                    <th>INR</th>
                    <th>USD</th>
                </tr>
                </thead>
                <tbody>

                <tr>
                    <td>14-02-2025 15:27</td>
                    <td><button class="btn btn-sm btn-dark mx-1 p-1 px-2"><i class="fa-solid fa-eye"></i></button> Order 123</td>
                    <td><a href="" class="btn btn-sm btn-dark mx-1 p-1 px-2"><i class="fa-solid fa-globe"></i></a>Ganesha</td>
                    <td>#40</td>
                    <td>30.55</td>
                    <td></td>
                </tr>
                <tr>
                    <td>14-02-2025 15:27</td>
                    <td><button class="btn btn-sm btn-dark mx-1 p-1 px-2"><i class="fa-solid fa-eye"></i></button> Order 123</td>
                    <td><a href="" class="btn btn-sm btn-dark mx-1 p-1 px-2"><i class="fa-solid fa-globe"></i></a>Ganesha</td>
                    <td>#40</td>
                    <td></td>
                    <td>40.99</td>
                </tr>
                <tr>
                    <td>14-02-2025 15:27</td>
                    <td><button class="btn btn-sm btn-dark mx-1 p-1 px-2"><i class="fa-solid fa-eye"></i></button> Order 123</td>
                    <td><a href="" class="btn btn-sm btn-dark mx-1 p-1 px-2"><i class="fa-solid fa-globe"></i></a>Ganesha</td>
                    <td>#40</td>
                    <td>30.55</td>
                    <td></td>
                </tr>
                <tr>
                    <td>14-02-2025 15:27</td>
                    <td><button class="btn btn-sm btn-dark mx-1 p-1 px-2"><i class="fa-solid fa-eye"></i></button> Order 123</td>
                    <td><a href="" class="btn btn-sm btn-dark mx-1 p-1 px-2"><i class="fa-solid fa-globe"></i></a>Ganesha</td>
                    <td>#40</td>
                    <td></td>
                    <td>40.99</td>
                </tr>
                <tr>
                    <td>14-02-2025 15:27</td>
                    <td><button class="btn btn-sm btn-dark mx-1 p-1 px-2"><i class="fa-solid fa-eye"></i></button> Order 123</td>
                    <td><a href="" class="btn btn-sm btn-dark mx-1 p-1 px-2"><i class="fa-solid fa-globe"></i></a>Ganesha</td>
                    <td>#40</td>
                    <td>30.55</td>
                    <td></td>
                </tr>
                <tr>
                    <td>14-02-2025 15:27</td>
                    <td><button class="btn btn-sm btn-dark mx-1 p-1 px-2"><i class="fa-solid fa-eye"></i></button> Order 123</td>
                    <td><a href="" class="btn btn-sm btn-dark mx-1 p-1 px-2"><i class="fa-solid fa-globe"></i></a>Ganesha</td>
                    <td>#40</td>
                    <td></td>
                    <td>40.99</td>
                </tr>
                <tr>
                    <td>14-02-2025 15:27</td>
                    <td><button class="btn btn-sm btn-dark mx-1 p-1 px-2"><i class="fa-solid fa-eye"></i></button> Order 123</td>
                    <td><a href="" class="btn btn-sm btn-dark mx-1 p-1 px-2"><i class="fa-solid fa-globe"></i></a>Ganesha</td>
                    <td>#40</td>
                    <td>30.55</td>
                    <td></td>
                </tr>
                <tr>
                    <td>14-02-2025 15:27</td>
                    <td><button class="btn btn-sm btn-dark mx-1 p-1 px-2"><i class="fa-solid fa-eye"></i></button> Order 123</td>
                    <td><a href="" class="btn btn-sm btn-dark mx-1 p-1 px-2"><i class="fa-solid fa-globe"></i></a>Ganesha</td>
                    <td>#40</td>
                    <td></td>
                    <td>40.99</td>
                </tr>


                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="productListDiv" class="full-screen d-flex text-white d-none px-2 col-12 justify-content-between flex-wrap">
    <div class="container-fluid pt-4 text-dark rounded">
        <div class="table-responsive">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
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
                    <tbody>
                    <tr>
                        <td>70.99</td>
                        <td>60.45</td>
                        <td>10.33</td>
                        <td>8.55</td>
                        <td>60.99</td>
                        <td>50.33</td>
                        <td>40.33</td>
                        <td>35.44</td>
                        <td>10.55</td>
                        <td>5.66</td>
                    </tr>


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
                        <th>Date & Time</th>
                        <th>User</th>
                        <th>Payment ID</th>
                        <th>File</th>
                        <th>INR</th>
                        <th>USD</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>14-02-2025 15:27</td>
                        <td>#50</td>
                        <td><button class="btn btn-sm btn-dark mx-1 p-1 px-2"><i class="fa-solid fa-eye"></i></button> Pay 123</td>
                        <td><a href="" class="btn btn-sm btn-dark mx-1 p-1 px-2"><i class="fa-solid fa-download"></i></a></td>
                        <td></td>
                        <td>59.99</td>
                    </tr>
                    <tr>
                        <td>14-02-2025 15:27</td>
                        <td>#30</td>
                        <td><button class="btn btn-sm btn-dark mx-1 p-1 px-2"><i class="fa-solid fa-eye"></i></button> Pay 123</td>
                        <td><a href="" class="btn btn-sm btn-dark mx-1 p-1 px-2"><i class="fa-solid fa-download"></i></a></td>
                        <td>24.99</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>14-02-2025 15:27</td>
                        <td>#50</td>
                        <td><button class="btn btn-sm btn-dark mx-1 p-1 px-2"><i class="fa-solid fa-eye"></i></button> Pay 123</td>
                        <td><a href="" class="btn btn-sm btn-dark mx-1 p-1 px-2"><i class="fa-solid fa-download"></i></a></td>
                        <td></td>
                        <td>59.99</td>
                    </tr>
                    <tr>
                        <td>14-02-2025 15:27</td>
                        <td>#30</td>
                        <td><button class="btn btn-sm btn-dark mx-1 p-1 px-2"><i class="fa-solid fa-eye"></i></button> Pay 123</td>
                        <td><a href="" class="btn btn-sm btn-dark mx-1 p-1 px-2"><i class="fa-solid fa-download"></i></a></td>
                        <td>24.99</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>14-02-2025 15:27</td>
                        <td>#50</td>
                        <td><button class="btn btn-sm btn-dark mx-1 p-1 px-2"><i class="fa-solid fa-eye"></i></button> Pay 123</td>
                        <td><a href="" class="btn btn-sm btn-dark mx-1 p-1 px-2"><i class="fa-solid fa-download"></i></a></td>
                        <td></td>
                        <td>59.99</td>
                    </tr>
                    <tr>
                        <td>14-02-2025 15:27</td>
                        <td>#30</td>
                        <td><button class="btn btn-sm btn-dark mx-1 p-1 px-2"><i class="fa-solid fa-eye"></i></button> Pay 123</td>
                        <td><a href="" class="btn btn-sm btn-dark mx-1 p-1 px-2"><i class="fa-solid fa-download"></i></a></td>
                        <td>24.99</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>14-02-2025 15:27</td>
                        <td>#50</td>
                        <td><button class="btn btn-sm btn-dark mx-1 p-1 px-2"><i class="fa-solid fa-eye"></i></button> Pay 123</td>
                        <td><a href="" class="btn btn-sm btn-dark mx-1 p-1 px-2"><i class="fa-solid fa-download"></i></a></td>
                        <td></td>
                        <td>59.99</td>
                    </tr>
                    <tr>
                        <td>14-02-2025 15:27</td>
                        <td>#30</td>
                        <td><button class="btn btn-sm btn-dark mx-1 p-1 px-2"><i class="fa-solid fa-eye"></i></button> Pay 123</td>
                        <td><a href="" class="btn btn-sm btn-dark mx-1 p-1 px-2"><i class="fa-solid fa-download"></i></a></td>
                        <td>24.99</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>14-02-2025 15:27</td>
                        <td>#50</td>
                        <td><button class="btn btn-sm btn-dark mx-1 p-1 px-2"><i class="fa-solid fa-eye"></i></button> Pay 123</td>
                        <td><a href="" class="btn btn-sm btn-dark mx-1 p-1 px-2"><i class="fa-solid fa-download"></i></a></td>
                        <td></td>
                        <td>59.99</td>
                    </tr>
                    <tr>
                        <td>14-02-2025 15:27</td>
                        <td>#30</td>
                        <td><button class="btn btn-sm btn-dark mx-1 p-1 px-2"><i class="fa-solid fa-eye"></i></button> Pay 123</td>
                        <td><a href="" class="btn btn-sm btn-dark mx-1 p-1 px-2"><i class="fa-solid fa-download"></i></a></td>
                        <td>24.99</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>14-02-2025 15:27</td>
                        <td>#50</td>
                        <td><button class="btn btn-sm btn-dark mx-1 p-1 px-2"><i class="fa-solid fa-eye"></i></button> Pay 123</td>
                        <td><a href="" class="btn btn-sm btn-dark mx-1 p-1 px-2"><i class="fa-solid fa-download"></i></a></td>
                        <td></td>
                        <td>59.99</td>
                    </tr>
                    <tr>
                        <td>14-02-2025 15:27</td>
                        <td>#30</td>
                        <td><button class="btn btn-sm btn-dark mx-1 p-1 px-2"><i class="fa-solid fa-eye"></i></button> Pay 123</td>
                        <td><a href="" class="btn btn-sm btn-dark mx-1 p-1 px-2"><i class="fa-solid fa-download"></i></a></td>
                        <td>24.99</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>14-02-2025 15:27</td>
                        <td>#50</td>
                        <td><button class="btn btn-sm btn-dark mx-1 p-1 px-2"><i class="fa-solid fa-eye"></i></button> Pay 123</td>
                        <td><a href="" class="btn btn-sm btn-dark mx-1 p-1 px-2"><i class="fa-solid fa-download"></i></a></td>
                        <td></td>
                        <td>59.99</td>
                    </tr>
                    <tr>
                        <td>14-02-2025 15:27</td>
                        <td>#30</td>
                        <td><button class="btn btn-sm btn-dark mx-1 p-1 px-2"><i class="fa-solid fa-eye"></i></button> Pay 123</td>
                        <td><a href="" class="btn btn-sm btn-dark mx-1 p-1 px-2"><i class="fa-solid fa-download"></i></a></td>
                        <td>24.99</td>
                        <td></td>
                    </tr>


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
                        <th>User</th>
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
                    <tbody>
                    <tr>
                        <td>User 1</td>
                        <td>#50</td>
                        <td>70.99</td>
                        <td>60.45</td>
                        <td>10.33</td>
                        <td>8.55</td>
                        <td>60.99</td>
                        <td>50.33</td>
                        <td>40.33</td>
                        <td>35.44</td>
                        <td>10.55</td>
                        <td>5.66</td>
                        <td>
                            <button class="btn btn-sm btn-dark mx-1 p-1 px-2"><i class="fa-solid fa-indian-rupee-sign"></i></button>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-dark mx-1 p-1 px-2"><i class="fa-solid fa-usd"></i></button>
                        </td>
                    </tr>


                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<script>
    $(document).ready(function(){
        showDiv(1);
    });

    function showDiv(id) {
        if(id==1){
            $('#newProductDiv').removeClass('d-none');
            $('#productListDiv').addClass('d-none');
            $('#userListDiv').addClass('d-none');
            $('.btn1').addClass('btn-primary');
            $('.btn1').removeClass('btn-outline-primary');
            $('.btn2').addClass('btn-outline-primary');
            $('.btn2').removeClass('btn-primary');
            $('.btn3').addClass('btn-outline-primary');
            $('.btn3').removeClass('btn-primary');
        }else if(id==2){
            $('#newProductDiv').addClass('d-none');
            $('#productListDiv').removeClass('d-none');
            $('#userListDiv').addClass('d-none');
            $('.btn2').addClass('btn-primary');
            $('.btn2').removeClass('btn-outline-primary');
            $('.btn1').addClass('btn-outline-primary');
            $('.btn1').removeClass('btn-primary');
            $('.btn3').addClass('btn-outline-primary');
            $('.btn3').removeClass('btn-primary');
        }else{
            $('#newProductDiv').addClass('d-none');
            $('#productListDiv').addClass('d-none');
            $('#userListDiv').removeClass('d-none');
            $('.btn3').addClass('btn-primary');
            $('.btn3').removeClass('btn-outline-primary');
            $('.btn2').addClass('btn-outline-primary');
            $('.btn2').removeClass('btn-primary');
            $('.btn1').addClass('btn-outline-primary');
            $('.btn1').removeClass('btn-primary');
        }
    }


</script>







<?php
include_once ('footer.php');

?>








