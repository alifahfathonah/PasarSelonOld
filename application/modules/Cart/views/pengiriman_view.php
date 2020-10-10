<link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css')?>" rel="stylesheet">
<!-- Start plugin star rating -->
<link rel="stylesheet" href="<?php echo base_url('assets/css/jquery.rateyo.min.css') ?>" type="text/css">
<!-- End plugin star rating -->

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
<!--        --><?php //echo $this->session->userdata('token'); ?>
        <?php echo Modules::run('Section/sidebar')?>
        <div class="col-sm-9">
            <div class="content-wrap mb-70">
                <!-- <a class="btn-konfirm" data-id="fauzan">konfirm disini</a> -->
                <h2 class="page-heading">
                    <span class="cat-name">&nbsp;</span>
                </h2>

                <ul class="nav nav-tabs" id="myTabs" role="tablist">
                    <li class="active"><a href="<?php echo base_url('pesan') ?>"><i class="fa fa-truck"></i> DALAM PROSES PENGIRIMAN</a></li>
                </ul>
                <div class="tab-content tab-content-rwy">
                    <div class="tab-pane active fade in table-responsive" id="pesan">
                    <table id="table" class="table table-bordered" style="font-size:11.5px">
                    <thead style="background-color:#f4a137;color:white">
                        <th>Detail</th>
                        <th>ord</th>
                        <th>Merchant</th>
                        <th>Order ID</th>
                        <th>No Resi</th>
                        <th>Metode Pengiriman</th>
                        <th>Biaya Pengiriman</th>
                        <th>Alamat Pengiriman</th>
                        <th>Status Pengiriman</th>
                    </thead>
                    <tbody>
                        <?php
                            $html = '';
//                            echo '<pre>';print_r($allDeliveredOrder);die;
                            foreach ($allDeliveredOrder as $order) :
                                $address = json_decode($order->customerAddress);
                                $courier = json_decode($order->courierCost);
                                if (@getimagesize(AVATAR_FILE . $order->logoFile)) {
                                    $img = AVATAR_FILE . $order->logoFile;
                                } else {
                                    $img = base_url('assets/img/product/default-image.png');
                                }
                                $html .= '<tr>';
                                $html .= '<td class="details-control-custom"><button class="btn btn-success btn-detail-pengiriman" data-toggle="tooltip" title="Lihat detail list barang yang dibeli" type="button">Lihat Detail <i class="fa fa-caret-right"></i></button></td>';
                                $html .= '<td>'.$order->OrderId.'</td>';
                                $html .= '<td align="left">'.$order->merchant->name.'</td>';
                                $html .= '<td>'.str_replace('/','/ ', $order->OrderId).'<br>'.$this->tanggal->formatDateTime($this->timezone->convertUtc($order->tglTransaksi)).'</td>';
                                if($order->shippingAirwaybill != '') {
                                    $html .= '<td>'.$order->shippingAirwaybill.'</td>';
                                }else{
                                    $html .= '<td> - </td>';
                                }
                                $html .= '<td>'.$courier->courierName.' - '.$courier->courierPackageName.'</td>';
                                $html .= '<td align="right">Rp. '.number_format($order->totalShippingCost).'</td>';
                                $html .= '<td>'.$address->address.' <br>Penerima : '.$address->recipientName.' ('.$address->recipientPhone.')</td>';
                                $html .= '<td align="center" class="view_status_pengiriman" id="'.str_replace('/','-', $order->OrderId).'"><a class="btn btn-primary"> Lacak </a> </td>';
                                $html .= '</tr>';
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

<div class="modal fade" id="modal_konfirmasi" role="dialog">
	<form id="form_address" class="form-horizontal" method="post" action="<?php echo base_url('Cart/barangDiterima') ?>">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h3 class="modal-title">Konfirmasi Penerimaan</h3>
				</div>
				<div class="modal-body form">
					<div class="form-body">
						<div class="form-group">
							<div class="col-sm-12">
								<input type="hidden" class="form-control" name="orderId" id="orderId" value="" required><?php echo form_error('orderId') ?>
								<p>Apakah paket yang Anda terima sudah sesuai dengan pesanan Anda?</p>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default btn-komplain">Tidak Sesuai</button>
                    <button type="submit" id="btnSave" class="btn btn-primary" data-loading-text="<i class='fa fa-spinner fa-pulse fa-fw'></i> Tambahkan">Sesuai</button>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</form>
</div><!-- /.modal -->

<div class="modal fade" id="modal_lacak" role="dialog">
    <form id="form_address" class="form-horizontal" method="post" action="<?php echo base_url('Cart/barangDiterima') ?>">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h3 class="modal-title" id="order_title"><i class="fa fa-truck"></i> Lacak Pengiriman Barang</h3>
                </div>
                <div class="modal-body form">
                    <div class="form-body">
                        <div class="form-group">
                            <div class="col-sm-12">
                                <div id="response_message"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal" aria-label="Close">Close</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </form>
</div>

<div class="modal fade" id="modal_review" role="dialog">
    <form id="form_modal_review" class="form-horizontal" method="post">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h3 class="modal-title">Review Product</h3>
                </div>
                <div class="modal-body form">
                <b>DESKRIPSI PRODUK</b>
            <table class="table table-striped" width="100%" style="font-size:11.5px">
            <tr><td>ID</td><td align="left"><div id="product_id"></div></td></tr>
            <tr><td>Nama Produk</td><td align="left"><div id="product_name"></div></td></tr>
            <tr><td>Kategori</td><td align="left"><div id="product_category"></div></td></tr>
            <tr><td>Rating</td><td align="left"><div class="rateYo" style="width:10px"></div></td></tr>
            <tr><td>Ulasan</td><td align="left"><textarea class="ulasanya" name="ulasan" cols="10" rows="4" required></textarea></td></tr>
            
            </table>

                    <div class="form-body">
                        <div class="form-group">
                            <input type="hidden" name="productId" id="productId">
                            <input type="hidden" name="orderId" id="orderId">
                            <input type="hidden" name="merchantId" id="merchantId">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"> Cancel</button>
                    <button type="submit" id="btnSave" class="btn btn-primary" data-loading-text="<i class='fa fa-spinner fa-pulse fa-fw'></i> Tambahkan"> Submit </button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </form>
</div><!-- /.modal -->

<div class="modal fade" id="modal_return" role="dialog">
    <form id="form_modal_review" class="form-horizontal" method="post" action="<?php echo base_url('Pesan/Komplain/returBarang') ?>">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h3 class="modal-title">Return Product</h3>
                </div>
                <div class="modal-body form">
                    <div class="col-sm-12">
                        <div class="form-body">
                            <div class="form-group">
                                <label for="complaintId">Complaint ID</label>
                                <input type="text" readonly value="" id="complaintId" name="complaintId">
                                <input type="hidden" readonly value="" id="merchantId" name="merchantId">
                            </div>
                            <div class="form-group">
                                <label for="remark">Remark</label>
                                <textarea name="remark" id="remark" placeholder="Alasan barang dikembalikan"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" id="btnSave" class="btn btn-primary" data-loading-text="<i class='fa fa-spinner fa-pulse fa-fw'></i> Tambahkan"> Return </button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal"> Cancel</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </form>
</div><!-- /.modal -->

<!-- newletter-area-start -->
<?php echo Modules::run('Section/subscribe')?>
<!-- newletter-area-end -->

<script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js')?>"></script>
<script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js')?>"></script>

<script type="text/javascript">

    var save_method; //for save method string
    var table;
    $(document).ready(function() {
        
        $('#form_modal_review').on('submit', function() {
            $.ajax({
                type: 'POST',
                url: '<?php echo base_url('Pesan/Ulasan/submit'); ?>',
                data: {
                    productId: $('input[name="productId"]').val(),
                    orderId: $('input[name="orderId"]').val(),
                    merchantId: $('input[name="merchantId"]').val(),
                    ulasan: $('textarea[name="ulasan"]').val(),
                    rating: $(".rateYo").rateYo("rating")
                },
                dataType: "json",
                success: function (data) {
                    if (data.status === 'success') {
//                        var jmlUlasanPerOrder = $('#jml-ulasan-'+ordering).text();
//                        document.getElementById('jml-ulasan-'+ordering).innerHTML = jmlUlasanPerOrder - 1;
//                        button.hide();
//                        alert("Sukses mereview");
//                        $('.rateYo-'+id).fadeOut();
//                        $('.ulasanya-'+id).fadeOut();
//
//                        var bintangKosong = 5 - bintang;
//                        var bintangContainer = '<div class="pro-rating ulasan-rating">';
//                        for(i=1;i<=bintang;i++) {
//                            bintangContainer += '<a><i class="fa fa-star"></i></a>';
//                        }
//                        for(a=1;a<=bintangKosong;a++) {
//                            bintangContainer += '<a><i class="fa fa-star-o"></i></a>';
//                        }
//                        bintangContainer += '</div>';
//                        var ulasannys = '<p>'+ulasan+'</p>';
//
//                        tr.find('.ulasan-wrapper').append(ulasannys).fadeIn();
//                        tr.find('.rating-wrapper').append(bintangContainer).fadeIn();

                        window.location.href = "<?php echo base_url('Cart/pengiriman') ?>";
//                        return false;
                    } else {
                        alert("Gagal mengirim diskusi");
                    }

                    button.button('reset');
                }
            });
        });

        $('body').on('click', '.btn-komplain', function() {
            var orderId = $('#orderId').val();
            $.getJSON("<?php echo site_url('Cart/orderComplaint?orderId=') ?>" + orderId, '', function (data) {
                console.log(data);
                $('.btn-aksi').css({"display":"inline-block"});
                $('#modal_konfirmasi').modal('toggle');
                $('.status-barang').hide();
                $('.p-konf-pener').hide();
            });
        });

        $('body').on('click', '.btn-ok', function(e) {
            e.preventDefault();
            var orderId = $(this).attr("data-id"), merchantId = $(this).attr('data-merchant-id'), productId = $(this).attr('data-product-id');
            $.getJSON("<?php echo site_url('Cart/getProductDetail?id=') ?>" + productId, '', function (data) {
                console.log(data);
                $('#product_id').html(data.id);
                $('#product_name').html(data.name);
                $('#product_category').html(data.productCategoryName);
                $('#product_description').html(data.shortDescription);
                $('input[name="orderId"]').val(orderId);
                $('input[name="productId"]').val(productId);
                $('input[name="merchantId"]').val(merchantId);
                $('#modal_review').modal('show');
            });
        });

        $('body').on('click', '.btn-konfirm', function() {
            var orderId = $(this).attr("data-id");
            $.getJSON("<?php echo site_url('Cart/orderDelivered?orderId=') ?>" + orderId, '', function (data) {
                console.log(data);
                $('input[name="orderId"]').val(orderId);
                $('#modal_konfirmasi').modal('show');
            });
        });

      table = $('#table').DataTable({ 
        "bPaginate": true,
        "bLengthChange": false,
        "bFilter": true,
        "bInfo": false,
        "columnDefs": [
            { "visible": false, "targets": 1 }
          ]
      });

      $('#table tbody').on('click', 'td.details-control-custom .btn', function () {
            var tr = $(this).closest('tr');
            var row = table.row( tr );
            var data = table.row( $(this).parents('tr') ).data();
            var inv = data[ 1 ];

            

            if ( row.child.isShown() ) {
                // This row is already open - close it
                row.child.hide();
                tr.removeClass('shown');
            }
            else {
                /*data*/
               
                $.getJSON("<?php echo site_url('Cart/getDetailOrderById?orderId=') ?>" + inv, '', function (data) {
                    response_data_from_invoice = data;
                     // Open this row
                    row.child( format( response_data_from_invoice ) ).show();
                    tr.addClass('shown');
                });
               
            }
        } );

        /*$('#table tbody').on('click', 'td.view_status_pengiriman', function () {
            var tr = $(this).closest('tr');
            var row = table.row( tr );
            var data = table.row( $(this).parents('tr') ).data();
            var inv = data[ 1 ];
            var newstr = '-';
            var string = inv.split('/').join(newstr);
            $.getJSON("" + inv, '', function (data) {
                    $('#'+string).html(data.message);
                });
        } );*/

        $('#table tbody').on('click', 'td.view_status_pengiriman', function () {
            var tr = $(this).closest('tr');
            var row = table.row( tr );
            var data = table.row( $(this).parents('tr') ).data();
            var inv = data[ 1 ];
            var newstr = '-';
            var string = inv.split('/').join(newstr);
            $.getJSON("<?php echo base_url().'Cart/traceOrder?orderId=' ?>" + inv, '', function (data) {
                    //$('#'+string).html(data.message);

                    $('#order_title').html('<i class="fa fa-truck"></i> Lacak Pengiriman Barang');
                    $('#response_message').html(data.message);

                    $('#modal_lacak').modal('show');
                });
        } );


        $("#submit_btn").click(function(event){
          event.preventDefault();
          alert('OK');
         /* var searchIDs = $("#dynamic-table input:checkbox:checked").map(function(){
            return $(this).val();
          }).toArray();
          delete_data(''+searchIDs+'')
          console.log(searchIDs);*/
        });

        $('#table tbody').on('click', '.retur-modal', function() {
            var complaintId = $(this).attr('data-complaint-id'), productId = $(this).attr('data-product-id'), merchantId = $(this).attr('data-merchant-id');
            $('#complaintId').val(complaintId);
            $('#merchantId').val(merchantId);
            $('#modal_return').modal('show');
        })

    });

    function format ( data ) {
        
        return data.html;
    }

    function reload_table()
    {
      table.ajax.reload(null,false); //reload datatable ajax 
    }

  </script>
