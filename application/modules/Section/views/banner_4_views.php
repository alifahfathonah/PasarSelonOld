<div class="banner-area">
<!--    <div class="container">-->
        <div class="row">
            <?php
            if(count($banner4Views) > 0 && $banner4Views->code==200){
                foreach ($banner4Views->data as $key => $valThreeViews) {
                    if($valThreeViews->BannerCategoryId == 2){
                        echo '<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <div class="slider-single-img">
                                    <a href="'.$valThreeViews->urlLink.'">
                                        <img width="100%" class="img_a" src="'.$valThreeViews->name.'" alt="'.$valThreeViews->name.'" />
                                        <img width="100%" class="img_b" src="'.$valThreeViews->name.'" alt="'.$valThreeViews->name.'" />
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
<!--    </div>-->
</div>