<!-- breadcrumb start -->
<div class="breadcrumb-area">
    <div class="container">
        <?php echo $breadcrumbs?>
    </div>
</div>
<!-- breadcrumb end -->
<!-- cart-main-area start -->
<div class="account-area pt-30 pb-30 log">
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-lg-6 col-sm-6 col-sm-offset-3">
                <div class="account-info pb-30">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Terima Kasih</h3>
                        </div>
                        <div class="panel-body">
                            <div class="alert alert-<?php echo $statusOrder['style']?>">
                                <center><img src="<?php echo base_url().'assets/images/cart.png'?>"><br>
                                <h3><b><?php echo $statusOrder['title']?></b></h3><?php echo $statusOrder['message']?><br>Terima Kasih telah melakukan transaksi di Pasar Selon
                                </center>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


