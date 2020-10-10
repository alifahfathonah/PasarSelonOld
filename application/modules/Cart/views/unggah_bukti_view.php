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
                                <div class="form-group">
                                    <label class="col-sm-3" for="invoiceId">Invoice</label>

                                    <div class="col-sm-9">
                                        <input disabled id="invoiceId" type="text" name="invoiceId" value="<?php echo $invoiceId ?>">
                                    </div>
                                </div>
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
                                            <?php foreach ($escrow_list->result as $escrow) { ?>
                                                <option value="<?php echo $escrow->id; ?>"><?php echo $escrow->bankName; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3" for="amountcust">Nominal</label>
                                    <div class="col-sm-9">
                                        
                                            <input id="amountcust" type="hidden" lang="en-150" readonly name="amount" value="<?php echo ($customerOrder->result->totalPayment)?$customerOrder->result->totalPayment:$customerOrder->result->total?>">

                                            <input id="amountcustshow" type="text" name="amountshow" value="<?php echo number_format(($customerOrder->result->totalPayment)?$customerOrder->result->totalPayment:$customerOrder->result->total)?>" readonly>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3" for="upload">Bukti Pembyaran</label>

                                    <div class="col-sm-9">
                                        <input id="attachmentFile" type="file" name="attachmentFile" required="required">
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
            invoiceId = $('#invoiceId').val();
            $.getJSON("<?php echo site_url('Cart/process_unggah_bukti?invoiceId=') ?>" + invoiceId, '', function (response){
                return false;
            });

            var settings = {
                "async": true,
                "crossDomain": true,
                "url" : "<?php echo API_DATA ?>Customers/<?php echo $this->session->userdata('user')->id ?>/pay/bank-transfer/confirm?access_token=<?php echo $this->session->userdata('token') ?>&invoiceId=<?php echo $invoiceId; ?>",
                "method": "POST",
                "processData": false,
                "contentType": false,
                "data": data
            };



            $.ajax(settings).done(function (response) {
                form.find('.btn-login').button('reset');
                $('#checkout-loading').fadeOut();
                window.location.href = "<?php echo base_url('Cart/pembayaran') ?>";
            }).error(function(response) {
                $('#checkout-loading').fadeOut();
                document.getElementById('unggah-error-message').innerHTML = response.responseJSON.error.message;
                console.log(response.responseJSON.error.message);
                form.find('.btn-login').button('reset');
            });
        });
    })
</script>