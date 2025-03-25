$(document).ready(function(){
    document.title = "My Profile";
});





function update_data() {
    var name=$("#name").val();
    var mobile=$("#mobile").val();
    var whatsapp=$("#whatsapp").val();
    var pincode=$("#pincode").val();
    var mobile_country=$("#mobile_country").val();
    var upi=$("#upi").val();
    var whatsapp_country=$("#whatsapp_country").val();

    if (name==''){
        alert_js('Please Input Your Name','Alert');
        $('#name').focus();
        return false;
    }else if (mobile==''){
        alert_js('Please Input Your Mobile Number','Alert');
        $('#mobile').focus();
        return false;
    }else if (whatsapp==''){
        alert_js('Please Input Your Whatsapp Number','Alert');
        $('#whatsapp').focus();
        return false;
    }else if (pincode==''){
        alert_js('Please Input Your Pincode','Alert');
        $('#pincode').focus();
        return false;
    }else if (mobile_country==0){
        alert_js('Please Choose Your Country','Alert');
        $('#mobile_country').focus();
        return false;
    }else if (whatsapp_country==0){
        alert_js('Please Choose Your Country','Alert');
        $('#whatsapp_country').focus();
        return false;
    }


    $.ajax({
        url :'profile_db.php',
        type:'POST',
        dataType:'json',
        data :{
            'action':'update_data',
            'name':name,
            'mobile':mobile,
            'whatsapp':whatsapp,
            'pincode':pincode,
            'whatsapp_country':whatsapp_country,
            'upi':upi,
            'mobile_country':mobile_country
        },
        beforeSend:function(){
            $('#submit_btn').prop("disabled", true);
        },
        async: false,
        success  :function(data){
            if(data.status==1){
                location.reload();
                alert_js('Updated Successfully.','Alert');
            }else {
                alert_js(data.msg,'Error Found');
            }
            $('#submit_btn').prop("disabled", false);
        }
    }).responseText;
}


function update_password() {
    var old_pass=$("#old_pass").val();
    var new_pass=$("#new_pass").val();
    var verify_pass=$("#verify_pass").val();

    if (old_pass==''){
        alert_js('Please Input Your Old Password','Alert');
        $('#old_pass').focus();
        return false;
    }else if (new_pass==''){
        alert_js('Please Input Your New Password','Alert');
        $('#new_pass').focus();
        return false;
    }else if (verify_pass=='' || verify_pass!=new_pass){
        alert_js('Please Input Verify password Correctly','Alert');
        $('#verify_pass').focus();
        return false;
    }

    if (new_pass.length<=6){
        alert_js('Password must be over 6 Character','Alert');
        $('#new_pass').focus();
        return false;
    }


    $.ajax({
        url :'profile_db.php',
        type:'POST',
        dataType:'json',
        data :{
            'action':'update_password',
            'old_pass':old_pass,
            'new_pass':new_pass
        },
        beforeSend:function(){
            $('#submit_pass_btn').prop("disabled", true);
        },
        async: false,
        success  :function(data){
            if(data.status==1){
                $("#old_pass").val('');
                $("#new_pass").val('');
                $("#verify_pass").val('');
                alert_js('Updated Successfully.','Alert');
            }else {
                alert_js(data.msg,'Error Found');
            }
            $('#submit_pass_btn').prop("disabled", false);
        }
    }).responseText;
}


