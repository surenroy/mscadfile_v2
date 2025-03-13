<?php
include_once ('../header.php');



if (isset($_GET['slug'])) {
    $slug = $_GET['slug'];


    $sql="SELECT `id`,`image`,`title`,DATE_FORMAT(`date_time`,'%d-%M-%Y') AS date_time FROM `blog` WHERE `slug`='$slug'";
    $query = $pdoconn->prepare($sql);
    $query->execute();
    $my_arr = $query->fetchAll(PDO::FETCH_ASSOC);

    if(count($my_arr)==0){
        header('Location: ../home.php');
        exit();
    }

    $id=$my_arr[0]['id'];
    $image=$my_arr[0]['image'];
    $title=$my_arr[0]['title'];
    $date_time=$my_arr[0]['date_time'];



} else {
    header('Location: ../home.php');
    exit();
}

?>



<script>
    function show_blog_data() {
        var id=<?php echo $id; ?>;

        fetch(site_url+'blog_data/'+id+'.json')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok ' + response.statusText);
                }
                return response.json();
            })
            .then(data => {
                $('#blog_content').html(data.description);
            })
            .catch(error => {
                console.error('There was a problem with the fetch operation:', error);
            });
    }

    $( document ).ready(function() {
        show_blog_data();
    });


</script>







<section class="container-fluid py-2">
    <div class="row">
        <div class="col-md-3">
            <div class="position-relative">
                <div class="blog-image">
                    <img id="blogImage" src="<?php echo $site_url; ?>blog_data/<?php echo $image; ?>" class="img-fluid magnify" alt="<?php echo $slug; ?>">
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <h4 class="text-primary"><?php echo $title; ?></h4>
            <p class="text-start text-black-50 mb-3"><span class="text-dark">Published On: </span><?php echo $date_time; ?></p>
            <div class="col-12" id="blog_content"></div>
        </div>
    </div>
</section>










<?php
include_once ('../footer.php');
?>








