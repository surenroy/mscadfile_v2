<?php
include_once ('header.php');

?>




<div class="container-fluid mb-3 mt-3 col-12 d-flex justify-content-center flex-row flex-wrap">
    <button class="btn btn-primary mx-2 mt-2 mt-sm-0 col-sm-3 btn1 top_page_button" onclick="showDiv(1)">Compose</button>
    <button class="btn btn-outline-primary mx-2 mt-2 mt-sm-0 col-sm-3 btn2 top_page_button" onclick="showDiv(2)">Inbox</button>
    <button class="btn btn-outline-primary mx-2 mt-2 mt-sm-0 col-sm-3 btn3 top_page_button" onclick="showDiv(3)">Sent</button>
</div>


<div id="newMessage" class="full-screen d-flex text-white d-none px-2 col-12 justify-content-between flex-wrap">
    <div class="container pt-4 pb-4 text-dark rounded">
        <div class="col-12 p-0 m-0">
            <div class="row g-2 mb-3 col-12 p-0 m-0 d-flex justify-content-between flex-wrap">
                <div class="col-md-6">
                    <label class="form-label col-12 w-100 fw-bold">Subject</label>
                    <input type="text" class="form-control form-control-sm w-100" id="subject" required>
                </div>
            </div>

            <div class="row g-2 mb-3 col-12 p-0 m-0 d-flex justify-content-between flex-wrap">
                <div class="col-12">
                    <label class="form-label col-12 w-100 fw-bold">Message</label>
                    <textarea class="form-control form-select-sm col-12 w-100" rows="10" id="message"></textarea>
                </div>
            </div>

            <div class="col-12 text-center">
                <button type="submit" class="btn btn-danger col-12 top_page_button text-center" onclick="sendMessage()" id="msg_btn">Submit</button>
            </div>
        </div>
    </div>
</div>

<div id="inboxMessage" class="full-screen d-flex text-white d-none px-2 col-12 justify-content-between flex-wrap">
    <div class="container-fluid pt-4 pb-4 text-dark rounded">
        <div class="table-responsive">
            <table class="table table-bordered table-hover table_center" id="table_inbox">
                <thead class="table-dark">
                <tr>
                    <th>Date & Time</th>
                    <th>Subject</th>
                    <th>From</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody id="inbox_data">

                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="sentMessage" class="full-screen d-flex text-white d-none px-2 col-12 justify-content-between flex-wrap">
    <div class="container-fluid pt-4 pb-4 text-dark rounded">
        <div class="table-responsive">
            <table class="table table-bordered table-hover table_center" id="table_outbox">
                <thead class="table-dark">
                <tr>
                    <th>Date & Time</th>
                    <th>Subject</th>
                    <th>To</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody id="outbox_data">

                </tbody>
            </table>
        </div>
    </div>
</div>



<div class="modal fade" id="messageModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">Message</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modalBody">

            </div>
        </div>
    </div>
</div>






<script src="<?php echo $site_url; ?>login/user/js/message.js?v=<?php echo $ver; ?>"></script>

<?php
include_once ('footer.php');

?>








