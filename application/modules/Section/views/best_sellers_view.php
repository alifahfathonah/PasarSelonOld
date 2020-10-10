<div class="new-product-area-4">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-title">
                    <h3 align="center">Produk Best Sellers</h3>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="feature-tab-product dotted-style-2">
                    <div class="feature-product-active-banyak owl-carousel owl-theme">
                        <?php foreach($best_sellers_products as $row) { ?>
                            <div class="single-product  white-bg">
                                <?php if($row->discount > 0) { ?>
                                    <span class="discount-label"><?php echo $row->discount; ?>%</span>
                                <?php } ?>
                                <div class="product-img produc-img-main-slider">
                                    <a href="<?php echo base_url() ?>Product/detail/<?php echo url_title($row->name,'-',true); ?>?id=<?php echo $row->id; ?>"><img src="<?php $prod = json_decode($row->images, JSON_OBJECT_AS_ARRAY); if(isset($prod[0]['thumbnail'])) {if(@getimagesize(IMG_PRODUCT.$prod[0]['thumbnail'])) {echo IMG_PRODUCT.$prod[0]['thumbnail'];} else{echo base_url().'assets/img/product/1.jpg';}}else{echo base_url().'assets/img/product/1.jpg';}?>" alt="" /></a>

                                </div>
                                <div class="product-content product-i product-sm">
                                    <div class="pro-title">
                                        <h4><a href="<?php echo base_url() ?>Product/detail/<?php echo url_title($row->name,'-',true); ?>?id=<?php echo $row->id; ?>"><?php echo $row->name; ?></a></h4>
                                    </div>
<!--                                    <div class="pro-rating pro-rating-small">-->
<!--                                        --><?php //for ($i = 1; $i <= $row->ratings; $i++) { ?>
<!--                                            <a href="#"><i class="fa fa-star"></i></a>-->
<!--                                        --><?php //}
//                                        for ($b = 1; $b <= (5 - $row->ratings); $b++) { ?>
<!--                                            <a href="#"><i class="fa fa-star-o"></i></a>-->
<!--                                        --><?php //} ?>
<!--                                    </div>-->
                                    <div class="price-box best-sellers-custom-price">
                                        <?php if($row->discount > 0) { ?>
                                            <span class="price product-price"><h4 class="price-h"><strike><?php echo "Rp".number_format($row->priceAfterMargin); ?></h4></strike><br><h5 class="price-h price-h-disc"><?php echo "Rp ".number_format($row->priceNett); ?></h5></span>
                                        <?php }else{ ?>
                                            <span class="price product-price"><h4 class="price-h"><?php echo "Rp ".number_format($row->priceNett); ?></h4></span>
                                        <?php } ?>
                                    </div>
                                </div>

                            </div>
                        <?php } ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>