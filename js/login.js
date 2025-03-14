$(document).ready(function(){
    $('#showForgot').click(function(){
        $('#loginForm').addClass('d-none');
        $('#forgotForm').removeClass('d-none');
    });

    $('#showRegister').click(function(){
        $('#loginForm').addClass('d-none');
        $('#registerForm').removeClass('d-none');
    });

    $('#backToLogin1, #backToLogin2').click(function(){
        $('#forgotForm, #registerForm').addClass('d-none');
        $('#loginForm').removeClass('d-none');
    });

});


function isValidEmail(email) {
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailPattern.test(email);
}



function login_user(){
    var login_password=$("#login_password").val();
    var login_email=$("#login_email").val();

    if (login_email==''){
        alert_js('Please Input Your Email ID','Alert');
        $('#login_email').focus();
        return false;
    }else if (login_password==''){
        alert_js('Please Input Password','Alert');
        $('#login_password').focus();
        return false;
    }

    $.ajax({
        url :site_url+'login_db.php',
        type:'POST',
        dataType:'json',
        data :{
            'action':'login_user',
            'login_password':login_password,
            'login_email':login_email
        },
        beforeSend:function(){
            $('#login_btn').prop("disabled", true);
        },
        async: false,
        success  :function(data){
            if(data.status==1){
               location.reload();
            }else {
                alert_js(data.msg,'Error Found');
                $('#login_btn').prop('disabled', false);
            }
        }
    }).responseText;
}


function register_otp(){
    var register_email=$("#register_email").val();
    var register_name=$("#register_name").val();

    if (register_email==''){
        alert_js('Please Input Your Email ID','Alert');
        $('#register_email').focus();
        return false;
    }else if (register_name==''){
        alert_js('Please Input Your name','Alert');
        $('#register_name').focus();
        return false;
    }

    var button = $('#registerOtp');
    button.prop('disabled', true);
    if (isValidEmail(register_email)) {
        $.ajax({
            url :site_url+'login_db.php',
            type:'POST',
            dataType:'json',
            data :{
                'action':'register_otp',
                'register_email':register_email
            },
            beforeSend:function(){
                $('#register_email').prop("disabled", true);
                $('#register_name').prop("disabled", true);
            },
            async: false,
            success  :function(data){
                if(data.status==1){
                    var counter = 120;
                    button.text('OTP Sent. Resend in ' + counter + 's');
                    var interval = setInterval(function(){
                        counter--;
                        button.text('OTP Sent. Resend in ' + counter + 's');
                        if (counter <= 0) {
                            clearInterval(interval);
                            button.prop('disabled', false);
                            button.text('Send OTP');
                        }
                    }, 1000);
                }else {
                    alert_js(data.msg,'Error Found');
                }
            }
        }).responseText;
    } else {
        alert_js('Please Input Valid Email ID','Alert');
        $('#register_email').focus();
        return false;
    }
}

function register_user(){
    var register_email=$("#register_email").val();
    var register_name=$("#register_name").val();
    var register_otp=$("#register_otp").val();
    var register_mobile=$("#register_mobile").val();
    var register_whatsapp=$("#register_whatsapp").val();
    var register_password=$("#register_password").val();
    var register_password_verify=$("#register_password_verify").val();

    if (register_email==''){
        alert_js('Please Input Your Email ID','Alert');
        $('#register_email').focus();
        return false;
    }else if (register_name==''){
        alert_js('Please Input Your name','Alert');
        $('#register_name').focus();
        return false;
    }else if (register_otp==''){
        alert_js('Please Input Email OTP','Alert');
        $('#register_otp').focus();
        return false;
    }else if (register_mobile==''){
        alert_js('Please Input Your Mobile Number','Alert');
        $('#register_mobile').focus();
        return false;
    }else if (register_whatsapp==''){
        alert_js('Please Input Your Whatsapp Number','Alert');
        $('#register_whatsapp').focus();
        return false;
    }else if (register_password==''){
        alert_js('Please Input Your Password','Alert');
        $('#register_password').focus();
        return false;
    }else if (register_password_verify==''){
        alert_js('Please Input Your Password Again','Alert');
        $('#register_password_verify').focus();
        return false;
    }

    if (register_password!=register_password_verify){
        alert_js('Please Input Your Password Again','Alert');
        $('#register_password_verify').focus();
        return false;
    }


    var register_seller= 0;
    if ($('#register_seller').is(':checked')) {
        register_seller = 1;
    }

    if(register_password.length<=6){
        alert_js('Password Must be more than 6 Character Long.','Alert');
        $('#register_password').focus();
        return false;
    }

    $('#register_btn').prop("disabled", true);

    $.ajax({
        url :site_url+'login_db.php',
        type:'POST',
        dataType:'json',
        data :{
            'action':'register_user',
            'register_email':register_email,
            'register_name':register_name,
            'register_otp':register_otp,
            'register_mobile':register_mobile,
            'register_whatsapp':register_whatsapp,
            'register_password':register_password,
            'register_seller':register_seller
        },
        beforeSend:function(){

        },
        async: false,
        success  :function(data){
            if(data.status==1){
                $('#loginModal').modal('hide');
                alert_js('Registered Successfully.','Alert');
                location.reload();
            }else {
                alert_js(data.msg,'Error Found');
                $('#register_btn').prop("disabled", false);
            }
        }
    }).responseText;
}




function forget_otp(){
    var forget_email=$("#forget_email").val();

    if (forget_email==''){
        alert_js('Please Input Your Email ID','Alert');
        $('#forget_email').focus();
        return false;
    }

    var button = $('#forgotOtp');
    button.prop('disabled', true);

    if (isValidEmail(forget_email)) {
        $.ajax({
            url :site_url+'login_db.php',
            type:'POST',
            dataType:'json',
            data :{
                'action':'forget_otp',
                'forget_email':forget_email
            },
            beforeSend:function(){
                $('#forget_email').prop("disabled", true);
            },
            async: false,
            success  :function(data){
                if(data.status==1){
                    var counter = 120;
                    button.text('OTP Sent. Resend in ' + counter + 's');
                    var interval = setInterval(function(){
                        counter--;
                        button.text('OTP Sent. Resend in ' + counter + 's');
                        if (counter <= 0) {
                            clearInterval(interval);
                            button.prop('disabled', false);
                            button.text('Send OTP');
                        }
                    }, 1000);
                }else {
                    alert_js('Error in OTP Sending Process.','Error Found');
                    $('#forget_email').prop("disabled", false);
                }
            }
        }).responseText;
    } else {
        alert_js('Please Input Valid Email ID','Alert');
        $('#forget_email').focus();
        return false;
    }
}


function forget_password(){
    var forget_email=$("#forget_email").val();
    var forget_otp=$("#forget_otp").val();
    var forget_password=$("#forget_password").val();
    var forget_password_verify=$("#forget_password_verify").val();

    if (forget_otp==''){
        alert_js('Please Input Email OTP','Alert');
        $('#forget_otp').focus();
        return false;
    }else if (forget_password==''){
        alert_js('Please Input Your Password','Alert');
        $('#forget_password').focus();
        return false;
    }else if (forget_password_verify==''){
        alert_js('Please Input Your Password Again','Alert');
        $('#forget_password_verify').focus();
        return false;
    }

    if (forget_password!=forget_password_verify){
        alert_js('Please Input Your Password Again','Alert');
        $('#forget_password_verify').focus();
        return false;
    }


    if(forget_password.length<=6){
        alert_js('Password Must be more than 6 Character Long.','Alert');
        $('#forget_password').focus();
        return false;
    }


    $.ajax({
        url :site_url+'login_db.php',
        type:'POST',
        dataType:'json',
        data :{
            'action':'forget_password',
            'forget_email':forget_email,
            'forget_otp':forget_otp,
            'forget_password':forget_password,
            'forget_password_verify':forget_password_verify
        },
        beforeSend:function(){
            $('#forget_btn').prop("disabled", true);
        },
        async: false,
        success  :function(data){
            if(data.status==1){
                $('#loginModal').modal('hide');
                alert_js('Updated Successfully.','Alert');
                location.reload();
            }else {
                alert_js(data.msg,'Error Found');
                $('#forget_btn').prop("disabled", false);
            }
        }
    }).responseText;
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
                toggleModal();
                $("#subject").val('');
                $("#message").val('');
                alert_js('Sent Successfully.','Alert');
            }else {
                alert_js(data.msg,'Error Found');
                $('#msg_btn').prop("disabled", false);
            }
        }
    }).responseText;
}


