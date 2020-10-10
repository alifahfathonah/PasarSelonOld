<!-- breadcrumb start -->
<div class="breadcrumb-area">
    <div class="container">
        <?php echo $breadcrumbs ?>
    </div>
</div>
<!-- breadcrumb end -->

<!-- cart-main-area start -->
<div class="checkout-area">
    <div class="container container-custom">
        <div class="row">
            <form id="checkout-process" method="post">
                <div class="row">
                    <div class="col-sm-12">

                        <!-- form hidden for customer -->
                        <input type="hidden" name="customerId" id="customerId" value="<?php echo $customerId ?>">
                        <input type="hidden" name="customerName" id="customerName"
                               value="<?php echo $this->session->userdata('user')->customerName ?>">

                        <div class="row row-cs" style="border-top:1px dashed lightgray;border-bottom: 1px dashed lightgray; padding-top: 15px;">
                            <div class="col-xs-4">
                                <h2 class="invoice-sec-title"><?php echo ucwords($invoiceId) ?></h2>
                            </div>
                            <div class="col-xs-3">
                                <h4 class="invoice-sec-title">Pembeli</h4>
                                <?php
                                    $response = json_decode($customerOrder->response);
                                    $customer = json_decode($customerOrder->customer);
                                    //echo '<pre>';print_r($customer);
                                ?>
                                <p class="" style="font-size:12px"><?php echo ucwords($customer->lastName.' '.$customer->firstName);?></p>
                            </div>
                            <div class="col-xs-3">
                                <h4 class="invoice-sec-title">Pembayaran</h4>
                                <?php if( ! is_null($response) ) : ?>
                                <p style="font-size:12px">
                                    <?php 
                                        echo isset($customerOrder->PaymentMethodName)?$customerOrder->PaymentMethodName.'<br>':'';
                                        if($customerOrder->method != 3) {
                                            if(isset($response->escrowBankAccount)){
                                                echo $response->escrowBankAccount->bankName.'&nbsp;'.$response->escrowBankAccount->accountNo.'<br>';
                                                echo $response->escrowBankAccount->accountName;
                                            }
                                        }
                                    ?>
                                </p>
                            <?php else : echo 'Belum ada transaksi pembayaran'; endif; ?>
                            </div>
                            
                        </div>

                        <div class="panel-body">
                            <div class="row">
                                <div class="panel-body">
                                    <div class="table-content table-responsive">
                                    
                                        <table class="table table-striped" width="100%" style="font-size:12px">

                                            <?php 
                                                foreach($customerOrder->order as $rowOrder) :
                                                        $merchant = json_decode($rowOrder->merchant);
                                            ?>

                                                <tr>
                                                    <th colspan="8" align="left"> Merchant : <?php echo strtoupper($merchant->name)?></th>
                                                </tr>
                                                <tr>
                                                    <th></th>
                                                    <th>Nama Produk</th>
                                                    <th colspan="2" style="width:200px">Status Barang</th>
                                                    <th>Jumlah Barang</th>
                                                    <th>Berat</th>
                                                    <th>Harga Barang</th>
                                                    <th>Subtotal</th>
                                                </tr>
                                                <tbody>
                                                <?php 
                                                    foreach($rowOrder->orderDetail as $rowOd) :
                                                        $product = json_decode($rowOd->product);
                                                        $priceNett = $rowOd->subtotalPrice + $rowOd->subtotalMargin - $rowOd->subtotalDiscount;
                                                        $subTotal[] = $priceNett;
                                                        $subTotalWeight[] = $rowOd->subtotalWeight;
                                                ?>
                                                    <tr>
                                                        <td>
                                                            <?php echo ($rowOd->rejected != '')?'<h2 style="color:red"><i class="fa fa-times"></i></h2>':'<h2 style="color:green"><i class="fa fa-check"></i></h2>'?>
                                                        </td>
                                                        <td align="left">
                                                            <?php echo $product->name?><br>
                                                            <span style="font-size: 12px;margin-top: 5px;font-weight: bold;"><i><?php echo ($rowOd->notes!='')?'Catatan : '.$rowOd->notes.'':''?></i></span>
                                                        </td>
                                                        <td align="center">
                                                            <?php 
                                                                if($rowOd->rejected!=''){
                                                                    $decode_json = json_decode($rowOd->rejected);
                                                                    echo ''.$decode_json->notes;
                                                                }

                                                            ?>
                                                        </td>
                                                        <td align="center">
                                                            <?php 
                                                                if($rowOd->rejected!=''){
                                                                    echo '<select class="form-control">';
                                                                    echo '<option>-Silahkan Pilih-</option>';
                                                                    echo '<option>Menunggu Ketersediaan Stok</option>';
                                                                    echo '<option>Return Payment</option>';
                                                                    echo '</select>';
                                                                }

                                                            ?>
                                                        </td>
                                                        <td align="center"><?php echo $rowOd->quantity?></td>
                                                        <td align="left"><?php echo $rowOd->subtotalWeight?> Gram</td>
                                                        <td align="right"><?php echo 'Rp '.number_format($priceNett)?></td>
                                                        <td align="right"><?php echo 'Rp '.number_format($priceNett)?></td>
                                                    </tr>
                                                <?php endforeach;?>

                                            <?php endforeach;?>
                                                

                                                <tr style="background: #f1f1f1" bgcolor="#f1f1f1">
                                                    <td colspan="7" align="right">
                                                        <b>Subtotal</b>
                                                    </td>
                                                    <td align="right"><?php echo 'Rp '.number_format($customerOrder->totalNett)?></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="7" align="right">Biaya Pengiriman (<?php echo $customerOrder->totalWeight?> Gram) </td>
                                                    <td align="right"><?php echo 'Rp '.number_format($customerOrder->totalShippingCost)?></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="7" align="right">Pajak</td>
                                                    <td align="right"><?php echo 'Rp '.number_format($customerOrder->totalTaxes)?></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="7" align="right">Kode Unik Pembayaran</td>
                                                    <td align="right"><?php $rand = isset($response->uniqueCode)?$response->uniqueCode:0; echo 'Rp '. $rand?></td>
                                                </tr>
                                                <tr style="background: #f1f1f1" bgcolor="#f1f1f1">
                                                    <td colspan="7" align="right"><b>Total Pembayaran</b></td>
                                                    <td align="right">
                                                        <?php
                                                            $total = $customerOrder->totalNett + $rand + $customerOrder->totalShippingCost;
                                                            echo '<b>Rp '.number_format($total).'</b>';
                                                        ?>
                                                    
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                            <div class="col-xs-6">
                                                 <div class="buttons-cart pull-left">
                                                    <a href="<?php echo base_url().'Cart/pembayaran' ?>"><i
                                                            class="fa fa-chevron-left"></i> &nbsp;&nbsp; Back</a>
                                                </div>
                                            </div>
                                            <div class="col-xs-6">
                                                 <div class="wc-proceed-to-checkout pull-right">
                                                    <button type="submit" id="checkout-finish">Proceed to Finish &nbsp;&nbsp;<i
                                                            class="fa fa-chevron-right"></i></button>
                                                </div>
                                            </div>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
