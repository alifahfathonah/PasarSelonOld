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
                    <li class="active"><a href="#"><b>TRANSAKSI DITOLAK</b></a></li>
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
                            <th>Metode Pembayaran</th>
                            <th>Bank Tujuan</th>
                            <th>Status</th>
                            <th>Alasan Reject</th>
                            <th>Rejected By</th>
                            </thead>
                            <tbody>

                            <?php
                                $no=0; foreach($allRejected as $row) : $no++; 
                                $response = json_decode($row->response);
                                $unique = isset($response->uniqueCode)?$response->uniqueCode:0;
                                if($row->logModifiedByRole == 3) {
                                    $username = 'Rejected by Admin';
                                }elseif($row->logModifiedByRole == 4 || $row->logModifiedByRole == 1) {
                                    $username = 'Rejected by System';
                                }else{
                                    $username = $this->db->select('name')->get_where('Merchant', ['id' => $row->logModifiedBy])->row()->name;
                                }
                                if($row->method == 1) {
                                    if($response->type == 'confirm') {
                                        $totalPrice = $response->amount;
                                    }else{
                                        $totalPrice = $row->totalPrice+$row->totalMargin-$row->totalDiscount + $unique + $row->totalShippingCost;
                                    }
                                }else{
                                    $totalPrice = $row->totalPrice+$row->totalMargin-$row->totalDiscount + $unique + $row->totalShippingCost;
                                }
                                ?>
                                <tr>
                                    <td align="center" class="details-control"></td>
                                    <td align="center"><?php echo $row->id?></td>
                                    <td align="center"><?php echo $no?></td>
                                    <td align="left"><?php echo str_replace('/','/ ', $row->id); ?></td>
                                    <td><?php echo $this->tanggal->formatDateTime($this->timezone->convertUtc($row->createdAt)); ?></td>
                                    <td align="right"><?php echo 'Rp '. number_format($totalPrice)?></td>
                                    <td><?php echo $row->PaymentMethodName?></td>
                                    <td align="center"><?php echo isset($response->escrowBankAccount)?$response->escrowBankAccount->bankName:'-'?></td>
                                    <td>
                                        <?php if($row->status == 2) { ?>
                                            <span class="label label-success"><?php echo $row->PaymentStatusName?></span>
                                        <?php }elseif($row->status == 4){ ?>
                                            <span class="label label-danger"><?php echo $row->PaymentStatusName?></span>
                                        <?php }else{ ?>
                                            <span class="label label-warning"><?php echo $row->PaymentStatusName?></span>
                                        <?php } ?>
                                    </td>
                                    <td><?php echo $row->LogNotes?></td>
                                    <td><?php echo '<b>'.$username.'</b><br>'. $this->tanggal->formatDateTime($this->timezone->convertUtc($row->logUpdatedAt)); ?></td>
                                    <!-- <td align="center">
                                        <a href="<?php echo base_url('Cart/invoice?invoiceId='. $row->id) ?>" target="_blank">detail</a>
                                    </td> -->
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