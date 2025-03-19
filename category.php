<?php
include_once ('header.php');
?>


<section class="container-fluid py-2">
    <div class="col-12 p-0 m-0 mt-1 mb-2 d-flex flex-row flex-wrap g-2 justify-content-center">

        <?php
        $sql="SELECT COUNT(`id`) AS counts,`category_id` FROM `products` GROUP BY `category_id`";
        $query = $pdoconn->prepare($sql);
        $query->execute();
        $my_arr = $query->fetchAll(PDO::FETCH_ASSOC);
        $categoryArray = [];
        foreach ($my_arr as $category) {
            $categoryArray[$category['category_id']] = $category['counts'];
        }

        $sql="SELECT `id`,`name`,`slug` FROM `categories` WHERE `del_flg`=0 ORDER BY `name` ASC";
        $query = $pdoconn->prepare($sql);
        $query->execute();
        $my_arr = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach ($my_arr as $val) {
            $id=$val['id'];
            $name=$val['name'];
            $slug=$val['slug'];

            $filepath = $site_url.'category_img/'.$id.'.jpg';

            if (file_exists($filepath)) {
                $img='<img src="'.$filepath.'" class="or_img" alt="Category">';
            } else {
                $img='';
            }

            if(isset($categoryArray[$id])){
                $cnt=$categoryArray[$id];
            }else{
                $cnt=0;
            }

            $url = $site_url . 'category/?name=' . $slug;
            
            echo '<div class="category-item-big col-sm-2 mx-2 mt-3">
            <a href="' . $url . '">
            <span class="counter">' . $cnt . '</span>
            <div class="category-name">' . $name . '</div>
            <img src="' . $filepath . '" alt="' . $slug . '">
            </a>
          </div>';

        }


        ?>




    </div>
</section>


<?php
include_once ('banner.php');
include_once ('footer.php');

?>








