<script>
function myFunction(keys, merchant_id, cart_id) {
    var qty = document.getElementById('qty_'+keys+'');

    // Select your input element.
//    var number = document.getElementsByClassName('qty_in');

    // Listen for input event on numInput.
    qty.onkeydown = function(e) {
        if (!((e.keyCode > 95 && e.keyCode < 106)
            || (e.keyCode > 47 && e.keyCode < 58)
            || e.keyCode == 8)) {
            return false;
        }
    };

    var product_weight = document.getElementById('product_weight_'+keys+''); 
    var price = document.getElementById('price_'+keys+'').value; 
    var totalPrice = document.getElementById('totalPrice_'+keys+''); 
    var totalPriceMerchant = document.getElementById('totalPriceMerchant_'+merchant_id+'').value; 

    

    if(qty != null){
      qty = qty.value?qty.value:0;
    }

    if(product_weight != null){
      product_weight = product_weight.value?product_weight.value:0;
    }

    if(totalPrice != null){
      totalPrice = totalPrice.value?totalPrice.value:0;
    }

    total_weight = (qty * product_weight) / 1000;
    total_price = qty * price;
    total_price_merchant = totalPriceMerchant + total_price;


    /*total weight product*/
    if(total_weight != null){
      document.getElementById('weight_'+keys+'').value = total_weight;
      document.getElementById('product_weight_Html'+keys+'').innerHTML = total_weight+' Kg';
    }

    /*total price product*/
    if(total_price != null){
      document.getElementById('totalPrice_'+keys+'').value = total_price;
      document.getElementById('totalPrice_Html'+keys+'').innerHTML = 'IDR '+total_price.format();
    }

    total_in_merchant = total_sum_parent('subTotalInMerchant'+merchant_id);

    /*total price merchant*/
    if(total_in_merchant != null){
      document.getElementById('totalPriceMerchant_'+merchant_id+'').value = total_in_merchant;
      document.getElementById('htmlPriceMerchant_'+merchant_id+'').innerHTML = 'Sub Total : IDR '+ total_in_merchant.format();
    }

    total_all_merchant = total_sum_parent('totalPriceMerchantAll');
    document.getElementById('AllTotalPrice').innerHTML = 'IDR '+total_all_merchant.format();
    //document.getElementById('totalCart').innerHTML = ''+total_sum_parent('number_format')+'';

    $.ajax({
            url : 'UpdateCart',
            type: "POST",
            data: {qty:qty, ID:cart_id},
            dataType: "JSON",
            success: function(data)
            { 
                return false;
            }
        });


}

function total_sum_parent(classname)

 {
 var items = document.getElementsByClassName(""+classname+"");
    var itemCount = items.length;
    var total = 0;
    for(var i = 0; i < itemCount; i++)
    {
        total = total +  parseInt(items[i].value);
    }

    //document.getElementById(classname).value = total;
    return total;
 }

 $(function() {
     $('#form_checkout').on('submit', function(e) {
         e.preventDefault();
         $('#proceed_checkout').button('loading');

         $.ajax({
             url : '<?php echo base_url('Cart/Checkout/update'); ?>',
             type: "POST",
             data: $('#form_checkout').serialize(),
             dataType: "JSON",
             headers: {'Content-Type': 'application/x-www-form-urlencoded'},
             processData: false,
             success: function (data) {
                 if(data.count > 0){
                     $('#proceed_checkout').button('reset');
                     window.location.href = '<?php echo base_url().'Cart/Checkout/process'?>';
                 }else{
                     alert('Error sent data to API');
                 }
                 console.log(data);
             },
             error: function(data) {
                 console.log(data);
             }
         });

         $('#proceed_checkout').button('reset');
     })
 })

</script>

<!-- breadcrumb start -->
<div class="breadcrumb-area">
    <div class="container">
        <?php echo $breadcrumbs?>
    </div>
</div>
<!-- breadcrumb end -->

<!-- cart-main-area start -->
<div class="cart-main-area pt-0">
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <?php if(isset($allCartByMerchant->result)) { ?>
                <form method="post" id="form_checkout">
                    <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                        <?php
                            $sum_total = [];
                            $isData = count($allCartByMerchant);
                            /*echo '<pre>';print_r($allCartByMerchant);die;*/
                            foreach($allCartByMerchant->result->carts as $merc) { 
                                $priceNett = $merc->totalPrice + $merc->totalMargin - $merc->totalDiscount;
                                $sum_total[] = $priceNett;
                        ?>
                        <div class="panel panel-default">
                            <div class="panel-heading" role="tab" id="headingOne">
                                <h4 class="panel-title pro-d-title-baru">
                                    <a role="button" data-toggle="collapse" data-parent="#accordion" href="#<?php echo $merc->merchantId?>" aria-expanded="true" aria-controls="<?php echo $merc->merchantId?>">Merchant Name : <?php echo $merc->merchant->name; ?>

                                        <input type="hidden" name="totalPriceMerchant_<?php echo $merc->merchantId?>" class="totalPriceMerchantAll" id="totalPriceMerchant_<?php echo $merc->merchantId?>" value="<?php echo $merc->netTotal; ?>"  />

                                        <span class="pull-right" class="number_format" id="htmlPriceMerchant_<?php echo $merc->merchantId?>"> Sub Total : <?php echo 'Rp &nbsp;'.number_format($merc->netTotal)?></span>
                                    </a>
                                </h4>
                            </div>
                            <div id="<?php echo $merc->merchantId?>" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                                <div class="panel-body">
                                    <div class="table-content table-responsive">

                                        <table class="table table-bordered">
                                            <tr>
                                                <td>No</td>
                                                <td>Image</td>
                                                <td>Nama Product</td>
                                                <td>Diskon</td>
                                                <td>Quantity</td>
                                                <td>Notes</td>
                                                <td>Berat (Kg)</td>
                                                <td>Harga Total (Rp)</td>
                                                <td>Hapus</td>
                                            </tr>
                                            <tbody id="checkout-row">
                                            <?php
                                                $i=1;
                                                foreach($merc->items as $key=>$prod) { ?>
                                            <tr id="<?php echo $prod->product->id; ?>">
                                                <td class="product-no" width="10px"><?php echo $i; ?></td>
                                                <td class="product-thumbnail">
                                                    <a href="<?php echo base_url() ?>Product/detail/<?php echo url_title($prod->product->name); ?>?id=<?php echo $prod->product->id; ?>"><img src="<?php $image = $prod->product->images; echo isset($image[0]->thumbnail) ? IMG_PRODUCT.$image[0]->thumbnail:'';?>" alt="" /></a>
                                                </td>
                                                <td class="product-name"><a href="<?php echo base_url() ?>Product/detail/<?php echo url_title($prod->product->name); ?>?id=<?php echo $prod->product->id; ?>"><?php echo $prod->product->name; ?></a></td>

                                                <td class="product-price">
                                                    <span class="amount">
                                                    <?php 
                                                        //$nett = $prod->product->price;
                                                    $priceAfterMargin = ceil($prod->product->price + ($prod->product->price * $prod->product->priceMargin / 100));

                                                    $lastdigits = $priceAfterMargin % 10;

                                                    if($lastdigits > 0 && $lastdigits < 9) {
                                                        $priceAfterMargin -= $lastdigits;
                                                    }

                                                     $priceNett = $priceAfterMargin - ($priceAfterMargin * $prod->product->discount/100);

                                                     $nett = ($prod->product->price + ($prod->product->price * $prod->product->priceMargin/100) - ($prod->product->price * ($prod->product->discount/100)));
                                                        echo 'Rp '.number_format(ceil($priceNett));
                                                    ?>
                                                    
                                                    </span>
                                                    <br>
                                                    <?php if($prod->product->discount > 0) { ?>
                                                        <span style="color: red;"><?php echo 'Disc Rp '.number_format($prod->product->price * ($prod->product->discount/100)).' ('.$prod->product->discount.'%)' ?></span>
                                                    <?php } ?>
                                                <input type="hidden" name="price_<?php echo $prod->id?>" id="price_<?php echo $prod->id?>" value="<?php echo ceil($priceNett); ?>"  />
                                                </td>

                                                <td class="product-quantity">
                                                    <input min="1" max="<?php echo $prod->product->stock; ?>" type="number" class="qty_in" name="qty[]" id="qty_<?php echo $prod->id?>" value="<?php echo $prod->quantity; ?>" onKeyUp="myFunction(<?php echo "'$prod->id'"?>,<?php echo $merc->merchantId?>, <?php echo "'$prod->id'"?>)" onchange="myFunction(<?php echo "'$prod->id'"?>,<?php echo $merc->merchantId?>, <?php echo "'$prod->id'"?>)" style="text-align:center" />
                                                    <input type="hidden" name="productId[]" value="<?php echo $prod->product->id; ?>">
                                                </td>

                                                <td width="200px">
                                                    <textarea name="notes[]" placeholder="Contoh: Warna, jenis, ukuran" cols="30"><?php echo $prod->notes; ?></textarea>
                                                </td>

                                                <td class="product-subtotal">
                                                    <?php $total_weight = $prod->subtotalWeight/1000; ?> 
                                                    <input type="hidden" name="product_weight_<?php echo $prod->id?>" id="product_weight_<?php echo $prod->id?>" value="<?php echo $prod->product->weight; ?>"  />

                                                    <input type="hidden" name="weight_<?php echo $prod->id?>" id="weight_<?php echo $prod->id?>" value="<?php echo $total_weight; ?>" style="text-align:center"/>

                                                    <span id="product_weight_Html<?php echo $prod->id?>"><?php echo $total_weight.' Kg'; ?></span>

                                                </td>
                                                <td class="product-subtotal">
                                                    <?php //echo $prod->currencyCode.'&nbsp;'.number_format('') ?>
                                                    <input type="hidden" name="totalPrice" class="subTotalInMerchant<?php echo $merc->merchantId?>" id="totalPrice_<?php echo $prod->id?>" value="<?php echo $prod->netTotal; ?>" onKeyUp="myFunction(<?php echo $i?>)" style="width:150px;text-align:center" />
                                                    <span id="totalPrice_Html<?php echo $prod->id?>"><?php echo 'Rp '.number_format($prod->netTotal); ?></span>
                                                </td>
                                                <td style="color:red" class="product-remove" width="10px">
                                                    <a  href="<?php echo base_url('Cart/delete_checkout/'.$prod->product->id) ?>"><i class="fa fa-times"></i></a></td>
                                            </tr>
                                            <?php $i++;} ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php } ?>
                        <?php }else{echo "<br><p>Belum ada produk</p>";} ?>
                        <script type="text/javascript">


// Select your input element.
var numInput = document.querySelector('qty_in');

// Listen for input event on numInput.
//numInput.addEventListener('input', function(){
//    // Let's match only digits.
//    var num = this.value.match(/^\d+$/);
//    if (num === null) {
//        // If we have no match, value will be empty.
//        this.value = "";
//    }
//}, false);
                        </script>
                    </div>
                    <div class="cart-total-wrapper">
                        <?php if(isset($allCartByMerchant->result)) { ?>
                        <table>
                            <tr>
                                <td>
                                    <ul class="cart-cash pull-right">
                                        
                                        <li>
                                            <h5>Total Harga</h5>
                                            <h4 class="total-pembayaran" id="AllTotalPrice"><?php echo 'Rp &nbsp;'.number_format($allCartByMerchant->result->netTotal);?></h4>
                                        </li>
                                    </ul>
                                </td>
                            </tr>
                            <?php if($isData > 0) :?>
                            <tr>
                                <td>
                                    <div class="buttons-cart pull-left">
                                        <a href="<?php echo base_url()?>"><i class="fa fa-shopping-cart"></i> &nbsp;&nbsp;Continue Shopping</a>
                                    </div>
                                    <div class="wc-proceed-to-checkout pull-right">
                                        <button type="submit" id="proceed_checkout" data-loading-text="<i class='fa fa-spinner fa-pulse fa-fw'></i> Proceed to Checkout &nbsp;&nbsp;<i class='fa fa-chevron-right'></i>">Proceed to Checkout &nbsp;&nbsp;<i class="fa fa-chevron-right"></i></button>
                                    </div>
                                </td>
                            </tr>
                            <?php endif; ?>

                        </table>
                        <?php }else{ ?>
                            <table>
                                <tr>
                                    <td>
                                        <ul class="cart-cash pull-right">

                                            <li>
                                                <h5>Total Harga</h5>
                                                <h4 class="total-pembayaran" id="AllTotalPrice">Rp 0</h4>
                                            </li>
                                        </ul>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="buttons-cart pull-left">
                                            <a href="<?php echo base_url()?>"><i class="fa fa-shopping-cart"></i> &nbsp;&nbsp;Continue Shopping</a>
                                        </div>
                                        <div class="wc-proceed-to-checkout pull-right">
                                            <button type="submit" class="btn disabled" data-loading-text="<i class='fa fa-spinner fa-pulse fa-fw'></i> Proceed to Checkout &nbsp;&nbsp;<i class='fa fa-chevron-right'></i>" id="proceed_checkout">Proceed to Checkout &nbsp;&nbsp;<i class="fa fa-chevron-right"></i></button>
                                        </div>
                                    </td>
                                </tr>

                            </table>
                        <?php } ?>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
<!-- cart-main-area end -->