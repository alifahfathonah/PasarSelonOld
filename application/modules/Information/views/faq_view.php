<!--breadcrumb start -->
<div class="breadcrumb-area">
    <div class="container">
        <?php echo $breadcrumbs?>
    </div>
</div>
<!-- breadcrumb end -->

<!-- coupon-area start -->
<div id="faq" class="coupon-area pt-30">
    <div class="container">
        <div class="section-title">
            <h3>FAQ</h3>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="coupon-accordion" style="padding-bottom: 30px">
                    <!-- ACCORDION START -->
                    <?php for($i=0;$i<count($faqs);$i++) { ?>
                    <h3><?php echo $faqs[$i]->title; ?> <span id="slideTg<?php echo $faqs[$i]->id; ?>">See details</span></h3>
                    <div id="slideCt<?php echo $faqs[$i]->id; ?>" class="coupon-content">
                        <div class="coupon-info">
                            <p class="coupon-text"><?php echo $faqs[$i]->content; ?></p>
                        </div>
                    </div>
                    <?php } ?>
                    <!-- ACCORDION END -->
                </div>
            </div>
        </div>
    </div>
</div>
<!-- coupon-area end -->