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

<!--        --><?php //echo '<pre>'; print_r($customerOrder);die; ?>
        <div class="col-sm-9">
            <div class="content-wrap mb-70">
                
                <h2 class="page-heading">
                    <span class="cat-name">&nbsp;</span>
                </h2>
                <ul class="nav nav-tabs" id="myTabs" role="tablist">
                    <li class="active"><a href="#"><b><i class="fa fa-shopping-cart"></i> DAFTAR PESANAN ANDA SAAT INI</b></a></li>
                </ul>
                <div class="tab-content tab-content-rwy">
                    <div class="tab-pane active fade in table-responsive">
                        <table id="table" class="table table-bordered" style="font-size:11.5px">
                            <thead style="background-color:#f4a137;color:white">
                            <th>Detail</th>
                            <th>Order ID</th>
                            <th>Merchant</th>
                            <th>Order ID</th>
                            <th>Metode Pengiriman</th>
                            <th>Biaya Pengiriman</th>
                            <th>Alamat Pengiriman</th>
                            </thead>
                            <tbody>
                            <?php
                            $no=0;
                            if(!isset($customerOrder->statusCode)) {
                                /*echo '<pre>';print_r($customerOrder);die;*/
                            foreach($customerOrder as $order) {
                                if($order->status == 1 || $order->status == 2 || $order->status == 3) {
                                    $address = $order->customerAddress;
                                    $courier = $order->courierCost;
                                    $no++;
//                                $responseLog = json_decode($order->response);
                                    /*echo '<pre>';print_r($order);die;*/
//                                    $unique = $responseLog !== null? $responseLog->uniqueCode : 0;
//                                    if($order->paymentMethod == 1) {
//                                        if($responseLog->type == 'confirm') {
//                                            $totalPrice = $responseLog->amount;
//                                        }else{
//                                            $totalPrice = $order->totalPrice+$order->totalMargin-$order->totalDiscount + $unique + $order->totalShippingCost;
//                                            $totalPrice -= isset($order->totalVoucher)?$order->totalVoucher:0;
//                                        }
//                                    }else{
//                                        $totalPrice = $order->totalPrice+$order->totalMargin-$order->totalDiscount + $unique + $order->totalShippingCost;
//                                        $totalPrice -= isset($order->totalVoucher)?$order->totalVoucher:0;
//                                    }
                                    ?>
                                    <tr <?php echo isset($order->notificationId)?'':'style="background-color:#ebebeb"'?> >
                                        <td align="center" class="details-control"></td>
                                        <td></td>
                                        <td><?php echo $order->merchant->name; ?></td>
                                        <td><?php echo $order->id?></td>
                                        <td><?php echo $courier->courierName.' - '.$courier->courierPackageName; ?></td>
                                        <td>Rp. <?php echo number_format($order->totalShippingCost); ?></td>
                                        <td><?php echo $address->address.' <br>Penerima : '.$address->recipientName. ($address->recipientPhone); ?></td>
                                    </tr>
                            <?php }} }else{ echo '<tr><td colspan="7">Tidak ada transaksi</td></tr>'; } ?>
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
            var inv = data[ 3 ];

            

            if ( row.child.isShown() ) {
                // This row is already open - close it
                row.child.hide();
                tr.removeClass('shown');
            }
            else {
                /*data*/
               
                $.getJSON("<?php echo site_url('Cart/getDetailOrderPemesanan?orderId=') ?>" + inv, '', function (data) {
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
