<!--breadcrumb start -->
<div class="breadcrumb-area">
    <div class="container">
        <?php echo $breadcrumbs?>
    </div>
</div>
<!-- breadcrumb end -->

<!-- about-us-area-start -->
<div class="about-us-area">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
<!--                --><?php //echo '<pre>'; print_r($tentang); ?>
                <div class="about-us-page">
                    <div class="about-content">
                        <?php foreach ($tentang as $panduan) { ?>
                            <h4 align="center"><?php echo $panduan->title; ?></h4>
                            <p><?php echo $panduan->content; ?></p>
                            <img class="center-block img1200" src="<?php echo IMG_CMS.$panduan->image; ?>" alt="<?php echo $panduan->content; ?>">
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<br><br><br>
<!-- about-us-area-end -->