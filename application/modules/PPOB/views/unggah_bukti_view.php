<?php
$bankSource = $this->templates_model->bankNameOnlyValue();
?>
<script type="text/javascript">
    $(function () {

        var source = jQuery.parseJSON('<?php echo $bankSource ?>');

        $( "#accountBankName" ).autocomplete({
            source: source,
            appendTo: '#autocomplete-wrapper'
        });

        $('#accountBankName').change(function () {
            if ($(this).val()) {
                $.getJSON("<?php echo site_url('Templates/References/getCourierPackageByCourier') ?>/" + $(this).val(), '', function (data) {
                    
                    $('#courierPackageId option').remove();
                    $('<option value="">-Silahkan Pilih-</option>').appendTo($('#ProvinceId'));
                    $.each(data, function (i, o) {
                        $('<option value="' + o.id + '">' + o.name + '</option>').appendTo($('#courierPackageId'));
                    });

                });
            } else {
                $('#courierPackageId option').remove()
            }
        });

    });

</script>
<div class="account-area pt-30 pb-30 log">
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-lg-6 col-sm-6 col-sm-offset-3">
                <div class="account-info pb-30">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Unggah Bukti Pembayaran</h3>
                        </div>
                        <div class="panel-body">
                            <form id="form-unggah" enctype="multipart/form-data" method="post">
                                <!-- hidden form -->
                                <input id="orderID" type="hidden" name="orderID" value="<?php echo $_GET['orderId']?>">
                                <input id="customerId" type="hidden" name="customerId"  value="<?php echo $customerId?>">
                                <input id="accountNoBank" type="hidden" name="accountNoBank" value="<?php echo $escrow->escrow->accountNo?>">

                                <div class="form-group">
                                    <label class="col-sm-3" for="accountBankName">Nama Bank</label>
                                    <div class="col-sm-9" style="position: relative">
                                        <input id="accountBankName" name="accountBankName" required="required">
                                        <div style="position: relative;" id="autocomplete-wrapper"></div>
                                        <small><i>Silahkan masukan nama bank anda (autocomplate)</i></small>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3" for="accountNo">Nomor Rekening Asal</label>

                                    <div class="col-sm-9">
                                        <input id="accountNo" type="number" name="accountNo"
                                               placeholder="Nomor Rekening Asal" required="required">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3" for="accountName">Nama Pemilik</label>

                                    <div class="col-sm-9">
                                        <input id="accountName" type="text" name="accountName"
                                               placeholder="Nama Pemilik Rekening Pengirim" required="required">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3" for="escrowBankAccountId">Bank Tujuan</label>

                                    <div class="col-sm-9">
                                        <select name="escrowBankAccountId" id="escrowBankAccountId" class="form-control"
                                                placeholder="Bank Yg ditransfer">
                                            <?php foreach ($escrow_list->result as $row_escrow) { ?>
                                                <option value="<?php echo $row_escrow->id; ?>"><?php echo $row_escrow->bankName; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3" for="amountcust">Nominal</label>
                                    <div class="col-sm-9">
                                        
                                            <input id="amountcust" type="hidden" lang="en-150" readonly name="amount" value="<?php echo ($escrow->totalPayment)?$escrow->totalPayment:0?>">

                                            <input id="amountcustshow" type="text" name="amountshow" value="<?php echo number_format(($escrow->totalPayment)?$escrow->totalPayment:0)?>" readonly>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3" for="upload">Bukti Pembyaran</label>

                                    <div class="col-sm-9">
                                        <input id="attachmentFile" type="file" name="file" required="required">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="pull-left">
                                        <p id="unggah-error-message" style="color: red;"></p>
                                    </div>
                                    <div class="pull-right">
                                        <button class="btn btn-login pull-right"
                                                data-loading-text="<i class='fa fa-spinner fa-pulse fa-fw'></i> Unggah Bukti"
                                                type="submit">
                                            Unggah Bukti
                                        </button>
                                    </div>
                                </div>

                                <div id="msgResponse"></div>
                                
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="checkout-loading">
    <div class="loading-content">
        <img width="125px" src="<?php echo base_url('assets/img/logo_2.png') ?>" alt="Logo Pasarselon">
        <br><br>
        <i class="fa fa-cog fa-spin fa-3x fa-fw"></i>
        <br><br>
        <span class="">Proses upload, harap menunggu sebentar . . .</span>
    </div>
</div>

<script type="text/javascript">
    $(function () {


        $('#form-unggah').on('submit', function (e) {
            e.preventDefault();

            $('#checkout-loading').fadeIn();

            var form = $(this);
//            var formdata = $('#form-unggah')[0];
            var data = new FormData($('#form-unggah')[0]);
            $(this).find('.btn-login').button('loading');

            var settings = {
                "async": true,
                "crossDomain": true,
                "url" : "<?php echo API_DATA ?>ppob/orders/payment/escrow/proof?access_token=<?php echo $this->session->userdata('token') ?>",
                "method": "POST",
                "processData": false,
                "contentType": false,
                "data": data
            };

            $.ajax(settings).done(function (response) {
                /*konfirmasi*/
                var object = JSON.stringify(response.result.fileName);
                console.log(object);
                confirmEscrowPayment(object);
                form.find('.btn-login').button('reset');
                $('#checkout-loading').fadeOut();
                //window.location.href = "<?php echo base_url('PPOB') ?>";
            }).error(function(response) {
                $('#checkout-loading').fadeOut();
                document.getElementById('unggah-error-message').innerHTML = response.responseJSON.error.message;
                console.log(response.responseJSON.error.message);
                form.find('.btn-login').button('reset');
            });
        });

        function confirmEscrowPayment(filename){

            var settings = {
              "async": true,
              "crossDomain": true,
              "url": "<?php echo API_DATA ?>ppob/orders/payment/escrow/confirm?access_token=<?php echo $this->session->userdata('token') ?>",
              "method": "POST",
              "data": {
                "customerId": $('#customerId').val(),
                "orderId": $('#orderID').val(),
                "escrowBankAccountId": $('#escrowBankAccountId').val(),
                "accountBankName":  $('#accountBankName').val(),
                "accountNo": $('#accountNoBank').val(),
                "accountOwnerName": $('#accountName').val(),
                "amount": $('#amountcust').val(),
                "attachedFile": filename
              }
            }

            $.ajax(settings).done(function (response) {
              console.log(response);
              $('#msgResponse').text(response.result);
            });


        }

    })

</script>