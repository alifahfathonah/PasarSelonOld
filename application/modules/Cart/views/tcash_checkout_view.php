<!-- <script type="text/javascript">
    $(function() {

        $('#nextToPay').click(function (e) {

            e.preventDefault();

            var checkoutUrl = $('#form-payment-tcash').attr('action');

            $.post(checkoutUrl,'', function (data) 
            {
                $('#linkCheckoutUrl').val(data.checkoutUrl);
                $('#formTcash').show();

            }, 'json');

        });

    })
</script> -->

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
                            <h3 class="panel-title">Pilih Metode Pembayaran</h3>
                        </div>
                        <div class="panel-body">
                            <form id="form-payment-tcash" action="<?php echo $checkoutUrl ?>" method="post" target="_blank">
                                
                                <div>
                                    <div class="form-group">
                                        <label class="col-sm-3" for="paymentMethod">Link Tcash</label>
                                        <div class="col-sm-9">
                                            <input type="text" name="linkCheckoutUrl" id="linkCheckoutUrl" value="<?php echo $checkoutUrl ?>">
                                        </div>
                                    </div>
                                </div>

                                <button id="nextToPay" class="btn btn-success"
                                        data-loading-text="<i class='fa fa-spinner fa-pulse fa-fw'></i> Lanjut"
                                        type="submit">
                                    Lanjutkan <i class="fa fa-arrow-right"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>