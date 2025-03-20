$(document).ready(function(){
    showDiv(1);
    $('.keyword').filter_input({regex: '[A-Za-z0-9, ]'});

    $('.atz').filter_input({regex: '[A-Za-z ]'});
    $('.atnp').filter_input({regex: '[A-Za-z0-9(),/ -]'});
    $('.ztn').filter_input({regex: '[0-9]'});
    $('.ztnd').filter_input({regex: '[0-9.]'});
    $('.atn').filter_input({regex: '[A-Za-z0-9]'});
});

function showDiv(id) {
    if(id==1){
        $('#newMessage').removeClass('d-none');
        $('.btn1').addClass('btn-primary');
        $('.btn2').addClass('btn-outline-primary');
        $('.btn1').removeClass('btn-outline-primary');
        $('.btn2').removeClass('btn-primary');
        $('#productListDiv').addClass('d-none');
    }else{
        $('#newMessage').addClass('d-none');
        $('#productListDiv').removeClass('d-none');
        $('.btn2').addClass('btn-primary');
        $('.btn1').addClass('btn-outline-primary');
        $('.btn2').removeClass('btn-outline-primary');
        $('.btn1').removeClass('btn-primary');
        load_data();
    }
}


function previewImage(input, previewId, nextInputId) {
    const file = input.files[0];
    if (file) {
        const allowedTypes = ['image/jpeg'];
        if (!allowedTypes.includes(file.type)) {
            alert_js('Only .jpg/.jpeg images are allowed.', 'Error');
            input.value = '';
            document.getElementById(previewId).classList.add('d-none');
            return;
        }

        const maxFileSize = 200 * 1024;
        if (file.size > maxFileSize) {
            alert_js('File size must be below 200 KB.','Error');
            input.value = '';
            document.getElementById(previewId).classList.add('d-none');
            return;
        }

        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById(previewId);
            const img = new Image();

            img.onload = function() {
                if (img.width === 300 && img.height === 300) {
                    preview.src = e.target.result;
                    preview.classList.remove('d-none');
                    document.getElementById(nextInputId)?.classList.remove('d-none');
                } else {
                    alert_js('Image dimensions must be 300x300 pixels.','Error');
                    input.value = '';
                    preview.classList.add('d-none');
                }
            };

            img.src = e.target.result;
        };
        reader.readAsDataURL(file);
    }
}

function removeImage(inputId, previewId) {
    document.getElementById(inputId).value = "";
    const previewImage = document.getElementById(previewId);
    previewImage.src = "#";
    previewImage.classList.add('d-none');
}

function submit_form() {
    var formData = new FormData();
    var category = $('#category').val();
    var imageFile = $('#featuredImage')[0].files[0];

    if (category==''){
        alert_js('Please Input Your Category','Alert');
        $('#category').focus();
        return false;
    }else if (!imageFile){
        alert_js('Please Input Category Image','Alert');
        return false;
    }

    formData.append('category', category);
    formData.append('featuredImage', imageFile);
    formData.append('action', 'save');

    $.ajax({
        url: 'category_db.php',
        type: 'POST',
        data: formData,
        dataType:'json',
        processData: false,
        contentType: false,
        beforeSend:function(){
            $('#submit_btn').prop("disabled", true);
        },
        async: false,
        success  :function(data){
            if(data.status==1){
                $("#category").val('');
                $("#remove_btn").click();
                alert_js('Saved Successfully.','Alert');
            }else {
                alert_js(data.msg,'Error Found');
            }
            $('#submit_btn').prop("disabled", false);
        }
    });
}



function load_data(){
    $.ajax({
        url :'category_db.php',
        type:'POST',
        dataType:'json',
        data :{
            'action':'load_data'
        },
        beforeSend:function(){
            $('#data_data').html('');
        },
        async: false,
        success  :function(data){
            if(data.status==1){
                if ($.fn.DataTable.isDataTable('#table_data')) {
                    $('#table_data').DataTable().destroy();
                }
                $("#data_data").html(data.html);
                $('#table_data').DataTable({
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
        url :'category_db.php',
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
