<link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/yosemodal/css/yosemodal.css">
<!--<link rel="stylesheet" type="text/css" href="--><?php //echo base_url()    ?><!--assets/datatable/media/css/jquery.dataTables.css">-->
<!--<style type="text/css" class="init">-->
<!---->
<!--</style>-->
<!--<script type="text/javascript" language="javascript" src="--><?php //echo base_url()    ?><!--assets/datatable/media/js/jquery.dataTables.js"></script>-->
<!---->
<!---->
<!--<script type="text/javascript" language="javascript" class="init">-->
<!---->
<!--$(document).ready(function() {-->
<!--  table2 = $('#history-order').DataTable({ -->
<!--    "processing": true, //Feature control the processing indicator.-->
<!--    "serverSide": false, //Feature control DataTables' server-side processing mode.-->
<!--    "paging":true,-->
<!--    "searching":true,-->
<!--    "bLengthChange": false,-->
<!--    "bInfo": false,-->
<!--    // Load data for the table's content from an Ajax source-->
<!--    /*"ajax": {-->
<!--        "url": "Cart/getHistoryOrder",-->
<!--        "type": "POST"-->
<!--    },*/-->
<!---->
<!--    //Set column definition initialisation properties.-->
<!--    "columnDefs": [-->
<!--      { -->
<!--        "targets": [ -1 ], //last column-->
<!--        "orderable": false, //set not orderable-->
<!--      },-->
<!--    ],-->
<!---->
<!--  });-->
<!---->
<!--/*$('#example').DataTable();*/-->
<!---->
<!--} );-->
<!---->
<!---->
<!--</script>-->
<!-- breadcrumb start -->
<script type="text/javascript">
    $(function () {
        $('.order-product-shipment').accordion({
            collapsible: true,
            active: 'none',
            autoHeight: true
        });
    })
</script>

<!-- Modal -->
<div id="kiselKomplain" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <form  method="post">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Komplain</h4>
                    <div>


                    </div>
                </div>
                <div class="modal-body">

                    <div class="form-group">
                        <label for="subjek">Subjek</label>
                        <input id="subjek" name="subject" placeholder="Judul komplain anda">
                    </div>
                    <div class="form-group">
                        <label for="pesan">Pesan</label>
                        <textarea id="pesan" name="message"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="kirim-pesan-btn" type="submit" class="btn btn-login" data-loading-text="<i class='fa fa-spinner fa-pulse fa-fw'></i> Kirim"><i class="fa fa-paper-plane"></i> Kirim</button>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- Modal End -->

<div class="breadcrumb-area">
    <div class="container">
        <?php echo $breadcrumbs ?>
    </div>
</div>
<!-- breadcrumb end -->
<!-- cart-main-area start -->
<div class="shop-area">
    <div class="container">
        <?php echo Modules::run('Section/sidebar') ?>
        <div class="col-sm-9">
            <div class="content-wrap mb-50">
                <h2 class="page-heading mt-40">
                    <span class="cat-name">Dalam Proses Pengiriman</span>
                </h2>
                <div class="tab-content tab-content-rwy">
                    <div class="tab-pane active fade in" id="pengiriman">
                        <table id="table">
                            <thead style="visibility: hidden;">
                                <th>List Pengiriman</th>
                            </thead>
                            <tbody>
                            <?php
                            foreach ($allDeliveredOrder as $order) {
                                ?>
                                <tr>
                                    <td>

                                        <div class="well" id="<?php echo $order->id; ?>">
                                            <div class="row">
                                                <div class="col-sm-3">
                                                    <div class="address-list" style="text-align: center">
                                                        <img src="<?php
                                                        if (@getimagesize(AVATAR_FILE . $order->logoFile)) {
                                                            echo AVATAR_FILE . $order->logoFile;
                                                        } else {
                                                            echo base_url('assets/img/product/default-image.png');
                                                        }
                                                        ?>" alt="" width="100%"><br>
                                                        <strong><?php echo $order->merchant->name; ?></strong>
                                                    </div>
                                                </div>
                                                <div class="col-sm-9">

                                                    <?php
                                                    $address = json_decode($order->customerAddress);
                                                    $courier = json_decode($order->courierCost);
                                                    $priceAfterMarginTotal = ceil($order->totalPrice + $order->totalMargin);

                                                    $lastdigits = $priceAfterMarginTotal % 10;
                                                    if($lastdigits > 0 && $lastdigits < 9) {
                                                        $priceAfterMarginTotal -= $lastdigits;
                                                    }
                                                    $discountTotal = ceil($priceAfterMarginTotal - $order->totalDiscount);
                                                    $priceNettTotal = $priceAfterMarginTotal - $discountTotal;
                                                    ?>
                                                    <p style="font-size:11.5px"><strong><a class="invoice-number" href="">Order ID : <?php echo $order->orderDetail[0]->orderId; ?></a></strong>
                                                        <br>Tanggal Transaksi : <?php echo $this->tanggal->formatDateTime($this->timezone->convertUtc($order->createdAt)); ?>
                                                        <br>Total harga : Rp <?php echo number_format($order->totalPrice + $order->totalMargin - $order->totalDiscount); ?>
                                                        <br>No Resi : <?php echo $order->shippingAirwaybill; ?>
                                                        <br>Alamat pengiriman : <?php echo $address->address ?>
                                                        <br>Metode Pengiriman : <?php echo $courier->courierName.' - '.$courier->courierPackageName     ?>
                                                        <br>Nama penerima : <?php echo $address->recipientName; ?>
                                                        <br>Nomor Telepon Penerima : <?php echo $address->recipientPhone; ?>
                                                        <?php
                                                        switch ($order->status) {
                                                            case 3:
                                                                echo "<br><span style='padding:5px;background:#5cb85c;color:white;'>Pemesanan Telah Dibayar, Menunggu Konfirmasi Merchant</span>";
                                                                break;
                                                            case 4:
                                                            case 5:
                                                                echo "<br><br><span style='padding:5px;background:#5cb85c;color:white;'>Barang anda sedang di proses Merchant</span>";
                                                                break;
                                                            case 6:
                                                                echo "<br><span style='padding:5px;background:#5cb85c;color:white;'> Barang Terkirim </span>";
                                                                break;
                                                            case 7:
                                                                echo "<br><span style='padding:5px;background:#5c5cb8;color:white;'> Sukses Diterima </span>";
                                                                break;
                                                            case 8:
                                                                echo "<br><span style='padding:5px;background:#b85c5c;color:white;'> Pemesanan Batal </span>";
                                                                break;
                                                            case 9:
                                                                echo "<br><span style='padding:5px;background:#b85c5c;color:white;'> <i>RETURN</i>  </span>";
                                                                break;
                                                            case 10:
                                                                echo "<br><span style='padding:5px;background:#b85c5c;color:white;'> <i>PENDING</i>  </span>";
                                                                break;
                                                            case 11:
                                                                echo "<br><span style='padding:5px;background:#b85c5c;color:white;'> Proses Komplain  </span>";
                                                                break;
                                                            case 12:
                                                                echo "<br><span style='padding:5px;background:#5c5cb8;color:white;'> Komplain Diselesaikan  </span>";
                                                                break;
                                                        }
                                                        ?>
                                                    </p>

                                                    <div id="shipment-details" class="order-product-shipment">
                                                        <h5 style="text-align: center;">Lihat barang yang dikirim <i class="fa fa-caret-down"></i></h5>
                                                        <div class="collapse">
                                                            <table class="table">
                                                                <?php foreach ($order->orderDetail as $detail) { ?>
                                                                    <tr>
                                                                        <td width="150"><img src="<?php echo isset($detail->product->images[0]->thumbnail) ? IMG_PRODUCT . $detail->product->images[0]->thumbnail : base_url('assets/img/product/2.jpg'); ?>" alt="" width="150px"></td>
                                                                        <td>
                                                                            <?php if (!empty($detail->product)) { ?>
                                                                                <?php
                                                                                $priceAfterMargin = ceil($detail->product->price + ($detail->product->price * $detail->product->priceMargin / 100));

                                                                                $lastdigits = $priceAfterMargin % 10;
                                                                                if($lastdigits > 0 && $lastdigits < 9) {
                                                                                    $priceAfterMargin -= $lastdigits;
                                                                                }
                                                                                $discount = ceil($priceAfterMargin * $detail->product->discount / 100);
                                                                                $priceNett = $priceAfterMargin - $discount;
                                                                                ?>
                                                                                <p><a href="#"><?php echo $detail->product->name; ?></a>
                                                                                    <br>Rp <?php echo number_format($priceNett); ?>
                                                                                    <br>Qty : <?php echo $detail->quantity ?>
                                                                                    <br>Disc: <?php echo number_format($detail->subtotalDiscount) ?>
                                                                                    <br><i>Notes :</i>
                                                                                    <br><?php echo $detail->notes; ?></p>
                                                                                <?php
                                                                            } else {
                                                                                echo '<p>Belum ada deskripsi</p>';
                                                                            }
                                                                            ?>
                                                                            <!--                                                                <a href="#" class="btn btn-custom btn-tidak-sesuai" onclick="showComplaint(<?php echo "'{$detail->orderId}','{$detail->productId}','{$detail->product->merchantId}'" ?>)">Komplain</a>-->
                                                                        </td>
                                                                    </tr>
                                                                <?php } ?>
                                                            </table>
                                                        </div>
                                                    </div>
                                                        <?php if ($order->status == 5 ) { ?>
                                                    <div class="pull-right">
                                                        <a onclick="showConfirm(<?php echo "'{$detail->orderId}','{$detail->product->merchantId}'" ?>)"  class="btn btn-confirm-pengiriman btn-custom" data-loading-text="<i class='fa fa-spinner fa-pulse fa-fw'></i> Konfirmasi Barang Sudah Diterima">:: Konfirmasi Penerimaan Barang ::  </a>
                                                    </div>
                                                <?php }elseif($order->status == 6) { ?>
                                                            <div class="pull-right">
                                                                <a onclick="showConfirmSuccess(<?php echo "'{$detail->orderId}','{$detail->product->merchantId}'" ?>)"  class="btn btn-confirm-pengiriman btn-custom" data-loading-text="<i class='fa fa-spinner fa-pulse fa-fw'></i> Konfirmasi Barang Sudah Diterima">:: Konfirmasi Barang Sesuai ::  </a>
                                                            </div>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                        <div class="content-sortpagibar">
                            <ul class="shop-pagi display-inline pull-right">
                                <?php echo $links; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Detail Order Modal -->
<div id="modal-complaint" class="white-popup mfp-hide panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">Komplain</h3>
    </div>
    <div class="panel-body">

    </div>
</div>
<!-- End Detail Order Modal -->

<script type="text/javascript">
    $(document).ready(function() {
        table = $('#table').DataTable({
            "bPaginate": false,
            "bLengthChange": false,
            "bFilter": true,
            "bInfo": false,
//            "pageLength": 6
        });
    });
</script>

<!--<div id="ym2b" class="overlay"></div>-->
<div class="modal fade modal-lg" id="modal_konfirm" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Konfirmasi Penerimaan Barang</h3>
            </div>
            <div class="modal-body form">
                <div id="konfirmasi-frame"></div>
            </div>
        </div>
    </div>
</div>