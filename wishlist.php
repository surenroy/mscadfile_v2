<?php
include_once ('header.php');

?>










<section class="container py-2" style="min-height: 70vh;">
    <div class="col-12 p-0 m-0 mt-1 mb-2 d-flex flex-row flex-wrap g-2 justify-content-center">



        <table class="table table-bordered table-hover table_center" id="product_table">
            <thead class="table-dark">
            <tr>
                <th>Name</th>
                <th>Image</th>
                <th>Price</th>
                <th>Category</th>
                <th>Remove</th>
            </tr>
            </thead>
            <tbody id="product_show_wish">

            </tbody>
        </table>


    </div>
</section>





<script>
    $(document).ready(function() {
        loadWishList();
    });
</script>

<?php
include_once ('footer.php');

?>








