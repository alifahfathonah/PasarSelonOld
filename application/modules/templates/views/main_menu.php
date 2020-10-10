<?php //echo '<pre>'; print_r($ProductCategory);exit; ?>
<div class="mainmenu-area mainmenu-area-2 white-bg hidden-xs">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-3">
                <div class="mainmenu-left visible-lg <?php echo ($this->uri->segment(1) == NULL) ? '' : 'checkout-page';?> visible-md">
                    <div class="product-menu-title">
                        <h2>Semua kategori<i class="fa fa-arrow-circle-down"></i></h2>
                    </div>
                    <div class="product_vmegamenu">
                        <ul>
                            <?php if(count($ProductCategory)>0) { foreach($ProductCategory->result as $rowPc) : ?>
                            <li>
                                <a href="<?php echo base_url().'Product?kategori='.$rowPc->id; ?>" class="a-parent <?php echo (count($rowPc->children) > 0)?'hover-icon':''?>">
                                <img src="<?php echo IMG_ICON.$rowPc->icon ?>" alt="" width="30px" style="margin: 0 5px 0 5px;"/>
                                <?php echo ucwords($rowPc->name)?></a>
                                <?php if(count($rowPc->children) > 0) : ?>
                                <div class="vmegamenu">
                                    <?php foreach($rowPc->children as $rowSub) : ?>
                                    <span>

                                            <a href="<?php echo base_url().'Product?kategori='.$rowSub->id; ?>" class="vgema-title"><?php echo $rowSub->name?></a>
                                            <?php foreach($rowSub->children as $rowSubProduct) :?>
                                            <a href="<?php echo base_url().'Product?kategori='.$rowSubProduct->id; ?>"><?php echo $rowSubProduct->name?></a>
                                            <?php endforeach;?>
                                    </span>
                                    <?php endforeach; ?>

                                </div>
                                <?php endif; ?>
                            </li>
                        <?php endforeach;} ?>
                            <li>
                                <a href="#" class="a-parent">
                                <img src="#" alt="" width="30px" style="margin: 0 5px 0 5px;"/>
                                Nama Pasar
                                <div class="vmegamenu">
                                    <span></span>
                                </div>
                            </li>
                            <li>
                                <a href="<?php echo base_url().'Product/'?>"><img width="30px" src="<?php echo base_url()?>assets/img/menu-l/1.png" alt="View All Product" style="margin: 0 5px 0 5px;" />View All Product</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-9 col-md-9">
                <div class="mainmenu">
                    <nav>
                        <ul>
                            <?php foreach($greymenu as $navbar) { ?>
                            <li><a href="<?php echo $navbar->url; ?>"><?php echo $navbar->name; ?></a></li>
                            <?php } ?>
                        </ul>
                    </nav>
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
                            <?php if(is_object($ProductCategory)):?>
					  <?php	foreach($ProductCategory->result as $rowPc) : ?>
							    <li class="active"><a class="<?php echo (count($rowPc->children) > 0)?'hover-icon':''?>" href="<?php echo base_url().'Product?kategori='.$rowPc->id; ?>"><?php echo ucwords($rowPc->name)?></a>
								<ul>
								<?php foreach($rowPc->children as $rowSub) : ?>
								    <li><a href="<?php echo base_url().'Product?kategori='.$rowSub->id; ?>"><?php echo $rowSub->name?></a></li>
								<?php endforeach; ?>
								</ul>
							    </li>
					<?php endforeach;?>
				 <?php endif; ?>
                            <li>
                                <a href="<?php echo base_url().'Product/'?>">View All Product</a>
                            </li>
                            <li>Nama Pasar</li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>
