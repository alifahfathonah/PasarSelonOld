<?php
    $ProductCategory = $this->Section_model->getProductSubCategory();
//    echo '<pre>';print_r($ProductCategory);die;
    if(is_object($ProductCategory) && $ProductCategory->code==200){
        foreach($ProductCategory->data as $key) {
            if($key->layouts == 1) {
?>
<div class="fashion-tab-area list-category-layout-1">
    <div class="container">
<!--        <h6>Layout 1</h6>-->
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="kategori-fashion-pria product-tab-menu white-bg border-2" style="background: <?php echo $key->color->code; ?>;line-height: normal;">
                    <ul>
                        <li class="active">
                            <a><?php echo $key->category->name; ?></a>
                        </li>
                        <?php $index=0; foreach($key->sub_category as $key_sub => $sub) { $index++ ?>
                            <li class="sub-category-name">
                                <!--                                <a href="#--><?php //echo $key_sub; ?><!--" data-toggle="tab"><img src="--><?php //echo base_url('assets/img/product-subcategory/'.$sub->icon) ?><!--" alt="" />--><?php //echo $sub->name; ?><!--</a>-->
                                <a href="<?php echo base_url('Product/index?kategori='.$sub->id); ?>"><?php echo $sub->name; ?></a>
                            </li>
                            <?php if($key_sub == 2) break;} ?>
                    </ul>
                </div>
            </div>
        </div>
        <!---SUB TAB KATEGORI PRODUK START-->
        <div class="tab-content tab-content-item">
            <div class="tab-pane fade active in">
                <div class="row">
                    <?php if(count($key->products) > 0) {  ?>
                    <div class="col-lg-4 col-md-4 col-sm-6  col-xs-12 pad">
                        <div class="col-sm-12">
                            <img src="<?php echo base_url('assets/img/product/426x287.png') ?>" width="100%">
                        </div>
                        <div class="col-sm-12">
                            <img src="<?php echo base_url('assets/img/product/426x287.png') ?>" width="100%">
                        </div>
                    </div>
                    <div class="padd-2 col-sm-8  col-xs-12 dotted-style-1">
                        <div class="together-single-product ">
                            <?php for($ab=1;$ab<7;$ab++) {
                                if(isset($key->products[$ab])) {
                                ?>
                            <div class="single-product white-bg col-sm-3">
                                <div class="product-img product-container-img product-img-home" style="width: 100%">
                                    <a href="<?php echo base_url('Product/detail/'.url_title($key->products[$ab]->name,'-',true).'?id='.$key->products[$ab]->id) ?>"><img src="<?php if(count($key->products[$ab]->images) > 0) {echo IMG_PRODUCT.$key->products[$ab]->images[0]->thumbnail;}else{echo base_url('assets/img/product/2.jpg');} ?>" alt=""/></a>
                                </div>
                                <div class="product-content product-i">
                                    <div class="pro-title">
                                        <h4><a href="<?php echo base_url('Product/detail/'.url_title($key->products[$ab]->name,'-',true).'?id='.$key->products[$ab]->id) ?>"><?php echo $key->products[$ab]->name; ?></a></h4>
                                    </div>
                                    <div class="price-box">
                                        <?php if($key->products[$ab]->discount > 0) { ?>
                                            <span class="price product-price"><h4 class="price-h"><strike><?php echo "Rp".number_format($key->products[$ab]->AfterMargin); ?></strike></h4><br><h5 class="price-h price-h-disc"><?php echo "Rp".number_format($key->products[$ab]->AfterMargin - $key->products[$ab]->DiscountPrice); ?></h5></span>
                                        <?php }else{ ?>
                                            <span class="price product-price"><h4 class="price-h"><?php echo "Rp".number_format($key->products[$ab]->AfterMargin); ?></h4></span>
                                        <?php } ?>
                                    </div>
                                </div>
                                <span class="new"><?php echo strtolower($key->products[$ab]->condition); ?></span>
                            </div>
                            <?php }else{ echo '<img src="'.base_url('assets/img/product/284x277.png').'">'; }} ?>
                        </div>
                    </div>
                    <?php }else{?>
                    <div class="col-sm-12">
                        <img src="<?php echo base_url('assets/img/no-product.png'); ?>">
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>
                <?php }elseif($key->layouts == 2) { ?>
                <div class="fashion-tab-area list-category-layout-2">
                    <div class="container">
<!--                        <h6>Layout 2</h6>-->
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <div class="kategori-fashion-pria product-tab-menu white-bg border-2" style="background: <?php echo $key->color->code; ?>;line-height: normal;">
                                    <ul>
                                        <li class="active">
                                            <a><?php echo $key->category->name; ?></a>
                                        </li>
                                        <?php $index=0; foreach($key->sub_category as $key_sub => $sub) { $index++ ?>
                                            <li class="sub-category-name">
                                                <!--                                <a href="#--><?php //echo $key_sub; ?><!--" data-toggle="tab"><img src="--><?php //echo base_url('assets/img/product-subcategory/'.$sub->icon) ?><!--" alt="" />--><?php //echo $sub->name; ?><!--</a>-->
                                                <a href="<?php echo base_url('Product/index?kategori='.$sub->id); ?>"><?php echo $sub->name; ?></a>
                                            </li>
                                            <?php if($key_sub == 2) break;} ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <!---SUB TAB KATEGORI PRODUK START-->
                        <div class="tab-content tab-content-item">
                            <div class="tab-pane fade active in">
                                <div class="row">
                                    <div class="together-single-product clearfix">
                                        <div class="col-sm-12">
                                            <?php foreach ($key->products as $index => $product) {
                                                if(!is_null($product)) {
                                                ?>
                                            <div class="single-product white-bg col-sm-2">
                                                <?php if($product->discount > 0) { ?>
                                                    <span class="discount-label"><?php echo $product->discount; ?>%</span>
                                                <?php } ?>
                                                <div class="product-img product-container-img product-img-home" style="width: 100%">
                                                    <a href="<?php echo base_url('Product/detail/'.url_title($product->name,'-',true).'?id='.$product->id) ?>"><img src="<?php if(count($product->images) > 0) {echo IMG_PRODUCT.$product->images[0]->thumbnail;}else{echo base_url('assets/img/product/2.jpg');} ?>" alt=""></a>
                                                </div>
                                                <div class="product-content product-i">
                                                    <div class="pro-title">
                                                        <h4><a href="<?php echo base_url('Product/detail/'.url_title($product->name,'-',true).'?id='.$product->id) ?>"><?php echo $product->name; ?></a></h4>
                                                    </div>
                                                    <div class="price-box">
                                                        <?php if($product->discount > 0) { ?>
                                                            <span class="price product-price"><h4 class="price-h"><strike><?php echo "Rp".number_format($product->AfterMargin); ?></strike></h4><br><h5 class="price-h price-h-disc"><?php echo "Rp".number_format($product->AfterMargin - $product->DiscountPrice); ?></h5></span>
                                                        <?php }else{ ?>
                                                            <span class="price product-price"><h4 class="price-h"><?php echo "Rp".number_format($product->AfterMargin); ?></h4></span>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                                <span class="new"><?php echo strtolower($product->condition); ?></span>
                                            </div>
                                            <?php }else{ echo '<img src="'.base_url('assets/img/product/423x287.png').'">';}} ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php } elseif($key->layouts == 3) { ?>
                <div class="fashion-tab-area list-category-layout-3">
                    <div class="container">
<!--                        <h6>Layout 3</h6>-->
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <div class="kategori-fashion-pria product-tab-menu white-bg border-2" style="background: <?php echo $key->color->code; ?>;line-height: normal;">
                                    <ul>
                                        <li class="active">
                                            <a><?php echo $key->category->name; ?></a>
                                        </li>
                                        <?php foreach ($key->sub_category as $subcategory) { ?>
                                        <li class="sub-category-name">
                                            <a href="<?php echo base_url('Product/index?kategori='.$subcategory->id); ?>"><?php echo $subcategory->name; ?></a>
                                        </li>
                                        <?php } ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <!---SUB TAB KATEGORI PRODUK START-->
                        <div class="tab-content tab-content-item">
                            <div class="tab-pane fade active in">
                                <div class="row">
                                    <div class="together-single-product clearfix">
                                        <div class="col-sm-4 no-right-padd">
                                            <div class="single-product white-bg" style="max-height: 574px;">
                                                <div class="row">
                                                    <?php if($key->images != null) { ?>
                                                    <a href="<?php echo $key->images[0]->link; ?>">
                                                        <img src="<?php if(@getimagesize(HEADER_MERCHANT.$key->images[0]->image)) { echo HEADER_MERCHANT.$key->images[0]->image; }else{ echo base_url('assets/img/product/2.jpg'); }; ?>" width="100%">
                                                    </a>
                                                    <?php }else{ ?>
                                                        <img src="<?php echo base_url('assets/img/product/433x574.png'); ?>" width="100%">
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-8 no-left-padd">
                                            <?php if($key->products != null) { ?>
                                            <?php foreach ($key->products as $product) {
                                                if(!is_null($product)) {
                                                ?>
                                            <div class="single-product white-bg col-sm-3">
                                                <?php if($product->discount > 0) { ?>
                                                    <span class="discount-label"><?php echo $product->discount; ?>%</span>
                                                <?php } ?>
                                                <div class="product-img product-container-img product-img-home" style="width: 100%">
                                                    <a href="<?php echo base_url('Product/detail/'.url_title($product->name,'-',true).'?id='.$product->id) ?>"><img src="<?php if(count($product->images) > 0) {echo IMG_PRODUCT.$product->images[0]->thumbnail;}else{echo base_url('assets/img/product/2.jpg');} ?>" alt=""></a>
                                                </div>
                                                <div class="product-content product-i">
                                                    <div class="pro-title">
                                                        <h4><a href="<?php echo base_url('Product/detail/'.url_title($product->name,'-',true).'?id='.$product->id) ?>"><?php echo $product->name; ?></a></h4>
                                                    </div>
                                                    <div class="price-box">
                                                        <?php if($product->discount > 0) { ?>
                                                            <span class="price product-price"><h4 class="price-h"><strike><?php echo "Rp".number_format($product->AfterMargin); ?></strike></h4><br><h5 class="price-h price-h-disc"><?php echo "Rp".number_format($product->AfterMargin - $product->DiscountPrice); ?></h5></span>
                                                        <?php }else{ ?>
                                                            <span class="price product-price"><h4 class="price-h"><?php echo "Rp".number_format($product->AfterMargin); ?></h4></span>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                                <span class="new"><?php echo strtolower($product->condition); ?></span>
                                            </div>
                                            <?php }else{ echo '<img src="'.base_url('assets/img/product/284x277.png').'">'; }}} ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <br>
                <?php }elseif(in_array($key->layouts, array(7,8))) { ?>
<!--                <pre>--><?php //print_r($key); ?><!--</pre>-->
                    <div class="fashion-tab-area list-category-layout-3">
                        <div class="container">
    <!--                        <h6>Layout 7</h6>-->
                            <h2>Kimart Express</h2>
                            <!---SUB TAB KATEGORI PRODUK START-->
                            <div class="tab-content tab-content-item">
                                <div class="tab-pane fade active in">
                                    <div class="row">
                                        <div class="together-single-product clearfix">
                                            <div class="col-sm-4 no-right-padd">
                                                <div class="single-product white-bg" style="max-height: 554px;">
                                                    <div class="row">
                                                        <?php if($key->images != null) { ?>
                                                        <a href="<?php echo $key->images[0]->link; ?>">
                                                            <img src="<?php if(@getimagesize(HEADER_MERCHANT.$key->images[0]->image)) { echo HEADER_MERCHANT.$key->images[0]->image; }else{ echo base_url('assets/img/product/2.jpg'); }; ?>" width="100%">
                                                        </a>
                                                        <?php }else{ ?>
                                                            <img src="<?php echo base_url('assets/img/product/433x574.png'); ?>" width="100%">
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-8 no-left-padd">
                                                <?php if($key->products != null) { ?>
                                                <?php foreach ($key->products as $product) {
                                                    if(!is_null($product)) {
                                                    ?>
                                                <div class="single-product white-bg col-sm-3">
                                                    <?php if($product->discount > 0) { ?>
                                                        <span class="discount-label"><?php echo $product->discount; ?>%</span>
                                                    <?php } ?>
                                                    <div class="product-img product-container-img product-img-home" style="width: 100%">
                                                        <a href="<?php echo base_url('Product/detail/'.url_title($product->name,'-',true).'?id='.$product->id) ?>"><img src="<?php if(count($product->images) > 0) {echo IMG_PRODUCT.$product->images[0]->thumbnail;}else{echo base_url('assets/img/product/2.jpg');} ?>" alt=""></a>
                                                    </div>
                                                    <div class="product-content product-i">
                                                        <div class="pro-title">
                                                            <h4 style="font-size:12px">
                                                                <a title="<?php echo $product->name;?>" href="<?php echo base_url('Product/detail/'.url_title($product->name,'-',true).'?id='.$product->id) ?>">
                                                                    <?php 
                                                                        $strln = strlen($product->name);
                                                                        echo substr($product->name, 0,20);
                                                                        echo ( $strln > 20 ) ? '...':'';
                                                                    ?>
                                                                        
                                                                </a>
                                                            </h4>
                                                            <a href="<?php echo base_url('Profile/Merchant/'.$product->merchant->name.'?id='.$product->merchant->id) ?>"><img width="20" class="produk-met" src="<?php echo base_url('assets/img/store.png') ?>"> <?php echo $product->merchant->name; ?></a>
                                                        </div>
                                                        <div class="price-box">
                                                            <?php if($product->discount > 0) { ?>
                                                                <span class="price product-price"><h4 class="price-h"><strike><?php echo "Rp".number_format($product->AfterMargin); ?></strike></h4><br><h5 class="price-h price-h-disc"><?php echo "Rp ".number_format($product->AfterMargin - $product->DiscountPrice); ?></h5></span>
                                                            <?php }else{ ?>
                                                                <span class="price product-price"><h4 class="price-h"><?php echo "Rp ".number_format($product->AfterMargin); ?></h4></span>
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                    <span class="new"><?php echo strtolower($product->condition); ?></span>
                                                </div>
                                                <?php }else{ echo '<img src="'.base_url('assets/img/product/284x277.png').'">'; }}} ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>

                <?php }elseif($key->layouts == 4) { ?>
                <div class="fashion-tab-area list-category-layout-4">
                    <div class="container">
<!--                        --><?php //echo '<pre>'; print_r($key); echo '</pre>'; ?>
<!--                        <h6>Layout 4</h6>-->
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <div class="kategori-fashion-pria product-tab-menu white-bg border-2" style="background: <?php echo $key->color->code; ?>;line-height: normal;">
                                    <ul>
                                        <li class="active">
                                            <a><?php echo $key->category->name; ?></a>
                                        </li>
                                        <?php foreach ($key->sub_category as $sub_category) { ?>
                                        <li class="sub-category-name">
                                            <a href="#"><?php echo $sub_category->name; ?></a>
                                        </li>
                                        <?php } ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <!---SUB TAB KATEGORI PRODUK START-->
                        <div class="tab-content tab-content-item">
                            <div class="tab-pane fade active in">
                                <div class="row">
                                    <div class="together-single-product clearfix">
                                        <div class="col-sm-4 no-right-padd">
                                            <div class="single-product white-bg single-tab-img" style="max-height: 554px;">
                                                <div class="row">
                                                    <?php if($key->images != null) { ?>
                                                    <a href="<?php echo $key->images[0]->link; ?>"><img src="<?php if(@getimagesize(HEADER_MERCHANT.$key->images[0]->image)) { echo HEADER_MERCHANT.$key->images[0]->image; }else{ echo base_url('assets/img/product/2.jpg'); }; ?>" width="100%"></a>
                                                    <?php }else{ echo '<img src="'.base_url('assets/img/product/433x574.png').'" width="100%">'; } ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-8 no-left-padd">
                                            <div class="clearfix row-list-category">
                                                <div class="single-product white-bg col-sm-6 item-list-category-product">
                                                    <div class="row">
                                                        <?php if($key->images != null) { ?>
                                                        <a href="<?php echo $key->images[1]->link; ?>"><img src="<?php if(@getimagesize(HEADER_MERCHANT.$key->images[1]->image)) { echo HEADER_MERCHANT.$key->images[1]->image; }else{ echo base_url('assets/img/product/2.jpg'); }; ?>" width="100%"></a>
                                                        <?php }else{ echo '<img src="'.base_url('assets/img/product/426x277.png').'" width="100%">' ; } ?>
                                                    </div>
                                                </div>
                                                <div class="single-product white-bg col-sm-6 item-list-category-product">
                                                    <div class="row">
                                                        <a href="<?php echo isset($key->images[2]->link) ? HEADER_MERCHANT.$key->images[2]->link : '#'; ?>"><img src="<?php echo isset($key->images[2]->image) ? HEADER_MERCHANT.$key->images[2]->image :base_url('assets/img/product/426x144.png'); ?>" width="100%"></a>
                                                    </div>
                                                    <div class="row">
                                                        <a href="<?php echo isset($key->images[3]->link) ? HEADER_MERCHANT.$key->images[3]->link : '#'; ?>"><img src="<?php echo isset($key->images[3]->image) ? HEADER_MERCHANT.$key->images[3]->image :base_url('assets/img/product/426x144.png'); ?>" width="100%"></a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="clearfix row-list-category">
                                                <?php foreach ($key->products as $product) { ?>
                                                <div class="single-product white-bg col-sm-3 item-list-category-product">
                                                    <?php if($product->discount > 0) { ?>
                                                        <span class="discount-label"><?php echo $product->discount; ?>%</span>
                                                    <?php } ?>
                                                    <div class="product-img product-container-img product-img-home" style="width: 100%">
                                                        <a href="<?php echo base_url('Product/detail/'.url_title($product->name,'-',true).'?id='.$product->id) ?>"><img src="<?php if(count($product->images) > 0) {echo IMG_PRODUCT.$product->images[0]->thumbnail;}else{echo base_url('assets/img/product/2.jpg');} ?>" alt=""></a>
                                                    </div>
                                                    <div class="product-content product-i">
                                                        <div class="pro-title">
                                                            <h4><a href="<?php echo base_url('Product/detail/'.url_title($product->name,'-',true).'?id='.$product->id) ?>"><?php echo $product->name; ?></a></h4>
                                                        </div>
                                                        <div class="price-box">
                                                            <?php if($product->discount > 0) { ?>
                                                                <span class="price product-price"><h4 class="price-h"><strike><?php echo "Rp".number_format($product->AfterMargin); ?></strike></h4><br><h5 class="price-h price-h-disc"><?php echo "Rp".number_format($product->AfterMargin - $product->DiscountPrice); ?></h5></span>
                                                            <?php }else{ ?>
                                                                <span class="price product-price"><h4 class="price-h"><?php echo "Rp".number_format($product->AfterMargin); ?></h4></span>
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                    <span class="new"><?php echo strtolower($product->condition); ?></span>
                                                </div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php }elseif($key->layouts == 5) { ?>
                <div class="list-category-3 list-category-layout-5">
                    <div class="container">
<!--                        <h6>Layout 5</h6>-->
                        <div class="row-table">
                            <div class="row-column sidebar-tb" style="background: white">
                                <div class="list-category-sidebar" style="border-color: <?php echo $key->color->code; ?>">
                                    <h2 class="list-category-title"><span class="list-category-title-icon"><img src="<?php echo $key->category->icon; ?>" width="100%"></span><?php echo $key->category->name; ?></h2>
                                    <ul class="list-category-sidebar-list">
                                        <?php foreach ($key->sub_category as $sub_category) { ?>
                                            <li><a href="<?php echo base_url('Product/index?kategori='.$sub_category->id); ?>"><?php echo $sub_category->name; ?></a></li>
                                        <?php } ?>
                                    </ul>
                                </div>
                            </div>
                            <div class="row-column content-tb" style="background: white">
                                <div class="clearfix row-list-category">
                                    <?php $limit=1; foreach ($key->products as $products) { ?>
                                        <div class="single-product white-bg col-sm-3 item-list-category-product">
                                            <div class="product-img product-container-img product-img-home" style="width: 100%">
                                                <a href="<?php echo base_url('Product/detail/'.url_title($products->name,'-',true).'?id='.$products->id) ?>"><img src="<?php if(count($products->images) > 0) {echo IMG_PRODUCT.$products->images[0]->thumbnail;}else{echo base_url('assets/img/product/2.jpg');} ?>" alt=""></a>
                                            </div>
                                            <div class="product-content product-i">
                                                <div class="pro-title">
                                                    <h4><a href="<?php echo base_url('Product/detail/'.url_title($products->name,'-',true).'?id='.$products->id) ?>"><?php echo $products->name; ?></a></h4>
                                                </div>
                                                <div class="price-box">
                                                    <span class="price product-price"><h4 class="price-h">Rp<?php echo number_format($products->AfterMargin - $products->discount); ?></h4></span>
                                                </div>
                                            </div>
                                            <span class="new"><?php echo strtolower($products->condition); ?></span>
                                        </div>
                                        <?php $limit++; if($limit>4) break;} ?>
                                </div>
                                <div class="clearfix row-list-category">
                                    <?php
                                    $images = $key->images;
                                    for ($i=1;$i<=3;$i++) { ?>
                                        <?php if($images[$i] != null) { ?>
                                            <div class="single-product white-bg col-sm-4 item-list-category-product no-padding">
                                                <div class="slider-single-img">
                                                    <a href="<?php echo $images->link; ?>">
                                                        <img width="100%" class="img_a" src="<?php echo HEADER_MERCHANT.$images[$i]->image; ?>" alt="">
                                                        <img width="100%" class="img_b" src="<?php echo HEADER_MERCHANT.$images[$i]->image; ?>" alt="">
                                                    </a>
                                                </div>
                                            </div>
                                        <?php }else{ echo '<div class="single-product white-bg col-sm-4 item-list-category-product no-padding"><img src="'.base_url('assets/img/product/313x287.png').'" width="100%"></div>'; } ?>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <br>
                <?php }elseif($key->layouts == 6) { //echo '<pre>'; print_r($key); ?>
                <div class="fashion-tab-area list-category-layout-1">
                    <div class="container">
<!--                        <h6>Layout 6</h6>-->
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <div class="kategori-fashion-pria product-tab-menu white-bg border-2" style="background: <?php echo $key->color->code; ?>;line-height: normal;">
                                    <ul>
                                        <li class="active">
                                            <a><?php echo $key->category->name; ?></a>
                                        </li>
                                        <?php $index=0; foreach($key->sub_category as $key_sub => $sub) { $index++ ?>
                                            <li class="sub-category-name">
                                                <!--                                <a href="#--><?php //echo $key_sub; ?><!--" data-toggle="tab"><img src="--><?php //echo base_url('assets/img/product-subcategory/'.$sub->icon) ?><!--" alt="" />--><?php //echo $sub->name; ?><!--</a>-->
                                                <a href="<?php echo base_url('Product/index?kategori='.$sub->id); ?>"><?php echo $sub->name; ?></a>
                                            </li>
                                            <?php if($key_sub == 2) break;} ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <!---SUB TAB KATEGORI PRODUK START-->
                        <div class="tab-content tab-content-item">
                            <div class="tab-pane fade active in">
                                <div class="row">
                                    <?php if(count($key->images) > 0) {  ?>
                                        <div class="col-lg-4 col-md-4 col-sm-6  col-xs-12 pad">
                                            <div class="single-product white-bg" style="background: none;padding-left: 15px;max-height: 548px;">
                                                <?php if(isset($key->images[0])) { ?>
                                                    <a href="<?php echo $key->images[0]->link; ?>"><img src="<?php if(@getimagesize(HEADER_MERCHANT.$key->images[0]->image)) { echo HEADER_MERCHANT.$key->images[0]->image; }else{ echo base_url('assets/img/product/433x574.png'); }; ?>" width="100%"></a>
                                                <?php }else{ echo "Tidak ada Produk"; } ?>
                                            </div>
                                        </div>
                                        <div class="padd-2 col-sm-8  col-xs-12 dotted-style-1">
                                            <div class="together-single-product ">
                                                <?php for($ab=1;$ab<7;$ab++) {
                                                    if(isset($key->images[$ab])) {
                                                        ?>
                                                        <div class="single-product white-bg col-sm-4 no-padding">
                                                            <a href="<?php echo $key->images[$ab]->link; ?>"><img src="<?php if(@getimagesize(HEADER_MERCHANT.$key->images[$ab]->image)) { echo HEADER_MERCHANT.$key->images[$ab]->image; }else{ echo base_url('assets/img/product/284x277.png'); }; ?>" width="100%"></a>
                                                        </div>
                                                    <?php }else{ echo "Tidak ada produk ditampilkan"; }} ?>
                                            </div>
                                        </div>
                                    <?php }else{?>
                                        <div class="col-sm-12">
                                            <img src="<?php echo base_url('assets/img/layout-6.png'); ?>" width="100%">
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php }elseif($key->layouts == 9) { ?>
            <?php //echo '<pre>';print_r($iklan);die; ?>
            <div class="fashion-tab-area list-category-layout-2">
                <div class="container">
<!--                    <h6>Layout 9</h6>-->
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <div class="kategori-fashion-pria product-tab-menu white-bg border-2" style="background: <?php echo $key->color->code; ?>;line-height: normal;">
                                <ul>
                                    <li class="active">
                                        <a>Iklan</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!---SUB TAB KATEGORI PRODUK START-->
                    <div class="tab-content tab-content-item">
                        <div class="tab-pane fade active in">
                            <div class="row">
                                <div class="together-single-product clearfix">
                                    <div class="col-sm-12">
                                        <?php
                                        if(count($key->advertisement)>0) {
                                            foreach ($key->advertisement as $ik) {
//                                        echo '<pre>';print_r($ik);echo '</pre>';
                                                ?>
                                                <div class="single-product white-bg col-sm-2">
                                                    <div class="row">
                                                        <a href="<?php echo base_url('Product/iklan/'.url_title($ik->name,'-',true).'?id='.$ik->id); ?>">
                                                            <img src="<?php echo @getimagesize($ik->imageFile) ? $ik->imageFile : base_url('assets/img/product/2.jpg'); ?>" alt="promotion" width="100%">
                                                        </a>
                                                    </div>
                                                </div>
                                            <?php }} ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
                <?php } ?>
            <br>

<?php }}else{echo "gagal membuat produk kategori" ;} ?>
