<link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css')?>" rel="stylesheet">
<!-- breadcrumb start -->
<div class="breadcrumb-area">
    <div class="container">
        <?php echo $breadcrumbs ?>
    </div>
</div>
<!-- breadcrumb end -->

<div class="shop-area">
    <div class="container">
        <?php echo Modules::run('Section/sidebar')?>
        <div class="col-sm-9">
            <div class="content-wrap mb-50">
                <h2 class="page-heading mt-40">
                    <span class="cat-name"></span>
                </h2>
                <ul class="nav nav-tabs" id="myTabs" role="tablist">
                    <li class="active"><a href="<?php echo base_url('Pesan/Komplain') ?>">KOMPLAIN ANDA</a></li>
                </ul>
                <div class="tab-content tab-content-rwy">
                    <div class="tab-pane active fade in table-responsive" id="pesan">
                        <table id="table" class="table table-bordered" style="font-size:11.5px">
                            <thead style="background-color:#f4a137;color:white">
                                <tr>    
                            <th>No</th>
                            <th>Nomor Tiket</th>
                            <th>Tanggal</th>
                            <th>Merchant</th>
                            <th>Order ID</th>
                            <th>Subject</th>
                            <th>Produk</th>
                            <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no=0; foreach($tiket as $row) { $no++; ?>
                                
                                <tr>
                                    <td align="center"><?php echo $no ?></td>
                                    <td> <a href="<?php echo base_url('Pesan/Komplain/detail?tiket='.$row['id']) ?>"><?php echo $row['id'] ?>   </a></td>
                                    <td><?php echo $this->tanggal->formatDate($row['createdAt']); ?></td>
                                    <td><?php echo $row['name_merchant'] ?></td>
                                    <td><?php echo $row['orderId'] ?></td>
                                    <td><?php echo $row['subject'] ?></td>
                                    <td><?php echo $row['name_product'] ?></td>
                                    <td><?php echo $row['name_status'] ?></td>
                                    
                                </tr>
                              
                                <?php } ?>
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
            "bPaginate": false,
            "bLengthChange": false,
            "bFilter": true,
            "bInfo": false
        });
    });


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