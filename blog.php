<?php
include_once ('header.php');

?>





<section class="container-fluid py-2">
    <div class="col-12 p-0 m-0 mt-1 mb-2 d-flex flex-row flex-wrap g-2 justify-content-center">


        <?php
        $sql="SELECT `id`,`title`,`slug`,DATE_FORMAT(`date_time`,'%d-%M-%Y') AS date_time,`short_text`,`image` FROM `blog` WHERE `del_flg`=0 ORDER BY `date_time` DESC";
        $query = $pdoconn->prepare($sql);
        $query->execute();
        $my_arr = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach ($my_arr as $val) {
            $id = $val['id'];
            $title = $val['title'];
            $slug = $val['slug'];
            $date_time = $val['date_time'];
            $short_text = $val['short_text'];
            $image = $val['image'];


            echo '<div class="item-item-blog row col-md-2 p-0 m-0 mt-2 mb-2 mx-2 pt-2 pb-2 d-flex flex-row flex-wrap gx-4 justify-content-between shadow rounded">
        <div class="col-12 d-flex flex-row justify-content-center justify-content-lg-start">
            <img src="'.$site_url.'blog_data/'.$image.'" alt="Blog_img">
        </div>

        <div class="col-12">
            <p class="text-start blog_text m-0 fw-bold fs-6 mt-2">'.$title.'</p>
            <p class="text-start text-black-50 mb-2"><small>'.$date_time.'</small></p>
            <p class="text-start">'.$short_text.'</p>
            <p class="text-start">
                <a class="btn btn-sm btn-link text-decoration-none p-0 fw-bold text-start" href="'.$site_url.'blog/blog.php?slug='.$slug.'">Read More</a>
            </p>
        </div>
    </div>';


        }


        ?>




    </div>
</section>








<?php
include_once ('footer.php');

?>








