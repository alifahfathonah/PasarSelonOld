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
                <h2 align="center" class="page-heading">Aturan Penggunaan</h2>
                <div class="about-us-page">
                    <div class="about-content">
                        <?php if(count($aturan_pengguna) == 0) {echo "<p>Belum ada kontenya</p>"; } else { foreach ($aturan_pengguna as $aturan) { ?>
                            <h4><?php echo $aturan->title; ?></h4>
                        <p><?php echo $aturan->content; ?></p><br><br>
                        <?php }} ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<br><br>
<!-- about-us-area-end -->