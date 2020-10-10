<div class="slider-area pb-10">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-3 hidden-sm hidden-xs" style="padding-right: 0"></div>
            <div class="col-lg-9 col-md-9 col-sm-12">
                <div class="row">
                    <div class="main-slider">
                        <div class="slider-container dotted-style-1">
                            <!-- Slider Image -->
                            <div id="mainSlider" class="slider-image owl-carousel owl-theme owl-loaded">
                                <?php
                                    if (count($banner) > 0 && $banner->code == 200) {
                                        if(isset($banner->data)) {
                                            foreach ($banner->data as $key => $value) {
                                                if($value->BannerCategoryId == 1) {
                                                    echo '<div><a href="'.$value->urlLink.'" ><img src="'.$value->name.'" alt="" title="#'.$value->id.'"/></a></div>';
                                                }
                                            }
                                        }
                                    } else {
                                        echo 'Error while get data from API';
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
<!--                <div class="slider-product dotted-style-1 pt-30">-->
<!--                    <h6><b>NEW PRODUCT</b></h6>-->
<!--                    <div class="slider-product-active owl-carousel owl-theme">-->
<!--                        --><?php
//                        foreach ($mainsliderpro as $slideitem) {
//                            $image = json_decode($slideitem['images']);
//                            //print_r($image[0]->image_low);die;
//                            ?>
<!--                            <div class="single-product single-product-sidebar white-bg">-->
<!--                                <div class="product-img product-img-left">-->
<!--                                    <a href="--><?php //echo base_url('Product/detail/' . url_title($slideitem['name'], '-', true) . '?id=' . $slideitem['id']) ?><!--">-->
<!--                                        <img-->
<!--                                            src="--><?php //echo isset($image[0]->image_low) ? IMG_PRODUCT . $image[0]->image_low : base_url() . 'assets/img/product/1.jpg' ?><!--"-->
<!--                                            alt=""/></a>-->
<!--                                </div>-->
<!--                                <div class="product-content product-content-right">-->
<!--                                    <div class="pro-title">-->
<!--                                        <h4>-->
<!--                                            <a href="--><?php //echo base_url('Product/detail/' . url_title($slideitem['name'], '-', true) . '?id=' . $slideitem['id']) ?><!--">--><?php //echo $slideitem['name']; ?><!--</a>-->
<!--                                        </h4>-->
<!--                                    </div>-->
<!--                                    <div class="price-box">-->
<!--                                        --><?php //if ($slideitem['discount'] > 0) { ?>
<!--                                            <span-->
<!--                                                class="price product-price"><strike>--><?php //echo "Rp" . number_format($slideitem['priceAfterMargin']); ?><!--</strike><br>-->
<!--                                                    <span-->
<!--                                                    style="color:red;font-size: 12px;">-->
<!--                                                        --><?php //echo number_format($slideitem['priceNett']); ?><!--</span>-->
<!--                                            </span>-->
<!--                                        --><?php //} else { ?>
<!--                                            <span class="price product-price"-->
<!--                                                  style="margin-bottom: 28px;">--><?php //echo "Rp" . number_format($slideitem['priceNett']); ?><!--</span>-->
<!--                                        --><?php //} ?>
<!--                                    </div>-->
<!--                                </div>-->
<!--                            </div>-->
<!--                        --><?php //} ?>
<!--                    </div>-->
<!--                </div>-->
            </div>

<!--            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">-->
<!--                <div class="slider-sidebar">-->
<!--                    <div class="slider-single-img mb-20">-->
<!--                        --><?php
//                        if (!empty($banner)) :
//                            if ($banner->code == 200) {
//                                foreach ($banner->data as $key => $valBs) {
//                                    if($valBs->BannerCategoryId == 6) {
//                                    echo '<a href="#">
//                                                <img width="100%" class="img_a" src="' . $valBs->name . '" alt="" />
//                                                <img width="100%" class="img_b" src="' . $valBs->name . '" alt="" />
//                                            </a>';
//                                    }
//                                }
//                            } else {
//                                echo 'Error while get data from API';
//                            }
//                        endif;
//
//                        ?>
<!--                    </div>-->
<!--                    <div class="slider-single-img none-sm">-->
<!--                        --><?php
//                            if (!empty($banner)) :
//                                if ($banner->code == 200) {
//                                    foreach ($banner->data as $key => $valBs) {
//                                        if($valBs->BannerCategoryId == 8) {
//                                            echo '<a href="#">
//                                                        <img width="100%" class="img_a" src="' . $valBs->name . '" alt="" />
//                                                        <img width="100%" class="img_b" src="' . $valBs->name . '" alt="" />
//                                                    </a>';
//                                        }
//                                    }
//                                } else {
//                                    echo 'Error while get data from API';
//                                }
//                            endif;
//
//                        ?>
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
        </div>
    </div>
</div>

