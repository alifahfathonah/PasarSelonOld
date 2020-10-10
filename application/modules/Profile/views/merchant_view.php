<?php //echo '<pre>';echo print_r($merchantInfo);die;?>
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
            <img class="header_merchant" src="<?php if(@getimagesize(HEADER_MERCHANT.$merchantInfo->headerFile)) {echo HEADER_MERCHANT.$merchantInfo->headerFile;}else{echo base_url().'assets/img/banner_merchant_default.png';} ?>" alt="<?php echo $merchantInfo->name; ?>" width="100%"></div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="media">
                    <div class="media-left">
                        <a class="user-photo merchant-avatar" href="#">
                            <img class="media-object" src="<?php if(@getimagesize(IMG_AVATAR_FILE.$merchantInfo->logoFile)) {echo IMG_AVATAR_FILE.$merchantInfo->logoFile;}else{echo base_url('assets/img/product/default-image.png');} ?>" alt="<?php echo $merchantInfo->name; ?>">
                        </a>
                    </div>
                    <div class="media-body">
                        <h4 class="pro-d-title-baru merchant-name"><?php echo $merchantInfo->name; ?></h4>
                        <h5 class="user-email merchant-motto"><i>" <?php echo $merchantInfo->motto; ?> "</i></h5>
                        <h6>Bergabung sejak <?php echo $this->tanggal->formatDateTime($merchantInfo->createdAt); ?></h6>
                    </div>


                </div>
            </div>
        </div>
    </div>
</div>
<!-- banner-area-end -->


<script type="text/javascript">
    $(function() {
        $("#kirim-pesan").on('submit', function (e) {
            e.preventDefault();
            var form = $(this);
            var button = $(this).find('#kirim-pesan-btn');
            button.button('loading');

            $.ajax({
                type: 'POST',
                url: '<?php echo base_url('Profile/Merchant/kirimPesan'); ?>',
                data: form.serialize(),
                dataType: "json",
                success: function (data) {
                    if (data.status == 'success') {

                        alert("Sukses mengirim pesan");
                        form.fadeOut();
//                        return false;
                    } else {
                        alert("Gagal mengirim pesan");
                    }

                    button.button('reset');
                }
            });
        });
    })
</script>
<!-- Modal -->
<?php if(!($this->session->userdata('logged_in'))) { ?>
    <div id="kiselPesanModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <form id="kirim-pesan" method="post">
                <input type="hidden" name="merchantId" value="<?php echo $merchantInfo->id; ?>">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Silahkan Login Terlebih Dahulu !</h4>
                    </div>
                    <div class="modal-body" style="text-align: center;">
                        <p>Silahkan klik tombol dibawah ini untuk menuju ke halaman login</p><br>
                        <a href="<?php echo base_url('Login') ?>" class="btn btn-login">Login</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
<?php }else{ ?>
<div id="kiselPesanModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <form id="kirim-pesan" method="post">
            <input type="hidden" name="merchantId" value="<?php echo $merchantInfo->id; ?>">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Kirim Pesan</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="subjek">Subjek</label>
                        <input id="subjek" name="subject" placeholder="Judul pesan anda">
                    </div>
                    <div class="form-group">
                        <label for="pesan">Pesan</label>
                        <textarea id="pesan" name="message"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="kirim-pesan-btn" type="submit" class="btn btn-login" data-loading-text="<i class='fa fa-spinner fa-pulse fa-fw'></i> Kirim"><i class="fa fa-paper-plane"></i> Kirim</button>
                </div>
            </div>
        </form>
    </div>
</div>
<?php } ?>
<!-- Modal End -->

<!-- pro-info-area start -->
<div class="pro-info-area">
    <div class="container">
        <div class="pro-info-box">
            <!-- Nav tabs -->
            <ul class="pro-info-tab merchant-info" role="tablist">
                <li class="active"><a href="#home3" data-toggle="tab">Tentang Merchant</a></li>
<!--                <li><a href="#profile3" data-toggle="tab">Ulasan</a></li>-->
<!--                <li><a href="#messages3" data-toggle="tab">Rating</a></li>-->
                <li class="pull-right"><a class="kirim-pesan-btn btn" href="#kiselPesanModal" data-toggle="modal" data-target="#kiselPesanModal"><i class="fa fa-envelope"></i> Kirim Pesan</a></li>
            </ul>

            <!-- Tab panes -->
            <div class="tab-content" id="merchant-profile-tab">
                <div class="tab-pane active" id="home3">
                    <div class="pro-desc">
                        <div class="col-sm-12">
                            <p><?php echo $merchantInfo->description; ?></p>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="profile3">
                    <div class="pro-desc">
                        <div class="col-sm-12">
                            <?php $products = $this->templates_model->retrieveUlasanByMerchant($merchantInfo->id); ?>
                            <h4 class="total-comments mb-30 pb-15"><?php echo count($products); ?> Ulasan</h4>
                            <ul class="media-list comment-list mt-30 mb-30">
                                <!-- Comment Item start-->
                                <?php
                                if (!empty($products)) {
                                    foreach ($products as $review) {
                                        ?>
                                        <li class="media">
                                            <div class="media-left">
                                                <a href="#">
                                                    <img alt="Jonathon Doe"
                                                         src="<?php if(@getimagesize(AVATAR_FILE.$result->avatarFile)) {echo AVATAR_FILE.$result->avatarFile; }else{echo base_url('assets/img/blog/man-4.jpg'); } ?>"
                                                         class="avatar">
                                                </a>
                                            </div>
                                            <div class="media-body">
                                                <p><?php echo $review->text; ?></p>
                                            </div>
                                            <h6 style="margin-top:15px;"><?php echo $review->firstName.' '.$review->lastName; ?></h6>
                                        </li>
                                    <?php }
                                } else { ?>
                                    <li class="media">
                                        Belum ada ulasan untuk produk ini
                                    </li>
                                <?php } ?>
                                <!-- End Comment Item -->
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="messages3">
                    <div class="pro-desc">
                        <div class="col-sm-12">
                            <ul class="media-list comment-list mt-30">
                                <!-- Comment Item start-->
                                <li class="media">
                                    <div class="pro-rating" align="center">
                                        <a href="#"><i class="fa fa-star"></i></a>
                                        <a href="#"><i class="fa fa-star"></i></a>
                                        <a href="#"><i class="fa fa-star"></i></a>
                                        <a href="#"><i class="fa fa-star"></i></a>
                                        <a href="#"><i class="fa fa-star-o"></i></a>
                                    </div>
                                </li>
                            </ul>
                            <br>
                            <h6 align="center">Total Rating :23 Orang</h6>

                            <br>
                            <h4 align="center">4/7</h4>
                            <!-- End Comment Item -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- pro-info-area end -->

<!-- shop-area start -->
<div class="shop-area">
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <h2 class="page-heading mt-40">
                    <span class="cat-name">List Produk</span>
                </h2>
            </div>
            <div class="shop-page-bar">
                <div class="row">
                    <?php
                    $i = 1;
                    foreach($merchantProducts as $key => $row) :
                        $image = json_decode($row->images);
                        ?>
                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <div class="single-product mb-30  white-bg">
                                <?php if($row->discount > 0) { ?>
                                    <span class="discount-label"><?php echo $row->discount; ?>%</span>
                                <?php } ?>
                                <div class="product-img">
                                    <a href="<?php echo base_url('Product/detail/'.url_title($row->name,'-',true).'?id='.$row->id) ?>"><img src="<?php echo isset($image[0]->thumbnail)?IMG_PRODUCT.$image[0]->thumbnail:base_url().'assets/img/product/3.jpg'?>" alt="" /></a>
                                </div>
                                <div class="product-content product-i">
                                    <div class="pro-title">
                                        <h4><a href="<?php echo base_url('Product/detail/'.url_title($row->name,'-',true).'?id='.$row->id) ?>"><?php echo $row->name?></a></h4>
                                    </div>
                                    <div class="price-box">
                                        <?php if($row->discount > 0) { ?>
                                            <span class="price product-price"><h4 class="price-h"><strike><?php echo "Rp".number_format($row->price + ($row->price * $row->priceMargin / 100)); ?></strike></h4><br><h5 class="price-h price-h-disc"><?php echo number_format(($row->price + ($row->price * $row->priceMargin / 100))-($row->price*$row->discount/100)); ?></h5></span>
                                        <?php }else{ ?>
                                            <span class="price product-price"><h4 class="price-h" style="height: 40px;"><?php echo "Rp".number_format($row->price + ($row->price * $row->priceMargin / 100)); ?></h4></span>
                                        <?php } ?>
                                    </div>
                                </div>
                                <span class="new">new</span>
                            </div>
                        </div>
                        <?php if($i % 6 == 0 ) {echo '</div><div class="row">';} $i++; ?>
                    <?php endforeach; ?>
                </div>
                <div class="content-sortpagibar">
                    <div class="product-count display-inline">
                        Showing <?php echo $config_pagination['page']?> - <?php echo $config_pagination['after_page']?> of <?php echo $config_pagination['total_rows']?> items
                    </div>
                    <ul class="shop-pagi display-inline">
                        <?php echo $links; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

