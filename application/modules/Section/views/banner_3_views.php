<div class="banner-area">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-title">
                    <h3>Promo</h3>
                </div>
            </div>
        </div>
        <div class="row">
            <?php
            if(count($banner3Views) > 0 && $banner3Views->code==200){
                foreach ($banner3Views->data as $key => $valThreeViews) {
                    if($valThreeViews->BannerCategoryId == 2){
                        echo '<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                <div class="slider-single-img">
                                    <a href="#">
                                        <img width="100%" class="img_a" src="'.$valThreeViews->name.'" alt="" />
                                        <img width="100%" class="img_b" src="'.$valThreeViews->name.'" alt="" />
                                    </a>
                                </div>
                            </div>';
                    }

                }
            }else{
                echo 'Error while get data from API';
            }

            ?>

        </div>
    </div>
</div>