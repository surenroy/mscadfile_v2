<?php
$error_msg='';
if(!isset($_COOKIE['error_msg'])) {
   header('location:../index.php');
} else {
    $error_msg= $_COOKIE['error_msg'];
}



?>





<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <title>Some Error Found</title>
</head>
<body>
<div class="col-sm-12" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center;">
    <h5>Hi! There Is An Error...</h5>

    <div class="col-sm-12" style="margin-top: 30px; margin-bottom: 30px;">
        <img src="../img/error.png" alt="Error Image" class="animate__animated animate__wobble">
    </div>

    <div class="col-sm-12" style="color: red; font-size: 20px; font-weight: bold;">
        <?php echo $error_msg; ?>
    </div>


</div>

<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<script>
    $( document ).ready(function() {
        setTimeout(function(){
            window.location.replace("../index.php");
        }, 4000);
    });
</script>

</body>
</html>








