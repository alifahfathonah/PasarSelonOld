<!-- breadcrumb start -->
<div class="breadcrumb-area">
    <div class="container">
        <!-- <ol class="breadcrumb">
            <li><a href="index.html"><i class="fa fa-home"></i> Home</a></li>
            <li class="active">Login</li>
        </ol> -->
        <?php echo $breadcrumbs ?>
    </div>
</div>
<!-- breadcrumb end -->
<!-- account area start -->
<div class="account-area pt-30 pb-30 log">
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-lg-6 col-sm-6 col-sm-offset-3">
                <?php if($this->session->flashdata('failed') != '' ) { ?>
                <div class="alert alert-danger alert-dismissible fade in" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <h4><?php echo $this->session->flashdata('failed'); ?></h4>
                </div>
                <?php } ?>
                <br/>   <br/>
                <?php if($info=='cart' && count($this->session->userdata('sess_cart')>0)) { ?>
                <div class="alert alert-info alert-dismissible fade in" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <h4>Anda Harus Login Terlebih Dahulu Sebelum <i>Checkout</i></h4>
                </div>
                <?php } ?>
                <div class="account-info">
                    <div class="panel panel-default panel-login">
                        <div class="panel-heading">
                            <h3 class="panel-title">Login</h3>
                        </div>
                        <div class="panel-body">
                            <?php $attributes = array('id' => 'loginForm', 'class' => 'clearfix'); ?>
                                    <?php
                                    $sess = $this->session->userdata('sess_cart');
                                    if(count($sess)>0) {
                                        echo form_open('Login/processLoginCart', $attributes); 
                                    }
                                    else {
                                      echo form_open('Login/processLogin', $attributes); 
                                    }
                                    ?>
                            <form>
                                <br>
                                <div class="form-fields">
                                    <div class="form-group">
                                        <label for="email">
                                            Nama Pengguna Email 
                                            <span class="required">*</span>
                                        </label>
                                        <input id="username" type="text" name="username" value="<?php echo set_value('username')?>" placeholder="Masukan Username Atau Email Anda" tabindex="1">
                                        <?php echo form_error('username'); ?>
                                    </div>
                                    <div class="form-group">
                                        <label for="password">
                                            Kata Sandi
                                            <span class="required">*</span>
                                        </label>
                                        <input id="password" type="password" name="password" value="<?php echo set_value('password')?>" placeholder="Masukan Password Anda" tabindex="2">
                                        <?php echo form_error('password'); ?>
                                    </div>
                                </div>
                                <div class="form-action">
                                    <a href="<?php echo base_url('Login/lupa_password') ?>">Lupa Kata Sandi(?)</a>
                                    <button class="btn pull-right btn-login" type="submit" tabindex="3">Masuk</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

