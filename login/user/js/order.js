$(document).ready(function () {
    $('.keyword').filter_input({regex: '[A-Za-z0-9, ]'});

    $('.atz').filter_input({regex: '[A-Za-z ]'});
    $('.atnp').filter_input({regex: '[A-Za-z0-9(),/ -]'});
    $('.ztn').filter_input({regex: '[0-9]'});
    $('.ztnd').filter_input({regex: '[0-9.]'});
    $('.atn').filter_input({regex: '[A-Za-z0-9]'});

    load_order();
});



function load_order(){
    $.ajax({
        url :'order_db.php',
        type:'POST',
        dataType:'json',
        data :{
            'action':'load_order'
        },
        beforeSend:function(){
            $('#order_data').html('');
        },
        async: false,
        success  :function(data){
            if(data.status==1){
                if ($.fn.DataTable.isDataTable('#order_table')) {
                    $('#order_table').DataTable().destroy();
                }
                $("#order_data").html(data.html);
                $('#order_table').DataTable({
                    order: []
                });
            }else {
                alert_js(data.msg,'Error Found');
            }
        }
    }).responseText;
}


function load_files(id){
    $.ajax({
        url :'order_db.php',
        type:'POST',
        dataType:'json',
        data :{
            'action':'load_files',
            'id':id
        },
        beforeSend:function(){
            $('#modal_body_product').html('');
        },
        async: false,
        success  :function(data){
            if(data.status==1){
                $('#modal_body_product').html(data.html);
                $('#productModal').modal('show');
            }else {
                alert_js('Some Error Found..','Error Found');
            }
        }
    }).responseText;
}
