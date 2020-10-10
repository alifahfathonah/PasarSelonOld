<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Pasar Selon - Kisel indonesia</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- all css here -->
    <!-- bootstrap.min.css -->
    <link rel="stylesheet" href="<?php echo base_url() ?>assets/css/bootstrap.min.css">
    <!-- font-awesome.min.css -->
    <link rel="stylesheet" href="<?php echo base_url() ?>assets/css/font-awesome.min.css">
    <!-- owl.carousel.css -->
    <link rel="stylesheet" href="<?php echo base_url() ?>assets/css/owl.carousel.css">
    <!-- owl.carousel.css -->
    <link rel="stylesheet" href="<?php echo base_url() ?>assets/css/meanmenu.min.css">
    <!-- shortcode/shortcodes.css -->
    <link rel="stylesheet" href="<?php echo base_url() ?>assets/css/shortcode/shortcodes.css">
    <!-- magnific-popup.css -->
    <link rel="stylesheet" href="<?php echo base_url() ?>assets/css/magnific-popup.css">
    <!-- nivo-slider.css -->
    <link rel="stylesheet" href="<?php echo base_url() ?>assets/css/nivo-slider.css">
    <link rel="stylesheet" href="<?php echo base_url() ?>assets/css/bootstrap-datepicker.min.css">
    <!-- style.css -->
    <link rel="stylesheet" href="<?php echo base_url() ?>assets/style.css">
    <!-- jquery-ui.min.css -->
    <link rel="stylesheet" href="<?php echo base_url() ?>assets/css/jquery-ui.min.css">
<!--    <script type="text/javascript" language="javascript" src="--><?php //echo base_url()  ?><!--assets/datatable/media/js/jquery.js"></script>-->
    <!-- responsive.css -->
    <link rel="stylesheet" href="<?php echo base_url() ?>assets/css/responsive.css">
    <link rel="stylesheet" href="<?php echo base_url() ?>assets/js/vendor/Zoomple-master/styles/zoomple.css" type="text/css">
    <!--    <link rel="stylesheet" href="--><?php //echo base_url()  ?><!--assets/css/bootstrap-switch.min.css" style="text/css">-->
    <link rel="stylesheet" href="<?php echo base_url() ?>assets/css/custom-style.css" type="text/css">
    <script src="<?php echo base_url() ?>assets/js/vendor/modernizr-2.8.3.min.js"></script>
    <!-- jquery-2.1.4 -->
    <script src="<?php echo base_url() ?>assets/js/jquery-2.1.4.min.js"></script>
    <!-- jquery-ui.min.js -->
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

</head>
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


<!-- Modal End -->
<!-- cart-main-area start -->
<div role="dialog" style="min-height: 100%">
    <div>
        <form id="kirim-pesan" method="post" enctype="multipart/form-data" action="<?php echo base_url() ?>Cart/prosescomplaint?<?php echo "order={$orderId}&product={$productId}&merchant={$merchantId}" ?>" style="padding: 30px;">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Komplain</h4>
                    <?php if($error!=null) { ?>
                    <div style="background-color: #fef1ec; padding: 10px 10px 10px 10px; text-align: center;">
                        <b>   <?php echo $error ?> </b>
                    </div>
                    <?php } ?>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="product">Produk :  </label> <b> <?php echo $product['name'] ?> </b> (<?php echo $product['condition'] ?>)  <br/>
                        <label for="merchant">Merchant : </label> <b> <?php echo $merchant['name'] ?> </b> &nbsp; &nbsp; <br/>  <label for="order">Nomor Order & Invoice : </label> <b> <?php echo $order['id'].' - '.$order['invoiceId'] ?> </b>
                    </div>
                    <div class="form-group">
                        <label for="subjek">Subjek</label>
                        <input id="subjek" name="subject" placeholder="Judul komplain anda">
                    </div>
                    <div class="form-group">
                        <label for="pesan">Pesan</label>
                        <textarea id="pesan" name="message"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="pesan">Gambar Produk Yang Di Komplain  <sup>*( Jika Diperlukan</sup></label>
                        <input type="file" name="pesan" placeholder="Judul komplain anda">
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="kirim-pesan-btn" type="submit" class="btn btn-login" data-loading-text="<i class='fa fa-spinner fa-pulse fa-fw'></i> Kirim"><i class="fa fa-paper-plane"></i> Kirim</button>
                </div>
            </div>
        </form>
    </div>
</div>



<script type="text/javascript">
    function barangDiterima($idOrder, elem) {
        $(elem).button('loading');
//        $(elem).addClass('btn-process');
//        $.ajax({
//            type: 'GET',
//            url: '<?php //echo base_url('Cart/barangDiterima');     ?>///'+$idOrder,
//            dataType: "json",
//            success: function(data) {
//                if(data.status == 'success'){
//                    $('#'+$idOrder).fadeOut();
//                    window.location.href = "<?php //echo base_url('Pesan/ulasan')     ?>//#"+$idOrder;
////                    window.location.assign("<?php ////echo base_url('Pesan/ulasan')     ?>////#"+$idOrder);
////                    window.location.replace("<?php ////echo base_url('Pesan/ulasan')     ?>////#"+$idOrder);
//                }else{
//                    alert("Gagal mengirim diskusi");
//                    $(elem).button('reset');
//                }
//            }
//        });
    }
</script>
<script src="<?php echo base_url() ?>assets/yosemodal/js/gsap.js"></script>
<!-- Yose Modal JS -->
<script src="<?php echo base_url() ?>assets/yosemodal/js/yosemodal.js"></script>
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
            theme: "black",
        });
    }

</script>

