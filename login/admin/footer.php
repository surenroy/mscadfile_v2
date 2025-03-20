<?php

?>












<div style="padding: 28px;"></div>


<nav class="navbar navbar-dark shadow-lg position-fixed bottom-0 w-100 d-lg-none"
     style="background: linear-gradient(90deg, #000000 30%, #ebb120 100%); padding: 8px 0; z-index: 9999;">
    <div class="container d-flex justify-content-around position-relative">

        <a href="<?php echo $site_url; ?>login/admin/product.php" class="nav-link text-white text-center">
            <i class="fa-solid fa-gem fa-lg"></i>
            <div style="font-size: 12px;">Product</div>
        </a>

        <a href="<?php echo $site_url; ?>login/admin/message.php" class="nav-link text-white text-center">
            <i class="fa-solid fa-message fa-lg"></i>
            <div style="font-size: 12px;">Message</div>
        </a>

        <a href="<?php echo $site_url; ?>login/admin/order.php" class="nav-link text-white text-center">
            <i class="fa-solid fa-microchip fa-lg"></i>
            <div style="font-size: 12px;">Order</div>
        </a>

        <a href="<?php echo $site_url; ?>login/admin/sell.php" class="nav-link text-white text-center">
            <i class="fa-solid fa-coins fa-lg"></i>
            <div style="font-size: 12px;">Sell</div>
        </a>

        <a href="<?php echo $site_url; ?>login/admin/job.php" class="nav-link text-white text-center">
            <i class="fa-solid fa-briefcase fa-lg"></i>
            <div style="font-size: 12px;">Jobs</div>
        </a>



<!--        <div class="position-relative">-->
<!--            <button class="nav-link text-white text-center" onclick="toggleMenu()">-->
<!--                <i class="fa fa-bars fa-lg"></i>-->
<!--                <div style="font-size: 12px;">Menu</div>-->
<!--            </button>-->
<!---->
<!--            <div id="popupMenu" class="menu-popup d-flex flex-column justify-content-start align-content-start align-items-start">-->
<!--                <a href="#" class="menu-item"><i class="fa-solid fa-desktop"></i> Dashboard</a>-->
<!--                <a href="#" class="menu-item"><i class="fa fa-sign-out-alt"></i> Logout</a>-->
<!--            </div>-->
<!--        </div>-->
    </div>
</nav>





<script src="<?php echo $site_url; ?>js/bootstrap.bundle.min.js?v=<?php echo $ver; ?>"></script>
<script src="<?php echo $site_url; ?>js/dataTables.js?v=<?php echo $ver; ?>"></script>
<script src="<?php echo $site_url; ?>js/dataTables.bootstrap5.js?v=<?php echo $ver; ?>"></script>
<script src="<?php echo $site_url; ?>js/js.cookie.min.js?v=<?php echo $ver; ?>"></script>
<script src="<?php echo $site_url; ?>js/jquery.filter_input.js?v=<?php echo $ver; ?>"></script>
<script src="<?php echo $site_url; ?>js/jquery-confirm.min.js?v=<?php echo $ver; ?>"></script>


<script>
function alert_js(data, type) {
    $.alert({
        title: type + '!',
        content: data,
    });
}

</script>

</body>

</html>
