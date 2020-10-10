<!-- breadcrumb start -->
<div class="breadcrumb-area">
    <div class="container">
        <?php echo $breadcrumbs ?>
    </div>
</div>
<!-- breadcrumb end -->

<?php
    $status = $this->db->get_where('ComplaintStatus',['id' => $tiket->status])->row();
?>

<div class="shop-area">
    <div class="container">
        <?php echo Modules::run('Section/sidebar') ?>
        <div class="col-sm-9">
            <div class="content-wrap mb-50">
                <h2 class="page-heading mt-40">
                    <span class="cat-name"></span>
                </h2>
                <ul class="nav nav-tabs" id="myTabs" role="tablist">
                    <li class="active"><a href="<?php echo base_url('Pesan/Komplain') ?>"> KOMPLAIN ANDA </a></li>
                </ul>
                <div class="tab-content tab-content-rwy">
                    <div class="tab-pane active fade in table-responsive" id="pesan">
                        <a href="<?php echo base_url().'Pesan/Komplain'?>"><i class="fa fa-angle-double-left"></i> Kembali ke daftar komplain</a><br>
                        <table class="table table-striped">
                            <tr>
                                <td width="130px">Merchant</td>
                                <td><?php echo $tiket->merchant->name; ?> &nbsp;&nbsp;&nbsp; Order ID : <?php echo $tiket->orderId; ?></td>
                            </tr>
                            <tr><td width="130px">Tanggal Komplain</td><td><?php echo $this->tanggal->formatDateTime($this->timezone->convertUtc($tiket->createdAt)); ?></td></tr>
                            <tr>
                                <td width="130px">Produk</td><td><?php echo $tiket->productHistory->name; ?></td>
                            </tr>
                            <tr>
                                <td colspan="2" align="right" id="btn_status_return_product">
                                    <?php 
                                        if( in_array($tiket->status, array(1,2)) ){
                                            if( $returnProduct != NULL ) {
                                                if( in_array($returnProduct->status, array(1)) ){
                                                    echo '<a id="btn_return_product" class="btn btn-danger retur-modal" data-complaint-id="'.$tiket->id.'" data-product-id="<?php echo $tiket->productId?>" data-merchant-id="<?php echo $tiket->merchantId?>"><i class="fa fa-truck"></i> Pengembalian Barang </a>';
                                                }else if ( $returnProduct->status == 3) {
                                                    echo '<h4><label class="label label-success"><i class="fa fa-check green"></i> Barang diterima merchant</label></h4>';
                                                }else if( $returnProduct->status == 4){
                                                    echo '<button type="button" id="accept_product" class="btn btn-info btn-komplain" data-complaint-id="'.$tiket->id.'"><i class="fa fa-cart-plus"></i> Terima barang kembali </button>';
                                                }else if( in_array($returnProduct->status, array(2,5)) ){
                                                    echo '<h4><label class="label label-info"><i class="fa fa-check green"></i> Menunggu konfirmasi merchant</label></h4>';
                                                }else{
                                                    echo 'Selesai';
                                                }

                                            }else{
                                                echo '<a id="btn_return_product" class="btn btn-danger retur-modal" data-complaint-id="'.$tiket->id.'" data-product-id="'.$tiket->productId.'" data-merchant-id="'.$tiket->merchantId.'"><i class="fa fa-truck"></i> Pengembalian Barang </a>';
                                            }
                                        }
                                        

                                    ?>
                                    
                                </td>
                            </tr>
                        </table>
                        <div class="row">
                            <div class="col-sm-6" style="font-size:20px">
                                <i class="fa fa-bookmark"></i> <?php echo $tiket->subject; ?>
                                <label class="<?php echo ($tiket->status==3)?'label label-success':'label label-danger';?>"><?php echo $status->name?></label>
                                <hr>
                            </div>
                        </div>

                        <ul id="pesan-list" class="media-list comment-list mt-30 pesan-list">
                            <?php 
                            foreach ($conf as $row) {

                                if ($row->modifiedByRole == 1) {
                                    $get = $this->db->query('select avatarFile, firstName, lastName from Customer where id = ' . $row->modifiedBy)->row_array();
                                    $img = AVATAR_FILE . $get['avatarFile'];
                                    $name = $get['firstName'] . ' ' . $get['lastName'];
                                } elseif ($row->modifiedByRole == 2) {
                                    $get = $this->db->query('select logoFile, name from Merchant where id = ' . $row->modifiedBy)->row_array();
                                    $img = IMG_AVATAR_FILE . $get['logoFile'];
                                    $name = $get['name'];
                                }elseif($row->modifiedByRole == 3){
                                    $get = $this->db->query('select avatarFile, firstName, lastName from Admin where id = '.$row->modifiedBy)->row_array();
                                    $img = IMG_AVATAR_FILE .$get['avatarFile'];
                                    $name = $get['firstName'] . ' ' . $get['lastName'];
                                } else {
                                    $img = base_url() . 'assets/img/logo_2.png';
                                    $name = 'Admin Pasar Selon';
                                }

                                if(!@getimagesize($img)) {
                                    $img = base_url('assets/img/product/1.jpg');
                                }
                                ?>


    <?php if ($row->modifiedByRole == 1) { ?>
                                    <li class="media">
                                        <div class="media-body" style="text-align: right;">
                                            <h6> <b> <?php echo $name ?> </b> <br/> <sub><?php  echo $this->tanggal->formatDateTime($this->timezone->convertUtc($row->createdAt)) ?></sub>  </h6>

                                            <p> <?php echo $row->message ?> </p>
                                        </div>
                                        <div class="<?php echo ($row->modifiedByRole == 1) ? 'media-right' : 'media-left' ?>">
                                            <a href="#">
                                                <img alt="<?php echo $name ?>"
                                                     src="<?php echo $img ?>"
                                                     class="avatar" width="60px">
                                            </a>
                                        </div>

                                    </li>
    <?php } else { ?>
                                    <li class="media">
                                        <div class="<?php echo ($row->modifiedByRole == 1) ? 'media-right' : 'media-left' ?>">
                                            <a href="#">
                                                <img alt="<?php echo $name ?>"
                                                     src="<?php echo $img ?>"
                                                     class="avatar" width="60px">
                                            </a>
                                        </div>
                                        <div class="media-body">
                                            <h6> <b> <?php echo $name ?> </b> <br/> <sub><?php  echo $this->tanggal->formatDateTime($this->timezone->convertUtc($row->createdAt)) ?></sub>  </h6>

                                            <p> <?php echo $row->message; ?> </p>
                                        </div>
                                    </li>
    <?php } ?>


<?php } ?>
                        </ul>
                        <form method="post" id="balas-pesan" action="<?php echo base_url('Cart/confcomplaint'); ?>">
                            <div class="input-group">
                                <input type="hidden" name="merchantId" value="<?php echo $tiket->merchantId; ?>">
                                <input type="hidden" name="complaintId" value="<?php echo $tiket->id; ?>">
                                <input type="hidden" name="Id" value="<?php echo $tiket->id; ?>">

                                <?php if($tiket->status != 3):?>
                                <input id="message" name="message" type="text" class="form-control" placeholder="Pesan anda...">

                                <span class="input-group-btn">
                                    <button id="kirim-pesan-btn" class="btn btn-login btn-kirim-pesan" data-loading-text="<i class='fa fa-spinner fa-pulse fa-fw'></i> Sedang Proses Kirim Pesan"><i class="fa fa-paper-plane"></i> Kirim</button>
                                </span>
                            <?php endif;?>

                            </div>
                            <!-- /input-group -->
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

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

<script type="text/javascript">
    $(function () {
        $("#balas-pesan").on('submit', function (e) {
            e.preventDefault();
            var form = $(this);
            var button = $(this).find('#kirim-pesan-btn');
            button.button('loading');

            html = '';
            html += '<li class="media">';
            html += '<div class="media-body" style="text-align: right"><h6> <?php echo $name ?></h6><p> ' + $('#message').val() + '</p></div>';
            html += '<div class="media-right"><a href="#"><img alt="" src="<?php echo $img ?>" width="60px" class="avatar"></a></div>';
            html += '</li>';
            $(html).appendTo($('#pesan-list'));

            $.ajax({
                type: 'POST',
                url: '<?php echo base_url('Pesan/Komplain/addComplaint'); ?>',
                data: form.serialize(),
                dataType: "json",
                success: function (data) {
                    if (data.result == 'OK') {
                        alert("Sukses membalas komplain");
                        $('#message').val('');
//                        return false;
                    } else {
                        alert("Gagal membalasa komplain");
                    }

                    button.button('reset');
                }
            });
        });

        $('#form_modal_review').on('submit', function() {
            $.ajax({
                type: 'POST',
                url: '<?php echo base_url('Pesan/Komplain/returBarang'); ?>',
                data: {
                    productId: $('input[name="productId"]').val(),
                    orderId: $('input[name="orderId"]').val(),
                    merchantId: $('input[name="merchantId"]').val(),
                    ulasan: $('textarea[name="ulasan"]').val(),
                },
                dataType: "json",
                success: function (data) {
                    if (data.status === 'success') {
                        window.location.href = "<?php echo base_url('Cart/pengiriman') ?>";
                    } else {
                        alert("Gagal mengirim diskusi");
                    }

                    button.button('reset');
                }
            });
        });

        $('#btn_return_product').on('click', function() {
            var complaintId = $(this).attr('data-complaint-id'), productId = $(this).attr('data-product-id'), merchantId = $(this).attr('data-merchant-id');
            $('#complaintId').val(complaintId);
            $('#merchantId').val(merchantId);
            $('#modal_return').modal('show');
        })

        $('#accept_product').on('click', function() {
            if( confirm('Apakah anda yakin?') ){
                var ticket = $(this).attr('data-complaint-id');
                $.getJSON("<?php echo site_url('Pesan/Komplain/accept_product?ticket=') ?>" + ticket, '', function (data) {
                        $('#btn_status_return_product').html('<h4><label class="label label-info"><i class="fa fa-check green"></i> Menunggu konfirmasi merchant</label></h4>');
                });
            }

        })

    })
</script>