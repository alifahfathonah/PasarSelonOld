<?php //echo "<pre>"; print_r($priceRange); ?>
<link rel="stylesheet" href="<?php echo base_url('assets/css/jquery.rateyo.min.css') ?>" type="text/css">
<script type="text/javascript" src="<?php echo base_url('assets/js/jquery.rateyo.min.js') ?>"></script>
<script type="text/javascript">
    $(function () {
        $("#rateYo").rateYo({
            onSet: function(rating, rateYoInstance) {
                $(this).next().val(rating);
              },
              rating: $(this).attr("data-rating"),
              starWidth: "30px",
              numStars: 5,
              fullStar: true
        });

        $('#btn_search').click(function(e) {

            e.preventDefault();
            
            var currentUrl = "<?php echo base_url().'Product'?>";

            var keyword = $('#keyword').val();
            var sort = $('#sort').val();
            var MerchantId = $('#MerchantId').val();
            var LocationId = encodeURIComponent($('#LocationId').val());
            var rating = $('#rating').val();
            var priceRange = $('#amount').val();
            var strRplc = priceRange;
            var url = currentUrl + '?keyword='+keyword+'&merchant='+MerchantId+'&rating='+rating+'&sort='+sort+'&priceRange='+strRplc+'&location='+LocationId;
            
            window.location.href = url;
        });

    });
</script>
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
            <div class="col-md-3 clearfix" style="position: inherit !important;">
                <div class="column mt-55 nav-stacked product-sidebar">
<!--                    <h2 class="title-block">Filter and Stage</h2>-->
                    <br>
                    <div class="sidebar-widget">
                        <h3 class="sidebar-title">Pencarian</h3>
                        <form id="search-parent" action="">
                            <input type="text" placeholder="Cari Sesuai Nama Barang anda " id="keyword" class="classValue form-control searching-category" value="<?php echo isset($params['keyword'])?$params['keyword']:''?>">
                        </form>
                    </div>
                    <div class="sidebar-widget">
                        <h3 class="sidebar-title">Urutkan</h3>
                        <form action="#">
                            <select name="sort" id="sort" class="classValue form-control">
                                <option value="">Pilih urutkan</option>
                                <option value="price ASC" <?php echo isset($params['sort'])?($params['sort']=='price ASC')?'selected':'':''?> >Price: Lowest first</option>
                                <option value="price DESC" <?php echo isset($params['sort'])?($params['sort']=='price DESC')?'selected':'':''?>>Price: Highest first</option>
                                <option value="name ASC" <?php echo isset($params['sort'])?($params['sort']=='name ASC')?'selected':'':''?>>Product Name: A to Z</option>
                                <option value="name DESC" <?php echo isset($params['sort'])?($params['sort']=='name ASC')?'selected':'':''?>>Product Name: Z to A</option>
                                <!-- <option value="stock">In stock</option> -->
                            </select>
                        </form>
                    </div>
                    <div class="sidebar-widget">
                        <h3 class="sidebar-title">Merchant</h3>
                        <select name="Merchant" id="MerchantId" class="classValue form-control">
                            <option value="">-Tampilkan Semua-</option>
                            <?php 
                                if(isset($merchants)) {
                                    foreach ($merchants as $merch) {
                                        $selected = isset($_GET['merchant'])?($merch->id==$_GET['merchant'])?'selected':'':'';
                                        echo '<option value="'.$merch->id.'" '.$selected.'>'.$merch->name.'</option>';
                                    }
                                }
                            ?>
                        </select>
                    </div>

                    <div class="sidebar-widget">
                        <h3 class="sidebar-title">Lokasi Merchant</h3>
                        <select name="Location" id="LocationId" class="classValue form-control">
                            <option value="">-Tampilkan Semua-</option>
                            <option value="Jabodetabek">Jabodetabek</option>
                            <option value="DKI Jakarta">DKI Jakarta</option>
                            <?php 
                                foreach ($locations as $location) { 
                                    $selected = isset($_GET['merchant'])?($location->id==$_GET['merchant'])?'selected':'':'';
                                    echo '<option value="'.urlencode($location->cityType.' '.$location->cityName).'" '.$selected.'>'.$location->cityType.'&nbsp;'.$location->cityName.'</option>';
                                }
                            ?>
                        </select>
                    </div>

                    <div class="sidebar-widget">
                        <h3 class="sidebar-title">Harga</h3>
                        <?php
                            $hargaRange = $this->input->get('priceRange');
                            $hargaSplit = explode('-', $hargaRange);
                        ?>
                        <input id="min-price" class="sliderValue classValue form-control searching-category" type="number" name="minimum" placeholder="harga minimum" style="margin-bottom: 15px;width:45%;float:left" value="<?php echo isset($priceRange->lowest)?$priceRange->lowest == '' ? '' : $priceRange->lowest : ''; ?>">
                        <span style="float:left; margin-left:12px; margin-top:5px"> - </span>
                        <input id="max-price" class="sliderValue classValue form-control searching-category" type="number" style="margin-bottom: 15px;width:45%;float:right" name="maksimum" placeholder="harga maksimum" value="<?php echo isset($priceRange->highest) ? $priceRange->highest == '' ? '' : $priceRange->highest : ''; ?>">
                        <small style="margin-top:-10px;float:left">Silahkan masukan jumlah lain</small>
                        <br>
                        <div class="price-filter">
                            <p class="center">
                                <input type="text" id="show-slider" class="classValue" value="0-38500000" readonly style="border:0; color:#f6931f; font-weight:bold;width:250px">
                                <input type="hidden" id="amount" class="classValue" readonly style="border:0; color:#f6931f; font-weight:bold;width:250px">
                            </p>
                            <div id="slider-range"></div>
                        </div>
                    </div>
                    <div class="sidebar-widget">
                        <h3 class="sidebar-title">Rating Produk</h3>
                        <div class="pro-rating ">
                            <div id="rateYo" class="classValue" data-rating="<?php echo isset($params['rating'])?$params['rating']:''?>"></div>
                            <input type="hidden" name="rating" class="classValue" id="rating" value="<?php echo isset($params['rating'])?$params['rating']:''?>">

                        </div>
                    </div>

                    <button id="btn_search" class="btn btn-large btn-block btn-success">Cari</button>
                    <br>
                    <br>
                </div>
            </div>
            <div class="col-md-9">
                <h2 class="page-heading mt-40">
                        <span class="cat-name">LIST PRODUK</span>
                        <span class="heading-counter">There are <?php echo $config_pagination['total_rows']?> products.</span>
                    </h2>
                <div class="shop-page-bar">
                    <div>
                        <div class="shop-bar">
                            <!-- Nav tabs -->
                            <ul class="shop-tab f-left" role="tablist">
                                <li role="presentation" class="active"><a href="#home" data-toggle="tab"><i class="fa fa-th-large" aria-hidden="true"></i></a></li>
                                <!---<li role="presentation"><a href="#profile" data-toggle="tab"><i class="fa fa-th-list" aria-hidden="true"></i></a></li>-->
                            </ul>
                           <!--- <div class="selector-field f-right ml-30 hidden-xs">
                                <form action="#">
                                    <label>Show</label>
                                    <select name="select">
                                        <option value="">12</option>
                                        <option value="">13</option>
                                        <option value="">14</option>
                                    </select>
                                </form>
                            </div>-->

                        </div>
                        <!-- Tab panes -->
                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane active" id="home">
<!--                                <pre>--><?php //print_r($merchant); ?><!--</pre>-->
                                <div class="row">
                                <?php
                                    if(!empty($results)) {
                                        $i = 1;
                                        foreach($results as $key => $row) :
//                                            $image = json_decode($row->images);
                                            $image = $row->images;

                                ?>
                                    <div class="col-lg-4 col-md-4 col-sm-6">
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
                                                    <?php if($row->discount > 0) {
                                                        $priceAfterMargin = $row->price + ($row->price * $row->priceMargin / 100);
                                                        $discount = $priceAfterMargin * $row->discount / 100;
                                                        $priceNett = $priceAfterMargin - $discount;
                                                        ?>
                                                        <span class="price product-price"><h4 class="price-h"><strike><?php echo "Rp".number_format(ceil($row->netTotal + $row->subtotalDiscount)); ?></strike></h4><br><h5 class="price-h price-h-disc"><?php echo number_format((ceil($row->netTotal))); ?></h5></span>
                                                    <?php }else{ ?>
                                                        <span class="price product-price"><h4 class="price-h" style="height: 40px;"><?php echo "Rp".number_format($row->netTotal); ?></h4></span>
                                                    <?php } ?>
                                                </div>
                                                <a href="<?php echo base_url('Profile/Merchant/'.url_title($row->merchant->name,'-',true).'?id='.$row->merchant->id); ?>"><img width="20" class="produk-met" src="<?php echo base_url('assets/img/store.png') ?>"> <?php echo $row->merchant->name; ?></a>
                                                <br><p><img width="20" class="produk-met" src="<?php echo base_url('assets/img/maps-and-flags.png') ?>"> <?php echo $row->merchant->cityName; ?></p>
                                            </div>
                                            <span class="new">new</span>
                                        </div>
                                    </div>
                                <?php endforeach; }else{ ?>
                                        <p>Pencarian tidak ditemukan</p>
                                    <?php } ?>
                                </div>
                            </div>
                           <!--- <div role="tabpanel" class="tab-pane view-list" id="profile">
                                <?php
                                foreach($results as $row) :
                                $image = json_decode($row->images);
                                ?>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="single-product  shop-single-product mb-30 white-bg">
                                            <div class="product-img pt-20">
                                                <a href="<?php echo base_url('Product/detail/'.url_title($row->name,'-',true).'?id='.$row->id) ?>"><img src="<?php echo isset($image[0]->thumbnail)?IMG_PRODUCT.$image[0]->thumbnail:base_url().'assets/img/product/3.jpg';?>" alt="" /></a>
                                            </div>
                                            <div class="product-content">
                                                <div class="pro-title">
                                                    <h4><a href="<?php echo base_url('Product/detail/'.url_title($row->name,'-',true).'?id='.$row->id) ?>"><?php echo $row->name?></a></h4>
                                                </div>
                                                <span class="prod-content">
                                                    <?php echo substr($row->description,0,200); ?>
                                                </span>
                                                <div class="price-box">
                                                    <span class="price product-price">Rp <?php echo number_format($row->price + ($row->price * $row->priceMargin / 100))?></span>
                                                </div>
                                                <div class="pro-rating">
                                                    <?php for ($i = 1; $i <= $row->ratings; $i++) { ?>
                                                        <a href="#"><i class="fa fa-star"></i></a>
                                                    <?php }
                                                    for ($b = 1; $b <= (5 - $row->ratings); $b++) { ?>
                                                        <a href="#"><i class="fa fa-star-o"></i></a>
                                                    <?php } ?>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>-->
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
</div></div>
<!-- .slider-product-area-3-end -->