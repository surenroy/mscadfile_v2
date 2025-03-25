$(document).ready(function(){
    showDiv(1);
});

function showDiv(id) {
    if(id==1){
        $('#newMessage').removeClass('d-none');
        $('#inboxMessage').addClass('d-none');
        $('.btn1').addClass('btn-primary');
        $('.btn1').removeClass('btn-outline-primary');
        $('.btn2').addClass('btn-outline-primary');
        $('.btn2').removeClass('btn-primary');
    }else if(id==2){
        $('#newMessage').addClass('d-none');
        $('#inboxMessage').removeClass('d-none');
        $('.btn2').addClass('btn-primary');
        $('.btn2').removeClass('btn-outline-primary');
        $('.btn1').addClass('btn-outline-primary');
        $('.btn1').removeClass('btn-primary');
        load_blog();
    }
}


function load_blog(){
    $.ajax({
        url :'blog_db.php',
        type:'POST',
        dataType:'json',
        data :{
            'action':'load_blog'
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


function status_change(id,del){
    $.ajax({
        url :'blog_db.php',
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
                load_blog();
            }else {
                alert_js(data.msg,'Error Found');
            }
        }
    }).responseText;
}


function previewImage(input, previewId, nextInputId) {
    const file = input.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById(previewId);
            preview.src = e.target.result;
            preview.classList.remove('d-none');
            document.getElementById(nextInputId)?.classList.remove('d-none');
        }
        reader.readAsDataURL(file);
    }
}

function removeImage(inputId, previewId) {
    document.getElementById(inputId).value = "";
    const previewImage = document.getElementById(previewId);
    previewImage.src = "#";
    previewImage.classList.add('d-none');
}

function removeHTMLTags(inputString) {
    return inputString.replace(/<[^>]*>/g, '');
}

function submit_blog(){
    var subject=$("#subject").val();
    var short_description=$("#short_description").val();
    var description = editor.getData();
    var description_str = removeHTMLTags(editor.getData());
    const inputFile = document.getElementById('featuredImage');
    const file = inputFile.files[0];

    if (!file) {
        alert_js("Please select a file to upload.",'Error');
        return;
    }

    const maxSizeInBytes = 300 * 1024; // 300kb
    if (file.size > maxSizeInBytes) {
        alert_js("File size exceeds the maximum limit of 300kb.","Error!");
        return;
    }


    if (subject==''){
        alert_js('Please Input Your Title.','Alert');
        $('#subject').focus();
        return false;
    }else if (short_description==''){
        alert_js('Please Input Short Description','Alert');
        $('#short_description').focus();
        return false;
    }else if (description_str==''){
        alert_js('Please Input Description','Alert');
        return false;
    }

    const formData = new FormData();
    formData.append('file', file);
    formData.append('title', subject);
    formData.append('short_description', short_description);
    formData.append('description', description);
    formData.append('action', 'submit_blog');

    $.ajax({
        url: 'blog_db.php',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        dataType:'json',
        beforeSend:function(){
            $('#blog_btn').prop("disabled", true);
        },
        success  :function(data){
            if(data.status==1){
                $.confirm({title: 'Success!',
                    content: 'Successfully Saved.',
                    buttons: {
                        confirm: function () {
                            location.reload();
                        }
                    }
                });
            }else {
                alert_js(data.msg,'Error Found');
            }
            $('#blog_btn').prop("disabled", false);
        }
    }).responseText;
}