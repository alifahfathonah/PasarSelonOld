<link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css')?>" rel="stylesheet">
<!-- Start plugin star rating -->
<link rel="stylesheet" href="<?php echo base_url('assets/css/jquery.rateyo.min.css') ?>" type="text/css">
<!-- End plugin star rating -->

<script type="text/javascript">
    $(function () {

        $('.ulasan-accordion').accordion({
            collapsible: true,
            active: 'none',
            autoHeight: true
        });

        $('.ulasan-btn').on('click', function(e) {
            e.preventDefault();
            var id = $(this).attr('data-id');
            var tr = $('#tr-'+id);
            var button = tr.find('.ulasan-btn');
            var ulasan = $('.ulasanya-'+id).val();
            var bintang = tr.find(".rateYo").rateYo("rating");

//            $('#td-'+ordering).find('.ulasanya').fadeOut();
            button.button('loading');

            if(ulasan.length > 0) {
                $.ajax({
                    type: 'POST',
                    url: '<?php echo base_url('Pesan/Ulasan/submit'); ?>',
                    data: {
                        productId: tr.find('.productId').val(),
                        orderId: tr.find('.orderId').val(),
                        merchantId: tr.find('.merchantId').val(),
                        ulasan: tr.find('.ulasanya-'+id).val(),
                        rating: tr.find(".rateYo").rateYo("rating")
                    },
                    dataType: "json",
                    success: function (data) {
                        if (data.status === 'success') {
//                        var jmlUlasanPerOrder = $('#jml-ulasan-'+ordering).text();
//                        document.getElementById('jml-ulasan-'+ordering).innerHTML = jmlUlasanPerOrder - 1;
                            button.hide();
//                        alert("Sukses mereview");
                            $('.rateYo-'+id).fadeOut();
                            $('.ulasanya-'+id).fadeOut();

                            var bintangKosong = 5 - bintang;
                            var bintangContainer = '<div class="pro-rating ulasan-rating">';
                            for(i=1;i<=bintang;i++) {
                                bintangContainer += '<a><i class="fa fa-star"></i></a>';
                            }
                            for(a=1;a<=bintangKosong;a++) {
                                bintangContainer += '<a><i class="fa fa-star-o"></i></a>';
                            }
                            bintangContainer += '</div>';
                            var ulasannys = '<p>'+ulasan+'</p>';
                            $('#modal_ulasan').modal('show');

                            tr.find('.ulasan-wrapper').append(ulasannys).fadeIn();
                            tr.find('.rating-wrapper').append(bintangContainer).fadeIn();


//                        window.location.href = "<?php //echo base_url('Pesan/ulasan') ?>//";
//                        return false;
                        } else {
                            alert("Gagal mengirim diskusi");
                        }

                        button.button('reset');
                    }
                });
            }else{
                alert('Ulasan tidak boleh kosong!');
                button.button('reset');
            }
        }) ;
    })
</script>
<div class="modal fade" id="modal_ulasan" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Sukses Mengulas</h3>
            </div>
            <div class="modal-body form">
                <div class="form-body">
                    <div class="form-group">
                        <div class="col-sm-12">
                            <p align="center">Review Anda telah kami terima.<br>Terima kasih telah berbelanja di Pasarselon.com, ditunggu transaksi berikutnya.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button id="btnSave" class="btn btn-primary" data-dismiss="modal" data-loading-text="<i class='fa fa-spinner fa-pulse fa-fw'></i> Tambahkan">Close</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- breadcrumb start -->
<div class="breadcrumb-area">
    <div class="container">
        <?php echo $breadcrumbs?>
    </div>
</div>
<!-- breadcrumb end -->
<!-- cart-main-area start -->
<div class="shop-area">
    <div class="container">
        <?php echo Modules::run('Section/sidebar')?>

        <div class="col-sm-9">
            <div class="content-wrap mb-70">
                
                <h2 class="page-heading">
                    <span class="cat-name">&nbsp;</span>
                </h2>
                <ul class="nav nav-tabs" id="myTabs" role="tablist">
                    <li class="active"><a href="<?php echo base_url('pesan') ?>"><i class="fa fa-comment"></i> ULASAN ANDA</a></li>
                </ul>
                <div class="tab-content tab-content-rwy">
                    <div class="tab-pane active fade in table-responsive" id="pesan">
                    <table id="table" class="table table-bordered" style="font-size:11.5px">
                    <thead style="background-color:#f4a137;color:white">
                        <th></th>
                        <th>ID</th>
                        <th>Image</th>
                        <th>Produk</th>
                        <th>Merchant</th>
                        <th>Action Rate</th>
                        <th>Ulasan</th>
                        <th>Submit</th>
                    </thead>
                    <tbody>
                        <?php
                            $html = '';
                            $id = 1;
//                            echo '<pre>';print_r($allOrder);
                            foreach ($allOrder->result as $order) :
//                                $merchant = json_decode($order->merchant);
//                                $product = json_decode($order->product);
//                                $checkUlasan = $this->checkout->checkUlasan($order->OrderId, $product->id);
//                                echo '<pre>';print_r($checkUlasan);die;
                                //$address = json_decode($order->customerAddress);
                                //$courier = json_decode($order->courierCost);
                                if (@getimagesize(IMG_PRODUCT . $order->productImages[0]->thumbnail)) {
                                    $img = IMG_PRODUCT . $order->productImages[0]->thumbnail;
                                } else {
                                    $img = base_url('assets/img/product/default-image.png');
                                }
                                $html .= '<tr id="tr-'.$id.'">';
                                $html .= '<td class="details-control"><i class="fa fa-spinner fa-spin"></i></td>';
                                $html .= '<td>'.$order->orderId.'</td>';
                                //$html .= '<td align="left"><ig src="'.$img.'"></td>';
                                //$html .= '<td>'.$merchant->name.'</td>';
                                //$html .= '<td>'.$product->price.'</td>';
                                $html .= '<td align="center"><img src="'.$img.'" width="80px"></td>';
                                $html .= '<td align="left">'.$order->productName.'<br>( '.$order->productId.' )</td>';
                                $html .= '<td align="center">'.$order->merchantName.'</td>';

                                if(count($order->review) > 0) {
                                    $ratinghtml = '<div class="pro-rating">';
                                    for ($i = 1; $i <= $order->review->rating; $i++) {
                                        $ratinghtml .= '<a href="#"><i class="fa fa-star"></i></a>';
                                    }
                                    for ($b = 1; $b <= (5 - $order->review->rating); $b++) {
                                        $ratinghtml .= '<a href="#"><i class="fa fa-star-o"></i></a>';
                                    }
                                    $ratinghtml .= '</div>';

                                    $html .= '<td align="center">'.$ratinghtml.'</td>';
                                    $html .= '<td><input type="hidden" class="merchantId" value="'.$order->merchantId.'"><input type="hidden" class="productId" value="'.$order->productId.'"><input type="hidden" class="orderId" value="'.$order->orderId.'"><p class="p-ulasan">'.$order->review->text.'</p><div class="ulasan-wrapper"></div></td>';
                                    $html .= '<td align="center"></td>';
                                }else{
                                    $html .= '<td align="center"><div class="rateYo rateYo-'.$id.'" style="width:10px"></div><div class="rating-wrapper"></div></td>';
                                    $html .= '<td><input type="hidden" class="merchantId" value="'.$order->merchantId.'"><input type="hidden" class="productId" value="'.$order->productId.'"><input type="hidden" class="orderId" value="'.$order->orderId.'"><textarea class="ulasanya-'.$id.'" cols="10" rows="4" required></textarea><div class="ulasan-wrapper"></div></td>';
                                    $html .= '<td align="center"><a class="btn btn-success ulasan-btn" data-id="'.$id.'"><i class="fa fa-check"></i> Submit</a></td>';
                                }

                                $html .= '</tr>';
                                $id++;
                            endforeach;
                            echo $html;
                        ?>
                    </tbody>
                </table>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</div>

<!-- newletter-area-start -->
<?php echo Modules::run('Section/subscribe')?>
<!-- newletter-area-end -->

<script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js')?>"></script>
<script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js')?>"></script>

<script type="text/javascript">

    var save_method; //for save method string
    var table;
    $(document).ready(function() {

      table = $('#table').DataTable({
        "bPaginate": true,
        "bLengthChange": false,
        "bFilter": true,
        "bInfo": false,
        "columnDefs": [
            { "visible": false, "targets": 1 }
          ]
      });

      $('#table tbody').on('click', 'td.details-control', function () {
            var tr = $(this).closest('tr');
            var row = table.row( tr );
            var data = table.row( $(this).parents('tr') ).data();
            var inv = data[ 1 ];
            var td = $(this);
          td.addClass('loading-td');
            

            if ( row.child.isShown() ) {
                // This row is already open - close it
                row.child.hide();
                tr.removeClass('shown');
                td.removeClass('loading-td');
            }
            else {
                /*data*/
               
                $.getJSON("<?php echo site_url('Cart/getDetailOrderByIdForUlasan?orderId=') ?>" + inv, '', function (data) {
                    response_data_from_invoice = data;
                     // Open this row
                    row.child( format( response_data_from_invoice ) ).show();
                    tr.addClass('shown');
                    td.removeClass('loading-td');
                });
               
            }
        } );


    });

    function format ( data ) {
        
        return data.html;
    }

    function reload_table()
    {
      table.ajax.reload(null,false); //reload datatable ajax 
    }

  </script>
