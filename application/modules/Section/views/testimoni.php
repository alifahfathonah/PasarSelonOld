<div class="brand-area pb-60 dotted-style-2">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="section-title">
                    <h3>Sub Kategori Produk</h3>
                </div>
                <div class="brand-active-3 border-1 owl-carousel owl-theme">
                    <?php
                    if(count($promoProduct) > 0) :
                        foreach ($promoProduct as $key => $valPp) {
                            $netPrice = $valPp->priceAfterMargin - ($valPp->priceAfterMargin * $valPp->discount / 100);
                            $image = json_decode($valPp->images);
                            $src_img = isset($image[0]->image_low) ? IMG_PRODUCT . $image[0]->image_low : base_url() . 'assets/img/product/1.jpg';
                            echo '<div class="single-brand">
                                    <div class="hover-container">
                                        <a href="'.base_url('Product/detail/' . url_title($valPp->name, '-', true) . '?id=' . $valPp->id).'">'.$valPp->name.'</a><br><br>
                                        <h4 class="price-h" style="height: 40px;">Rp '.number_format(ceil($netPrice)).'</h4>
                                    </div>
                                            <a href="'.base_url('Product/detail/' . url_title($valPp->name, '-', true) . '?id=' . $valPp->id).'"><img src="'.$src_img.'" /></a>
                                        </div>';
                        }
                    endif;
                    ?>

                </div>
            </div>

<!--            <div class="col-lg-6 col-md-6 col-sm-12">-->
<!--                <div class="client-area client-2 dotted-style-2">-->
<!--                    <div class="section-title">-->
<!--                        <h3>Testimoni</h3>-->
<!--                    </div>-->
<!--                    <div class="clinet-active border-1 owl-carousel owl-theme">-->
<!--                        --><?php
//                            if($testimoni->code==200){
//                                foreach ($testimoni->data as $key => $valTestimony2) {
//                                    /*print_r($valTestimony2->customer);
//                                    $address = (count($valTestimony2->customer->addresses) > 0) ? $valTestimony2->customer->addresses[0]->provinceName : '';*/
//                                    echo '<div class="single-client fix white-bg">
//                                            <div class="client-content">
//                                                <a href="#">
//                                                    <p>'.$valTestimony2->message.'</p>
//                                                </a>
//                                            </div>
//                                            <div class="clint-img">
//                                                <div class="client-img-left">
//                                                    <img src="'.$valTestimony2->customer->avatarFile.'" alt="" width="80px" height="80px"/>
//                                                </div>
//                                                <div class="client-name">
//                                                    <span>'.$valTestimony2->customer->fullName.'</span>
//                                                </div>
//                                            </div>
//                                        </div>';
//                                }
//                            }else{
//                                echo 'Error while get data from API';
//                            }
//
//                        ?>
<!---->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
        </div>
    </div>
</div>