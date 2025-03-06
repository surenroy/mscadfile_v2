<?php
include_once ('header.php');

?>





<section class="container-fluid py-2">
    <div class="col-12 p-0 m-0 mt-1 mb-2 d-flex flex-row flex-wrap g-2 justify-content-center">


        <?php
        $sql="SELECT `id`,`title`,DATE_FORMAT(`date_time`,'%d-%M-%Y') AS date_time,`short_text`,`image` FROM `blog` WHERE `del_flg`=0 ORDER BY `date_time` DESC";
        $query = $pdoconn->prepare($sql);
        $query->execute();
        $my_arr = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach ($my_arr as $val) {
            $id = $val['id'];
            $title = $val['title'];
            $date_time = $val['date_time'];
            $short_text = $val['short_text'];
            $image = $val['image'];


            echo '<div class="item-item-blog row col-md-2 p-0 m-0 mt-2 mb-2 mx-2 pt-2 pb-2 d-flex flex-row flex-wrap gx-4 justify-content-between shadow rounded">
        <div class="col-12 d-flex flex-row justify-content-center justify-content-lg-start">
            <img src="'.$site_url.'blog/'.$image.'" alt="Blog_img">
        </div>

        <div class="col-12">
            <p class="text-start blog_text m-0 fw-bold fs-6 mt-2">'.$title.'</p>
            <p class="text-start text-black-50 mb-2"><small>'.$date_time.'</small></p>
            <p class="text-start">'.$short_text.'</p>
            <p class="text-start">
                <button class="btn btn-sm btn-link text-decoration-none p-0 fw-bold text-start" onclick="blog('.$id.', \''.addslashes($title).'\')">Read More</button>
            </p>
        </div>
    </div>';


        }


        ?>




    </div>
</section>







<div class="modal fade" id="blogModal" tabindex="-1" aria-labelledby="blogModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modalContent">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<script>
    function blog(id,title) {
        fetch(site_url+'blog/'+id+'.json')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok ' + response.statusText);
                }
                return response.json();
            })
            .then(data => {
                $('#modalTitle').text(title);
                $('#modalContent').html(data.description);
                $('#blogModal').modal('show');
            })
            .catch(error => {
                console.error('There was a problem with the fetch operation:', error);
            });
    }
</script>


<?php
include_once ('footer.php');

?>








