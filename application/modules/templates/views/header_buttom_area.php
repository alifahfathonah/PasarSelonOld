<div class="header-bottom-area header-bottom-area-2 white-bg ptb-20">
    <div class="container">
        <div class="row">
		<div class="header-bot_inner_wthreeinfo_header_mid">
			<!-- header-bot-->
			<div class="col-md-4 logo_agile">
				<h1>
					<a href="<?php echo base_url() ?>">
						<span>H</span>alal
						<span>S</span>hopping <img src=""<?php echo base_url() ?>"><img src="<?php echo base_url() ?>assets/img/logo_2.png" alt="" widht=10px>
					</a>
				</h1>
			</div>
			<div class="col-md-8 header">
				
				<ul>
					<li>
						<a class="play-icon popup-with-zoom-anim" href="#small-dialog1">
							<span class="fa fa-map-marker" aria-hidden="true" src="<?php echo base_url() ?> assets/gis/index.html"></span> Shop Locator</a>
					</li>
					<li>
						<a href="#" data-toggle="modal" data-target="#myModal1">
							<span class="fa fa-truck" aria-hidden="true"></span>Track Order</a>
					</li>
					<li>
						<span class="fa fa-phone" aria-hidden="true"></span> 001 234 5678
					</li>
					<li>
						<a href="#" data-toggle="modal" data-target="#myModal1">
							<span class="fa fa-unlock-alt" aria-hidden="true"></span> Sign In </a>
					</li>
					<li>
						<a href="#" data-toggle="modal" data-target="#myModal2">
							<span class="fa fa-pencil-square-o" aria-hidden="true"></span> Sign Up </a>
					</li>
				</ul>
			<!-- header lists -->
			<div>
			<div>
            <div>
            <div class="col-lg-9 col-md-10 col-xs-9">
                <div class="header-bottom-middle">
                    <div class="search-box">
                        <form action="<?php echo base_url('Product/index'); ?>" method="get" id="form-search-category">
                            <select name="kategori" id="select">
                                <option value="">Semua Kategori</option>
                                <?php
                                    if(is_object($ProductCategory)):
                                        foreach ($ProductCategory->result as $rowPc) {
                                            echo '<option value="' . $rowPc->id . '"><strong>' . $rowPc->name . '</strong></option>';
                                        }
                                    endif;
                                ?>
                            </select>
                            <input name="keyword" type="text" placeholder="" />
                            <button type="submit" id="search"><i class="fa fa-search"></i></button>
                        </form>
					</div>
					<!--/.search-box -->
				<!-- /.header-bottom-middle -->
			</div>
                <div class="header-bottom-right hide768">
                    <div class="left-cart">
                        <div class="header-compire">
                            <a href="<?php echo base_url('Product/LastSeen'); ?>" data-toggle="tooltip" title="Last Seen"><i class="fa fa-refresh"></i>&nbsp;<span class="visible-lg">Last Seen</span> <?php echo count($this->session->userdata('last_seen')); ?> </a>
                        </div>
                    </div>
                    <div class="shop-cart-area shop-cart-area-2">
                        <div class="top-cart-wrapper">
                            <div id="block-cart" class="block-cart">
                                <div class="top-cart-title top-cart-title-2">
                                    <a href="#">
                                        <span class="title-cart visible-lg visible-md">my cart</span><br>
                                        <span id="total-item" class="count-item visible-lg visible-md">
                                            <?php
                                            if (isset($this->session->userdata('user')->id)) {
                                                echo $this->templates_model->cart_total();
                                            } else {
                                                $sess_cart = $this->session->userdata('sess_cart');
                                                if (isset($sess_cart)) {
                                                    echo count($sess_cart);
                                                } else {
                                                    echo '0';
                                                }
                                            }
                                            ?> 
                                            item(s)</span>
                                        <?php
                                        //$jmlCart = array_sum(array_column($cartList->result, ));
                                        $jmlCartSession = 0;
                                        $sess = $this->session->userdata('sess_cart');
                                        if(count($sess) != 0) {
                                            $sess_cart_array = $this->session->userdata['sess_cart'];
//                                            echo '<script type="text/javascript">alert("belum ada session cart")</script>';
                                        }

                                        if(count($sess) != 0) {
                                            foreach ($sess as $key => $s_cart) {

                                            }
                                        }
                                        ?>
                                        <br>
                                        <span id="total-price" class="price"><?php if (isset($this->session->userdata('user')->id)) { $jmlCart = $this->templates_model->cart_total_price(); echo "Rp " . number_format($jmlCart);} ?></span>
                                    </a>
                                </div>
                                <div class="top-cart-content">
                                    <ol class="mini-products-list cart-list" style="font-size:12px">
                                        <?php
                                        $arrPriceNett = [];
                                        $cartList = $this->templates_model->getAllCart();
                                        if (isset($this->session->userdata('user')->id)) {
                                            if (isset($cartList->result)) :

                                                foreach ($cartList->result as $row) {
                                                    ?>
                                                    <li class="cart-list-li" id="cart-list_<?php echo $row->product->id; ?>">
                                                        <a class="product-image" href="<?php echo base_url('Product/detail/' . url_title($row->product->name, '-', true) . '?id=' . $row->product->id) ?>">
                                                            <img alt="" src="<?php $image = $row->product->images;
                                        echo isset($image[0]->thumbnail) ? IMG_PRODUCT . $image[0]->thumbnail : ''; ?>">
                                                        </a>
                                                        <div class="product-details" >
                                                            <p class="cartproduct-name" style="margin-top:-5px;" >
                                                                <a style="font-size:12px;line-height:5px;margin-bottom:-5px" href="<?php echo base_url('Product/detail/' . url_title($row->product->name, '-', true) . '?id=' . $row->product->id) ?>"><?php echo $row->product->name; ?></a>
                                                            </p>
                                                            <input type="hidden" id="qty_<?php echo $row->product->id; ?>" value="<?php echo $row->quantity; ?>">
                                                            <strong class="qty">Qty : <span class="qty-per-product" id="qty-nya_<?php echo $row->product->id; ?>"><?php echo $row->quantity; ?></span></strong>
                                                            <input type="hidden" class="price-per-product" id="price_<?php echo $row->product->id; ?>" value="<?php echo ceil($row->quantity * $row->product->netTotal); ?>">
                                                            <span style="font-size:12px" id="price-nya_<?php echo $row->product->id; ?>" class="sig-price">
                                                                <?php
                                                                $priceNett = $row->product->netTotal * $row->quantity;
                                                                $arrPriceNett[] = ceil($priceNett);
                                                                echo 'Rp ' . number_format(ceil($priceNett));
                                                                /* echo '<br>';
                                                                  echo "Price ".$row->product->price;
                                                                  echo '<br>';
                                                                  echo "Margin ".$row->product->price*$row->product->priceMargin/100;
                                                                  echo '<br>';
                                                                  echo "discount".$row->product->price*$row->product->discount/100; */
                                                                ?>        
                                                            </span>
                                                        </div>
                                                        <div class="pro-action">
                                                            <a class="btn delete-cart" data-session="yes" data-id="<?php echo $row->product->id; ?>" data-toggle="tooltip" title="Hapus!"><i class="fa fa-times"></i></a>
                                                        </div>
                                                    </li>
                                                <?php
                                                } else : echo '<li> Anda Belum Ada Barang Yang Dibeli </li>';
                                            endif;
                                        }
                                        else { //print_r($this->session->userdata('sess_cart')); exit;
                                            $sess_cart = $this->session->userdata('sess_cart');
                                                if (isset($sess_cart)) {
                                            foreach ($sess_cart as $row_product) {

                                                $product = $this->db->query('select * from Product where id = "' . $row_product['productId'] . '"')->row();
                                                ?>

                                                <li class="cart-list-li" id="cart-list_<?php echo $product->id; ?>">
                                                    <a class="product-image" href="<?php echo base_url('Product/detail/' . url_title($product->name, '-', true) . '?id=' . $product->id) ?>">
                                                        <img alt="" src="<?php $image = json_decode($product->images);
                                                echo isset($image[0]->thumbnail) ? IMG_PRODUCT . $image[0]->thumbnail : ''; ?>">
                                                    </a>
                                                    <div class="product-details">
                                                        <p class="cartproduct-name">
                                                            <a href="<?php echo base_url('Product/detail/' . url_title($product->name, '-', true) . '?id=' . $product->id) ?>"><?php echo $product->name; ?></a>
                                                        </p>
                                                        <input type="hidden" id="qty_<?php echo $product->id; ?>" value="<?php echo $row_product['quantity']; ?>">
                                                        <strong class="qty">Qty : <span class="qty-per-product" id="qty-nya_<?php echo $product->id; ?>"><?php echo $row_product['quantity']; ?></span></strong>
                                                        <input type="hidden" class="price-per-product" id="price_<?php echo $product->id; ?>" value="<?php echo ceil($row_product['quantity'] * ($product->price + ($product->price * $product->priceMargin / 100) - ($product->price * $product->discount / 100))); ?>">
                                                        <span id="price-nya_<?php echo $product->id; ?>" class="sig-price">
                                                            <?php
                                                            $priceAfterMargin = ($product->price + ($product->price * $product->priceMargin / 100)) * $row_product['quantity'];
                                                            $priceNett = $priceAfterMargin - ($priceAfterMargin * $product->discount / 100);
                                                            $arrPriceNett[] = ceil($priceNett);
                                                            echo 'Rp ' . number_format(ceil($priceNett));
                                                            /* echo '<br>';
                                                              echo "Price ".$row->product->price;
                                                              echo '<br>';
                                                              echo "Margin ".$row->product->price*$row->product->priceMargin/100;
                                                              echo '<br>';
                                                              echo "discount".$row->product->price*$row->product->discount/100; */
                                                            ?>        
                                                        </span>
                                                    </div>
                                                    <div class="pro-action">
                                                        <a class="btn delete-cart" data-session="no" data-id="<?php echo $product->id; ?>" data-toggle="tooltip" title="Hapus!"><i class="fa fa-trash"></i></a>
                                                    </div>
                                                </li>  
                                                <?php } } else {
                                                    echo '<li class="li-belum-ada"> Anda Belum Ada Barang Yang Dibeli </li>';
                                                }
                                    } ?>
                                    </ol>
                                        <div class="top-subtotal">
                                            Subtotal: <span id="sub-total-price" class="sig-price"><?php echo "Rp " . number_format(array_sum($arrPriceNett)); ?></span>
                                        </div>
<?php if (isset($this->session->userdata('user')->id)) { ?>
                                        <div class="cart-actions">
                                            <button onclick="window.location.href = '<?php echo base_url('Cart/Checkout') ?>'"><span>Checkout</span></button>
                                        </div>
<?php } else { ?>
                                        <div class="cart-actions">
                                            <button onclick="window.location.href = '<?php echo base_url('Login/Login/index/cart') ?>'"><span>Checkout</span></button>
                                        </div>
<?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
