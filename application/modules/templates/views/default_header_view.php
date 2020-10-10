<!doctype html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title><?php echo isset($title) ? $title : 'Pasar Selon'; ?></title>
    <link rel="icon" href="<?php echo base_url('') ?>">
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- all css here -->
    <!-- bootstrap.min.css -->
    <!-- <link rel="stylesheet" href="<?php echo base_url() ?>assets/css/bootstrap.min.css"> -->
    <link href="assets/assets-new/css/bootstrap.css" rel="stylesheet" type="text/css" media="all" />
	<link href="assets/assets-new/css/style.css" rel="stylesheet" type="text/css" media="all" />
	<link href="assets/assets-new/css/font-awesome.css" rel="stylesheet">
    <!-- font-awesome.min.css -->
    <link rel="stylesheet" href="<?php echo base_url() ?>assets/css/font-awesome.min.css">
    <!-- owl.carousel.css -->
    <link rel="stylesheet" href="<?php echo base_url() ?>assets/css/owl.carousel.css">
    <!-- owl.carousel.css -->
    <link rel="stylesheet" href="<?php echo base_url() ?>assets/css/meanmenu.min.css">
    <!-- shortcode/shortcodes.css -->
    <link rel="stylesheet" href="<?php echo base_url() ?>assets/css/shortcode/shortcodes.css">
    <!-- magnific-popup.css -->
    <link rel="stylesheet" href="<?php echo base_url() ?>assets/css/magnific-popup.css">
    <!-- nivo-slider.css -->
    <link rel="stylesheet" href="<?php echo base_url() ?>assets/css/nivo-slider.css">
    <link rel="stylesheet" href="<?php echo base_url() ?>assets/css/bootstrap-datepicker.min.css">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/dropzone.min.css') ?>" type="text/css">
    <!-- style.css -->
    <link rel="stylesheet" href="<?php echo base_url() ?>assets/style.css">
    <!-- jquery-ui.min.css -->
    <link rel="stylesheet" href="<?php echo base_url() ?>assets/css/jquery-ui.min.css">
    <!--    <script type="text/javascript" language="javascript" src="--><?php //echo base_url()
                                                                            ?>
    <!--assets/datatable/media/js/jquery.js"></script>-->
    <!-- responsive.css -->
    <link rel="stylesheet" href="<?php echo base_url() ?>assets/css/responsive.css">
    <link rel="stylesheet" href="<?php echo base_url() ?>assets/js/vendor/Zoomple-master/styles/zoomple.css" type="text/css">
    <!--    <link rel="stylesheet" href="-->
    <?php echo ''; //echo base_url() 
    ?>
    <!--assets/css/bootstrap-switch.min.css" style="text/css">-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto">
    <link rel="stylesheet" href="<?php echo base_url() ?>assets/css/custom-style.css" type="text/css">
    <script src="<?php echo base_url() ?>assets/js/vendor/modernizr-2.8.3.min.js"></script>
    <!-- jquery-2.1.4 -->
    <script src="<?php echo base_url() ?>assets/js/jquery-2.1.4.min.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    !--<script async src="https://www.googletagmanager.com/gtag/js?id=UA-109583513-1"></script>-->

    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());
        gtag('config', 'UA-109583513-1');
    </script>
    <script type="text/javascript">
        //        $(function() {
        //            $('#block-cart').load('<?php //echo base_url('Product/tesloadcart'); 
                                                ?>//', function(response) {
        //                $(this).html(response);
        //            });
        //        })
    </script>
    <?php if((isset($tomtom_links) && is_array($tomtom_links)) || (isset($tomtom_scripts) && is_array($tomtom_scripts))):?>
       <!--<pre>-->
       <?php echo '';//print_r($tomtom_links);?>
       <!--</pre>-->
       <?php foreach($tomtom_links as $href): ?>
            <link rel="stylesheet" type="text/css" href="<?=$href;?>">
       <?php endforeach; ?> 
       <?php foreach($tomtom_scripts as $src): ?>
       <script type="text/javascript" src="<?=$src?>"></script>
       <?php endforeach; ?> 
   <?php else: ?>
   <?php endif; ?>
</head>

<body>

    <!-- header -->

    <header class="navbar-fixed-top" id="header">

        <!-- header-top-area -->

        <?php echo Modules::run('templates/top_header') ?>

        <!-- header-top-area end -->

        <!-- header-bottom-area-start -->

        <?php echo Modules::run('templates/buttom_header') ?>

        <!-- header-bottom-area-end -->

    </header>

    <!-- end header -->

    <!-- mainmenu-area-start -->
    <?php echo Modules::run('templates/main_menu') ?>
