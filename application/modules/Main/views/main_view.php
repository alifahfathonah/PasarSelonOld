<?php ini_set('max_execution_time', 1000); ?>

<?php if ($this->session->flashdata('register_success') != '') { ?>
    <script type="text/javascript">
        $(function() {
            $('#selamat-bergabung').modal('show');
        })
    </script>
<?php } ?>

<!-- <pre> -->
<?php //echo json_encode($this->session->userdata('user')) 
?>
<!-- </pre> -->

<div class="modal fade in" id="successLoginModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document" align="center">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="gridSystemModalLabel" align="center">Berhasil Login</h4>
            </div>
            <div class="modal-body">
                <p>Silahkan menikmati fitur-fitur kami.</p>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div id="selamat-bergabung" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content" align="center">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Selamat Datang</h4>
            </div>
            <div class="modal-body">
                <p><?php echo $this->session->flashdata('register_success'); ?></p>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- slider-area-start -->
<?php echo Modules::run('Section/main_slider') ?>
<!-- slider-area-end -->

<div class="container">
    <div class="row">
        <div class="col-md-6">
            <!-- banner category baru  -->
            <?php echo Modules::run('section/banner_4_views')
            ?>
            <!-- end banner category baru  -->
            <br>
        </div>
        <div class="col-md-6">
            <?php echo Modules::run('section/ppob_home');
            ?>
        </div>
    </div>
</div>

<!-- banner-fullwidth-area-start -->
<?php echo ''; //Modules::run('section/banner_full_width') 
?>
<!-- banner-fullwidth-area-end -->
<br>

<!-- best sellers product section -->
<?php echo Modules::run('Section/best_sellers')
?>
<!-- best sellers product section end -->

<!-- feataure-product-area-4-start -->
<?php echo Modules::run('Section/new_product')
?>
<!-- feataure-product-area-4-end -->
<br>

<!-- discount-today-area-start -->
<?php //echo Modules::run('Section/discount_today')
?>
<!-- discount-today-area-end -->
<br>

<!-- banner-3-views-area-start -->
<?php echo ''; //Modules::run('Section/banner_3_views') 
?>
<!-- banner-3-views-area-end -->
<br>

<!-- list-category-product-start -->
<?php echo '';//Modules::run('Section/list_category_product')
?>
<!-- list-category-product-end -->
<br>

<!-- promo-product-area-start -->
<?php echo ''; //Modules::run('Section/promo_product') 
?>
<!-- promo-product-area-end -->
<br>

<!-- promo-product-area-start -->
<?php //echo Modules::run('Section/most_popular_this_week')
?>
<!-- promo-product-area-end -->

<!-- brand-area-4-start -->
<?php //echo Modules::run('Section/best_merchant')
?>
<!-- brand-area-4-end -->

<!-- banner-3-views-area-start -->
<?php //echo Modules::run('Section/testimoni')
?>
<!-- banner-3-views-area-end -->

<!-- promo-product-area-start -->
<?php //echo Modules::run('Section/kelebihan')
?>
<!-- promo-product-area-end -->
<!-- show my-location starts here -->
<?=Modules::run('Section/mylocation');?>
<!-- show my-location ends here -->
