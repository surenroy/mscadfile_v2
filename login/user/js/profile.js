$(document).ready(function(){
    load_data();
});


function load_data(){
    $.ajax({
        url :'profile_db.php',
        type:'POST',
        dataType:'json',
        data :{
            'action':'load_data'
        },
        beforeSend:function(){
            $('#name').val('');
            $('#email').val('');
            $('#mobile').val('');
            $('#whatsapp').val('');
            $('#pincode').val('');
            $('#currency').val(0);
            $('#upi').val('');
            $('#country').val(0);
        },
        async: false,
        success  :function(data){
            if(data.status==1){
                $('#name').val(data.name);
                $('#email').val(data.email);
                $('#mobile').val(data.mobile);
                $('#whatsapp').val(data.whatsapp);
                $('#pincode').val(data.pincode);
                $('#currency').val(data.currency);
                $('#upi').val(data.upi);
                $('#country').val(data.country);
            }else {
                alert_js(data.msg,'Error Found');
            }
        }
    }).responseText;



}




function update_data() {
    var name=$("#name").val();
    var mobile=$("#mobile").val();
    var whatsapp=$("#whatsapp").val();
    var pincode=$("#pincode").val();
    var currency=$("#currency").val();
    var upi=$("#upi").val();
    var country=$("#country").val();

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
    }else if (currency==''){
        alert_js('Please Choose Your Currency','Alert');
        $('#currency').focus();
        return false;
    }else if (country==''){
        alert_js('Please Choose Your Country','Alert');
        $('#country').focus();
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
            'currency':currency,
            'upi':upi,
            'country':country
        },
        beforeSend:function(){
            $('#submit_btn').prop("disabled", true);
        },
        async: false,
        success  :function(data){
            if(data.status==1){
                load_data();
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


