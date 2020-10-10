<div class="banner-area-2 pb-15">
    <div class="slider-single-img">
        <?php
//        print_r($bannerFullWidth);die;
        if(count($bannerFullWidth) > 0 && $bannerFullWidth->code == 200 && count($bannerFullWidth->data) > 0) {
            echo '<a href="'.$bannerFullWidth->data[0]->urlLink.'">
                                        <img width="100%" class="img_b" src="'.$bannerFullWidth->data[0]->name.'" alt="" />
                                    </a>';
        }
        ?>
    </div>
</div>