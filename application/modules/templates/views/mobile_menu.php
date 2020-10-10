<?php
if(in_array($this->uri->segment(1), array('Login','Register'))){
    $checkout = 'checkout-page';
}
//$ProductCategory = array();
$ProductCategory = $this->Layout_model->getAllProductCategory();
//echo '<pre>'; print_r($ProductCategory);die;
?>
<div class="mobile-menu-area hidden-md hidden-lg">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="mobile-menu">
                    <nav id="mobile-menu">
                        <ul>
                            <?php foreach($ProductCategory as $rowPc) : ?>
                            <li class="active"><a class="<?php echo (count($rowPc['first']) > 0)?'hover-icon':''?>" href="<?php echo base_url('Product'); ?>"><?php echo ucwords($rowPc['name'])?></a>
                                <ul>
                                <?php foreach($rowPc['first'] as $rowSub) : ?>
                                    <li><a href="<?php echo base_url('Product'); ?>"><?php echo $rowSub['name']?></a></li>
                                <?php endforeach; ?>
                                    <li>
                                        <a href="<?php echo base_url().'Product/'?>"><img width="30px" src="<?php echo base_url()?>assets/img/menu-l/1.png" alt="View All Product" style="margin: 0 5px 0 5px;" />View All Product</a>
                                    </li>
                                </ul>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>