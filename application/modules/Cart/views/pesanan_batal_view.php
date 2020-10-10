<link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css')?>" rel="stylesheet">
<!-- breadcrumb start -->
<div class="breadcrumb-area">
    <div class="container">
        <?php echo $breadcrumbs?>
    </div>
</div>
<!--<pre>-->
<!--    --><?php //print_r($allRejected); ?>
<!--</pre>-->
<!-- breadcrumb end -->
<!-- cart-main-area start -->
<div class="shop-area">
    <div class="container">
        <?php echo Modules::run('Section/sidebar')?>
        <div class="col-sm-9">
            <div class="content-wrap mb-50">
                <h2 class="page-heading">
                    <span class="cat-name">&nbsp;</span>
                </h2>
                <ul class="nav nav-tabs" id="myTabs" role="tablist">
                    <li class="active"><a href="#"><b><i class="fa fa-times-circle-o"></i> TRANSAKSI YANG DIBATALKAN</b></a></li>
                </ul>
                <div class="tab-content tab-content-rwy">
                    <div class="tab-pane active fade in table-responsive" id="pesan">
                        <table id="table" class="table table-bordered" style="font-size:11.5px">
                            <thead style="background-color:#f4a137;color:white">
                            <th></th>
                            <th>ord</th>
                            <th>Merchant</th>
                            <th>Order ID</th>
                            <th>Metode Pengiriman</th>
                            <th>Total Pembayaran</th>
                            <th>Tanggal Dibatalkan</th>
                            <th>Keterangan</th>
                            </thead>
                            <tbody>
                            <?php
                            $html = '';
//                            echo '<pre>';print_r($allRejected);die;
                            foreach ($allRejected as $order) :
                                $address = json_decode($order->customerAddress);
                                $courier = json_decode($order->courierCost);
                                if (@getimagesize(AVATAR_FILE . $order->logoFile)) {
                                    $img = AVATAR_FILE . $order->logoFile;
                                } else {
                                    $img = base_url('assets/img/product/default-image.png');
                                }

                                $alasanRejected = $this->checkout->getReason($order->OrderId);

                                $alasan2 = '';
                                foreach ($alasanRejected as $rejected) {
                                    $alasan2 .= '<span style="color: red;">'.$rejected->remark.'</span>';
                                }

                                $totalPrice = $order->totalPrice + $order->totalMargin;
                                $totalNet = $totalPrice + $order->totalShippingCost;
                                $html .= '<tr>';
                                $html .= '<td class="details-control"></td>';
                                $html .= '<td>'.$order->OrderId.'</td>';
                                $html .= '<td align="left">'.$order->merchant->name.'</td>';
                                $html .= '<td>'.str_replace('/','/ ', $order->OrderId).'<br>'.$this->tanggal->formatDateTime($order->tglTransaksi).'</td>';
                                $html .= '<td>'.$courier->courierName.' - '.$courier->courierPackageName.'</td>';
                                $html .= '<td align="right">Rp. '.number_format($totalNet).'</td>';
                                $html .= '<td align="center">'.$this->tanggal->formatDateTime($order->tglOrderUpdate).'</td>';
                                $html .= '<td align="center">'.$alasan2.'</td>';
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



            if ( row.child.isShown() ) {
                // This row is already open - close it
                row.child.hide();
                tr.removeClass('shown');
            }
            else {
                /*data*/

                $.getJSON("<?php echo site_url('Cart/getDetailOrderById?orderId=') ?>" + inv +'&status=canceled', '', function (data) {
                    response_data_from_invoice = data;
                     // Open this row
                    row.child( format( response_data_from_invoice ) ).show();
                    tr.addClass('shown');
                });

            }
        } );

    });

    function format ( data ) {

        return data.html;
    }

    function view_invoice(id)
    {
        save_method = 'update';
        $('#form')[0].reset(); // reset form on modals

        //Ajax Load data from ajax
        $.ajax({
            url : "<?php echo base_url('Cart/invoice?invoiceId=') ?>" + id,
            //url : "<?php echo site_url('person/ajax_edit/')?>/" + id,
            type: "GET",
            dataType: "JSON",
            success: function(data)
            {

                $('[name="id"]').val(data.id);
                $('[name="firstName"]').val(data.firstName);
                $('[name="lastName"]').val(data.lastName);
                $('[name="gender"]').val(data.gender);
                $('[name="address"]').val(data.address);
                $('[name="dob"]').val(data.dob);

                $('#modal_form').modal('show'); // show bootstrap modal when complete loaded
                $('.modal-title').text('Edit Person'); // Set title to Bootstrap modal title

            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error get data from ajax');
            }
        });
    }

    function reload_table()
    {
        table.ajax.reload(null,false); //reload datatable ajax
    }

</script>