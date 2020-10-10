<div class="pb-15">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-title">
                    <h3><?php echo ucwords($label[1]->name); ?></h3>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <div class="grid-container clearfix">
                    <div class="grid col-4">
                        <img src="<?php if(@getimagesize(IMG_PRODUCT.$mostPopular->products[0]->images[0]->original)) {echo IMG_PRODUCT.$mostPopular->products[0]->images[0]->original; }else{ echo base_url('assets/img/product/1.jpg');}; ?>" width="100%">
                    </div>
                    <?php $i = 1; for($kolom = 1; $kolom<=3;$kolom++) { ?>
                    <div class="grid col-2">
                        <?php for($loop = 1;$loop<=2;$loop++) { ?>
                            <div class="most-popular-photo" style="max-height: 211.66px;">
                                <img src="<?php if(@getimagesize(IMG_PRODUCT.$mostPopular->products[$i]->images[0]->original)) {echo IMG_PRODUCT.$mostPopular->products[$i]->images[0]->original; }else{ echo base_url('assets/img/product/1.jpg');}; ?>" width="100%">
                            </div>
                        <?php $i++; } ?>
                    </div>
                    <?php } ?>
                    <div class="grid col-2 col-height-4">
                        <img src="<?php echo base_url('assets/img/product/1.jpg') ?>" width="100%">
                        <img src="<?php echo base_url('assets/img/product/1.jpg') ?>" width="100%">
                        <img src="<?php echo base_url('assets/img/product/1.jpg') ?>" width="100%">
                        <img src="<?php echo base_url('assets/img/product/1.jpg') ?>" width="100%">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>