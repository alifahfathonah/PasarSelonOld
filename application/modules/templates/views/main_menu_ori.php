<div class="mainmenu-area mainmenu-area-2 white-bg hidden-xs hidden-sm">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-3">
                <div class="mainmenu-left visible-lg <?php echo ($this->uri->segment(1) == NULL) ? '' : 'checkout-page';?> visible-md">
                    <div class="product-menu-title">
                        <h2>All categories<i class="fa fa-arrow-circle-down"></i></h2>
                    </div>
                    <div class="product_vmegamenu">
                        <ul>
                            <?php foreach($ProductCategory as $rowPc) : ?>
                            <li>
                                <a href="<?php echo base_url().'Product?kategori='.$rowPc['id'].''?>" class="<?php echo (count($rowPc['first']) > 0)?'hover-icon':''?>">
                                <img src="<?php echo base_url().'assets/img/menu-l/'.$rowPc['icon'].''?>" alt="" />
                                <?php echo ucwords($rowPc['name'])?></a>
                                <?php if(count($rowPc['first']) > 0) : ?>
                                <div class="vmegamenu">
                                    <?php foreach($rowPc['first'] as $rowSub) : ?>
                                    <span>

                                            <a href="<?php echo base_url().'Product?kategori='.$rowSub['id'].''?>" class="<?php echo (count($rowSub['second']) > 0)?'vgema-title':''?>"><?php echo $rowSub['name']?></a>
                                            <?php foreach($rowSub['second'] as $rowSubProduct) :?>
                                            <a href="<?php echo base_url().'Product?kategori='.$rowSubProduct['id'].''?>"><?php echo $rowSubProduct['name']?></a>
                                            <?php endforeach;?>
                                    </span>
                                    <?php endforeach; ?>

                                </div>
                                <?php endif; ?>
                            </li>
                        <?php endforeach; ?>
                            <li>
                                <a href="<?php echo base_url().'Product/'?>"><img src="<?php echo base_url()?>assets/img/menu-l/1.png" alt="" />View All Product</a>
                            </li>

                            
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-9 col-md-9">
                <div class="mainmenu">
                    <br>

                    <marquee>

                    </marquee>
                </div>
            </div>
        </div>
    </div>
</div>

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