<div class="brand-area-4 pb-60">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-title">
                    <h3>Best Merchant</h3>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="brand-active">
                    <?php
                        if(count($logoMerchant)>0 && $logoMerchant->code==200){
                            foreach ($logoMerchant->data as $key => $valLm) {
                                echo '<div class="single-brand">
                                            <a href="#"><img src="'.$valLm->logo.'" alt="" /></a>
                                        </div>';
                            }
                        }else{
                            echo 'Error while get data from API';
                        }
                        
                    ?>

                </div>

            </div>
        </div>
    </div>
</div>