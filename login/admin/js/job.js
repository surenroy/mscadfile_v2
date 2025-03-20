$(document).ready(function(){
    showDiv(2);
});

function showDiv(id) {
    load_other();
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



function submit_job(){
    var description=$("#description").val();
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


    if (description==0){
        alert_js('Please Select Your Description.','Alert');
        $('#description').focus();
        return false;
    }

    const formData = new FormData();
    formData.append('file', file);
    formData.append('description', description);
    formData.append('action', 'submit_job');

    $.ajax({
        url: 'job_db.php',
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



function load_other(){
    $.ajax({
        url :'job_db.php',
        type:'POST',
        dataType:'json',
        data :{
            'action':'load_other'
        },
        beforeSend:function(){
            $('#other_job').html('');
        },
        async: false,
        success  :function(data){
            if(data.status==1){
                $("#other_job").html(data.html);
                data.ids.forEach(id => {
                    load_table_oth(id);
                });
            }else {
                alert_js(data.msg,'Error Found');
            }
        }
    }).responseText;
}

function load_table_oth(id){
    $.ajax({
        url :'job_db.php',
        type:'POST',
        dataType:'json',
        data :{
            'action':'load_table_oth',
            'id':id
        },
        beforeSend:function(){
            $("#table_oth_"+id).html('');
        },
        async: false,
        success  :function(data){
            if(data.status==1){
                $("#table_oth_"+id).html(data.html);
            }else {
                alert_js(data.msg,'Error Found');
            }
        }
    }).responseText;
}

function load_my(){
    $.ajax({
        url :'job_db.php',
        type:'POST',
        dataType:'json',
        data :{
            'action':'load_my'
        },
        beforeSend:function(){
            $('#my_job').html('');
        },
        async: false,
        success  :function(data){
            if(data.status==1){
                $("#my_job").html(data.html);
                data.ids.forEach(id => {
                    load_table_my(id);
                });
            }else {
                alert_js(data.msg,'Error Found');
            }
        }
    }).responseText;
}


function load_table_my(id){
    $.ajax({
        url :'job_db.php',
        type:'POST',
        dataType:'json',
        data :{
            'action':'load_table',
            'id':id
        },
        beforeSend:function(){
            $("#table_my_"+id).html('');
        },
        async: false,
        success  :function(data){
            if(data.status==1){
                $("#table_my_"+id).html(data.html);
            }else {
                alert_js(data.msg,'Error Found');
            }
        }
    }).responseText;
}


function hide_job(id){
    $.ajax({
        url :'job_db.php',
        type:'POST',
        dataType:'json',
        data :{
            'action':'hide_job',
            'id':id
        },
        beforeSend:function(){

        },
        async: false,
        success  :function(data){
            if(data.status==1){
                $('.hide'+id).addClass('d-none');
            }else {
                alert_js(data.msg,'Error Found');
            }
        }
    }).responseText;
}


function del_job(id){
    $.ajax({
        url :'job_db.php',
        type:'POST',
        dataType:'json',
        data :{
            'action':'del_job',
            'id':id
        },
        beforeSend:function(){

        },
        async: false,
        success  :function(data){
            if(data.status==1){
                $('.card'+id).addClass('d-none');
            }else {
                alert_js(data.msg,'Error Found');
            }
        }
    }).responseText;
}

function reply_product_modal(id) {
    $.ajax({
        url :'job_db.php',
        type:'POST',
        dataType:'json',
        data :{
            'action':'reply_product_modal',
            'id':id
        },
        beforeSend:function(){
            $('#modal_body_product').html('');
        },
        async: false,
        success  :function(data){
            if(data.status==1){
                $('#modal_body_product').html(data.html);
                $('#reply_btn').attr('data-id',id);
                $('#productModal').modal('show');
            }else {
                alert_js(data.msg,'Error Found');
            }
        }
    }).responseText;
}


function reply_with_product() {
    var job = $('#reply_btn').attr('data-id');

    const checkboxes = document.querySelectorAll('input[name="product_ids[]"]:checked');
    const checkedValues = Array.from(checkboxes).map(checkbox => checkbox.value);

    if (checkedValues.length === 0) {
        alert_js("No products selected!",'Error');
        return false;
    }
    const checked_value=checkedValues.join(",")

    $.ajax({
        url :'job_db.php',
        type:'POST',
        dataType:'json',
        data :{
            'action':'reply_with_product',
            'job':job,
            'checked_value':checked_value
        },
        beforeSend:function(){
            $('#reply_btn').prop("disabled", true);
        },
        async: false,
        success  :function(data){
            if(data.status==1){
                $('#reply_btn').prop("disabled", false);
                $('#productModal').modal('hide');
                load_table_oth(job);
                alert_js('Successsully Posted','Success');
            }else {
                alert_js(data.msg,'Error Found');
            }
        }
    }).responseText;
}


function show_product(id){
    $.ajax({
        url :'job_db.php',
        type:'POST',
        dataType:'json',
        data :{
            'action':'show_product',
            'id':id
        },
        beforeSend:function(){
            $('#modal_body_product_show').html('');
        },
        async: false,
        success  :function(data){
            if(data.status==1){
                $('#modal_body_product_show').html(data.html);
                $('#productModal_show').modal('show');
            }else {
                alert_js(data.msg,'Error Found');
            }
        }
    }).responseText;
}
