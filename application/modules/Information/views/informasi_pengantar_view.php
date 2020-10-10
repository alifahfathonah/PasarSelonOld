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
                <?php foreach($inf_pengantar as $row) { ?>
                <h2 align="center" class="page-heading"><?php echo $row->title; ?></h2>
                <div class="about-us-page">
                    <div class="about-content">
                        <p><?php echo $row->content; ?></p>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
<br><br><br>
<!-- about-us-area-end -->