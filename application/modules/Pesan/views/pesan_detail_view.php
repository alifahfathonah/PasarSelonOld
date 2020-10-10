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
                <h2 class="page-heading">
                    <span class="cat-name">&nbsp;</span>
                </h2>
                <ul class="nav nav-tabs" id="myTabs" role="tablist">
                    <li class="active"><a href="<?php echo base_url('pesan') ?>"><i class="fa fa-wechat"></i> PERCAKAPAN ANDA</a></li>
                </ul>
                <div class="tab-content tab-content-rwy">
                    <div class="tab-pane active fade in table-responsive" id="pesan">
                        <a href="<?php echo base_url().'pesan'?>"><i class="fa fa-angle-double-left"></i> Back to message</a>
                        <div style="font-size:16px">
                            <?php echo $msg->subject.' <br> <small style="font-size:11px">('. $msg->name.' - '.$msg->firstName.')'; ?>
                            <i class="fa fa-clock-o"></i> <?php echo $this->tanggal->formatDateTime($msg->createdAt)?></small>
                        </div>

                        <ul id="pesan-list" class="media-list comment-list mt-30 pesan-list">
                            <?php
                                foreach ($allPesanDetail as $row) {
                                    if($row->modifiedByRole == 2) {
                                        $query = $this->db->select('logoFile')->get_where('Merchant',['id' => $row->modifiedBy])->row();
                                        $images = $query->logoFile;
                                        $imageFix = IMG_AVATAR_FILE.$images;
                                    }elseif($row->modifiedByRole == 1) {
                                        $query = $this->db->select('avatarFile')->get_where('Customer',['id' => $row->modifiedBy])->row();
                                        $images = $query->avatarFile;
                                        $imageFix = AVATAR_FILE.$images;
                                    }

//                                    print_r($images);
                            ?>
                                <li class="media">
                                    <div class="media-left">
                                        <a href="#">
                                            <img alt="<?php echo $row->username; ?>"
                                                 src="<?php echo $imageFix; ?>"
                                                 class="avatar">
                                        </a>
                                    </div>
                                    <div class="media-body">
                                        <h6> <i class="fa fa-user"></i> <?php echo $row->username; ?></h6>

                                        <p>
                                            <?php echo ucfirst($row->message); ?>
                                            <?php echo '<br><small style="font-size:11px"><i class="fa fa-clock-o"></i> '.$this->tanggal->formatDateTime($row->createdAt).'' ?>
                                        </p>
                                        
                                    </div>
                                </li>
                            <?php } ?>
                        </ul>
                        <form method="post" id="balas-pesan">
                            <div class="input-group">
                                <input id="username" type="hidden" name="username" value="<?php echo $row->username; ?>">
                                <input type="hidden" name="messageId" value="<?php echo $messageId; ?>">
                                <input id="message" name="message" type="text" class="form-control" placeholder="Pesan anda...">
                                <span class="input-group-btn">
                                   <button id="kirim-pesan-btn" type="submit" class="btn btn-login btn-kirim-pesan" data-loading-text="<i class='fa fa-spinner fa-pulse fa-fw'></i> Kirim"><i class="fa fa-paper-plane"></i> Kirim</button>
                                </span>
                            </div>
                            <!-- /input-group -->
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(function() {
        $("#balas-pesan").on('submit', function (e) {
            e.preventDefault();
            var form = $(this);
            var button = $(this).find('#kirim-pesan-btn');
            //button.button('loading');

            html = '';
            html += '<li class="media">';
            html += '<div class="media-left"><a href="#"><img class="avatar" src="<?php if(@getimagesize(AVATAR_FILE.$avatar)) { echo AVATAR_FILE.$avatar;}else{ echo base_url('assets/img/product/1.jpg'); } ?>" alt="<?php echo $this->Section_model->getUserDetail($this->session->userdata('user')->id)->firstName; ?>"></a></div>';
            html += '<div class="media-body"><h6><i class="fa fa-user"></i> '+$('#username').val()+' </h6><p> ' + $('#message').val() + ' <?php echo '<br><small style="font-size:11px"><i class="fa fa-clock-o"></i> '.$this->tanggal->formatDateTime(date('Y-m-d H:i:s')).'' ?> </p></div>';
            html += '</li>';
            $(html).appendTo($('#pesan-list'));

            $.ajax({
                type: 'POST',
                url: '<?php echo base_url('Pesan/Pesan/addConversation'); ?>',
                data: form.serialize(),
                dataType: "json",
                success: function (data) {
                    if (data.status == 'success') {
                        $('#message').val('');
                        //alert("Sukses mengirim pesan");
//                        return false;
                    } else {
                        alert("Gagal mengirim pesan");
                    }

                    //button.button('reset');
                }
            });
        });
    })
</script>