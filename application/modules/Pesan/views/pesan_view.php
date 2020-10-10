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
            <div class="content-wrap mb-70">
                
                <h2 class="page-heading">
                    <span class="cat-name">&nbsp;</span>
                </h2>
                <ul class="nav nav-tabs" id="myTabs" role="tablist">
                    <li class="active"><a href="#"><b><i class="fa fa-envelope"></i> PESAN MASUK</b></a></li>
                </ul>
                <div class="tab-content tab-content-rwy">
                    <div class="tab-pane active fade in table-responsive" id="pesan">
                    <table id="table" class="table" style="font-size:11.5px">
                    <thead style="background-color:#f4a137;color:white">
                        <th style="width:5px"></th>
                        <th style="width:100px">From</th>
                        <th style="width:130px">Subject</th>
                        <th>Message</th>
                        <th>Notes</th>
                        <th style="width:150px">Date</th>
                    </thead>
                    <tbody>
                    <?php
                        foreach($allPesan as $row) {
                            $belumDiBaca = $this->pesanm->numReadAt($row->id);
                    ?>
                        <tr onclick="window.location = '<?php echo base_url('pesan/detail?messageId='.$row->id.'&subject='.$row->subject) ?>'" <?php echo ($row->count_notif > 0)?'style="font-weight:bold;cursor:pointer"':'style="background-color:#e4e4e4;cursor:pointer"'?> >
                            <td align="center"><?php echo ($row->count_notif > 0)?'<a href="#"><i class="fa fa-wechat"></i></a>':'<i class="fa fa-wechat"></i>'?> </td>
                            <td><?php echo $row->name; ?> &nbsp; <?php echo '('.$row->conversationCount.')'; ?> </td>
                            <td><?php echo $row->subject; ?></td>
                            <td>
                                <?php
                                    if($row->count_notif > 0){
                                        $strlen = strlen($row->new_msg);
                                        echo ($strlen > 50) ? substr($row->new_msg, 0,50).'...' : $row->new_msg;
                                    }else{
                                        echo $row->message;
                                    } 
                                ?>
                            </td>
                            <td align="center"><?php echo ($row->count_notif > 0)?'<span style="color:green">'.$row->count_notif.' new messages</span>':''; ?></td>
                            <td align="right"><?php echo $this->tanggal->formatDateTime($row->readAt); ?></td>
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
