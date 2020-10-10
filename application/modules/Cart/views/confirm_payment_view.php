<?php //echo '<pre>'; print_r($createdAt); ?>
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
                            <h3 class="panel-title">Pembayaran via Transfer</h3>
                        </div>
                        <div class="panel-body">
                            <div>
                                <p align="center">
                                    Lakukan pembayaran sebelum tanggal
                                        <b><?php echo $this->tanggal->formatDateTime($this->timezone->convertUtcAdd2Day($createdAt->createdAt));?> </b>
                                </p>
<!--                                <pre>--><?php //print_r($result); ?><!--</pre>-->
                                <div class="alert alert-warning" role="alert">
                                    <p align="center">Jumlah tagihan</p>
                                    <h4 class="jumlah-tagih" align="center">
                                        <?php $totalLength= strlen($result->totalPayment); echo strlen($result->totalPayment) > 3 ? 'Rp ' . number_format(substr($result->totalPayment, 0, $totalLength-3)).',' : 'Rp '; ?>
                                        <span class="kode-unik" style="background-color: #f2e18c;"><?php echo substr($result->totalPayment,-3); ?></span>

                                    </h4>
                                    <span style="margin: 20px auto 0 auto;display: table;" class="tagihan-pengingat">Transfer tepat hingga 3 digit terakhir agar tidak menghambat proses verifikasi</span>
                                    <p align="center" style="margin-top: 20px;">Kode Unik <b><?php echo $result->uniqueCode; ?></b></p>
                                </div>
                                <p align="center">Informasi Rekening Tujuan</p>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <?php if($result->escrow->bankId == '344b68a2-07b4-42cf-bcee-8df0dbd34bb5') { ?>
                                        <img src="<?php echo base_url().'assets/img/icon/icon-bca.png' ?>" alt="logo bank bni.png" width="100%" style="margin-bottom:10px">
                                        <?php }elseif($result->escrow->bankId == '2abe5ab2-fa7c-48c8-a67d-59c2a4cee600') { ?>
                                            <img src="<?php echo base_url().'assets/img/icon/icon-bni.png' ?>" alt="logo bank bni.png" width="100%" style="margin-bottom:10px">
                                        <?php }elseif($result->escrow->bankId == '432667c1-b071-4acc-9324-2af072b6f0d0') { ?>
                                            <img src="<?php echo base_url().'assets/img/icon/icon-bri.png' ?>" alt="logo bank bni.png" width="100%" style="margin-bottom:10px">
                                        <?php }elseif($result->escrow->bankId == 'd0164369-2b7a-4593-965d-f77313ff69e8') { ?>
                                            <img src="<?php echo base_url().'assets/img/icon/icon-mandiri.png' ?>" alt="logo bank bni.png" width="100%" style="margin-bottom:10px">
                                        <?php }elseif($result->escrow->bankId == '6ad7dba5-e8c8-4344-921e-7c2a6f800d3c') { ?>
                                            <img src="<?php echo base_url().'assets/img/icon/icon-cimb.png' ?>" alt="logo bank bni.png" width="100%" style="margin-bottom:10px">
                                        <?php } ?>
                                        <br>
                                    </div>

                                    <div class="col-sm-6">
                                        <p><?php echo $result->escrow->bankName?>
                                            <br>Nomor rekening: <b><?php echo $result->escrow->accountNo?></b>
                                            <br>Cabang: <?php echo $result->escrow->location?>
                                            <br>Nama Pemilik Rekening: <?php echo $result->escrow->accountName?></p>
                                    </div>
                                </div>
                                <div style="text-align: center;">
                                    <a class="btn btn-login" href="<?php echo base_url('Cart/unggah_bukti?invoiceId='.$invoiceId); ?>">Unggah Bukti</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>