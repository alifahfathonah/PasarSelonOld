<div class="col-sm-3">
    <div class="sidebar column mt-55 mb-50">
        <ul class="sidebar-nav list-group row">
            <li class="user-profile">
                <div class="media">
                    <div class="media-left">
                        <a class="user-photo" href="#">
                            <img id="sidebar-user-photo" class="media-object" src="<?php if(@getimagesize(AVATAR_FILE.$avatar)) { echo AVATAR_FILE.$avatar;}else{ echo base_url('assets/img/product/1.jpg'); } ?>" alt="<?php echo $this->Section_model->getUserDetail($this->session->userdata('user')->id)->firstName; ?>">
                        </a>
                    </div>
                    <div class="media-body">
                        <h4 class="media-heading"><?php echo $profile->firstName." ".$profile->lastName; ?></h4>
                        <h5 class="user-email"><?php echo $profile2->email; ?></h5>
                        <h5 class="user-email"><?php echo $profile->phoneNo; ?></h5>
                    </div>
                </div>
            </li>
            <li>
                <h2 class="page-heading">Pesan</h2>
                <ul>
                    <li><a href="<?php echo base_url().'Pesan/'?>">Pesan Masuk <?php echo ($jmlPesanMasuk==0)?'':'<label class="badge">'.$jmlPesanMasuk.'</label>'; ?></a></li>
                    <li><a href="<?php echo base_url('Pesan/Ulasan') ?>">Ulasan <?php echo ($jmlUlasan==0)?'':'<label class="badge">'.$jmlUlasan.'</label>'; ?></a></li>
                </ul>
            </li>

            <li>
                <h2 class="page-heading">Riwayat Transaksi</h2>
                <ul>
                    <li><a href="<?php echo base_url().'Cart/tagihan'?>">Tagihan Anda <?php echo ($countNotifTagihan==0)?'':'<label class="badge">'.$countNotifTagihan.'</label>'; ?> </a></li>
                    <li><a href="<?php echo base_url().'Cart/pembayaran'?>">Pemesanan Saat Ini <?php echo ($countNotifOrder==0)?'':'<label class="badge">'.$countNotifOrder.'</label>'; ?> </a></li>
                    <li><a href="<?php echo base_url('Cart/pengiriman') ?>">Dalam Proses Pengiriman <?php echo ($countNotifPengiriman==0)?'':'<label class="badge">'.$countNotifPengiriman.'</label>'; ?> </a></li>
                    <li><a href="<?php echo base_url('Cart/transaksi_dibatalkan') ?>">Transaksi Dibatalkan</a></li>
                    <li><a href="<?php echo base_url('Cart/transaksi_ditolak') ?>">Transaksi Ditolak</a></li>
                    <li><a href="<?php echo base_url('Cart/transaksiBerhasil') ?>">Transaksi Berhasil <?php echo ($countNotifRiwayat==0)?'':'<label class="badge">'.$countNotifRiwayat.'</label>'; ?> </a></li>
                    <li><a href="<?php echo base_url('PPOB/history') ?>">PPOB <?php echo ($countNotifRiwayat==0)?'':'<label class="badge">'.$countNotifRiwayat.'</label>'; ?> </a></li>
                </ul>
            </li>

        </ul>
    </div>
</div>