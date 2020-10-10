<!-- breadcrumb start -->
<div class="breadcrumb-area">
    <div class="container">
        <!-- <ol class="breadcrumb">
            <li><a href="index.html"><i class="fa fa-home"></i> Home</a></li>
            <li class="active">Login</li>
        </ol> -->
        <?php echo $breadcrumbs?>
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
                <div class="account-info">
                    <div class="panel panel-default panel-login">
                        <div class="panel-heading">
                            <h3 class="panel-title">Reset Password</h3>
                        </div>
                        <div class="panel-body">
                            <?php $attributes = array('id' => 'loginForm', 'class' => 'clearfix'); ?>
                            <?php echo form_open('Login/processLogin', $attributes); ?>
                            <div class="form-fields">
                                <br>
                                <div class="form-group">
                                    <label for="reset_password">
                                        Silahkan reset password anda dengan yang baru
                                        <span class="required">*</span>
                                    </label>
                                    <input id="reset_password" type="text" name="newPassword" placeholder="Password baru">
                                    <?php echo form_error('username'); ?>
                                </div>
                            </div>
                            <div class="form-action">
                                <button class="btn pull-right btn-login" type="submit">Reset Password</button>
                            </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

