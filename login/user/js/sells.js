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
        load_sell();
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
        load_payment();
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
        user_payment();
    }
}






function load_sell(){
    $.ajax({
        url :'sells_db.php',
        type:'POST',
        dataType:'json',
        data :{
            'action':'load_sell'
        },
        beforeSend:function(){
            $('#sell_data').html('');
        },
        async: false,
        success  :function(data){
            if(data.status==1){
                if ($.fn.DataTable.isDataTable('#sell_table')) {
                    $('#sell_table').DataTable().destroy();
                }
                $("#sell_data").html(data.html);
                $('#sell_table').DataTable({
                    order: []
                });
            }else {
                alert_js(data.msg,'Error Found');
            }
        }
    }).responseText;
}


function load_payment(){
    $.ajax({
        url :'sells_db.php',
        type:'POST',
        dataType:'json',
        data :{
            'action':'load_payment'
        },
        beforeSend:function(){
            $('#payment_summary').html('');
            $('#payment_history').html('');
        },
        async: false,
        success  :function(data){
            if(data.status==1){
                $("#payment_summary").html(data.payment_summary);
                $("#payment_history").html(data.payment_history);
            }else {
                alert_js(data.msg,'Error Found');
            }
        }
    }).responseText;
}

function user_payment(){
    $.ajax({
        url :'sells_db.php',
        type:'POST',
        dataType:'json',
        data :{
            'action':'user_payment'
        },
        beforeSend:function(){
            $('#user_payment').html('');
        },
        async: false,
        success  :function(data){
            if(data.status==1){
                $("#user_payment").html(data.user_payment);
            }else {
                alert_js(data.msg,'Error Found');
            }
        }
    }).responseText;
}

function open_modal(id,type,lim){
    $('#paymentDate').val('');
    $('#amount').val('');
    $('#reAmount').val('');
    $('#note').val('');
    $('#paymentID').val('');

    if(type==1){
        $('#paymentModalLabel').text('INR Payment');
    }else{
        $('#paymentModalLabel').text('USD Payment');
    }

    var today = new Date();
    var sevenDaysAgo = new Date(today);
    sevenDaysAgo.setDate(today.getDate() - 7);

    $('#paymentDate').datepicker({
        format: 'dd-mm-yyyy',
        autoclose: true,
        startDate: sevenDaysAgo,
        endDate: today
    });

    document.getElementById('submit_btn').setAttribute('data-id', id);
    document.getElementById('submit_btn').setAttribute('data-type', type);
    document.getElementById('submit_btn').setAttribute('data-lim', lim);

    $('#paymentModal').modal('show');
}



function submit_payment(){
    var dataId = document.getElementById('submit_btn').getAttribute('data-id');
    var dataType = document.getElementById('submit_btn').getAttribute('data-type');
    var lim = document.getElementById('submit_btn').getAttribute('data-lim');

    var paymentDate=$('#paymentDate').val();
    var amount=$('#amount').val();
    var reAmount=$('#reAmount').val();
    var note=$('#note').val();
    var paymentID=$('#paymentID').val();

    if(amount!=reAmount){
        alert_js('Amount & Reverify must be same.');
        return false;
    }

    if(parseFloat(amount)>parseFloat(lim)){
        alert_js('Amount must be less or equal to '+lim);
        return false;
    }

    if(parseFloat(amount)<=0){
        alert_js('Amount must be greater than 0');
        return false;
    }

    if(paymentDate==''){
        alert_js('Put Payment Date..');
        return false;
    }else if(note==''){
        alert_js('Put Note..');
        return false;
    }else if(paymentID==''){
        alert_js('Put Payment ID..');
        return false;
    }



    $.ajax({
        url :'sells_db.php',
        type:'POST',
        dataType:'json',
        data :{
            'action':'submit_payment',
            'usrid':dataId,
            'type':dataType,
            'date':paymentDate,
            'amount':amount,
            'note':note,
            'payid':paymentID
        },
        beforeSend:function(){

        },
        async: false,
        success  :function(data){
            if(data.status==1){
                user_payment();
                $('#paymentModal').modal('hide');
            }else {
                alert_js(data.msg,'Error Found');
            }
        }
    }).responseText;
}
