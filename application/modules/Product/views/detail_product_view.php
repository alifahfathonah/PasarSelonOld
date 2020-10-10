<script type="text/javascript">
    $(function () {

//        if($('#block-cart').has('.cart-list-li')) {alert('sudah ada produk')}else{alert('belum ada')};

        $("#formDiscussion").on('submit', function (e) {
            e.preventDefault();
            var form = $(this);
            var button = $(this).find('#saveDiscussion');
            button.button('loading');

            $.ajax({
                type: 'POST',
                url: '<?php echo base_url('Product/saveDiscussion') ?>',
                data: form.serialize(),
                dataType: "json",
                success: function (data) {
                    if (typeof data.result !== 'undefined') {
//                        location.reload();

                        html = '<ul class="detail-discussion-wrapper" id="'+data.result.id+'">';
                        html += '<li class="media discussion-media">';
                        html += '<div class="media-left"><a href="#"><img alt="<?php echo $this->session->userdata("avatarFile"); ?>" src="<?php if(@getimagesize(AVATAR_FILE.$this->session->userdata("avatarFile"))) {echo AVATAR_FILE.$this->session->userdata("avatarFile"); }else{echo base_url('assets/img/blog/man-4.jpg'); } ?>" class="avatar"></a></div>';
                        html += '<div class="media-body"><div class="discussion-message">'+$('#question').val()+'</div><span><?php echo $this->tanggal->formatDateTime(date('Y-m-d H:m:s')); ?> <br> by ' + $('#customerName').val() + '</span><br></div>';
                        html += '</li></ul><form class="reply-discussion" method="post" style="margin-top:15px;padding-left:80px;"><input type="hidden" name="productDiscussionId" value="'+data.result.id+'"><textarea name="reply-message" placeholder="Ketik balasan anda"></textarea><button id="reply-button" class="btn" type="submit" data-loading-text="<i class='+"'fa fa-spinner fa-pulse fa-fw'"+'></i> Balas">Balas</button></form>';
                        $(html).prependTo($('#diskusi-list'));

                        document.getElementById('question').value = '';
                        $('#modal-success-add-discussion .modal-title').html('Berhasil');
                        $('#modal-success-add-discussion .modal-body').html('Berhasil menambah diskusi');
                        $('#modal-success-add-discussion').modal('show');
                        $('#tidak-ada-diskusi').remove();
                    } else {
                        alert("Gagal mengirim diskusi");
                    }
                    $('#saveDiscussion').button('reset');
                    console.log(data);
                }
            });

        });

        $(".reply-discussion").on('submit', function (e) {
            e.preventDefault();

            var form = $(this);
            var button = $(this).find('#reply-button');
            button.button('loading');

//            html = '';
//            html += '<li class="media">';
//            html += '<div class="media-left"><a href="#"><img alt="" src="<?php ////echo base_url(AVATAR_FILE.$this->session->userdata('user')->avatarFile)?>//" class="avatar"></a></div>';
//            html += '<div class="media-body"><p> ' + $('#question').val() + ' <br> ' + ' <small>Posted : ' + '<?php //echo $this->tanggal->formatDateTime(date('Y-m-d H:i:s')); ?>//' + ' <br> ' + $('#customerName').val() + '</p></div>';
//            html += '</li>';
//            $(html).prependTo($('#resultDiscussion'));

            <?php if($this->session->userdata('logged_in')) { ?>
            $.ajax({
                type: 'POST',
                url: '<?php echo base_url('Product/replyDiscussion') ?>',
                data: form.serialize(),
                dataType: "json",
                success: function (data) {
                    if (typeof data.result !== 'undefined' && data.result === 'OK') {
//                        location.reload();

                        html = '';
                        html += '<li class="media discussion-media">';
                        html += '<div class="media-left"><a href="#"><img alt="<?php echo $this->session->userdata("avatarFile"); ?>" src="<?php if(@getimagesize(AVATAR_FILE.$this->session->userdata("avatarFile"))) {echo AVATAR_FILE.$this->session->userdata("avatarFile"); }else{echo base_url('assets/img/blog/man-4.jpg'); } ?>" class="avatar"></a></div>';
                        html += '<div class="media-body"><div class="discussion-message">'+form.find('textarea[name="reply-message"]').val()+'</div><span><?php echo $this->tanggal->formatDateTime(date('Y-m-d H:m:s')); ?> <br> by <?php echo $this->session->userdata('user')->firstName.' '.$this->session->userdata('user')->lastName ?></span><br></div>';
                        html += '</li>';

                        $(html).appendTo($('#'+form.find('input[name="productDiscussionId"]').val()));
                        $('#modal-success-add-discussion .modal-title').html('Berhasil');
                        $('#modal-success-add-discussion .modal-body').html('Berhasil membalas diskusi');
                        $('#modal-success-add-discussion').modal('show');
                        form.find('textarea[name="reply-message"]').val('');

                    } else {
                        $('#modal-success-add-discussion .modal-title').html('Gagal');
                        $('#modal-success-add-discussion .modal-body').html(data.error.message);
                        $('#modal-success-add-discussion').modal('show');
                    }
                    button.button('reset');
                    console.log(data);

                }
            });
            <?php } ?>

        });
    });
</script>

<div class="modal fade" id="modal-success-add-cart" role="dialog" data-backdrop="static">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title"><i class="fa fa-thumbs-o-up"></i> Berhasil</h3>
      </div>
      <div class="modal-body form"> 
        <i class="fa fa-check"></i> Berhasil menambahkan ke keranjang anda
      </div>
      <div class="modal-footer">
          <button type="button" class="btn btn-default btn-login" data-dismiss="modal"><i class="fa fa-chevron-left"></i> Lanjut Belanja</button>
          <?php if($this->session->userdata('logged_in')) { ?>
              <a class="btn btn-default btn-login pull-right checkout-modal-btn" href="<?php echo base_url('Cart/Checkout') ?>">Checkout <i class="fa fa-shopping-cart"></i></a>
          <?php }else{ ?>
              <a class="btn btn-default btn-login pull-right checkout-modal-btn" href="<?php echo base_url('/Login/Login/index/cart') ?>">Checkout <i class="fa fa-shopping-cart"></i></a>
          <?php } ?>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" id="modal-success-add-discussion" role="dialog" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title"></h3>
            </div>
            <div class="modal-body form">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-login" data-dismiss="modal"><i class="fa fa-chevron-left"></i> Lanjut Belanja</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!--breadcrumb start -->
<div class="breadcrumb-area">
    <div class="container">
        <?php echo $breadcrumbs ?>
<!--        --><?php //echo $this->session->userdata('token'); ?>
    </div>
</div>
<!-- breadcrumb end -->

<?php //echo '<pre>'; print_r($products) ?>
<!-- product-details-start -->
<div class="product-details-area pt-20" id="page-area-checkout">
    <div class="container container-custom">
        <?php
        $image = json_decode($products->images);

        ?>
        <div class="row">
            <div class="col-xs-12 col-sm-6 col-md-5">
                <div class="product-zoom dotted-style-1">
                    <!-- Tab panes -->
                    <div class="tab-content">
                        <?php
                        foreach ($image as $key => $valimg) {
                            ?>
                            <div class="tab-pane <?php echo ($key == 0) ? 'active' : '' ?>" id="<?php echo $key ?>">
                                <div class="pro-large-img">
                                    <a href="<?php echo IMG_PRODUCT . $valimg->thumbnail ?>" class="zoomple">
                                        <img src="<?php echo IMG_PRODUCT . $valimg->thumbnail ?>" alt=""/></a>
                                    <a class="popup-link" href="<?php echo IMG_PRODUCT . $valimg->thumbnail ?>">View larger <i
                                            class="fa fa-search-plus" aria-hidden="true"></i></a>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                    <!-- Nav tabs -->
                    <ul class="details-tab owl-carousel owl-theme">
                        <?php foreach ($image as $key2 => $valimg2) { ?>
                            <li class="<?php echo ($key2 == 0) ? 'active' : '' ?>">
                                <a href="#<?php echo $key2 ?>" data-toggle="tab"><img
                                        src="<?php echo IMG_PRODUCT . $valimg2->thumbnail ?>" alt=""/></a>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-7">
                <div class="product-details">
                    <h1 class="pro-d-title">
                        <?php echo $products->name;
                        if ($products->discount > 0) { ?>
                            <span class="discount-label"><?php echo $products->discount; ?>%</span>
                        <?php } ?>
                    </h1>
                    <h6>Status Barang: <span class="label label-success"><?php echo $products->condition; ?></span></h6>
                    <div class="pro-ref">
                        <p>
                            <span><b>Merchant : <a href="<?php echo base_url("Profile/Merchant/".$products->merchantName."?id=".$products->merchantId); ?>"><?php echo $products->merchantName ?></a></b></span>
                        </p>
                    </div>
                    <h6><img width="20" class="produk-met" src="<?php echo base_url('assets/img/maps-and-flags.png') ?>"> <?php echo $products->merchantCity; ?></h6>
                    <div class="price-box">
                        <span class="price product-price">Harga : 
                            <span>
                                <?php
                                $priceAfterMargin = ceil($products->price + ($products->price * $products->priceMargin / 100));

                                $lastdigits = $priceAfterMargin % 10;
                                if($lastdigits > 0 && $lastdigits < 9) {
                                    $priceAfterMargin -= $lastdigits;
//                                    $products->priceMargin -= $lastdigits;
                                }

                                $discount = ceil($priceAfterMargin * $products->discount / 100);
                                $priceNett = $priceAfterMargin - $discount;
                                    if ($products->discount > 0) { 
//                                        $pricePublish = $products->priceAfterDiscount + ($products->priceAfterDiscount * $products->priceMargin/100);

                                    ?>
                                <?php echo "Rp". number_format(ceil($priceNett)); ?>
                                <h6 class="price-h price-h-disc"><strike><?php echo number_format($products->priceAfterMargin); ?></strike></h6></span>
                                <?php } else {
                                    echo number_format(ceil($priceNett));
                                } ?> &nbsp;&nbsp;&nbsp; Jumlah Stok : <?php echo $products->stock; ?></span>
                    </div>
                    <!-- <div class="selector-field f-left ml-5">
                        <form action="#">
                            <button class="compare"><font style="color:#ffffff">BELI SEKARANG</font></button>
                        </form>
                    </div> -->
                    <div class="box-quantity clearfix">
                        <form method="post" id="addToCart">
                            <label>Quantity</label>
                            <input name="productId" id="productIdHidden" type="hidden"
                                   value="<?php echo $products->id; ?>"/>
                            <input name="qty" id="qty" type="number" value="1" min="1" max="<?php echo $products->stock; ?>"/>
                            <button type="submit" class="btn compare" style="margin-top:2px"
                                    data-loading-text="<i class='fa fa-spinner fa-pulse fa-fw'></i> Add to cart" <?php if($products->stock <= 0) echo "disabled"; ?>><font
                                    style="color:#ffffff">Add to cart</font></button>
                            <?php if($products->stock == 0) echo "<span style='color: red;margin-left: 5px;'>Stok sedang habis</span>"; ?>
                        </form>
                    </div>

                    <div class="pro-rating">
                        <?php for ($i = 1; $i <= $products->ratings; $i++) { ?>
                            <a href="#"><i class="fa fa-star"></i></a>
                        <?php }
                        for ($b = 1; $b <= (5 - $products->ratings); $b++) { ?>
                            <a href="#"><i class="fa fa-star-o"></i></a>
                        <?php } ?>
                    </div>

                    <hr>

                    <p class="short-desc" align="justify">
                        <?php echo $products->shortDescription; ?>
                        <?php echo $products->tnc ?>
                    </p>

                </div>
            </div>
        </div>
        <br>

        <div class="pro-info-box">
            <!-- Nav tabs -->
            <ul class="pro-info-tab" role="tablist">
                <li class="active"><a href="#informasiproduk" data-toggle="tab">Informasi Produk</a></li>
                <li><a href="#spesifikasi" data-toggle="tab">Spesifikasi</a></li>
                <li><a href="#ulasan" data-toggle="tab">Ulasan</a></li>
                <li><a href="#diskusi" data-toggle="tab">Diskusi Produk</a></li>
                <li><a href="#rating" data-toggle="tab">Rating</a></li>
            </ul>

            <!-- Tab panes -->
            <div class="tab-content">
                <!---informasiproduk-->
                <div class="tab-pane active" id="informasiproduk">
                    <div class="pro-desc">
                        <p><?php echo $products->description; ?></p>
                    </div>
                </div>
                <!-- spesifikasi product -->
                <div class="tab-pane" id="spesifikasi">
                    <div class="pro-desc">

                        <?php if (count($products->spesification) > 0) : ?>
                            <table class="table-data-sheet">
                                <tbody>
                                <?php foreach ($products->spesification as $rowspec) : ?>
                                    <tr class="odd">
                                        <td><?php echo $rowspec->key ?></td>
                                        <td><?php echo $rowspec->value ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php else : echo 'Belum ada spesifikasi untuk produk ini'; endif; ?>

                    </div>
                </div>
                <!---End spesifikasi product -->

                <!---Ulasan Produk---->
                <div class="tab-pane" id="ulasan">
                    <div class="pro-desc">
                        <!-- <h4 class="total-comments mb-30 pb-15">4 Ulasan</h4> -->
                        <ul class="media-list comment-list mt-30">
                            <!-- Comment Item start-->
                            <?php
                            if (!empty($products->review)) {
                                foreach ($products->review as $review) {
                                    ?>
                                    <li class="media discussion-media">
                                        <div class="media-left">
                                            <a href="#">
                                                <img alt="Jonathon Doe"
                                                     src="<?php if(@getimagesize(AVATAR_FILE.$review->avatarFile)) {echo AVATAR_FILE.$review->avatarFile; }else{echo base_url('assets/img/blog/man-4.jpg'); } ?>"
                                                     class="avatar">
                                            </a>
                                        </div>
                                        <div class="media-body">
                                            <div class="discussion-message"><?php echo $review->text; ?></div>
                                            <div class="pro-rating">
                                                <?php for ($i = 1; $i <= $review->rating; $i++) { ?>
                                                    <a href="#"><i class="fa fa-star"></i></a>
                                                <?php }
                                                for ($b = 1; $b <= (5 - $review->rating); $b++) { ?>
                                                    <a href="#"><i class="fa fa-star-o"></i></a>
                                                <?php } ?>
                                            </div>
                                            <span>
                                                <?php echo $this->tanggal->formatDateTime($review->createdAt) ?><br>
                                                <?php echo 'by '.$review->firstName.' '.$review->lastName; ?>
                                            </span>
                                        </div>
                                        <h6 style="margin-top:15px;"></h6>
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
                <!---Ulasan Produk---->

                <!---Diskusi Produk---->
                <div class="tab-pane" id="diskusi">
                    <div class="pro-desc">
                        <!-- Comment Item start-->
                        <?php if ($this->session->userdata('logged_in') == TRUE) : ?>
                            <div class="ask-wrapper">
                                <h3>Ada pertanyaan mengenai produk ini?</h3>
                                <p>Diskusikan langsung dengan penjual</p>

                                <form method="post" id="formDiscussion">
                                    <input type="hidden" name="productId" id="productId"
                                           value="<?php echo $products->id ?>"> <br>
                                    <input type="hidden" name="customerName" id="customerName"
                                           value="<?php echo $this->session->userdata('user')->firstName.' '.$this->session->userdata('user')->lastName ?>">

                                    <input type="hidden" name="customerId" id="customerId"
                                           value="<?php echo $this->session->userdata('user')->id ?>">

                                    <input type="hidden" name="merchantId" id="merchantId"
                                           value="<?php echo $products->merchantId; ?>">

                                    <textarea name="question" id="question"></textarea>
                                    <button type="submit" class="ask-button btn" id="saveDiscussion"
                                            data-loading-text="<i class='fa fa-spinner fa-pulse fa-fw'></i> Tanyakan">
                                        Tanyakan
                                    </button>
                                </form>
                            </div>
                            <div role="separator" class="divider divider-dashed"></div>
                        <?php else : echo 'Silahkan <a class="a-link" href="' . base_url() . 'Login' . '">login</a> terlebih dahulu untuk melakukan diskusi produk'; endif; ?>
                        <ul class="media-list comment-list mt-30" id="diskusi-list">
                                <?php
                                if (count($products->discussion) > 0) {
                                    foreach ($products->discussion as $key => $valmsg) {
                                        $this->load->model('product_model', 'product');
                                        $detilDiscussion = $this->product->getDetailDiscussion($valmsg->id);

                                        ?>
                                        <li class="media discussion-media"><ul class="detail-discussion-wrapper" id="<?php echo $valmsg->id; ?>">
                                        <?php foreach($detilDiscussion as $key2 => $detilDiscuss) {
                                            if($detilDiscuss->modifiedByRole == 1) {
                                                $customerInfo = $this->db->get_where('Customer',['id'=>$detilDiscuss->modifiedBy])->row();
                                                $fullName = $customerInfo->firstName.' '.$customerInfo->lastName;
                                                $foto = $customerInfo->avatarFile;
                                            }
                                    ?>
                                            <li class="media discussion-media">
                                                <div class="media-left">
                                                    <a href="#">
                                                        <img alt="<?php echo $fullName; ?>" src="<?php if(@getimagesize(AVATAR_FILE.$foto)) {echo AVATAR_FILE.$foto; }else{echo base_url('assets/img/blog/man-4.jpg'); } ?>" class="avatar">
                                                    </a>
                                                </div>
                                                <div class="media-body">
                                                    <div class="discussion-message"><?php echo $detilDiscuss->message ?></div>
                                                    <span><?php echo $this->tanggal->formatDateTime($detilDiscuss->createdAt) . ' <br> by ' . $fullName; ?></span><br>
                                                </div>
                                            </li>

                                    <?php } ?>
                                            </ul>
                                        <?php if ($this->session->userdata('logged_in') == TRUE) : ?>
                                            <form class="reply-discussion" method="post" style="margin-top:15px;padding-left:80px;">
                                                <input type="hidden" name="productDiscussionId" value="<?php echo $valmsg->id; ?>">
                                                <textarea name="reply-message" placeholder="Ketik balasan anda"></textarea>
                                                <button id="reply-button" class="btn" type="submit" data-loading-text="<i class='fa fa-spinner fa-pulse fa-fw'></i> Balas">Balas</button>
                                            </form>
                                        <?php endif; ?>
                                        </li>
                                <?php }}else{ echo '<p id="tidak-ada-diskusi">-Tidak ada diskusi untuk produk ini-</p>'; } ?>

                        </ul>


                        <!-- End Comment Item -->
                    </div>
                </div>
                <!---End Diskusi Produk---->

                <!-----Rating Produk---->
                <div class="tab-pane" id="rating">

                    <br>

                    <div class="pro-rating rating-detail-produk" align="center">
                        <?php for ($i = 1; $i <= $products->ratings; $i++) { ?>
                            <a href="#"><i class="fa fa-star"></i></a>
                        <?php }
                        for ($b = 1; $b <= (5 - $products->ratings); $b++) { ?>
                            <a href="#"><i class="fa fa-star-o"></i></a>
                        <?php } ?>
                    </div>

                    <h6 align="center" style="line-height: 21px;">
                        Total Rating : <?php echo number_format($products->ratings,2) ?> Stars
                        <br> Jumlah Responden : <?php echo $products->reviewCount; ?>
                    </h6>

                </div>
                <!-----Rating Produk---->
            </div>
        </div>
    </div>
</div>
<!-- product-details-end -->

<!-- .slider-product-area-3-start -->
<!--<div class="slider-product-area-4 pt-30 pb-50">-->
<!--    <div class="container">-->
<!--        <div class="row">-->
<!--            <div class="col-lg-12">-->
<!--                <div class="section-title mb-40 text-center section-title-pro">-->
<!--                    <h3>Barang lainnya Merchant ini</h3>-->
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
<!--        <div class="row">-->
<!--            <div class="col-lg-12">-->
<!--                <div class="slider-product dotted-style-1">-->
<!--                    <div class="slider-product-active-3 owl-carousel owl-theme">-->
<!--                        --><?php
//                        foreach ($products->productMerchant as $prodby) {
//                            $priceAfterMargin2 = $prodby['price'] + ($prodby['price'] * $prodby['priceMargin'] / 100);
//                            $netPrice = $priceAfterMargin2 - ($priceAfterMargin2 * $prodby['discount'] / 100);
//                            $image = json_decode($prodby['images'], JSON_OBJECT_AS_ARRAY)
//                            ?>
<!--                            <div class="single-product single-product-sidebar white-bg">-->
<!--                                <div class="product-img product-img-left">-->
<!--                                    <a href="--><?php //echo base_url() ?><!--Product/detail/--><?php //echo url_title($prodby['name'], '-', true); ?><!--?id=--><?php //echo $prodby['id']; ?><!--">-->
<!--                                        <img-->
<!--                                            src="--><?php //echo isset($image[0]['thumbnail']) ? IMG_PRODUCT . $image[0]['thumbnail'] : base_url() . 'assets/img/product/1.jpg'; ?><!--"-->
<!--                                            alt=""/></a>-->
<!--                                </div>-->
<!--                                <div class="product-content product-content-right">-->
<!--                                    <div class="pro-title">-->
<!--                                        <h4>-->
<!--                                            <a href="--><?php //echo base_url() ?><!--Product/detail/--><?php //echo url_title($prodby['name'], '-', true); ?><!--?id=--><?php //echo $prodby['id']; ?><!--">--><?php //echo $prodby['name']; ?><!--</a>-->
<!--                                        </h4>-->
<!--                                    </div>-->
<!--                                    <div class="price-box">-->
<!--                                        <span-->
<!--                                            class="price product-price">--><?php //echo 'Rp ' . number_format(ceil($netPrice)); ?><!--</span>-->
<!--                                    </div>-->
<!--                                </div>-->
<!--                            </div>-->
<!--                        --><?php //} ?>
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->
<!--</div>-->
<!-- .slider-product-area-3-end -->
<!-- brand-area-start -->
<div class="brand-area pb-60 pt-30 dotted-style-2">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-title">
                    <h3>Produk Sejenis</h3>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="brand-active border-1 owl-carousel owl-theme">
                    <?php ?>

                    <?php
                    if (count($products->related) > 0) :
                        //echo '<pre>';print_r($products->related);die;
                        foreach ($products->related as $key => $valRelated) :
                            $image = json_decode($valRelated->images);
                            if (isset($image[0]->thumbnail)) {

                                if (@getimagesize(IMG_PRODUCT . $image[0]->thumbnail)) {
                                    $linkImg = IMG_PRODUCT . $image[0]->thumbnail;
                                } else {
                                    $linkImg = base_url() . 'assets/img/product/1.jpg';
                                }
                            } else {
                                $linkImg = base_url() . 'assets/img/product/1.jpg';
                            }

                            if (isset($image[0]->thumbnail)) :
                                echo '<div class="single-brand">
                                        <a href="' . base_url() . 'Product/detail/' . url_title($valRelated->name, '-', true) . '?id=' . $valRelated->id . '"><img src="' . $linkImg . '" alt="" /></a>
                                    </div>';
                            endif;
                        endforeach;
                    else :
                        echo 'Tidak ada produk sejenis';
                    endif;
                    ?>

                </div>
            </div>
        </div>
    </div>
</div>
<!-- brand-area-end -->