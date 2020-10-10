<div id="checkout-loading">
    <div class="loading-content">
        <img width="125px" src="<?php echo base_url('assets/img/linkAja-1.png') ?>" alt="Logo LinkAja">
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
                            <h3 class="panel-title">Detail Pembelian</h3>
                        </div>
                        <div class="panel-body">
                            <form id="form-payment-method" action="" method="post">
                                <table class="table table-striped" style="font-size:11.5px">
                                    <tr>
                                        <td align="right"><img src="<?php echo base_url().'assets/images/cart.png'?>"></td>
                                        <td>
                                            <h3><?php echo $post_data['no_hp']?></h3><br>
                                            <div style="margin-top:-5%">
                                                <i style="font-size:14px"><b><?php echo isset($post_data['description_text']) ? $post_data['description_text'] : ''?></i></b> </br>
                                                Total harga  <?php echo 'Rp. '.number_format($post_data['nominal_text']+$margin)?><br>
                                                Biaya admin Rp. 0
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                                <!-- <div class="alert alert-success">
                                    <p><b>Keterangan :</b></p>
                                    <?php echo $post_data['redaksional_text']?>
                                </div> -->

                                <div id="form_methode_pembayaran">
                                    <label>Metode Pembayaran</label>
                                    <select class="form-control" name="paymentMethod" id="paymentMethod" required="" selected="">
                                        <option value="0" selected=""> - Silahkan pilih - </option>
                                        <option value="1">BANK TRANSFER</option>
    <!--                                                <option value="2">Credit Card</option>-->
                                        <option value="3">LinkAja</option>
                                    </select>
                                    <?php echo form_error('payment') ?>
                                    <input type="hidden" name="customerId" id="customerId" value="<?php echo $customerId?>">
                                    <input type="hidden" name="access_token" id="access_token" value="<?php echo $token?>">
                                    <input type="hidden" name="orderId" id="orderId" value="<?php echo $orderId?>">
                                </div>
                                <br>
                                <!-- bank transfer -->
                                <div id="tf" style="display:none">
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

                                <label>Kode Voucher</label>
                                <div class="input-group">
                                    <input type="text" name="kodeVouceher" class="form-control search-query" placeholder="Masukan Kode Vouceher">
                                    <input type="hidden" name="voucherValue" id="voucherValue" value="<?php echo isset($voucher)?$voucher->value:0?>">
                                    <input name="voucherId" value="" type="hidden">
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-default btn-sm" id="validateVouceher">
                                            Gunakan Promo
                                        </button>
                                    </span>
                                </div>
                                <div id="failed" class="alert alert-danger left" style="margin-top:5px;margin-bottom:-2px;display:none">
                                    <i class="fa fa-times-circle"></i> <b>Peringatan! </b> Validasi Voucher gagal dilakukan.
                                </div>

                                <div class="success_validate" style="display:none">
                                    <div class="alert alert-success left" style="margin-top:5px;margin-bottom:-2px">
                                        <i class="fa fa-check-circle"></i><b> Berhasil!</b> Validasi Voucher berhasil dilakukan.
                                    </div>
                                    <br>
                                </div>

                                <br>
                                <label>Rincian Pembelian</label>
                                <table class="table table-striped">
                                    <tr>
                                        <td>Harga</td>
                                        <td id="harga" align="right"> <?php echo 'Rp. '.number_format($post_data['nominal_text']+$margin)?> </td>
                                    </tr>
                                    
                                    <tr>
                                        <td>Promo/Kode Voucher</td>
                                        <td id="potongan_voucher_html" align="right">-</td>
                                    </tr>

                                    <tr>
                                        <td>TOTAL BAYAR</td>
                                        <td id="total_bayar" align="right"> <?php echo 'Rp. '.number_format($post_data['nominal_text']+$margin)?></td>
                                    </tr>
                                </table>

                                <div  id="formTcash" style="display:block">
                                    <div class="form-group">
                                        <div class="col-sm-9">
                                            <input type="hidden" name="linkCheckoutUrl" id="linkCheckoutUrl" value="" class="form-control">
                                            <input type="hidden" name="message" id="tCash-token" value="">
                                        </div>
                                    </div>
                                </div>

                                <!-- hidden form -->
                                <input type="hidden" name="customerId" id="customerId" value="<?php echo $customerId?>">
                                <!-- <input type="hidden" name="invoiceId" id="invoiceId" value="<?php echo $invoiceId?>"> -->
                                <input type="hidden" name="orderId" id="orderId" value="<?php echo $orderId?>">
                                <input type="hidden" name="token" id="token" value="<?php echo $token?>">

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
                    $('#imgLogoHtml').html('<img src="<?php echo base_url('assets/img/BNI_logo.svg.png') ?>" alt="" width="100">');
                });

            }

        });

        $('select[name="paymentMethod"]').change(function () {

            if ($(this).val() === '1') {
                /*show escrowList*/
                $.getJSON("<?php echo site_url('templates/References/getEscrowList') ?>", '', function (data) {
                    $.each(data, function (i, o) {
                        $('<option value="#">-Silahkan Pilih-</option>').appendTo($('#escrowBankAccountId'));
                        $('<option value="' + o.id + '">' + o.bankName + '</option>').appendTo($('#escrowBankAccountId'));
                    });  
                }); 

                $('#tf').show();
                $('#formTcash').hide();
                $('#form-payment-method').removeClass('form-tcash');
                $('#form-payment-method').attr('action', 'PPOB/checkoutWithEscrow');
                $('#lanjut').prop('disabled', false);

            }  else if ($(this).val() === '3') {

                var post = {
                    customerid:$('#customerId').val(),
                    source:'web',
                    orderId:$('#orderId').val(),
                    access_token:$('#token').val()
                };

                $.post("PPOB/checkoutFinish",post, function (data)
                {
                    console.log(data);
                    $('#linkCheckoutUrl').val(data.checkoutUrl);
                    $('#tCash-token').val(data.token);
                    $('#form-payment-method').attr('action', data.checkoutUrl).addClass('form-tcash');

                }, 'json');

                $('#tf').hide();
                $('#formTcash').show();
                $('#lanjut').prop('disabled', false);
                
            }

        });

    });

    $(function() {

        $('#form-payment-method').submit(function (e) {
            e.preventDefault();
            var form = this;

            if($('select[name="paymentMethod"]').val() === '3') {

                $('#checkout-loading').fadeIn();

                setTimeout(function () {
                    $('#checkout-loading').fadeOut();
                    form.submit();
                }, 3000)

            }

            if($('select[name="paymentMethod"]').val() === '1') {

                $('#checkout-loading-bank-tf').fadeIn();

                setTimeout(function () {
                    $('#checkout-loading-bank-tf').fadeOut();
                    form.submit();
                }, 3000)

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
            orderId = $('input[name=orderId]').val();
            var totalPembayaran = $('input[name=total_pembayaran]').val();
            if(voucherCode != ''){
                $.getJSON("<?php echo site_url('PPOB/PPOB/validate_voucher_code') ?>?vcCode="+voucherCode+'&orderId='+orderId, '', function (data) {
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

        /*$('#voucherId').click(function() {
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


        }*/

        
//        $('#lanjut').on('click', function() {
//            $('#checkout-loading').fadeIn();
//        });

    })
</script>
