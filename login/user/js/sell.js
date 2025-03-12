$(document).ready(function(){
    showDiv(1);
});

function showDiv(id) {
    if(id==1){
        $('#newProductDiv').removeClass('d-none');
        $('.btn1').addClass('btn-primary');
        $('.btn2').addClass('btn-outline-primary');
        $('.btn1').removeClass('btn-outline-primary');
        $('.btn2').removeClass('btn-primary');
        $('#productListDiv').addClass('d-none');
        load_sell();
    }else{
        $('#newProductDiv').addClass('d-none');
        $('#productListDiv').removeClass('d-none');
        $('.btn2').addClass('btn-primary');
        $('.btn1').addClass('btn-outline-primary');
        $('.btn2').removeClass('btn-outline-primary');
        $('.btn1').removeClass('btn-primary');
        load_payment();
    }
}


function load_sell(){
    $.ajax({
        url :'sell_db.php',
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
        url :'sell_db.php',
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
