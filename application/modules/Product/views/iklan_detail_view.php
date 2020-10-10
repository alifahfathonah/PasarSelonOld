<?php //echo "<pre>"; print_r($iklanDetail2); ?>
<div class="container" style="padding: 30px 0">
    <div class="col-sm-8">
        <div class="iklan-detail-content clearfix">
            <div id="header_merchant">
                <img class="header_merchant" src="<?php if(@getimagesize($iklanDetail2->headerFile)) {echo $iklanDetail2->headerFile;}else{echo base_url().'assets/img/banner_merchant_default.png';} ?>" alt="<?php echo $iklanDetail2->name; ?>" width="100%">
            </div>
            <!--breadcrumb start -->
            <div class="breadcrumb-area">
                <div class="container">
                    <?php echo $breadcrumbs ?>
                </div>
            </div>
            <!-- breadcrumb end -->
            <div class="row">
                <hr>
            </div>
            <div class="col-sm-12">
                <h1 style="margin-bottom: 30px;"><?php echo $iklanDetail2->name ?></h1>
                <h4>Deskripsi</h4>
                <p><?php echo $iklanDetail2->description; ?></p>
            </div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="iklan-detail-content clearfix">
            <div class="row">
                <div class="info-title-wrap">
                    <h3 align="center" style="margin-top: 30px;">Info Iklan</h3>
                </div>
                <div class="row">
                    <hr>
                </div>
                <div class="col-sm-12">
                    <p align="center" class="postbox-content-title text-secondary"> Periode Promo</p>
                    <p align="center" class="postbox-content__p"><b><?php echo $this->tanggal->formatDateNoYear($this->timezone->convertUtc($iklanDetail2->startDate)); ?> - <?php echo $this->tanggal->formatDate($this->timezone->convertUtc($iklanDetail2->endDate)); ?></b></p>
                </div>
            </div>
        </div>
    </div>
</div>