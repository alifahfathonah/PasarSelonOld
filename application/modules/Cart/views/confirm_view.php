<script type="text/javascript">
    $(function () {
        $('.order-product-shipment').accordion({
            collapsible: true,
            active: 'none',
            autoHeight: true
        });
    })
</script>


<!-- Modal End -->
<!-- cart-main-area start -->
<div role="dialog" style="min-height: 100%">
    <div>
        <h4 class="modal-title" style="padding-left: 25px; padding-top: 20px;">
            <label for="merchant">Merchant : </label> <b> <?php echo $merchant['name'] ?> </b> &nbsp; &nbsp; <br/>  Nomor Order : <b> <?php echo $order['id'] ?> </b> |  Invoice :  <b> <?php echo $order['invoiceId'] ?> </b>
        </h4>
        <form id="kirim-pesan"  enctype="multipart/form-data">
            <!-- Modal content-->
            <?php foreach ($product as $row) {
                $images = json_decode($row->images); ?>
                <div class="form-group">
                    <table>
                        <tr>
                            <td>  <img src="<?php echo isset($images[0]->thumbnail) ? IMG_PRODUCT . $images[0]->thumbnail : base_url('assets/img/product/2.jpg'); ?>" alt="" width="150px">
                            </td>
                            <td style="padding-left: 20px;">
                                <p>
                                    <a> <?php echo $row->name; ?></a>
                                    <br/> <?php echo $row->name_category; ?>
                                    <br>Harga : Rp <?php echo number_format($row->price); ?>
                                    Jumlah Kuantiti : <?php echo $row->quantity ?> Diskon : <?php echo number_format($row->subtotalDiscount); ?>
                                    <br><i>Notes :</i>
                                    <br><?php echo $row->notes; ?></p> </td>
                        </tr>
                    </table>

                </div>
                <div class="modal-footer">
                    <a href="<?php echo base_url() ?>Cart/opencomplaint?<?php echo "order={$orderId}&merchant={$merchantId}&product={$row->id}" ?>" class="btn btn-danger" style="padding-right: 20px;">Komplain</a>
                    <a href="<?php echo base_url() ?>Cart/barangDiterima?<?php echo "order={$orderId}&merchant={$merchantId}" ?>" class="btn btn-success" target="_parent"> Konfirmasi Barang Sudah Diterima Dengan Baik </a>
                </div>
            <?php } ?>
        </form>
    </div>
</div>

<script>
    function showComplaint(orderId, productId, merchantId) {
        $('html, body').animate({scrollTop: 0}, 'slow');
        $.yosemodal({
            title: "Complaint ",
            iframe: "<?php echo base_url() ?>cart/opencomplaint/" + orderId + "/" + productId + "/" + merchantId,
            loadingmessage: "Sedang Proses...",
            loadingicon: "fa-refresh",
            top: "5%",
            width: "75%",
            height: "75%",
            closeonclick: true,
            imgrounded: true,
            bgopacity: 0.7,
            showbuttons: false,
            theme: "black"
        });
    }
</script>

