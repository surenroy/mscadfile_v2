$(document).ready(function () {
    // Category Carousel Duplication for Seamless Scrolling
    let $carouselWrapper = $('.carousel-wrapper');
    let $items = $('.category-item');
    let numDuplicates = Math.ceil($(window).width() / $items.outerWidth(true));

    for (let i = 0; i < numDuplicates; i++) {
        $items.clone().appendTo($carouselWrapper);
    }

    // Newsletter Subscription Handling
    $('.newsletter-subscribed').on('click', function () {
        let email = $('#newsletter_email').val().trim();

        if (!email) {
            alert_js('Please enter a valid email address.','Error');
            return;
        }

        $.ajax({
            url: "https://mscadfile.com/newsletter",
            type: "POST",
            data: $('#frm_newsletter').serialize(),
            success: function (response) {
                alert_js(response.message || 'Subscription successful!','Success');
                $('#frm_newsletter')[0].reset();
            },
            error: function () {
                alert_js('An error occurred. Please try again.','Error');
            }
        });
    });



    // Load Saved Currency Preferences
    let savedCurrency = localStorage.getItem("selectedCurrency") || "INR(₹)";
    let savedFlag = localStorage.getItem("selectedCurrencyFlag") || "https://flagcdn.com/w40/in.png";
    updateCurrencyDisplay(savedCurrency, savedFlag);

    // Event Listener for Currency Change
    $('.dropdown-menu a').on('click', function () {
        let currency = $(this).text().trim();
        let flagUrl = $(this).find('img').attr('src');
        let typed = $(this).data('type');
        localStorage.setItem('selectedCurrencyType', type);

        changeCurrency(currency, flagUrl, typed);
    });








    // Toggle Google Translate Visibility
    $('#translate-button').on('click', function () {
        $('#google_translate_element').fadeToggle(200);
    });




    loadGoogleTranslate();




    $(".product-main-image").on("mousemove", function (event) {
        let $img = $("#mainImage");
        let offsetX = event.offsetX;
        let offsetY = event.offsetY;
        let width = $(this).width();
        let height = $(this).height();

        let posX = (offsetX / width) * 100;
        let posY = (offsetY / height) * 100;

        $img.css({
            "transform": "scale(3)", /* Zoom Level */
            "transform-origin": `${posX}% ${posY}%`
        });
    });

    $(".product-main-image").on("mouseleave", function () {
        $("#mainImage").css({
            "transform": "scale(1)", /* Reset */
            "transform-origin": "center"
        });
    });

    checkWidth();

    $(window).resize(function () {
        checkWidth();
    });









    // Store image sources in an array
    let imageSources = [];
    $(".thumb-img").each(function () {
        imageSources.push($(this).attr("src"));
    });

    let currentIndex = 0;
    let mainImage = $("#mainImage");

    // Function to update main image
    function updateMainImage(index) {
        currentIndex = index;
        mainImage.attr("src", imageSources[currentIndex]);

        // Update active thumbnail
        $(".thumb-img").removeClass("active");
        $(".thumb-img").eq(currentIndex).addClass("active");
    }

    // Next Image
    $(".next-arrow").on("click", function (e) {
        e.preventDefault();
        let nextIndex = (currentIndex + 1) % imageSources.length; // Loop back to first if at last
        updateMainImage(nextIndex);
    });

    // Previous Image
    $(".prev-arrow").on("click", function (e) {
        e.preventDefault();
        let prevIndex = (currentIndex - 1 + imageSources.length) % imageSources.length; // Loop to last if at first
        updateMainImage(prevIndex);
    });

    // Thumbnail Click Event
    $(".thumb-img").on("click", function () {
        let newIndex = imageSources.indexOf($(this).attr("src"));
        updateMainImage(newIndex);
    });


    currency();
    setTimeout(function() {
        wish_cart();
    }, 2000);
});

function all_search() {
    var search_str = $('#top_search').val();
    if(search_str==''){
        alert_js('Please Add Search Parameter.','Error');
        return false;
    }
    var encodedSearchStr = encodeURIComponent(search_str);

    var finalUrl = `${site_url}search/search.php?search=${encodedSearchStr}`;

    window.open(finalUrl, '_blank');
}

function remove_wish(id){
    $.ajax({
        url :site_url+'login_db.php',
        type:'POST',
        dataType:'json',
        data :{
            'action':'remove_wish',
            'id':id
        },
        beforeSend:function(){
        },
        async: false,
        success  :function(data){
            if(data.status==1){
                loadWishList();
            }else{
                alert_js('Login First','Error');
            }
        }
    }).responseText;
}

function add_wish(id) {
    if ($('.wishlist'+id).hasClass('fa-solid')) {
        var rmv=1;
        $('.wishlist'+id).removeClass('fa-solid').addClass('fa-regular');
    } else {
        var rmv=0;
    }

    $.ajax({
        url :site_url+'login_db.php',
        type:'POST',
        dataType:'json',
        data :{
            'action':'add_wish',
            'id':id,
            'rmv':rmv
        },
        beforeSend:function(){
        },
        async: false,
        success  :function(data){
            if(data.status==1){
                wish_cart();
            }else{
                alert_js('Login First','Error');
            }
        }
    }).responseText;
}


function add_cart(id) {
    $.ajax({
        url :site_url+'login_db.php',
        type:'POST',
        dataType:'json',
        data :{
            'action':'add_cart',
            'id':id
        },
        beforeSend:function(){
        },
        async: false,
        success  :function(data){
            if(data.status==1){
                wish_cart();
            }else{
                alert_js('Login First','Error');
            }
        }
    }).responseText;
}

function remove_cart(id) {
    $.ajax({
        url :site_url+'login_db.php',
        type:'POST',
        dataType:'json',
        data :{
            'action':'remove_cart',
            'id':id
        },
        beforeSend:function(){
        },
        async: false,
        success  :function(data){
            if(data.status==1){
                wish_cart();
            }else{
                alert_js('Login First','Error');
            }
        }
    }).responseText;
}



function wish_cart() {
    $.ajax({
        url :site_url+'login_db.php',
        type:'POST',
        dataType:'json',
        data :{
            'action':'wish_cart'
        },
        beforeSend:function(){
        },
        async: false,
        success  :function(data){
            if(data.status==1){
                localStorage.setItem('wish', JSON.stringify(data.wish));
                localStorage.setItem('cart', JSON.stringify(data.cart));
                $('#wishlist_cnt').html(data.wish_cnt);
                $('#cart_cnt').html(data.cart_cnt);

                $('.wishlist_heart').removeClass('fa-solid').addClass('fa-regular');
                data.wish.forEach(id => {
                    $(`.wishlist${id}`).removeClass('fa-regular').addClass('fa-solid');
                });

                $('.addcart').removeClass('d-none');
                $('.removecart').addClass('d-none');
                data.cart.forEach(id => {
                    $(`.add_cart_${id}`).addClass('d-none');
                    $(`.remove_cart_${id}`).removeClass('d-none');
                });
            }
        }
    }).responseText;
}


function currency(){
    const selectedCurrency = localStorage.getItem("selectedCurrencyType");
    if (selectedCurrency) {
        if(selectedCurrency==1){
            $('.usd_price').addClass('d-none');
            $('.inr_price').removeClass('d-none');
        }else{
            $('.inr_price').addClass('d-none');
            $('.usd_price').removeClass('d-none');
        }

        Cookies.set('user_currency', selectedCurrency);
    } else {
        changeCurrency('INR(₹)', 'https://flagcdn.com/w40/in.png',1);
    }
}

function loadProducts(page, limit) {
    const offset = (page - 1) * limit;

    $.ajax({
        url :site_url+'login_db.php',
        type:'POST',
        dataType:'json',
        data :{
            'action':'loadProducts',
            'limit':limit,
            'offset':offset
        },
        beforeSend:function(){
        },
        async: false,
        success  :function(data){
            if(data.status==1){
                $('#product_list').html(data.html);
                $('.page-item').removeClass('active');
                $('.page'+page).addClass('active');
                currency();
            }
        }
    }).responseText;
}


function loadBanner() {
    $.ajax({
        url :site_url+'login_db.php',
        type:'POST',
        dataType:'json',
        data :{
            'action':'loadBanner'
        },
        beforeSend:function(){
        },
        async: false,
        success  :function(data){
            if(data.status==1){
                $('#banner_div').html(data.html);
            }
        }
    }).responseText;
}


function loadProductsFeatured(page, limit) {
    const offset = (page - 1) * limit;

    $.ajax({
        url :site_url+'login_db.php',
        type:'POST',
        dataType:'json',
        data :{
            'action':'loadProductsFeatured',
            'limit':limit,
            'offset':offset
        },
        beforeSend:function(){
        },
        async: false,
        success  :function(data){
            if(data.status==1){
                $('#product_list').html(data.html);
                $('.page-item').removeClass('active');
                $('.page'+page).addClass('active');
                currency();
            }
        }
    }).responseText;
}

function loadNewProducts(page, limit) {
    const offset = (page - 1) * limit;

    $.ajax({
        url :site_url+'login_db.php',
        type:'POST',
        dataType:'json',
        data :{
            'action':'loadNewProducts',
            'limit':limit,
            'offset':offset
        },
        beforeSend:function(){
        },
        async: false,
        success  :function(data){
            if(data.status==1){
                $('#product_list').html(data.html);
                $('.page-item').removeClass('active');
                $('.page'+page).addClass('active');
                currency();
            }
        }
    }).responseText;
}


function loadSearchProducts(page, limit) {
    const offset = (page - 1) * limit;

    $.ajax({
        url :site_url+'search/search_db.php',
        type:'POST',
        dataType:'json',
        data :{
            'action':'loadSearchProducts',
            'limit':limit,
            'offset':offset
        },
        beforeSend:function(){
        },
        async: false,
        success  :function(data){
            if(data.status==1){
                var cnt=data.cnt;
                $('#product_list').html(data.html);
                $('.page-item').removeClass('active');
                $('.page'+page).addClass('active');
                currency();

                if(cnt==0){
                    $('.pagination_container').addClass('d-none');
                    $('#product_list').html('<h5>No Product Matched With Your Search Parameter.</h5>');
                }
            }
        }
    }).responseText;
}


function loadProductsCategory(page, limit, cat) {
    const offset = (page - 1) * limit;

    $.ajax({
        url :site_url+'login_db.php',
        type:'POST',
        dataType:'json',
        data :{
            'action':'loadProductsCategory',
            'limit':limit,
            'offset':offset,
            'cat':cat
        },
        beforeSend:function(){
        },
        async: false,
        success  :function(data){
            if(data.status==1){
                $('#product_list').html(data.html);
                $('.page-item').removeClass('active');
                $('.page'+page).addClass('active');
                currency();
            }
        }
    }).responseText;
}

function loadProductsSeller(page, limit, seller) {
    const offset = (page - 1) * limit;

    $.ajax({
        url :site_url+'login_db.php',
        type:'POST',
        dataType:'json',
        data :{
            'action':'loadProductsSeller',
            'limit':limit,
            'offset':offset,
            'seller':seller
        },
        beforeSend:function(){
        },
        async: false,
        success  :function(data){
            if(data.status==1){
                $('#product_list').html(data.html);
                $('.page-item').removeClass('active');
                $('.page'+page).addClass('active');
                currency();
            }
        }
    }).responseText;
}

function loadWishList(){
    $.ajax({
        url :site_url+'login_db.php',
        type:'POST',
        dataType:'json',
        data :{
            'action':'loadWishList'
        },
        beforeSend:function(){
            $('#product_show_wish').html('');
        },
        async: false,
        success  :function(data){
            if(data.status==1){
                $('#product_show_wish').html(data.html);
                currency();
            }
        }
    }).responseText;
}

function loadCartList(){
    $.ajax({
        url :site_url+'login_db.php',
        type:'POST',
        dataType:'json',
        data :{
            'action':'loadCartList'
        },
        beforeSend:function(){
            $('#product_show_cart').html('');
        },
        async: false,
        success  :function(data){
            if(data.status==1){
                $('#product_show_cart').html(data.html);
                currency();
            }
        }
    }).responseText;
}


function buy_now(){
    window.location.href = site_url+'cart_final.php';
}

function toggleModal() {
    const modal = document.getElementById("popupForm");
    modal.style.display = (modal.style.display === "block") ? "none" : "block";
}

function sendMessage() {
    var subject=$("#subject").val();
    var message=$("#message").val();

    if (subject==''){
        alert_js('Please Input Your Subject','Alert');
        $('#subject').focus();
        return false;
    }else if (message==''){
        alert_js('Please Input Your Message','Alert');
        $('#message').focus();
        return false;
    }

    $.ajax({
        url :site_url+'login_db.php',
        type:'POST',
        dataType:'json',
        data :{
            'action':'sendMessage',
            'subject':subject,
            'message':message
        },
        beforeSend:function(){
            $('#msg_btn').prop("disabled", true);
        },
        async: false,
        success  :function(data){
            if(data.status==1){
                $("#subject").val('');
                $("#message").val('');
                toggleModal();
                alert_js('Sent Successfully.','Alert');
            }else {
                alert_js(data.msg,'Error Found');
            }
            $('#msg_btn').prop("disabled", false);
        }
    }).responseText;
}

function newsletter() {
    var newsletter_email=$("#newsletter_email").val();

    if (newsletter_email==''){
        alert_js('Please Input Your Email','Alert');
        $('#newsletter_email').focus();
        return false;
    }

    if (!isValidEmail(email)) {
        alert_js('Please Input Valid Email','Alert');
        $('#newsletter_email').focus();
        return false;
    }

    $.ajax({
        url :site_url+'login_db.php',
        type:'POST',
        dataType:'json',
        data :{
            'action':'newsletter',
            'newsletter_email':newsletter_email
        },
        beforeSend:function(){
            $('#newsletter_email_btn').prop("disabled", true);
        },
        async: false,
        success  :function(data){
            if(data.status==1){
                $("#newsletter_email").val('');
                alert_js('Subscribed Successfully.','Alert');
            }else {
                alert_js('Put A Valid Email','Error Found');
            }
            $('#newsletter_email_btn').prop("disabled", false);
        }
    }).responseText;
}


function isValidEmail(email) {
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return regex.test(email);
}

function openInNewTab(url) {
    const newTab = window.open(url, '_blank');
    newTab.focus();
}

function toggleMenu() {
    $("#popupMenu").toggleClass("show");
}





// Currency Selection Handling
function updateCurrencyDisplay(currency, flagUrl) {
    $("#selectedCurrency").text(currency);
    $("#selectedCurrencyFlag").attr("src", flagUrl);
}

function changeCurrency(currency, flagUrl, type) {
    localStorage.setItem("selectedCurrency", currency);
    localStorage.setItem("selectedCurrencyFlag", flagUrl);
    localStorage.setItem("selectedCurrencyType", type);

    if(type==1){
        $('.usd_price').addClass('d-none');
        $('.inr_price').removeClass('d-none');
    }else{
        $('.inr_price').addClass('d-none');
        $('.usd_price').removeClass('d-none');
    }
    Cookies.set('user_currency', type);
    updateCurrencyDisplay(currency, flagUrl);
}

function checkWidth() {
    if ($(window).width() <= 992) {
        $('.store-welcome-mobile').removeClass('d-none');
    } else {
        $('.store-welcome-mobile').addClass('d-none');
    }
}

function googleTranslateElementInit() {
    new google.translate.TranslateElement({
        pageLanguage: 'en',
        includedLanguages: 'en,hi,bn,fr,es,de,zh',
        layout: google.translate.TranslateElement.InlineLayout.SIMPLE,
        autoDisplay: false
    }, 'google_translate_element');

    // Wait for the translate widget to load and modify the text
    setTimeout(function () {
        let selectLangText = document.querySelector('.goog-te-gadget-simple');
        if (selectLangText) {
            selectLangText.innerHTML = '<button id="translate-button"><i class="fa-solid fa-language"></i></button>';
            $("#google_translate_element").css("z-index", "auto");
        }
    }, 500);
}


function loadGoogleTranslate() {
    let gtScript = document.createElement('script');
    gtScript.src = 'https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit';
    gtScript.async = true;
    document.body.appendChild(gtScript);
}


function changeImage(element) {
    document.getElementById("mainImage").src = element.src;
    let thumbnails = document.querySelectorAll(".thumb-img");
    thumbnails.forEach(img => img.classList.remove("active"));
    element.classList.add("active");
}


function alert_js(data, type) {
    $.alert({
        title: type + '!',
        content: data,
    });
}








function openCategoryUrl(element) {
    const url = element.getAttribute('data-url');
    window.open(url, '_blank');
}


