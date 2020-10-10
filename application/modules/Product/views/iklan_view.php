<?php //echo '<pre>';echo print_r($iklanV);die;?>
<!-- banner-area-start -->
<style type="text/css">
    #header_merchant{
        width: 100%;
        z-index: 1;
        text-align: center;
        display: block;
        margin-top:-60px;
    }
</style>
<div class="banner-area- ptb-60">
    <div class="container">
        <div id="header_merchant">
            <img class="header_merchant" src="<?php if(@getimagesize($iklanV->headerFile)) {echo $iklanV->headerFile;}else{echo base_url().'assets/img/banner_merchant_default.png';} ?>" alt="<?php echo $iklanV->name; ?>" width="100%">
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="media">
                    <div class="media-left">
                        <a class="user-photo merchant-avatar" href="#">
                            <img class="media-object" src="<?php if(@getimagesize($iklanV->imageFile)) {echo $iklanV->imageFile;}else{echo base_url('assets/img/product/default-image.png');} ?>" alt="<?php echo $iklanV->name; ?>">
                        </a>
                    </div>
                    <div class="media-body">
                        <h4 class="pro-d-title-baru merchant-name"><?php echo $iklanV->name ?></h4>
                        <p><?php echo $iklanV->description ?></p>
                    </div>


                </div>
            </div>
        </div>
    </div>
</div>
<!-- banner-area-end -->

<!-- shop-area start -->
<div class="shop-area">
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <h2 class="page-heading mt-40">
                    <span class="cat-name">List Iklan</span>
                </h2>
            </div>
            <div class="shop-page-bar">
                <div class="row">
                    <?php foreach($iklanV->lists as $list) { ?>
                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <div class="single-product mb-30  white-bg">
                                <div class="product-img">
                                    <a href="<?php echo base_url('Product/iklanDetail/'.url_title($list->name,'-',true).'?advertisementId='.$iklanV->id.'&id='.$list->id) ?>"><img src="<?php echo isset($list->imageFile) ? $list->imageFile : base_url('assets/img/product/3.jpg'); ?>" alt="<?php $list->name; ?>" /></a>
                                </div>
                                <div class="product-content product-i">
                                    <div class="pro-title" style="margin-bottom: 15px;">
                                        <h4><a href="<?php echo base_url('Product/iklanDetail/'.url_title($list->name,'-',true).'?advertisementId='.$iklanV->id.'&id='.$list->id) ?>"><?php $list->name; ?></a></h4>
                                    </div>
                                    <div class="price-box">
                                        <h6 class="iklan-date"><i class="fa fa-calendar" style="color:#CCCCCC;"></i> <?php echo $this->tanggal->formatDateNoYear($this->timezone->convertUtc($list->startDate)); ?> - <?php echo $this->tanggal->formatDate($this->timezone->convertUtc($list->endDate)); ?></h6>
                                    </div>
                                </div>
                                <span class="new">new</span>
                            </div>
                        </div>
                    <?php } ?>
                </div>
<!--                <div class="content-sortpagibar">-->
<!--                    <div class="product-count display-inline">-->
<!--                        Showing --><?php //echo $config_pagination['page']?><!-- - --><?php //echo $config_pagination['after_page']?><!-- of --><?php //echo $config_pagination['total_rows']?><!-- items-->
<!--                    </div>-->
<!--                    <ul class="shop-pagi display-inline">-->
<!--                        --><?php //echo $links; ?>
<!--                    </ul>-->
<!--                </div>-->
            </div>
        </div>
    </div>
</div>