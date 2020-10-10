<!-- FOOTER AREA START -->
<footer>
<!-- footer-top-area -->
<div class="footer-top-area border-1 ptb-30 bg-color-3">
    <div class="container">
        <ul class="footer-socmed">
            <?php 
if(is_array($socmed)):
foreach($socmed as $sos) { ?>
            <li><a href="<?php echo $sos->link; ?>"><i class="<?php echo $sos->icon ?>"></i></a></li>
            <?php 
}
endif; ?>
        </ul>
        <div class="row">
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="footer-title">
                    <h4>Store Information</h4>
                </div>
                <div class="footer-widget">
                    <div class="contact-info">
                        <ul>
                            <li>
                                <div class="contact-icon">
                                    <i class="fa fa-envelope-o"></i>
                                </div>
                                <div class="contact-text">
                                    <a href="mailto:cs@halalshopping.mykisel.com"><span>cs@halalshopping.mykisel.com</span></a>
                                </div>
                            </li>
                            <li>
                                <div class="contact-icon">
                                    <i class="fa fa-phone"></i>
                                </div>
                                <div class="contact-text">
                                    <span>Telp. </span>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
                <div class="footer-title">
                    <h4>Halal Shopping</h4>
                </div>
                <div class="footer-widget">
                    <div class="list-unstyled">
                        <ul>
                            <li><a href="<?=base_url().$default_footer['about']['url'];?>"><?=$default_footer['about']['content'];?></a></li>
                            <li><a href="<?php echo base_url('Information/panduan_berbelanja') ?>">Panduan Berbelanja</a></li>
<!--                            <li><a href="--><?php //echo base_url('Information/informasi_pengantar') ?><!--">Informasi Pengantar</a></li>-->
                            <li><a href="<?php echo base_url('Information/faq') ?>">FAQ</a></li>
<!--                            <li><a href="--><?php //echo base_url('Information/aturan_penggunaan') ?><!--">Aturan Penggunaan</a></li>-->
                            <li><a href="<?php echo base_url('Information/privacy_policy') ?>">Privacy Policy</a></li>
                            <li><a href="<?php echo base_url('Information/syarat_ketentuan') ?>">Syarat dan Ketentuan</a></li>
                       </ul>
                    </div>
                </div>
            </div>
            <div class="clearfix clearfix-cs"></div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
                <div class="footer-title">
                    <h4>Customer</h4>
                </div>
                <div class="footer-widget">
                    <div class="list-unstyled">
                        <ul>
                            <li><a href="<?php echo base_url('Profile'); ?>">My Account</a></li>
                            <li><a href="<?php echo base_url('Cart/pembayaran'); ?>">Order History</a></li>
                            <li><a href="<?php echo base_url('Product/LastSeen') ?>">Last Viewed</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</footer>
    <!-- FOOTER AREA END -->

    <!-- copyright-area-start -->
    <div class="copyright-area text-center bg-color-3">
    <div class="container">
        <div class="copyright-border ptb-30">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="copyright text-left">
                        <p>Copyright <a target="_blank" href="<?php echo base_url(); ?>">Kisel @<?=date('Y')?></a>. All Rights Reserved</p>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="payment text-right">
<!--                        <a href="#"><img src="--><?php //echo base_url()?><!--assets/img/payment.png" alt="" /></a>-->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    <!-- copyright-area-end -->

<?php echo Modules::run('Templates/plugin')?>
<?php if(isset($_GET['popup']) && $_GET['popup'] == 'register_success') { ?>
    <div align="center" id="konfirmasi-modal" class="modal bs-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content" id="dialog-download">
                <img style="margin:15px 0;" src="<?php echo base_url('assets/img/logo_2.png') ?>" alt="">
                <h2>Thank You</h2>
                <hr/>
                <p style="margin-top: -10px; padding: 15px; font-size: 11px;">Terima kasih telah melakukan konfirmasi, Anda sudah bisa <a href="<?php echo base_url('Login') ?>">login</a>.</p>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $(function() {
            $('#konfirmasi-modal').modal('show');
        })
    </script>
<?php } ?>
