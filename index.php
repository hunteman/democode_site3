<?php
session_start();
include_once ("pages/classes.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body style="background:#f2f2bf; padding-top:6vw;">

<div class="container">
    <!--        <div class="row">
                <header class="col-sm-12 col-md-12 col-lg-12">

                </header>
            </div>-->
    <div class="row">
        <nav class="col-sm-12 col-md-12 col-lg-12 navbar-dark bg-dark fixed-top">
            <?php include_once("pages/menu.php"); ?>
        </nav>
    </div>
    <div class="row">
        <section class="col-sm-12 col-md-12 col-lg-12">
            <?php
            if(isset($_GET['page'])) {
                $page = $_GET['page']; // т.е. значение 1,2,3,4
                if($page == 1) {include_once('pages/catalog.php');}
                if($page == 2) {include_once('pages/cart.php');}
                if($page == 3) {include_once('pages/registration.php');}
                if($page == 4) {include_once('pages/admin.php');}
            }
            ?>
        </section>
    </div>
    <footer class="row">Step academy &copy; 2019</footer>
</div>



<script src="js/jquery-3.4.1.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/cookiejq.js"></script>

<script>
    $(function () {
        var location = window.location.href;
        var cur_url = location.split('/').pop();

        $('.nav-item').each(function () {
            var link = $(this).find('a').attr('href');

            if (cur_url == link) {
                $(".nav-item a").removeClass("active");
                $(this).find('a').addClass('active');
            }
        });
    });
</script>
</body>
</html>