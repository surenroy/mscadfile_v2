$(document).ready(function(){
    showDiv(1);
});

function showDiv(id) {
    if(id==1){
        $('#newMessage').removeClass('d-none');
        $('#inboxMessage').addClass('d-none');
        $('#sentMessage').addClass('d-none');
        $('.btn1').addClass('btn-primary');
        $('.btn1').removeClass('btn-outline-primary');
        $('.btn2').addClass('btn-outline-primary');
        $('.btn2').removeClass('btn-primary');
        $('.btn3').addClass('btn-outline-primary');
        $('.btn3').removeClass('btn-primary');
    }else if(id==2){
        $('#newMessage').addClass('d-none');
        $('#inboxMessage').removeClass('d-none');
        $('#sentMessage').addClass('d-none');
        $('.btn2').addClass('btn-primary');
        $('.btn2').removeClass('btn-outline-primary');
        $('.btn1').addClass('btn-outline-primary');
        $('.btn1').removeClass('btn-primary');
        $('.btn3').addClass('btn-outline-primary');
        $('.btn3').removeClass('btn-primary');

        load_inbox();
    }else{
        $('#newMessage').addClass('d-none');
        $('#inboxMessage').addClass('d-none');
        $('#sentMessage').removeClass('d-none');
        $('.btn3').addClass('btn-primary');
        $('.btn3').removeClass('btn-outline-primary');
        $('.btn2').addClass('btn-outline-primary');
        $('.btn2').removeClass('btn-primary');
        $('.btn1').addClass('btn-outline-primary');
        $('.btn1').removeClass('btn-primary');

        load_outbox();
    }
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
        url :'message_db.php',
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
                alert_js('Sent Successfully.','Alert');
            }else {
                alert_js(data.msg,'Error Found');
            }
            $('#msg_btn').prop("disabled", false);
        }
    }).responseText;
}


function load_inbox(){
    $.ajax({
        url :'message_db.php',
        type:'POST',
        dataType:'json',
        data :{
            'action':'load_inbox'
        },
        beforeSend:function(){
            $('#inbox_data').html('');
        },
        async: false,
        success  :function(data){
            if(data.status==1){
                if ($.fn.DataTable.isDataTable('#table_inbox')) {
                    $('#table_inbox').DataTable().destroy();
                }
                $("#inbox_data").html(data.html);
                $('#table_inbox').DataTable({
                    order: []
                });
            }else {
                alert_js(data.msg,'Error Found');
            }
        }
    }).responseText;



}

function load_outbox(){
    $.ajax({
        url :'message_db.php',
        type:'POST',
        dataType:'json',
        data :{
            'action':'load_outbox'
        },
        beforeSend:function(){
            $('#outbox_data').html('');
        },
        async: false,
        success  :function(data){
            if(data.status==1){
                if ($.fn.DataTable.isDataTable('#table_outbox')) {
                    $('#table_outbox').DataTable().destroy();
                }
                $("#outbox_data").html(data.html);
                $('#table_outbox').DataTable({
                    order: []
                });
            }else {
                alert_js(data.msg,'Error Found');
            }
        }
    }).responseText;



}

function show_msg(id,type,open){
    $.ajax({
        url :'message_db.php',
        type:'POST',
        dataType:'json',
        data :{
            'action':'show_msg',
            'id':id,
            'type':type,
            'open':open
        },
        beforeSend:function(){

        },
        async: false,
        success  :function(data){
            if(data.status==1){
                $('#modalBody').html(data.html);
                $('#messageModal').modal('show');

                $('#msg_'+id).removeClass('text-danger');
                $('#msg_'+id).addClass('text-dark');
            }else {
                alert_js(data.msg,'Error Found');
            }
        }
    }).responseText;



}