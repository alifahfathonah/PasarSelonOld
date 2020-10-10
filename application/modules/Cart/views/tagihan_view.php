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
                    <li class="active"><a href="#"><b><i class="fa fa-shopping-cart"></i> DAFTAR TAGIHAN ANDA SAAT INI</b></a></li>
                </ul>
                <div class="tab-content tab-content-rwy">
                    <div class="tab-pane active fade in table-responsive" id="pesan">
                        <table id="table" class="table table-bordered" style="font-size:11.5px">
                            <thead style="background-color:#f4a137;color:white">
                            <th></th>
                            <th>inv</th>
                            <th>No</th>
                            <th>Invoice</th>
                            <th>Tanggal</th>
                            <th>Total Pembayaran</th>
                            <th>Metode  Pembayaran</th>
                            <th>Bank Tujuan</th>
                            <th>Status</th>
                            <th>Metode Pengiriman</th>
                            <th align="center">Aksi</th>
                            </thead>
                            <tbody>
                            <?php
                            $no=0;
                            //                            echo '<pre>';
                            //                            print_r($customerOrder);die;
                            foreach($customerOrder as $order) :
                                $no++;
                                $responseLog = json_decode($order->response);
                                //print_r($responseLog);die;
                                $unique = isset($responseLog->uniqueCode)?$responseLog->uniqueCode:0;
                                if($order->method == 1) {
                                    if($responseLog->type == 'confirm') {
                                        $totalPrice = $responseLog->amount;
                                    }else{
                                        $totalPrice = $order->totalPrice+$order->totalMargin-$order->totalDiscount + $unique + $order->totalShippingCost;
                                        $totalPrice -= $order->totalVoucher;
                                    }
                                }else{
                                    $totalPrice = $order->totalPrice+$order->totalMargin-$order->totalDiscount + $unique + $order->totalShippingCost;
                                    $totalPrice -= $order->totalVoucher;
                                }
                                ?>
                                <tr <?php echo ($order->notificationId != '')?'':'style="background-color:#ebebeb"'?> >
                                    <td align="center" class="details-control"></td>
                                    <td align="center"><?php echo $order->id?></td>
                                    <td align="center"><?php echo $no?></td>
                                    <td>
                                        <?php
                                        /*echo '<a href="'.base_url().'Cart/detail_invoice?invoiceId='.$order->id.'">'.str_replace('/','/ ', $order->id);*/
                                        echo '<a href="#">'.str_replace('/','/ ', $order->id);
                                        ?>

                                    </td>
                                    <td align="center"><?php echo $this->tanggal->formatDateTime($this->timezone->convertUtc($order->createdAt)); ?></td>
                                    <td align="right"><?php echo 'Rp '. number_format($totalPrice)?></td>
                                    <td align="center"><?php echo $order->PaymentMethodName == 'N/A' ? 'Belum Dibayar': $order->PaymentMethodName ;?></td>
                                    <td align="center"><?php echo isset($responseLog->escrowBankAccount)?$responseLog->escrowBankAccount->bankName:'-'?></td>
                                    <td align="center">
                                        <?php if($order->status == 2) { ?>
                                            <span class="label label-info" style="font-size: 100%;"><?php echo $order->PaymentStatusName?></span>
                                        <?php }elseif($order->status == 1) { ?>
                                            <span class="label label-default" style="font-size: 100%;"><?php echo $order->PaymentStatusName?></span>
                                        <?php }elseif($order->status == 3) { ?>
                                            <span class="label label-success" style="font-size: 100%;"><?php echo $order->PaymentStatusName?></span>
                                        <?php }else{ ?>
                                            <span class="label label-warning" style="font-size: 100%;"><?php echo $order->PaymentStatusName?></span>
                                        <?php } ?>
                                    </td>

                                    <td align="center">
                                        <?php echo $order->PaymentMethodName?>
                                    </td>
                                    <td align="center">
                                        <?php
                                        $ntf = ($order->notificationId != NULL)?'&ntf='.$order->notificationId.'':'';
                                        echo '<a class="label label-info" title="Lihat invoice" href="'.base_url().'Cart/invoice?invoiceId='.$order->id.$ntf.'" target="_blank"> <i class="fa fa-file"></i></a> ';
                                        if($order->status == 1) {
                                            if($order->method == 0 || $order->method == 3) {
                                                echo '<a class="label label-danger" title="Lanjutkan pembayaran" href="'.base_url().'Cart/Checkout/payment_method?invoiceId='.$order->id.$ntf.'"><i class="fa fa-money"></i></a>';
                                            }elseif($order->method == 1){
                                                echo '<a class="label label-success" title="Unggah bukti pembayaran" href="'.base_url().'Cart/unggah_bukti?invoiceId='.$order->id.$ntf.'"><i class="fa fa-upload"></i></a>';
                                            }elseif ($order->method == 2) {
                                                $url = json_decode($order->response);
                                                echo '<a class="label label-danger" title="Pembayaran" href="'.$url->body->redirecturl.'" target="blank"><i class="fa fa-credit-card"></i></a>';
                                            }
                                        }
                                        ?>

                                    </td>
                                </tr>
                            <?php endforeach; ?>
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



            if ( row.child.isShown() ) {
                // This row is already open - close it
                row.child.hide();
                tr.removeClass('shown');
            }
            else {
                /*data*/

                $.getJSON("<?php echo site_url('Cart/getDetailOrder?invoiceId=') ?>" + inv, '', function (data) {
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