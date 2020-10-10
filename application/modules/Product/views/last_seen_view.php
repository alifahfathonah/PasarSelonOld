<?php //unset($this->session->userdata['last_seen']); ?>
<!-- breadcrumb start -->
<div class="breadcrumb-area">
    <div class="container">
        <?php echo $breadcrumbs?>
    </div>
</div>
<!-- breadcrumb end -->
<!-- product-details-start -->
<div class="shop-area">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <h2 class="page-heading mt-40">
                    <span class="cat-name">LIST PRODUK TERKAHIR DILIHAT</span>
                </h2>
                <div class="shop-page-bar">
                    <div>
                        <div class="shop-bar">
                            <!-- Nav tabs -->
                            <ul class="shop-tab f-left" role="tablist">
                                <li role="presentation" class="active"><a href="#home" data-toggle="tab"><i class="fa fa-th-large" aria-hidden="true"></i></a></li>
                            </ul>
                        </div>
                        <!-- Tab panes -->
                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane active" id="home">
                                <div class="row">
                                    <?php
                                    $i = 1;
                                    $lastseen = $this->session->userdata('last_seen');

                                    if(isset($lastseen) && count($lastseen) > 0) {
                                    foreach($lastseen as $key => $value) :
                                        $row = $this->db->select('Product.*, Merchant.name as merchantName, Merchant.cityName as merchantCity')->join('Merchant','Merchant.id = Product.merchantId','left')->get_where('Product', array('Product.id' => $value['id']))->row();
                                        $image = json_decode($row->images);
                                        ?>
                                        <div class="col-md-3 col-sm-4">
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
                                                    <div class="pro-rating">
                                                        <?php for ($i = 1; $i <= $row->ratings; $i++) { ?>
                                                            <a href="#"><i class="fa fa-star"></i></a>
                                                        <?php }
                                                        for ($b = 1; $b <= (5 - $row->ratings); $b++) { ?>
                                                            <a href="#"><i class="fa fa-star-o"></i></a>
                                                        <?php } ?>
                                                    </div>
                                                    <div class="price-box">
                                                        <?php if($row->discount > 0) { ?>
                                                            <span class="price product-price"><h4 class="price-h"><strike><?php echo "Rp".number_format($row->price + ($row->price * $row->priceMargin / 100)); ?></strike></h4><br><h5 class="price-h price-h-disc"><?php echo number_format(($row->price + ($row->price * $row->priceMargin / 100))-($row->price*$row->discount/100)); ?></h5></span>
                                                        <?php }else{ ?>
                                                            <span class="price product-price"><h4 class="price-h" style="height: 40px;"><?php echo "Rp".number_format($row->price + ($row->price * $row->priceMargin / 100)); ?></h4></span>
                                                        <?php } ?>
                                                    </div>
                                                    <a href="<?php echo base_url('Profile/Merchant/'.url_title($row->merchantName,'-',true).'?id='.$row->merchantId); ?>"><img width="20" class="produk-met" src="<?php echo base_url('assets/img/store.png') ?>"> <?php echo $row->merchantName; ?></a>
                                                    <br><p><img width="20" class="produk-met" src="<?php echo base_url('assets/img/maps-and-flags.png') ?>"> <?php echo $row->merchantCity; ?></p>
                                                </div>
                                                <span class="new">new</span>
                                            </div>
                                        </div>
                                    <?php endforeach; }else{ echo "<div class='col-sm-12'>Belum ada produk yang di lihat sebelumnya <br><br></div>"; } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- .slider-product-area-3-end -->