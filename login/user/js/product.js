$(document).ready(function () {
    $('.keyword').filter_input({regex: '[A-Za-z0-9, ]'});

    $('.atz').filter_input({regex: '[A-Za-z ]'});
    $('.atnp').filter_input({regex: '[A-Za-z0-9(),/ -]'});
    $('.ztn').filter_input({regex: '[0-9]'});
    $('.ztnd').filter_input({regex: '[0-9.]'});
    $('.atn').filter_input({regex: '[A-Za-z0-9]'});

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
    }else{
        $('#newProductDiv').addClass('d-none');
        $('#productListDiv').removeClass('d-none');
        $('.btn2').addClass('btn-primary');
        $('.btn1').addClass('btn-outline-primary');
        $('.btn2').removeClass('btn-outline-primary');
        $('.btn1').removeClass('btn-primary');

        load_product();
    }
}


function load_product(){
    $.ajax({
        url :'product_db.php',
        type:'POST',
        dataType:'json',
        data :{
            'action':'load_product'
        },
        beforeSend:function(){
            $('#product_data').html('');
        },
        async: false,
        success  :function(data){
            if(data.status==1){
                if ($.fn.DataTable.isDataTable('#product_table')) {
                    $('#product_table').DataTable().destroy();
                }
                $("#product_data").html(data.html);
                $('#product_table').DataTable({
                    order: []
                });
            }else {
                alert_js(data.msg,'Error Found');
            }
        }
    }).responseText;



}



function generateSEOContent() {
    $('#seo_btn').prop("disabled", true);
    const title = $('#product').val();
    const description = removeHTMLTags(editor.getData());
    const category_val = $('#category').val();
    const category = $('#category option:selected').text();

    if (title == '' || description == '' || category_val == 0) {
        alert_js('Please Enter All Details to Generate SEO Based Data.', 'Alert');
        $('#seo_btn').prop("disabled", false);
        return false;
    }

    const metaTitle = `${title} | ${category}`;
    const metaDescription = description.length > 160 ? description.substring(0, 157) + '...' : description;
    const seoDescription = `Explore this article on ${category} to learn more about ${title}. ${description}`;
    const keywords = `${category}, ${title}, ${description.split(' ')[0]}, ${description.split(' ')[1]}`;

    $('#meta_title').val(metaTitle);
    $('#meta_description').text(metaDescription);
    $('#seo_description').text(seoDescription);
    $('#meta_keyword').val(keywords);

    $('#product').prop('disabled', true);
    editor.enableReadOnlyMode("editor");
    $('#category').prop('disabled', true);
    $('#seo_btn').prop("disabled", true);

    $('#meta_title').prop("disabled", true);
    $('#meta_description').prop("disabled", true);
    $('#seo_description').prop("disabled", true);
    $('#meta_keyword').prop("disabled", true);
}


function removeHTMLTags(inputString) {
    return inputString.replace(/<[^>]*>/g, '');
}


function twoDigit(input) {
    let value = input.value.replace(/[^0-9.]/g, '');
    if ((value.split('.').length - 1) > 1) {
        value = value.substring(0, value.length - 1);
    }
    if (value.includes('.')) {
        const parts = value.split('.');
        if (parts[1].length > 2) {
            value = parts[0] + '.' + parts[1].substring(0, 2);
        }
    }
    input.value = value;
}


function threeDigit(input) {
    let value = input.value.replace(/[^0-9.]/g, '');
    if ((value.split('.').length - 1) > 1) {
        value = value.substring(0, value.length - 1);
    }
    if (value.includes('.')) {
        const parts = value.split('.');
        if (parts[1].length > 3) {
            value = parts[0] + '.' + parts[1].substring(0, 3);
        }
    }
    input.value = value;
}


function add_product() {
    var product = $('#product').val();
    var category = $('#category').val();
    var description = editor.getData();
    var description_str = removeHTMLTags(editor.getData());
    var currency = $('#currency').val();
    var price = $('#price').val();
    var offer = $('#offer').val();
    var active = $('#active').prop('checked');
    var feature = $('#feature').prop('checked');
    var size = $('#size').val();
    var size_unit = $('#size_unit').val();
    var weight = $('#weight').val();
    var weight_unit = $('#weight_unit').val();
    var purity = $('#purity').val();
    var purity_unit = $('#purity_unit').val();
    var volume = $('#volume').val();
    var volume_unit = $('#volume_unit').val();
    var file_3dm = $('#3dm').prop('checked');
    var file_stl = $('#stl').prop('checked');
    var file_mgx = $('#mgx').prop('checked');
    var file_obj = $('#obj').prop('checked');
    var meta_title = $('#meta_title').val();
    var meta_keyword = $('#meta_keyword').val();
    var meta_description = $('#meta_description').val();
    var seo_description = $('#seo_description').val();

    if (product==''){
        alert_js('Please Input Product Name','Alert');
        $('#product').focus();
        return false;
    }else if (category==0){
        alert_js('Please Choose Product Category','Alert');
        $('#category').focus();
        return false;
    }else if (description_str==''){
        alert_js('Please Enter Product Description','Alert');
        return false;
    }else if (currency==0){
        alert_js('Please Choose Currency','Alert');
        $('#currency').focus();
        return false;
    }else if (price<=0 || price==''){
        alert_js('Please Input Price','Alert');
        $('#price').focus();
        return false;
    }else if (offer==0 || offer=='' || offer>price){
        alert_js('Please Input Sell Price & Its must less than Original price','Alert');
        $('#offer').focus();
        return false;
    }else if ((size.length > 0 && size_unit == 0) || (size.length == 0 && size_unit > 0)) {
        alert_js('Please Enter Size Value & Size Unit Properly.', 'Alert');
        return false;
    }else if ((weight.length > 0 && weight_unit == 0) || (weight.length == 0 && weight_unit > 0)) {
        alert_js('Please Enter Weight Value & Weight Unit Properly.', 'Alert');
        return false;
    }else if ((purity > 0 && purity_unit == 0) || (purity == 0 && purity_unit > 0)) {
        alert_js('Please Enter Purity Value & Purity Unit Properly.', 'Alert');
        return false;
    }else if ((volume.length > 0 && volume_unit == 0) || (volume.length == 0 && volume_unit > 0)) {
        alert_js('Please Enter Volume Value & Volume Unit Properly.', 'Alert');
        return false;
    }else if (file_3dm==false && file_stl==false && file_mgx==false && file_obj==false) {
        alert_js('Please Select Type Of File Properly.', 'Alert');
        return false;
    }


    if(active===true){
        var active_i=1;
    }else{
        var active_i=0;
    }

    if(feature===true){
        var feature_i=1;
    }else{
        var feature_i=0;
    }

    if(file_3dm===true){
        var file_3dm_i=1;
    }else{
        var file_3dm_i=0;
    }

    if(file_stl===true){
        var file_stl_i=1;
    }else{
        var file_stl_i=0;
    }

    if(file_mgx===true){
        var file_mgx_i=1;
    }else{
        var file_mgx_i=0;
    }

    if(file_obj===true){
        var file_obj_i=1;
    }else{
        var file_obj_i=0;
    }



    $.ajax({
        url :'product_db.php',
        type:'POST',
        dataType:'json',
        data :{
            'action':'add_product',
            'product':product,
            'category':category,
            'description':description,
            'currency':currency,
            'price':price,
            'offer':offer,
            'active':active_i,
            'feature':feature_i,
            'size':size,
            'size_unit':size_unit,
            'weight':weight,
            'weight_unit':weight_unit,
            'purity':purity,
            'purity_unit':purity_unit,
            'volume':volume,
            'volume_unit':volume_unit,
            'file_3dm':file_3dm_i,
            'file_stl':file_stl_i,
            'file_mgx':file_mgx_i,
            'file_obj':file_obj_i,
            'meta_title':meta_title,
            'meta_keyword':meta_keyword,
            'meta_description':meta_description,
            'seo_description':seo_description
        },
        beforeSend:function(){
            $('#add_btn').prop("disabled", true);
        },
        async: false,
        success  :function(data){
            if(data.status==1){
                $('#product').prop('disabled', true);
                editor.enableReadOnlyMode("editor");
                $('#category').prop("disabled", true);
                $('#currency').prop('disabled', true);
                $('#price').prop('disabled', true);
                $('#offer').prop('disabled', true);
                $('#active').prop('disabled', true);
                $('#feature').prop('disabled', true);
                $('#size').prop('disabled', true);
                $('#size_unit').prop('disabled', true);
                $('#weight').prop('disabled', true);
                $('#weight_unit').prop('disabled', true);
                $('#purity').prop('disabled', true);
                $('#purity_unit').prop('disabled', true);
                $('#volume').prop('disabled', true);
                $('#volume_unit').prop('disabled', true);
                $('#3dm').prop('disabled', true);
                $('#stl').prop('disabled', true);
                $('#mgx').prop('disabled', true);
                $('#obj').prop('disabled', true);
                $('#meta_title').prop("disabled", true);
                $('#meta_description').prop("disabled", true);
                $('#seo_description').prop("disabled", true);
                $('#meta_keyword').prop("disabled", true);

               $('#submit_div').removeClass('d-none');
            }else {
                alert_js(data.msg,'Error Found');
                $('#add_btn').prop("disabled", false);
            }
        }
    }).responseText;
}



















let cropper;
let currentImageId = null;
let imageCounter = 1;
let fileCounter = 1;


$('#cropModal').on('shown.bs.modal', function () {
    let image = document.getElementById('cropImage');
    if (cropper) {
        cropper.destroy();
    }

    cropper = new Cropper(image, {
        aspectRatio: 1,
        viewMode: 1,
        autoCropArea: 0.5,
        responsive: true
    });
});

function previewImage(input, previewId, imageId) {
    if (imageCounter >= 16) {
        alert_js('Image Limit Reached. Cannot upload More Image.', 'Alert');
        return false;
    }

    let file = input.files[0];
    if (file) {
        let validExtensions = ['image/jpeg', 'image/jpg', 'image/png'];
        if (!validExtensions.includes(file.type)) {
            alert_js('Only JPG, JPEG, and PNG images are allowed.', 'Alert');
            document.getElementById('input1').value = '';
            document.getElementById('input1').disabled = false;
            return false;
        }

        let maxSize = 1024 * 1024;
        if (file.size > maxSize) {
            alert_js('File size exceeds the 1 mb limit.', 'Alert');
            document.getElementById('input1').value = '';
            document.getElementById('input1').disabled = false;
            return false;
        }

        document.getElementById(input.id).disabled = true;
        let reader = new FileReader();
        reader.onload = function (e) {
            document.getElementById('cropImage').src = e.target.result;
            if (cropper) {
                cropper.destroy();
            }

            cropper = new Cropper(document.getElementById('cropImage'), {
                aspectRatio: 1,
                viewMode: 1,
                autoCropArea: 0.5,
                responsive: true
            });

            $('#cropModal').modal('show');
            currentImageId = imageId;
        };
        reader.readAsDataURL(file);
    }
}


$('#cropModal').on('hidden.bs.modal', function () {
    document.getElementById('input1').value = '';
    document.getElementById('input1').disabled = false;
});


function cropAndUpload() {
    if (!currentImageId) {
        alert_js('No image selected for cropping!', 'Alert');
        return false;
    }

    let croppedCanvas = cropper.getCroppedCanvas();
    croppedCanvas.toBlob(function (blob) {
        let formData = new FormData();
        formData.append('file', blob);
        formData.append('action', 'upload_img');

        let inputFile = document.getElementById('input1');
        let fileName = inputFile.files[0].name;
        formData.append('fileName', fileName);

        $('#progressBar1Container').removeClass('d-none');

        $.ajax({
            url: 'product_db.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            xhr: function () {
                let xhr = new XMLHttpRequest();
                xhr.upload.onprogress = function (e) {
                    if (e.lengthComputable) {
                        let percent = (e.loaded / e.total) * 100;
                        let progressBar = document.getElementById('progressBarImg');
                        progressBar.style.width = percent + '%';
                        progressBar.innerText = Math.round(percent) + '%';
                    }
                };
                return xhr;
            },
            success: function (response) {
                let parsedResponse = JSON.parse(response);
                if (parsedResponse.success) {
                    load_img_table();
                    $('#progressBar1Container').addClass('d-none');
                    document.getElementById('input1').value = '';
                    document.getElementById('input1').disabled = false;
                    $('#cropModal').modal('hide');
                } else {
                    alert_js('Upload Failed..', 'Alert');
                    document.getElementById('input1').value = '';
                    document.getElementById('input1').disabled = false;
                }
            },
            error: function () {
                alert_js('Upload Failed.', 'Alert');
                document.getElementById('input1').value = '';
                document.getElementById('input1').disabled = false;
            }
        });
    });
}



function load_img_table(){
    $.ajax({
        url :'product_db.php',
        type:'POST',
        dataType:'json',
        data :{
            'action':'load_img_table'
        },
        beforeSend:function(){
            $('#imageTableBody').html('');
        },
        async: false,
        success  :function(data){
            if(data.status==1){
                $("#imageTableBody").html(data.html);
                imageCounter=data.imageCounter;
            }else {
                alert_js(data.msg,'Error Found');
            }
        }
    }).responseText;
}


function delete_img(id){
    $.ajax({
        url :'product_db.php',
        type:'POST',
        dataType:'json',
        data :{
            'action':'delete_img',
            'id':id
        },
        beforeSend:function(){

        },
        async: false,
        success  :function(data){
            if(data.status==1){
                load_img_table();
            }else {
                alert_js(data.msg,'Error Found');
            }
        }
    }).responseText;
}







function uploadFile() {
    if (fileCounter >= 16) {
        alert_js('File Limit Reached. Cannot upload More Files.', 'Alert');
        return false;
    }
    const inputFile = document.getElementById('inputFile');
    const file = inputFile.files[0];

    if (!file) {
        alert_js("Please select a file to upload.",'Error');
        return;
    }

    //const maxSizeInBytes = 1.5 * 1024 * 1024 * 1024; // 3GB
    const maxSizeInBytes = 10 * 1024 * 1024; // 10mb
    if (file.size > maxSizeInBytes) {
        alert_js("File size exceeds the maximum limit of 1.5GB.","Error!");
        return;
    }

    const formData = new FormData();
    formData.append('file', file);
    formData.append('action', 'upload_file');

    const progressBarContainer = document.getElementById('progressBarFileContainer');
    const progressBar = document.getElementById('progressBarFile');

    progressBarContainer.classList.remove('d-none');
    progressBar.style.width = '0%';
    progressBar.textContent = '0%';
    $('#inputFile').prop("disabled", true);

    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'product_db.php', true);

    // Track progress
    xhr.upload.onprogress = (event) => {
        if (event.lengthComputable) {
            const percentComplete = (event.loaded / event.total) * 100;
            progressBar.style.width = percentComplete + '%';
            progressBar.textContent = Math.round(percentComplete) + '%';
        }
    };


    xhr.onload = () => {
        if (xhr.status === 200) {
            const response = JSON.parse(xhr.responseText);
            if (response.success) {
                inputFile.value = '';
                progressBar.style.width = '100%';
                progressBar.textContent = '100%';
                progressBarContainer.classList.add('d-none');
                load_file_table();
            } else {
                alert_js(response.message, "Error!");
            }
        } else {
            alert_js('Upload failed: ' + xhr.statusText, "Error!");
            inputFile.value = '';
            progressBarContainer.classList.add('d-none');
            load_file_table();
        }
        $('#inputFile').prop("disabled", false);
    };

    xhr.onerror = () => {
        alert_js('An error occurred during the upload.',"Error!");
    };

    xhr.send(formData);
}




function load_file_table(){
    $.ajax({
        url :'product_db.php',
        type:'POST',
        dataType:'json',
        data :{
            'action':'load_file_table'
        },
        beforeSend:function(){
            $('#fileTableBody').html('');
        },
        async: false,
        success  :function(data){
            if(data.status==1){
                $("#fileTableBody").html(data.html);
                fileCounter=data.fileCounter;
            }else {
                alert_js(data.msg,'Error Found');
            }
        }
    }).responseText;
}


function delete_file(id){
    $.ajax({
        url :'product_db.php',
        type:'POST',
        dataType:'json',
        data :{
            'action':'delete_file',
            'id':id
        },
        beforeSend:function(){

        },
        async: false,
        success  :function(data){
            if(data.status==1){
                load_file_table();
            }else {
                alert_js(data.msg,'Error Found');
            }
        }
    }).responseText;
}




function final_product() {
    $.ajax({
        url :'product_db.php',
        type:'POST',
        dataType:'json',
        data :{
            'action':'final_product'
        },
        beforeSend:function(){
            $('#final_product').prop("disabled", true);
            $('#inputFile').prop("disabled", true);
            $('#input1').prop("disabled", true);
            $('.del_btn').prop("disabled", true);
        },
        async: false,
        success  :function(data){
            if(data.status==1){
                $.confirm({title: 'Success!',
                    content: 'Successfully Saved. Your Product will be public after file processing. It will take some hours.',
                    buttons: {
                        confirm: function () {
                            location.reload();
                        }
                    }
                });
            }else {
                alert_js(data.msg,'Error Found');
                $('#final_product').prop("disabled", false);
                $('#inputFile').prop("disabled", false);
                $('#input1').prop("disabled", false);
                $('.del_btn').prop("disabled", false);
            }
        }
    }).responseText;
}



function change_status(id,active){
    $.ajax({
        url :'product_db.php',
        type:'POST',
        dataType:'json',
        data :{
            'action':'change_status',
            'id':id,
            'active':active
        },
        beforeSend:function(){

        },
        async: false,
        success  :function(data){
            if(data.status==1){
                load_product();
            }else {
                alert_js(data.msg,'Error Found');
            }
        }
    }).responseText;
}

function change_feature(id,feature){
    $.ajax({
        url :'product_db.php',
        type:'POST',
        dataType:'json',
        data :{
            'action':'change_feature',
            'id':id,
            'feature':feature
        },
        beforeSend:function(){

        },
        async: false,
        success  :function(data){
            if(data.status==1){
                load_product();
            }else {
                alert_js(data.msg,'Error Found');
            }
        }
    }).responseText;
}