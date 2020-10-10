<?php
    //print_r($this->session->userdata('timezone'));
//    echo $this->timezone->convertUtc('2017-06-11 08:04:40');
?>

<link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css')?>" rel="stylesheet">
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
                <div class="content-wrap mb-50">
                    <h2 class="page-heading">
                        <span class="cat-name">&nbsp;</span>
                    </h2>
                    <ul class="nav nav-tabs" id="myTabs" role="tablist">
                    <li class="active"><a href="#"><b>TRANSAKSI BERHASIL</b></a></li>
                </ul>
                <div class="tab-content tab-content-rwy">
                    <div class="tab-pane active fade in table-responsive" id="pesan">
                        <table id="table" class="table table-bordered" style="font-size:11.5px">
                            <thead style="background-color:#f4a137;color:white">
                            <th></th>
                            <th>ord</th>
                            <!-- <th>Merchant</th> -->
                            <th>Order ID</th>
                            <th>No Resi</th>
                            <th>Metode Pengiriman</th>
                            <th>Biaya Pengiriman</th>
                            <th>Penerima</th>
                            <th>Alamat Pengiriman</th>
                            <th>Status Order</th>
                            </thead>
                            <tbody>
                            <?php
                            $html = '';
                            //echo '<pre>';print_r($allDeliveredOrder);die;
                            foreach ($allDeliveredOrder as $order) :
                                $address = json_decode($order->customerAddress);
                                $courier = json_decode($order->courierCost);
                                $shippingNotes = json_decode($order->shippingNotes);
    //                            echo '<pre>'; print_r($allDeliveredOrder);die;
                                if($order->status == 7) {
                                    $status = '<label class="label label-success">Order Sukses</label>';
                                }else{
                                    $status = '<label class="label label-info">Order Diterima</label>';
                                }
                                if (@getimagesize(AVATAR_FILE . $order->logoFile)) {
                                    $img = AVATAR_FILE . $order->logoFile;
                                } else {
                                    $img = base_url('assets/img/product/default-image.png');
                                }
                                $html .= '<tr>';
                                $html .= '<td class="details-control"></td>';
                                $html .= '<td>'.$order->OrderId.'</td>';
                                //$html .= '<td align="left">'.$order->merchant->name.'</td>';
                                $html .= '<td>'.str_replace('/','/ ', $order->OrderId).'<br>'.$this->tanggal->formatDateTime($this->timezone->convertUtc($order->tglTransaksi)).'</td>';
                                $html .= '<td>'.$order->shippingAirwaybill.'</td>';
                                $html .= '<td>'.$courier->courierName.' - '.$courier->courierPackageName.'</td>';
                                $html .= '<td align="right">Rp. '.number_format($order->totalShippingCost).'</td>';
                                $html .= '<td>'.$address->recipientName.' ('.$address->recipientPhone.')</td>';
                                $html .= '<td align="left">'.$address->address.'</td>';
                                $html .= '<td align="center" class="view_status_pengiriman">'.$status.'</td>';
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

                $.getJSON("<?php echo site_url('Cart/getDetailOrderByIdTransaksiBerhasil?orderId=') ?>" + inv + '&status=success', '', function (data) {
                    response_data_from_invoice = data;
                    // Open this row
                    row.child( format( response_data_from_invoice ) ).show();
                    tr.addClass('shown');
                });

            }
        } );

        $('#table tbody').on('click', 'td.view_status_pengiriman', function () {
            var tr = $(this).closest('tr');
            var row = table.row( tr );
            var data = table.row( $(this).parents('tr') ).data();
            var inv = data[ 1 ];

            $.getJSON("<?php echo base_url().'Cart/traceOrder?orderId=' ?>" + inv, '', function (data) {
                $('td.view_status_pengiriman').html(data.message);
                /*response_data_from_invoice = data;
                 // Open this row
                 row.child( format( response_data_from_invoice ) ).show();
                 tr.addClass('shown');*/
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

    });

    function format ( data ) {

        return data.html;
    }

    function reload_table()
    {
        table.ajax.reload(null,false); //reload datatable ajax
    }

</script>