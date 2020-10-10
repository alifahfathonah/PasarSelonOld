<!-- header-top-area-start -->
<div class="header-most-top">
		<p>Halal Shop</p>
	</div>
                    <?php
                        if(count($headerInformation) > 0) {
                    ?>
                            <div class="col-md-3 col-sm-4 col-xs-6 hidden-768">
                                <div class="header-top-left">
                                    <span><i class="fa <?php echo $headerInformation[0]->icon; ?>"></i>&nbsp;<a href="#"><?php echo $headerInformation[0]->key?> : <?php echo $headerInformation[0]->value?></a></span>
                                </div>
                            </div>

                            <div class="col-md-3 hidden-sm hidden-xs">
                                <div class="header-top-left">
                                    <span> <i class="fa <?php echo $headerInformation[1]->icon; ?>"></i>&nbsp; <?php echo $headerInformation[1]->key; ?> :  <?php echo $headerInformation[1]->value?><a href=""></a></span>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-4 hidden-xs hidden-sm">
                                <div class="header-top-left">
                                    <span> <a href="mailto:<?php echo $headerInformation[2]->value; ?>"><i class="fa <?php echo $headerInformation[2]->icon; ?>">&nbsp;</i> <?php echo $headerInformation[2]->key; ?> : <?php echo $headerInformation[2]->value?></a></span>
                                </div>
                            </div>
                    <?php } ?>
                    <div class="col-xs-3 show768">
                        <ul class="nav navbar-nav nav-cart-ul">
                            <li>
                                <a href="<?php echo base_url('Product/LastSeen'); ?>" data-toggle="tooltip" title="Last Seen"><i class="fa fa-refresh"></i></a>
                            </li>
                            <li>
                                <a href="<?php echo base_url('Cart/Checkout') ?>"><i class="fa fa-shopping-cart"></i></a>
                            </li>
                        </ul>
                    </div>
                    <?php if($this->session->userdata('logged_in')){?>
                        <div class="col-sm-8 col-md-3 user-menu col-xs-9">
                        <div class="header-top-right pull-right">
                            <ul class="nav navbar-nav navbar-right header-nav-cust">
                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-bell"></i><?php echo ($notification->num_rows() > 0)?'<label class="badge">'.$notification->num_rows().'</label>':''; ?></a>
                                    <?php if($notification->num_rows() > 0) : ?>
                                    <ul class="dropdown-menu notification dropdown-menu-custom">
                                        <?php
                                            foreach ($notification->result() as $key => $val_notif) {
                                        ?>
                                        <li>
                                            <a href="<?php echo base_url('Notification/').$val_notif->id; ?>">
                                                <div class="media">
                                                    <div class="media-left">
                                                        <span class="notif-icon delivery"> <i class="<?php echo $val_notif->contentImage?>"></i> </span>
                                                    </div>
                                                    <div class="media-body">
                                                        <h4 class="media-heading"><?php echo $val_notif->content?></h4>
                                                        <h6 class="user-email"><?php echo $this->tanggal->formatDate($val_notif->createdAt); ?></h6>
                                                    </div>
                                                </div>
                                            </a>
                                        </li>
                                        <li class="divider"></li>
                                        <?php }?>
                                    </ul>
                                    <?php endif; ?>
                                </li>
                                <li class="dropdown">
                                    <a href="<?php echo base_url('Pesan') ?>" ><i class="fa fa-envelope"></i></a>
                                </li>
                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-user"></i> <span class="username-sp"><?php echo $this->session->userdata('user')->firstName?></span> <span class="caret"></span></a>
                                    <ul class="dropdown-menu">
                                        <li><a href="<?php echo base_url('Profile/') ?>"><i class="fa fa-user"></i> Profil</a></li>
                                        <li><a href="<?php echo base_url('Cart/pembayaran') ?>"><i class="fa fa-history"></i> Riwayat Transaksi <?php echo ($notification->num_rows() > 0)?'<label class="badge">'.$notification->num_rows().'</label>':''; ?> </a></li>
                                        <li><a href="<?php echo base_url('Pesan/ulasan') ?>"><i class="fa fa-comments"></i> Ulasan</a></li>
                                        <li><a href="<?php echo base_url('Pesan/Komplain') ?>"><i class="fa fa-exclamation-circle"></i> Keluhan</a></li>
                                        <li><a href="<?php echo base_url().'Login/logout'?>"><i class="fa fa-sign-out"></i> Keluar</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>
                    
                    <?php }else{?>
                    <div class="col-sm-4 col-md-3 col-xs-6 login-top-container pull-right">
                        <div class="header-top-right">
                            <a class="text-uppercase header-top-btn-right" href="<?php echo base_url().'Login'?>">MASUK</a>
                            <a class="text-uppercase header-top-btn-right" href="<?php echo base_url().'Register'?>">DAFTAR</a>
                        </div>
                    </div>
                    <?php }?>
                </div>
            </div>
        </div>
<!-- header-top-area-end -->
