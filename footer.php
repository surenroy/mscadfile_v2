<?php

?>















<nav class="navbar navbar-dark shadow-lg position-fixed bottom-0 w-100 d-lg-none"
     style="background: linear-gradient(90deg, #000000 30%, #ebb120 100%); padding: 8px 0; z-index: 9999;">
    <div class="container d-flex justify-content-around position-relative">
        <a href="<?php echo $site_url; ?>product.php" class="nav-link text-white text-center">
            <i class="fa-solid fa-gem fa-lg"></i>
            <div style="font-size: 12px;">Product</div>
        </a>

        <a href="<?php echo $site_url; ?>category.php" class="nav-link text-white text-center">
            <i class="fa-solid fa-layer-group fa-lg"></i>
            <div style="font-size: 12px;">Category</div>
        </a>

        <a href="<?php echo $site_url; ?>new_arrivals.php" class="nav-link text-white text-center">
            <i class="fa-solid fa-gift fa-lg"></i>
            <div style="font-size: 12px;">New</div>
        </a>

        <a href="<?php echo $site_url; ?>featured.php" class="nav-link text-white text-center">
            <i class="fa-solid fa-medal fa-lg"></i>
            <div style="font-size: 12px;">Featured</div>
        </a>

        <a href="<?php echo $site_url; ?>blog.php" class="nav-link text-white text-center">
            <i class="fa-solid fa-newspaper fa-lg"></i>
            <div style="font-size: 12px;">Blog</div>
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




<footer id="footer" class="bg-light pt-4">
    <div class="help-box py-3">
        <div class="container-fluid d-flex flex-column flex-md-row justify-content-center align-items-center text-center text-md-start">
            <div class="help-text me-sm-12">Need help? <a class="text-decoration-none text-dark" target="_blank" href="<?php echo $site_url; ?>page/contact.php"><strong>Contact us</strong></a></div>
            <div class="or-box bg-dark text-white px-3 py-1 mx-sm-5 rounded-circle fw-bold">OR</div>
            <div class="help-text ms-sm-12">Mail Us <a href="mailto:info@mscadfile.com" class="text-primary fw-bold text-decoration-none">info@mscadfile.com</a></div>
        </div>
    </div>

    <div class="link-faprt py-3">
        <div class="container-fluid">
            <div class="row col-12 d-flex justify-content-around m-0 p-0 flex-wrap">


                <div class="col-md-6 d-flex justify-content-center flex-wrap">
                    <div class="col-12 d-flex align-items-center mb-3 mb-md-0">
                        <i class="fa fa-envelope fa-lg text-primary me-2"></i>
                        <div class="subscribe-text">
                            Subscribe to <span class="fw-bold">our newsletter!</span>
                        </div>
                    </div>

                    <div class="col-12">
                        <div  class="col-12 d-flex flex-row justify-content-around flex-wrap" class="d-flex w-100">
                            <div class="input-group input-group-sm">
                                <input type="email" class="form-control form-control-sm flex-grow-1 me-2" id="newsletter_email" placeholder="Your email address">
                                <button type="button" class="btn btn-primary btn-sm newsletter-subscribed" id="newsletter_email_btn" onclick="newsletter()">Subscribe</button>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-md-3 col-sm-6 col-12  mt-md-0 mt-3 d-flex justify-content-sm-center justify-content-start">
                    <ul class="list-unstyled">
                        <li>› <a  href="<?php echo $site_url; ?>page/shipping-policy/">Shipping Policy</a></li>
                        <li>› <a  href="<?php echo $site_url; ?>page/terms-and-conditions/">Terms & Conditions</a></li>
                        <li>› <a  href="<?php echo $site_url; ?>page/privacy-policy/">Privacy Policy</a></li>
                        <li>› <a  href="<?php echo $site_url; ?>page/contact-us/">Contact Us</a></li>
                    </ul>
                </div>
                <div class="col-md-3 col-sm-6 col-12  mt-md-0 mt-sm-3 mt-0 d-flex justify-content-sm-center justify-content-start">
                    <ul class="list-unstyled">
                        <li>› <a  href="<?php echo $site_url; ?>page/about-us/">About Us</a></li>
                        <li>› <a  href="<?php echo $site_url; ?>page/return-policy/">Return Policy</a></li>
                        <li>› <a  href="<?php echo $site_url; ?>page/frequently-asked-questions/">FAQ</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Copyright & Payment Methods -->
    <div class="copyright py-3">
        <div class="container-fluid d-flex flex-column flex-md-row align-items-center justify-content-center justify-content-md-between text-center text-md-start">
            <div class="copy-text">&copy; Copyright 2025 MSCAD File</div>
            <div class="payment-icons mt-2 mt-md-0">
                <img loading="lazy" src="<?php echo $site_url; ?>images/pay1.png?v=1" width="30" height="30" class="payment-icon mx-1" alt="Pay1">
                <img loading="lazy" src="<?php echo $site_url; ?>images/pay2.png?v=1" width="30" height="30" class="payment-icon mx-1" alt="Pay2">
                <img loading="lazy" src="<?php echo $site_url; ?>images/pay3.png?v=1" width="30" height="30" class="payment-icon mx-1" alt="Pay3">
                <img loading="lazy" src="<?php echo $site_url; ?>images/pay4.png?v=1" width="30" height="30" class="payment-icon mx-1" alt="Pay4">
                <img loading="lazy" src="<?php echo $site_url; ?>images/pay5.png?v=1" width="30" height="30" class="payment-icon mx-1" alt="Pay5">
                <img loading="lazy" src="<?php echo $site_url; ?>images/pay6.png?v=1" width="30" height="30" class="payment-icon mx-1" alt="Pay6">
            </div>
        </div>
    </div>
</footer>






<div class="modal fade" id="loginModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div id="loginForm">
                    <h6 class="mb-4 mt-2 text-center">User Login</h6>
                    <input type="email" id="login_email" class="form-control form-control-sm w-100 mb-2" placeholder="Email ID" maxlength="40">
                    <input type="password" id="login_password" class="form-control form-control-sm w-100 mb-2" placeholder="Password">

                    <div class="col-12 d-flex justify-content-between">
                        <button class="btn btn-primary btn-sm col-sm-5" id="login_btn" onclick="login_user()">Login</button>
                        <button type="button" class="btn btn-sm btn-dark col-sm-5" data-bs-dismiss="modal">Close</button>
                    </div>

                    <div class="mt-3 text-center">
                        <a href="#" id="showForgot" class="text-decoration-none">Forgot Password?</a> |
                        <a href="#" id="showRegister" class="text-decoration-none">Register</a>
                    </div>
                </div>

                <div id="forgotForm" class="d-none">
                    <h6 class="mb-4 mt-2 text-center">Forget Password</h6>
                    <input type="email" class="form-control form-control-sm w-100 mb-2" placeholder="Email ID" id="forget_email" maxlength="40">
                    <button class="btn btn-secondary btn-sm w-100 mb-2 send-otp" id="forgotOtp" onclick="forget_otp()">Send OTP</button>


                    <input type="text" class="form-control form-control-sm w-100 mb-2 d-none forget_pass_inp" placeholder="OTP" id="forget_otp">
                    <input type="text" class="form-control form-control-sm w-100 mb-2 d-none forget_pass_inp" placeholder="New Password" id="forget_password">
                    <input type="password" class="form-control form-control-sm w-100 mb-2 d-none forget_pass_inp" placeholder="Verify Password" id="forget_password_verify">
                    <div class="col-12 d-flex justify-content-between d-none forget_pass_inp">
                        <button class="btn btn-primary btn-sm col-sm-5" id="forget_btn" onclick="forget_password()">Update</button>
                        <button type="button" class="btn btn-sm btn-dark col-sm-5" data-bs-dismiss="modal">Close</button>
                    </div>


                    <div class="mt-3 text-center">
                        <a href="#" id="backToLogin1" class="text-decoration-none">Back to Login</a>
                    </div>
                </div>

                <div id="registerForm" class="d-none">
                    <h6 class="mb-4 mt-2 text-center">Registration</h6>
                    <input type="text" class="form-control form-control-sm w-100 mb-2" placeholder="User Name" id="register_name" maxlength="30">
                    <input type="email" class="form-control form-control-sm w-100 mb-2" placeholder="Email ID" id="register_email" maxlength="40">
                    <button class="btn btn-secondary btn-sm w-100 mb-2 send-otp" id="registerOtp" onclick="register_otp()">Send OTP</button>

                    <?php
                        $sql="SELECT `id`,`country_nsme`,`code` FROM `country`";
                        $query = $pdoconn->prepare($sql);
                        $query->execute();
                        $my_countryarr = $query->fetchAll(PDO::FETCH_ASSOC);
                    ?>


                    <input type="number" class="form-control form-control-sm w-100 mb-2 register_inp d-none" placeholder="Email OTP" id="register_otp">

                    <div class="input-group mb-2 register_inp d-none">
                        <select class="form-control form-control-sm w-25" id="mobile_country">
                            <option value="0" disabled selected>Select Country</option>
                            <?php foreach ($my_countryarr as $country): ?>
                                <option value="<?php echo $country['id']; ?>">
                                    <?php echo $country['country_nsme'].' ('.$country['code'].')'; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <input type="number" class="form-control form-control-sm w-75" placeholder="Mobile Number" id="register_mobile">
                    </div>


                    <div class="input-group mb-2 register_inp d-none">
                        <select class="form-control form-control-sm w-25" id="whatsapp_country">
                            <option value="0" disabled selected>Select Country</option>
                            <?php foreach ($my_countryarr as $country): ?>
                                <option value="<?php echo $country['id']; ?>">
                                    <?php echo $country['country_nsme'].' ('.$country['code'].')'; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <input type="number" class="form-control form-control-sm w-75" placeholder="Whatsapp Number" id="register_whatsapp">
                    </div>
                    <input type="text" class="form-control form-control-sm w-100 mb-2 register_inp d-none" placeholder="New Password" maxlength="20" id="register_password">
                    <input type="password" class="form-control form-control-sm w-100 mb-2 register_inp d-none" placeholder="Verify Password" maxlength="20" id="register_password_verify">
                    <div class="form-check register_inp d-none">
                        <input class="form-check-input" type="checkbox" value="" id="register_seller">
                        <label class="form-check-label" for="register_seller">
                            Register as Seller?
                        </label>
                    </div>
                    <div class="col-12 d-flex justify-content-between mt-3 register_inp d-none">
                        <button class="btn btn-primary btn-sm col-sm-5" onclick="register_user()" id="register_btn">Register</button>
                        <button type="button" class="btn btn-sm btn-dark col-sm-5" data-bs-dismiss="modal">Close</button>
                    </div>

                    <div class="mt-2 text-center">
                        <a href="#" id="backToLogin2" class="text-decoration-none">Back to Login</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>




<script src="<?php echo $site_url; ?>js/bootstrap.bundle.min.js?v=<?php echo $ver; ?>"></script>
<script src="<?php echo $site_url; ?>js/index.js?v=<?php echo $ver; ?>" defer></script>
<script src="<?php echo $site_url; ?>js/login.js?v=<?php echo $ver; ?>" defer></script>

<script src="<?php echo $site_url; ?>js/js.cookie.min.js?v=<?php echo $ver; ?>"></script>
<script src="<?php echo $site_url; ?>js/jquery.filter_input.js?v=<?php echo $ver; ?>" defer></script>
<script src="<?php echo $site_url; ?>js/jquery-confirm.min.js?v=<?php echo $ver; ?>" defer></script>


</body>

</html>
