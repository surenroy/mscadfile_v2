$(document).ready(function(){
    load_data();
});


function load_data(){
    $.ajax({
        url :'users_db.php',
        type:'POST',
        dataType:'json',
        data :{
            'action':'load_data'
        },
        beforeSend:function(){
            $('#users_data').html('');
        },
        async: false,
        success  :function(data){
            if(data.status==1){
                if ($.fn.DataTable.isDataTable('#table_users')) {
                    $('#table_users').DataTable().destroy();
                }
                $("#users_data").html(data.html);
                $('#table_users').DataTable({
                    order: []
                });
            }else {
                alert_js(data.msg,'Error Found');
            }
        }
    }).responseText;



}



function status_change(del,id){
    $.ajax({
        url :'users_db.php',
        type:'POST',
        dataType:'json',
        data :{
            'action':'status_change',
            'id':id,
            'del':del
        },
        beforeSend:function(){

        },
        async: false,
        success  :function(data){
            if(data.status==1){
                load_data();
            }else {
                alert_js(data.msg,'Error Found');
            }
        }
    }).responseText;
}
