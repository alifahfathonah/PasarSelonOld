<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Pasar Selon - Kisel indonesia</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- all css here -->
    <!-- bootstrap.min.css -->
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/bootstrap.min.css">
    <!-- font-awesome.min.css -->
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/font-awesome.min.css">
    <!-- owl.carousel.css -->
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/owl.carousel.css">
    <!-- owl.carousel.css -->
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/meanmenu.min.css">
    <!-- shortcode/shortcodes.css -->
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/shortcode/shortcodes.css">
    <!-- magnific-popup.css -->
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/magnific-popup.css">
    <!-- nivo-slider.css -->
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/nivo-slider.css">
    <!-- style.css -->
    <link rel="stylesheet" href="<?php echo base_url()?>assets/style.css">
    <!-- jquery-ui.min.css -->
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/jquery-ui.min.css">
    <!--    <script type="text/javascript" language="javascript" src="--><?php //echo base_url()?><!--assets/datatable/media/js/jquery.js"></script>-->
    <!-- responsive.css -->
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/responsive.css">
    <link rel="stylesheet" href="<?php echo base_url()?>assets/js/vendor/Zoomple-master/styles/zoomple.css" type="text/css">
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/bootstrap-switch.min.css" type="text/css">
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/custom-style.css" type="text/css">
    <script src="<?php echo base_url()?>assets/js/vendor/modernizr-2.8.3.min.js"></script>
</head>
<body style="padding-top: 0;">
<div class="invoice" style="width: 790px; padding: 20px;">
    <div class="row row-cs">
        <div class="col-xs-6">
            <img src="<?php echo base_url('assets/img/logo_2.png'); ?>">
        </div>
        <div class="col-xs-6" align="right">
            <button id="print" class="btn" onclick="window.print()"><i class="fa fa-print"></i> Print</button><br>
            <h4 style="margin-top: 15px;" class="invoice-number">No <?php echo $customerOrder->id?></h4>
        </div>
    </div>
    <div class="row row-cs" style="border-top:1px dashed lightgray;border-bottom: 1px dashed lightgray; padding-top: 15px;">
        <div class="col-xs-4">
            <h4 class="invoice-sec-title">Pembeli</h4>
            <?php
                $subTotal = 0;
                $response = json_decode($invoiceLog->response);
                $customer = $customerOrder->customer;
                $payment = $this->db->get_where('PaymentMethod', ['id' => $customerOrder->method])->row();
//                echo '<pre>';print_r($customerOrder);die;
            ?>
            <p class="" style="font-size:12px"><?php echo ucwords($customer->firstName.' '.$customer->lastName);?></p>
        </div>
        <div class="col-xs-3">
            <h4 class="invoice-sec-title">Pembayaran</h4>
            <?php if( ! is_null($customerOrder) ) : ?>
            <p style="font-size:12px">
                <?php 
                    echo isset($payment->name)?$payment->name.'<br>':'';
                    if($customerOrder->method == 1) {
                        if(isset($response->escrowBankAccount)){
                            echo $response->escrowBankAccount->bankName.'&nbsp;'.$response->escrowBankAccount->accountNo.'<br>';
                            echo $response->escrowBankAccount->accountName;
                        }
                    }
                ?>
            </p>
        <?php else : echo 'Belum ada transaksi pembayaran'; endif; ?>
        </div>
        <div class="col-xs-5">
            <?php
                if($customerOrder->status == 2) {
                    echo '<img class="stamp" src="'.base_url('assets/img/invoice-paid.png').'" alt="paid stamp">';
                }elseif($customerOrder->status == 3) {
                    echo '<img class="stamp" src="'.base_url('assets/img/invoice-payment-verified.png').'" alt="payment verified stamp">';
                }
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">

            <table class="table table-striped" width="100%" style="font-size:12px">

            <?php 
                foreach($customerOrder->orders as $rowOrder) :
                        $merchant = $rowOrder->merchant;
                        $courier = $rowOrder->courierCost;
                        $address = $rowOrder->customerAddress;
            ?>

                <thead>
                <tr>
                    <th colspan="3"> Merchant : <?php echo strtoupper($merchant->name)?></th>
                    <th colspan="3"> Metode Pengiriman : <?php echo ($courier->courierName) ? strtoupper($courier->courierName) : '-'; ?>  (<?php echo ($courier->courierPackageName) ? strtoupper($courier->courierPackageName) : '-'; ?>)<br>
                        <?php if($merchant->type == 2) {echo '<i class="fa fa-map-marker"></i> KiMart Telkomsel Smart Office, Jl. Jend. Gatot Subroto Kav. 52, RT. 6 / RW. 1, Kuningan Barat , Mampang Prapatan , Jakarta Selatan , Dki Jakarta';}else{echo $address->address.'<br>'.$address->cityName.'<br>'.$address->zipCode;} ?>
                    </th>
                </tr>
                <tr>
                    <th>Nama Produk</th>
                    <th>Jumlah Barang</th>
                    <th>Berat</th>
                    <th>Harga Barang</th>
                    <th>Diskon</th>
                    <th>Subtotal</th>
                </tr>
                </thead>
                <tbody>
                <?php 
                    foreach($rowOrder->items as $rowOd) :
                        $product = $rowOd->product;
                        $priceNett = $product->netTotal;
                        $subTotal += $priceNett;
                        $subTotalWeight[] = $rowOd->subtotalWeight;
                ?>
                    <tr>
                        <td>
                            <?php echo $product->name?><br>
                            <span style="font-size: 12px;margin-top: 5px;font-weight: bold;">Catatan: <i><?php echo $rowOd->notes?></i></span>
                        </td>
                        <td><?php echo $rowOd->quantity?></td>
                        <td><?php echo $rowOd->subtotalWeight?> Gram</td>
                        <td><?php echo 'Rp '.number_format($product->price + $product->subtotalMargin)?></td>
                        <td><?php echo $product->subtotalDiscount>0?'Rp '.number_format($product->subtotalDiscount):'Rp 0'; echo ' ('.$product->discount.'%)'; ?></td>
                        <td><?php echo 'Rp '.number_format($priceNett)?></td>
                    </tr>
                <?php endforeach;?>

            <?php endforeach;?>
                

                <tr style="background: #f1f1f1" bgcolor="#f1f1f1">
                    <td colspan="5">
                        <b>Subtotal</b>
                    </td>
                    <td align="right"><?php echo 'Rp '.number_format($subTotal)?></td>
                </tr>
                <tr>
                    <td colspan="2">Biaya Pengiriman</td>
                    <td colspan="3"><?php echo $customerOrder->totalWeight?> Gram</td>
                    <td align="right"><?php echo 'Rp '.number_format($customerOrder->totalShippingCost)?></td>
                </tr>
                <!-- <tr>
                    <td colspan="4">Total Diskon</td>
                    <td><?php echo 'Rp '.number_format($customerOrder->totalDiscount)?></td>
                </tr> -->
                <!-- <tr>
                    <td colspan="4">Total Voucher</td>
                    <td><?php echo 'Rp '.number_format($customerOrder->totalVoucher)?></td>
                </tr> -->
                <tr>
                    <td colspan="5">Pajak</td>
                    <td align="right"><?php echo 'Rp '.number_format($customerOrder->totalTaxes)?></td>
                </tr>
                <tr>
                    <td colspan="5">Kode Voucher</td>
                    <td align="right"><?php echo ($customerOrder->voucherCode)? $customerOrder->voucherCode: '-'?></td>
                </tr>
                <tr>
                    <td colspan="5">Potongan Voucher</td>
                    <td align="right"><?php echo 'Rp '.$customerOrder->totalVoucher?></td>
                </tr>
                <tr>
                    <td colspan="5">Total Diskon</td>
                    <td align="right"><?php echo $customerOrder->totalDiscount>0? 'Rp '.number_format($customerOrder->totalDiscount).',-':'Rp 0' ?></td>
                </tr>
                <tr>
                    <td colspan="5">Kode Unik Pembayaran</td>
                    <td align="right"><?php $rand = isset($response->uniqueCode)?$response->uniqueCode:0; echo 'Rp '. $rand?></td>
                </tr>
                <tr style="background: #f1f1f1" bgcolor="#f1f1f1">
                    <td colspan="5"><b>Total Pembayaran</b></td>
                    <td align="right">
                        <?php
                            $total = $rand + $customerOrder->netTotal;
                            echo '<b>Rp '.number_format($total).'</b>';
                        ?>
                    
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
<!--    --><?php //echo '<pre>'; print_r($customerOrder); ?>
</div>
</body>
</html>