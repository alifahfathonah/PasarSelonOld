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
        <img width="125px" src="<?php echo base_url('assets/img/logo_2.png') ?>" alt="Logo Bank BNI">
        <br><br>
        <i class="fa fa-cog fa-spin fa-3x fa-fw"></i>
        <br><br>
        <span class="">Mempersiapkan metode bank transfer, silahkan menunggu . . .</span>
    </div>
</div>
<div id="checkout-loading-cc">
    <div class="loading-content">
        <img width="125px" src="<?php echo base_url('assets/img/finpaylogo.jpg') ?>" alt="Logo T Cash">
        <br><br>
        <i class="fa fa-cog fa-spin fa-3x fa-fw"></i>
        <br><br>
        <span class="">Mempersiapkan metode bayar dengan Credit Card, silahkan menunggu . . .</span>
    </div>
</div>

<!--<div id="checkout-loading-atmbersama">
    <div class="loading-content">
        <img width="125px" src="<?php echo base_url('assets/img/atmbersama.jpg') ?>" alt="Logo T Cash">
        <br><br>
        <i class="fa fa-cog fa-spin fa-3x fa-fw"></i>
        <br><br>
        <span class="">Mempersiapkan metode bayar dengan ATM Bersama Payment Gateway, silahkan menunggu . . .</span>
    </div>
</div>
/#checkout-loading-atmbersama -->
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
                            <h3 class="panel-title">Metode Pembayaran</h3>
                        </div>
                        <div class="panel-body">
                            <form id="form-payment-method" action="<?php echo base_url('Cart/Checkout/confirm_payment') ?>" method="post">
                                <input name="voucherId" value="" type="hidden">
                                <table class="table table-striped" style="font-size:11.5px">
                                    <tr>
                                        <td colspan="3" style="padding-top:15px"><h4><i class="fa fa-money"></i> Metode Pembayaran</h4></td>
                                    </tr>
                                    <tr>
                                        <td style="padding-top:15px;width:180px">Pilih Metode Pembayaran</td>
                                        <td colspan="2">
                                            <select class="form-control" name="paymentMethod" id="paymentMethod" required="" selected="">
                                                <option value="0" selected=""> - Silahkan pilih - </option>
                                                 <option value="1">BANK TRANSFER</option>
<!--                                                <option value="2">Credit Card</option>-->
                                                <option value="3">Link Aja</option>
                                                <option value="4">ATM Bersama</option>
                                            </select>
                                            <?php echo form_error('payment') ?>
                                            <input type="hidden" name="customerId" id="customerId" value="<?php echo $this->session->userdata('user')->id?>">
                                            <input type="hidden" name="token" id="token" value="<?php echo $this->session->userdata('token')?>">
                                            <input type="hidden" name="invoiceId" id="invoiceId" value="<?php echo $invoiceId?>">
                                        </td>
                                    </tr>
                                </table>
                                <!-- bank transfer -->
                                <div id="tf" style="display:none">
                                    <b><i class="fa fa-exchange"></i> BANK TRANSFER</b>
                                    <table class="table table-striped" style="font-size:11.5px;">
                                        <tr>
                                            <td style="padding-top:15px">Bank Tujuan</td>
                                            <td colspan="2">
                                                <select class="form-control" name="escrowBankAccountId" id="escrowBankAccountId">
                                                    <?php foreach ($escrow_list as $list) { ?>
                                                    <option value="<?php echo $list->id; ?>"><?php echo $list->bankName; ?></option>
                                                    <?php } ?>
                                                </select>
                                                <?php echo form_error('escrowBankAccountId') ?>
                                            </td>
                                        </tr>
                                        <!-- hidden escrowBank -->
                                        <input type="hidden" id="accountName" class="form-control" value="Bank BNI" name="bankName" readonly>
                                        <input type="hidden" id="accountNo" name="accountNo" class="form-control" value="507059426" readonly>
                                        <input type="hidden" id="bankCabang" name="bankCabang" class="form-control" value="Tebet" readonly>
                                           
                                        <tr>
                                            <td rowspan="3" width="180px" id="imgLogoHtml"  align="center">-</td>
                                            <td>Atas Nama</td>
                                            <td id="accountNameHtml">-</td>
                                        </tr>
                                        
                                        <tr>
                                            <td>No Rekening</td>
                                            <td id="accountNoHtml">-</td>
                                        </tr>

                                        <tr>
                                            <td>Kantor Cabang</td>
                                            <td id="locationHtml">-</td>
                                        </tr>
                                    </table>
                                </div>
                                
                                <!-- Kartu Kredit -->
                                <div id="cc" style="display:none">
                                    <b><i class="fa fa-credit-card"></i> KARTU KREDIT</b>
                                    <table class="table table-striped" style="font-size:11.5px;">
                                        <tr>
                                            <td>
                                                <img src="<?php echo base_url().'assets/images/payment-logo.png'?>" alt="" height="20" width="100%">
                                                        <br>
                                                        <br>
                                                        <p>Anda akan melakukan pembayaran menggunakan kartu kredit, Silahkan klik lanjut untuk melanjutkan pembayaran menggunakan kartu kredit</p>
                                            </td>
                                        </tr>
                                        
                                    </table>
                                </div>

<!--                                 vouceher -->
                                <div id="voucher">

                                        <div class="panel panel-default">
                                            <div class="panel-heading" role="tab" id="headingOne">
                                                <h4 align="center" class="panel-title pro-d-title-baru">
                                                    <a role="button" data-toggle="collapse" data-parent="#accordion" href="#1>" aria-expanded="false" aria-controls="1">
                                                    <h4 style="color:white"> <i class="fa fa-gift"></i> Masukan Kode Voucher </h4>
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="1" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                                                <div class="panel-body">
                                                    <div class="table-content table-responsive">
                                                        <table style="font-size:11.5px;width:100%">
                                                            <tr>
                                                                <td align="center">
                                                                    <b>KODE VOUCHER</b> <br>
                                                                    <input class="form-control" type="text" name="kodeVouceher" style="height:50px;width:450px; font-size:30px; text-align:center;" value="<?php echo isset($voucher)?$voucher->code:''?>">
                                                                    <input type="hidden" name="voucherValue" id="voucherValue" value="<?php echo isset($voucher)?$voucher->value:0?>">
                                                                    <br>
                                                                    <div>
                                                                        <h2 id="potongan_voucher">Rp. <?php echo isset($voucher)?number_format($voucher->value):0?>,-</h2>
                                                                    </div>

                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td align="left">
                                                                    <div style="margin-top:-15px !important; text-align: center">
                                                                        <button type="button" id="validateVouceher" class="btn btn-primary">Validate Voucher</button><br>
                                                                        <div id="failed" class="alert alert-danger left" style="margin-top:5px;margin-bottom:-2px;display:none">
                                                                            <i class="fa fa-times-circle"></i> <b>Peringatan! </b> Validasi Voucher gagal dilakukan.
                                                                        </div>

                                                                        <div class="success_validate" style="display:none">
                                                                            <div class="alert alert-success left" style="margin-top:5px;margin-bottom:-2px">
                                                                                <i class="fa fa-check-circle"></i><b> Berhasil!</b> Validasi Voucher berhasil dilakukan.
                                                                            </div>
                                                                            <br>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>

                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>



                                </div>

                                

                                <!-- total pembayaran -->
                                <table class="table table-striped" style="font-size:11.5px">
                                    <tr>
                                        <td colspan="2" style="padding-top:15px"><h4> <i class="fa fa-info-circle"></i> Total Pembayaran</h4></td>
                                    </tr>
                                    <tr>
                                        <td width="80%" align="right">Subtotal</td>
                                        <td align="right">Rp. <?php echo number_format($customerOrder->netTotal); ?> 
                                            <input type="hidden" name="subtotalOrder" id="subtotalOrder" value="<?php echo $customerOrder->netTotal?>" readonly>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="right">Kode Voucher</td>
                                        <td align="right"><div id="kodeVoucher"><?php echo isset($voucher)?$voucher->code : '-' ; ?></div></td>
                                    </tr>
                                    <tr>
                                        <td align="right">Potongan Voucher</td>
                                        <td align="right" id="potongan_voucher_html"><?php echo isset($voucher)?number_format($voucher->value):'-'?></td>
                                    </tr>
                                    <!-- total + vocher -->
                                    <?php 
                                        $voucherTotal = isset($voucher)?$voucher->value:0;
                                        $subtotal = $customerOrder->netTotal-$voucherTotal;
                                    ?>
                                    <tr>
                                        <input name="total_pembayaran" type="hidden" value="<?php echo $subtotal; ?>">
                                        <td align="right">Total Pembayaran</td>
                                        <td align="right" id="valueSubtotalHtml">Rp. <?php echo number_format($subtotal); ?></td>
                                    </tr>
                                </table>

                                <input type="hidden" name="invoiceId" value="<?php echo $invoiceId; ?>">

                                <div  id="formTcash" style="display:none">
                                    <div class="form-group">
                                        <label class="col-sm-3" for="paymentMethod">Link Aja</label>
                                        <div class="col-sm-9">
                                            <input type="text" name="linkCheckoutUrl" id="linkCheckoutUrl" value="" class="form-control">
                                            <input type="text" name="message" id="tCash-token" value="">
                                        </div>
                                    </div>
                                </div>
                                <!-- form b-secure starts here -->
                                <div  id="formBsecure" style="display:none">
                                    <div class="form-group">
                                        <label class="col-sm-3" for="bsecureCheckoutUrl">B-Secure</label>
                                        <div class="col-sm-9">
                                            <input type="text" name="txnReference" id="bsecure-reference" value="" class="form-control">
                                            <input type="text" name="txnToken" id="bsecure-token" value="">
                                        </div>
                                    </div>
                                </div>
                                <!-- form b-secure ends here -->
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

<script type="text/javascript">
    var base_url = '<?php echo base_url()?>';
    var api = '<?php echo API_DATA; ?>';
    var dimasApi = '<?php echo DIMAS_API; ?>';
    document.addEventListener('DOMContentLoaded', function () {

        $('select[name="escrowBankAccountId"]').change(function () {
            if ($(this).val()) {
                $.getJSON("<?php echo site_url('templates/References/getEscrowBankAccount') ?>/" + $(this).val(), '', function (data) {
                   $('#bankName').val(data.accountName);
                   $('#accountNo').val(data.accountNo);
                   $('#location').val(data.location);

                   /*html*/
                    $('#accountNameHtml').html(data.accountName);
                    $('#accountNoHtml').html(data.accountNo);
                    $('#locationHtml').html(data.location);
                    $('#imgLogoHtml').html('<img src="'+base_url+'assets/img/icon/'+data.logoFile+'" alt="" width="100">');


                });

            }

        });

        $('select[name="paymentMethod"]').change(function () {

            if ($(this).val() === '1') {
                $('#tf').show();
                $('#cc').hide();
                /*document.getElementById('tf').style.display = 'block';
                document.getElementById('cc').style.display = 'none';*/
                $('#formTcash').hide();
                $('#form-payment-method').removeClass('form-tcash');
//                $('#form-payment-method #lanjut').attr('type','submit');
                $('#lanjut').prop('disabled', true);
            } else if ($(this).val() === '2') {
                $('#tf').hide();
                $('#cc').show();
                /*document.getElementById('tf').style.display = 'none';
                document.getElementById('cc').style.display = 'block';*/
                $('#formTcash').hide();
                $('#form-payment-method').removeClass('form-tcash');
//                $('#form-payment-method #lanjut').attr('type','submit');
                $('#lanjut').prop('disabled', true);
            } else if ($(this).val() === '3') {
                $('#tf').hide();
                $('#cc').hide();
                /*document.getElementById('tf').style.display = 'none';
                document.getElementById('cc').style.display = 'none';*/
                $('#formTcash').hide();
                $('#lanjut').prop('disabled', false);
            } else if($(this).val() === '4'){
                $("#tf").hide();
                $("#cc").hide();
                $("#formBsecure").hide();
                $('#lanjut').prop('disabled', false);
            }

        });
    });

    $(function() {

        $('select[name="paymentMethod"]').change(function () {

            if ($(this).val() === '2') {

                var post = {
                    id:$('#customerId').val(),
                    source:'web',
                    invoiceId:$('#invoiceId').val(),
                    access_token:$('#token').val()
                };

                $.post(""+api+"Customers/"+post.id+"/pay/credit-card?source="+post.source+"&invoiceId="+post.invoiceId+"&access_token="+post.access_token+"",'', function (data)
                {
                    console.log(data);
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
                        $('#form-payment-method').attr('action', '<?php echo base_url('Cart/Checkout/confirm_payment') ?>');
                        /*html*/
                        $('#accountNameHtml').html(data.accountName);
                        $('#accountNoHtml').html(data.accountNo);
                        $('#locationHtml').html(data.location);
                        $('#imgLogoHtml').html('<img src="<?php echo IMG_ICON; ?>'+data.logoFile+'" alt="" width="100">');
                        $('#lanjut').prop('disabled', false);
                    }else{
                        alert('Error get escrow bank detail');
                    }

                }, 'json');
            } else if($(this).val() === '4'){ // pilihan b-secure
                   
	    }
        });
        
        $('#form-payment-method').submit(function (e) {
            e.preventDefault();
            var form = this;
            //                $('#checkout-loading').fadeIn();
            
                            var post = {
                                id:$('#customerId').val(),
                                source:'web',
                                invoiceId:$('#invoiceId').val(),
                                access_token:$('#token').val(),
                                voucherId: $('input[name=voucherId]').val()
                            };
            //                alert(post.voucherId);

            if($('select[name="paymentMethod"]').val() === '3') {

                $.post(""+api+"Customers/"+post.id+"/pay/tcash/token?source=web&invoiceId="+post.invoiceId+"&access_token="+post.access_token+"&voucherId="+post.voucherId,'', function (data)
                {
                    $('#linkCheckoutUrl').val(data.checkoutUrl);
                    $('#tCash-token').val(data.token);
                    $('#form-payment-method').attr('action', data.checkoutUrl).addClass('form-tcash');
//                    $('.form-tcash #lanjut').attr('type','button');
                    $('#lanjut').prop('disabled', false);

                    setTimeout(function () {
                        $('#checkout-loading').fadeOut();
                        form.submit();
                    }, 3000)

                }, 'json');

            }else if($('select[name="paymentMethod"]').val() === '4'){
               /* 
                $.post(""+api+"Customers/"+post.id+"/pay/bsecure/token?source=web&invoiceId="+post.invoiceId+"&access_token="+post.access_token+"&voucherId="+post.voucherId,'', function (data)
                {
                    $('#bsecureCheckoutUrl').val(data.checkoutUrl);
                    $('#bsecure-token').val(data.token);
                    $('#form-payment-method').attr('action', data.checkoutUrl).addClass('form-tcash');
                    //$('.form-tcash #lanjut').attr('type','button');
                    $('#lanjut').prop('disabled', false);

                    setTimeout(function () {
                        $('#checkout-loading').fadeOut();
                        form.submit();
                    }, 3000)

                }, 'json');
               */ 
                    let url = 'Cart/Checkout/get_bsecure_token?';
                    url += 'invoice_id=' + encodeURIComponent(post.invoiceId) + '&voucher_id=';
                    url += post.voucherId;

                    $.getJSON('<?=site_url("' + url + '")?>',function(response){
                            console.log(response);
                            console.table(response);

                        if (response.error ) {
                                let error = response.error;
                                swal('OOps','Error: '+error.message ,'error');
                                return;
                        } else {
                                let checkoutUrl = response.checkoutUrl;
                                let txnReference = response.dataTxn.txnReference;
                                let txnToken = response.dataTxn.txnToken;
              
                                console.log(response.txnReference);
                                console.log(response.txnToken);
                        
                                $('#bsecure-reference').val(txnReference);
                                $('#bsecure-token').val(txnToken);
                                $('#form-payment-method').attr('action', checkoutUrl).addClass('form-tcash');
                                $('#lanjut').prop('disabled', false);
    
                                setTimeout(function(){
                                    $('#checkout-loading').fadeOut();
                                    form.submit();
                                }, 3000);
                        }
                    });
            } else if($('select[name="paymentMethod"]').val() === '1') {
                $('#checkout-loading-bank-tf').fadeIn();

                setTimeout(function () {
                    $('#checkout-loading-bank-tf').fadeOut();
                    form.submit();
                }, 3000)
            }else if($('select[name="paymentMethod"]').val() === '2') {
                $('#checkout-loading-cc').fadeIn();
                setTimeout(function () {
                    $('#checkout-loading').fadeOut();
                    form.submit();
                }, 3000)
            }
        });

        $('#voucherId').click(function() {
            voucher = parseInt($('#voucherValue').val());
            subtotal = parseInt($('#subtotalOrder').val())
          if ($(this).is(':checked')) {
            var total =  subtotal - voucher;
            $('#valueSubtotalHtml').html('Rp. ' +total.format()+ '').show('fast');
            $('#potongan_voucher').html('Rp. ' +voucher.format()+ ',-').show('fast');
            $('#potongan_voucher_html').html('Rp. ' +voucher.format()+ '').show('fast');
          }else{
            var total = subtotal;
            $('#valueSubtotalHtml').html('Rp. ' +total.format()+ '').show('fast');
            $('#potongan_voucher').html('Rp. 0,-').show('fast');
            $('#potongan_voucher_html').html('Rp. 0').show('fast');
          }
        });

        $('#validateVouceher').click(function (e) {
            e.preventDefault();
            $('.success_validate').hide('fast');
            $('#failed').hide('fast');
            validate_voucher_code();
        });

        function validate_voucher_code(){
            voucherCode = $('input[name=kodeVouceher]').val();
            invoiceId = $('input[name=invoiceId]').val();
            var totalPembayaran = $('input[name=total_pembayaran]').val();
            if(voucherCode != ''){
                $.getJSON("<?php echo site_url('Cart/Checkout/validate_voucher_code') ?>?vcCode="+voucherCode+'&invoiceId='+invoiceId, '', function (data) {
                    if(data.totalVoucher > 0){
                        $('.success_validate').show('fast');
                        $('#potongan_voucher').html(' Rp. '+ data.totalVoucher.format() +',-');
                        $('input[name=totalVoucher]').val(data.totalVoucher);
                        $('#potongan_voucher').html(' Rp. '+data.totalVoucher.format() +',-');
                        $('input[name=voucherId]').val(data.voucherId);
                        $('#potongan_voucher_html').html(' Rp. '+ data.totalVoucher.format() +',-');
                        $('#kodeVoucher').html(voucherCode);
//                        $('#totalVoucher').html(' Rp. '+ data.totalVoucher.format());
                        totalPembayaran = totalPembayaran - data.totalVoucher;
                        $('#valueSubtotalHtml').html(totalPembayaran.format());
//                        $('#totalPayDiv').html(' Rp. '+totalPembayaran.format());
                        $('#voucherId').val(data.voucherId);
                        $('#failed').hide('fast');
                    }else{
                        $('#potongan_voucher').html('Rp. 0,-');
                        $('#kodeVoucher').html('-');
                        $('#potongan_voucher_html').html('-');
                        $('input[name=voucherId]').val();
                        $('#valueSubtotalHtml').html('Rp '+parseInt(totalPembayaran).format());
                        $('.success_validate').hide('fast');
                        $('#failed').show('fast');
                    }

                });
            }else{
                alert('Masukan Kode Voucher');
                return false;
            }


        }

        
//        $('#lanjut').on('click', function() {
//            $('#checkout-loading').fadeIn();
//        });

    })
</script>
