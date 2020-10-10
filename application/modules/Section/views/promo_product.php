<?php
$kategoriBaru = $this->Section_model->getKategoriProdukTerbaru();
//echo count($kategoriBaru);die;
if(isset($kategoriBaru)){
?>
<div class="brand-area pb-15 dotted-style-2">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-title">
                    <h3><?php echo isset($label) && count($label) > 0 ? ucwords($label[0]->name):''; ?></h3>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="container">
                <div class="promo-banner-container clearfix">
                    <div class="col-sm-2 no-padding promo-header">
                        <a href="#"><img src="<?php if(@getimagesize($kategoriBaru->banner)) {echo $kategoriBaru->banner;}else{echo base_url('assets/img/electronic/1.jpg');} ?>" width="100%"></a>
                    </div>
                    <div class="col-sm-10">
                        <div class="brand-active owl-carousel owl-theme promo-slider">
                            <?php
                            if (isset($kategoriBaru->products)) {
                                foreach ($kategoriBaru->products as $key => $valPp) {
                                    $src_img = isset($valPp->images[0]->image_low) ? IMG_PRODUCT . $valPp->images[0]->thumbnail : base_url() . 'assets/img/product/1.jpg';
                                    echo '<div class="single-brand">
                                    <div class="hover-container">
                                        <a href="' . base_url('Product/detail/' . url_title($valPp->name, '-', true) . '?id=' . $valPp->id) . '">' . $valPp->name . '</a><br><br>
                                        <h4 class="price-h" style="height: 40px;">Rp ' . number_format(ceil($valPp->AfterMargin - $valPp->DiscountPrice)) . '</h4><br><br>
                                        <span class="category-name" style="color:white;">' . $valPp->productCategoryName . '</span>
                                    </div>
                                            <a href="' . base_url('Product/detail/' . url_title($valPp->name, '-', true) . '?id=' . $valPp->id) . '"><img src="' . $src_img . '" /></a>
                                        </div>';
                                }
                            }
                            ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php } ?>