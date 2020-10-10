<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function () {

        $('select[name="escrowBankAccountId"]').change(function () {
            if ($(this).val()) {
                $.getJSON("<?php echo site_url('templates/References/getEscrowBankAccount') ?>/" + $(this).val(), '', function (data) {
                   $('#bankName').val(data.accountName);
                   $('#accountNo').val(data.accountNo);
                   $('#location').val(data.location);
                });

            }

        });

        $('select[name="paymentMethod"]').change(function () {

            if ($(this).val() === '1') {
                document.getElementById('tf').style.display = 'block';
                document.getElementById('cc').style.display = 'none';
                $('#formTcash').hide();
                $('#form-payment-method').removeClass('form-tcash');
//                $('#form-payment-method #lanjut').attr('type','submit');
                $('#lanjut').prop('disabled', false);
            } else if ($(this).val() === '2') {
                document.getElementById('tf').style.display = 'none';
                document.getElementById('cc').style.display = 'block';
                $('#formTcash').hide();
                $('#form-payment-method').removeClass('form-tcash');
//                $('#form-payment-method #lanjut').attr('type','submit');
                $('#lanjut').prop('disabled', false);
            } else if ($(this).val() === '3') {
                document.getElementById('tf').style.display = 'none';
                document.getElementById('cc').style.display = 'none';
                $('#formTcash').hide();
                $('#lanjut').prop('disabled', true);
            }

        });
    });

    $(function() {

        $('select[name="paymentMethod"]').change(function () {

            if ($(this).val() === '3') {


                var post = {
                    id:$('#customerId').val(),
                    source:'web',
                    invoiceId:$('#invoiceId').val(),
                    access_token:$('#token').val()
                };

                $.post("https://api.pasarselon.com/api/Customers/"+post.id+"/pay/tcash/token?source=web&invoiceId="+post.invoiceId+"&access_token="+post.access_token+"",'', function (data)
                {
                    $('#linkCheckoutUrl').val(data.checkoutUrl);
                    $('#tCash-token').val(data.token);
                    $('#form-payment-method').attr('action', data.checkoutUrl).addClass('form-tcash');
//                    $('.form-tcash #lanjut').attr('type','button');
                    $('#lanjut').prop('disabled', false);

                }, 'json');
            }

            if ($(this).val() === '2') {

                var post = {
                    id:$('#customerId').val(),
                    source:'web',
                    invoiceId:$('#invoiceId').val(),
                    access_token:$('#token').val()
                };

                $.post("https://api.pasarselon.com/api/Customers/"+post.id+"/pay/credit-card?source="+post.source+"&invoiceId="+post.invoiceId+"&access_token="+post.access_token+"",'', function (data)
                {
                    $('#linkCheckoutUrl').val(data.checkoutUrl);
                    $('#form-payment-method').attr('action', data.checkoutUrl);
                    $('#lanjut').prop('disabled', false);

                }, 'json');
            }else if($(this).val() === '1') {
                var escrowBankAccountId = $('select[name="escrowBankAccountId"]').val();
                $.post("<?php echo base_url('Cart/Checkout/escrowFind?id=') ?>"+escrowBankAccountId,'', function (data)
                {
                    if(data.status === 200) {
                        $('#accountName').val(data.accountName);
                        $('#accountNo').val(data.accountNo);
                        $('#location').val(data.location);
                    }else{
                        alert('Error get escrow bank detail');
                    }

                }, 'json');
            }
        });
        
        $('#form-payment-method').submit(function (e) {
            e.preventDefault();
            var form = this;

            if($('select[name="paymentMethod"]').val() === '3') {
                $('#checkout-loading').fadeIn();

                setTimeout(function () {
                    $('#checkout-loading').fadeOut();
                    form.submit();
                }, 3000)
            }else if($('select[name="paymentMethod"]').val() === '1') {
                $('#checkout-loading-bank-tf').fadeIn();

                setTimeout(function () {
                    $('#checkout-loading-bank-tf').fadeOut();
                    form.submit();
                }, 3000)
            }
        });
        
//        $('#lanjut').on('click', function() {
//            $('#checkout-loading').fadeIn();
//        });

    })
</script>

<div id="checkout-loading">
    <div class="loading-content">
        <img width="125px" src="<?php echo base_url('assets/img/Logo_tcash.png') ?>" alt="Logo T Cash">
        <br><br>
        <i class="fa fa-cog fa-spin fa-3x fa-fw"></i>
        <br><br>
        <span class="">Pastikan saldo anda cukup untuk melakukan transaksi . . .</span>
    </div>
</div>
<div id="checkout-loading-bank-tf">
    <div class="loading-content">
        <img width="125px" src="<?php echo base_url('assets/img/BNI_logo.svg.png') ?>" alt="Logo Bank BNI">
        <br><br>
        <i class="fa fa-cog fa-spin fa-3x fa-fw"></i>
        <br><br>
        <span class="">Mempersiapkan metode bank transfer, silahkan menunggu . . .</span>
    </div>
</div>

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
                            <form id="form-payment-method" action="<?php echo base_url('Cart/Checkout/confirm_payment') ?>" method="post">
                                <input type="hidden" name="invoiceId" value="<?php echo $invoiceId; ?>">
                                <div class="form-group">
                                    <label class="col-sm-4" for="paymentMethod">* Metode Pembayaran</label>
                                    <div class="col-sm-6">
<!--                                        --><?php //echo $this->master->get_custom($params = array('table' => 'PaymentMethod', 'id' => 'id', 'name' => 'name', 'where' => array()), '', 'paymentMethod', 'paymentMethod', 'form-control', 'required selected', 'onchange="showDiv()"') ?>
                                        <select class="form-control" name="paymentMethod" id="paymentMethod" required="" selected="">
                                            <option value="0" selected=""> - Silahkan pilih - </option>
                                            <option value="1">BANK TRANSFER</option>
                                            <!-- <option value="2">Credit Card</option> -->
                                            <option value="3">TCASH</option>
                                            <option value="4">B-Secure Payment Gateway</option>
                                        </select>
                                        <?php echo form_error('payment') ?>
                                        <input type="hidden" name="customerId" id="customerId" value="<?php echo $this->session->userdata('user')->id?>">
                                        <input type="hidden" name="token" id="token" value="<?php echo $this->session->userdata('token')?>">
                                        <input type="hidden" name="invoiceId" id="invoiceId" value="<?php echo $invoiceId?>">
                                    </div>
                                </div>

                                <div  id="formTcash" style="display:none">
                                    <div class="form-group">
                                        <label class="col-sm-3" for="paymentMethod">Link Tcash</label>
                                        <div class="col-sm-9">
                                            <input type="text" name="linkCheckoutUrl" id="linkCheckoutUrl" value="" class="form-control">
                                            <input type="text" name="message" id="tCash-token" value="">
                                        </div>
                                    </div>
                                </div>
                                

                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <div id="tf" style="display:none">
                                            <div class="panel-body">
                                                <div class="row">
                                                    <img
                                                        src="<?php echo base_url('assets/img/icon/icon-bni.png'); ?>"
                                                        alt="" width="100">

<!--                                                    <img-->
<!--                                                        src="https://ecs7.tokopedia.net/img/toppay/payment-logo/icon-cimb.png"-->
<!--                                                        alt="" width="100">-->

                                                </div>
                                                <div class="row mt-20">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="control-label" for="escrowBankAccountId">Bank Tujuan</label>
                                                            <select class="form-control" name="escrowBankAccountId" id="escrowBankAccountId">
                                                                <?php foreach ($escrow_list as $list) { ?>
                                                                <option value="<?php echo $list->id; ?>"><?php echo $list->bankName; ?></option>
                                                                <?php } ?>
                                                            </select>
<!--                                                            --><?php //echo $this->master->get_custom($params = array('table' => 'EscrowBankAccount', 'id' => 'id', 'name' => 'bankName', 'where' => array()) , '' , 'escrowBankAccountId', 'escrowBankAccountId', 'form-control', 'required', 'required') ?>
                                                            <?php echo form_error('escrowBankAccountId') ?>

                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="control-label" for="accountName">Nama Pemilik
                                                                Rekening</label>
                                                            <input type="text" id="accountName" class="form-control" value="Bank BNI" name="bankName" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="control-label" for="accountNo">No
                                                                Rekening</label>
                                                            <input type="text" id="accountNo" name="accountNo" class="form-control" value="507059426" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label class="control-label" for="bankCabang">Kantor Cabang </label>
                                                            <input type="text" id="bankCabang" name="bankCabang" class="form-control" value="Tebet" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="cc" style="display:none">
                                            <div class="panel-body">

                                                <div class="row">

                                                    <img src="<?php echo base_url().'assets/images/payment-logo.png'?>" alt="" height="20" width="100%">
                                                    <br>
                                                    <p>Anda akan melakukan pembayaran menggunakan kartu kredit, Silahkan klik lanjut untuk melanjutkan pembayaran menggunakan kartu kredit</p>
                                                </div>
                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button id="lanjut" class="btn btn-login pull-right"
                                        data-loading-text="<i class='fa fa-spinner fa-pulse fa-fw'></i> Lanjut"
                                        type="submit" disabled>
                                    Lanjut
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>