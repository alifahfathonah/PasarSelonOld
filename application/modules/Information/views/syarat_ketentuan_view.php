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
<!--                --><?php //print_r($syarat_ketentuan); ?>
                <h2 align="center" class="page-heading"><?php echo $syarat_ketentuan->title; ?></h2>
                <div class="about-us-page">
                    <div class="about-content">
                        <?php echo $syarat_ketentuan->content; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<br><br><br>
<!-- about-us-area-end -->