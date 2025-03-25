<?php
include_once ('header.php');

?>

<div style="height: 90vh;"></div>


<style>
    .fixed-btn_footer {
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 9;
        font-size: 20px;
        padding: 10px 13px;
        border-radius: 50%;
    }

    .custom-modal_footer {
        position: fixed;
        bottom: 0;
        right: 0px;
        width: 100vw;
        max-width: 400px;
        background: white;
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        padding: 15px;
        display: none;
        z-index: 10;
    }
    .modal-header_footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
</style>



<button class="btn btn-primary fixed-btn_footer btn-sm" onclick="toggleModal()"><i class="fa-solid fa-comments"></i></button>


<div class="custom-modal_footer" id="popupForm">
    <div class="modal-header_footer">
        <h5 class="modal-title">Send a Message</h5>
        <button type="button" class="btn-close" onclick="toggleModal()"></button>
    </div>
    <div class="modal-body mt-3">
        <div class="mb-2 w-100">
            <input type="text" class="form-control w-100 form-control-sm" id="subject" placeholder="Enter subject">
        </div>
        <div class="mb-2 w-100">
            <textarea class="form-control w-100 form-control-sm" id="message" rows="4" placeholder="Write your message"></textarea>
        </div>
        <button class="btn btn-success w-100" onclick="sendMessage()" id="msg_btn">Send</button>
    </div>
</div>



<script>
    $(document).ready(function() {
        document.title = "Dashboard";
    });


    function toggleModal() {
        const modal = document.getElementById("popupForm");
        modal.style.display = (modal.style.display === "block") ? "none" : "block";
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
                    toggleModal();
                    alert_js('Sent Successfully.','Alert');
                }else {
                    alert_js(data.msg,'Error Found');
                }
                $('#msg_btn').prop("disabled", false);
            }
        }).responseText;
    }
</script>


<?php
include_once ('footer.php');
?>








