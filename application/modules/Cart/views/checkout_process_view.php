<?php ini_set('max_execution_time', 3000); ?>
<?php
    $bankSource = $this->templates_model->bankNameOnlyValue();
?>
<script src="<?php echo base_url() ?>assets/js/jquery-2.1.4.min.js"></script>
<script type="text/javascript">

function add_address()
{
  save_method = 'add';
  $('#form_address')[0].reset(); // reset form on modals
  $('#modal_form_add_address').modal('show'); // show bootstrap modal
  $('.modal-title').text('Tambah Alamat'); // Set Title to Bootstrap modal title
}

function reload_address()
{
 $.getJSON("<?php echo site_url('Cart/Checkout/getAddressList') ?>", '', function (data) {
    $('#addressDefaultId option').remove();
                   $('<option value="">- Silahkan Pilih -</option>').appendTo($('#addressDefaultId'));
                    $.each(data, function (i, o) {
                        $('<option value="' + o.id + '">'+ o.name + ' - ' + o.address.substr(0,30) + '</option>').appendTo($('#addressDefaultId'));
                    });
                });
}

function save()
{
    var data = {
        addressId : $('#addressId').val(),
        name : $('#name').val(),
        recipientName : $('#recipientName').val(),
        recipientPhone : $('#recipientPhone').val(),
        address : $('#address').val(),
        countryId : $('#countryId').val(),
        provinceId : $('#provinceId').val(),
        cityId : $('#cityId').val(),
        districtId : $('#districtId').val(),
        zipCode : $('#kodePos').val()
    };

   // ajax adding data to database
    $.ajax({
        url : '<?php echo base_url('Profile/addAddress') ?>',
        type: "POST",
        data: $.param(data),
        dataType: "JSON",
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        processData: false,
        success: function(data, textStatus, jqXHR, responseText)
        {
            console.log(data);
            $('#form_address')[0].reset();
            $("#modal_form").modal("hide");
            $("#modal_success").modal("show");
            $('#modal_form_add_address').modal('hide');

            reload_address();

        },
        error: function (data, jqXHR, textStatus, errorThrown)
        {
            alert(errorThrown);
            alert(errorThrown);
            $("#modal_error").modal("show");
            $("#modal_form").modal("hide");
            $("#error-content").html(errorThrown);
        }
    });
}

function get_shipping_cost(addressId='', courierId='', packageId=''){
    api_url = "<?php echo base_url('Cart/Checkout/checkShippingCost'); ?>";

    var VarAddressId = (addressId != '') ? addressId : $('#shippingAddressId').val();
    var VarCourierId = (courierId != '') ? courierId : $('#courierId').val();
    var VarPackageId = (packageId != '') ? packageId : $('#courierPackageId').val();
    
    var data = {
        shippingAddressId : VarAddressId,
        courierId : VarCourierId,
        courierPackageId : VarPackageId
    };

    $.ajax({
        url : api_url,
        type: "POST",
        data: $.param(data),
        dataType: "JSON",
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        processData: false,
        success: function (data) {
            console.log(data);
            var totalPrice = document.getElementById('totalPrice').value; 
            var AlltotalPrice = parseInt(totalPrice) + parseInt(data.result);
            var message = '';
            if(data.message !== 'Success'){
                message = '<small>('+data.message+')</small>';
            }

            $('#totalShippingCost').val(data.result);
            $('#totalPay').val(AlltotalPrice);
            $('#totalPayDiv').html('Rp. '+AlltotalPrice.format());
            $('.totalShippingCostdiv').html('<div style="font-size:20px"><i class="fa fa-truck"></i> Biaya Pengiriman : Rp. '+data.result.format()+'</div> <span style="color:red"><i>'+message+'</i></span> ');
            $('.totalShippingCostdiv2').html('Rp. '+data.result.format()+'');

            /*var orders = data.data;
            $.each(orders.orders, function (i, o) {
                $('#shippingCostByMerchant'+o.merchantId).val(o.subtotalShippingCost);
            });*/
            if(data.message === "Success") {
                if(data.data.warns.length !== 0) {
                    for(i = 0; i < data.data.warns.length; i++) {
                        if(data.data.warns[i].errorCode === "JNE_TARIFF_NOT_FOUND") {
                            alert('Merchant '+data.data.warns[i].merchantName+' belum tersedia metode pengiriman JNE. Hapus dahulu order dari merchant ini untuk melanjutkan checkout!');
                            $('#merchant-'+data.data.warns[i].merchantId).css({"background-color":"khaki"});
                            $('#merchant-'+data.data.warns[i].merchantId+' .ongkir-message').html(data.data.warns[i].errorMessage);
                        }
                    }
                }
            }
            $('#btnReloadApi').button('reset');
        }
    });

}

    document.addEventListener('DOMContentLoaded', function () {


        $('select[name="addressDefaultId"]').change(function () {
            $('#textAddressDiv').show();
            if ($(this).val()) {
                $('#btnReloadApi').button('loading');
                $.getJSON("<?php echo site_url('templates/References/getDetailCustomerAddress') ?>/" + $(this).val(), '', function (data) {
                   $('#shippingAddressId').val(data.id);
                   $('#textAddress').html('<i class="fa fa-map-marker"></i> '+data.name+', '+data.address+' , '+data.districtName+' , '+data.cityName+' , '+data.provinceName+' ' );
                });
                if($(this).val() === '10c9f08d-3645-11e7-a8f3-001c429bf617') {
                    $('#courierId option').hide();
                    $('#courier-1').show();
                    $('#courier-1').prop('selected', true);
                }else{
                    $('#courierId option').show();
                    $('#courier-1').hide();
                    $("#courier-2").prop('selected', true);
                }

                $.getJSON("<?php echo site_url('templates/References/getCourierPackageByCourier') ?>/" + $("#courierId option:checked").val(), '', function (data) {
                    $('#courierPackageId option').remove();
                    $('<option value="">-Silahkan Pilih-</option>').appendTo($('#ProvinceId'));
                    $.each(data, function (i, o) {
                        $('<option value="' + o.id + '">' + o.name + '</option>').appendTo($('#courierPackageId'));
                    });

                });
                get_shipping_cost();
            }


        });

        $('select[name="courierPackageId"]').change(function () {
            $('#btnReloadApi').button('loading');
            $('#textAddressDiv').show();
            get_shipping_cost();
        });

        $('#btnReloadApi').click(function (e) {
            e.preventDefault();
            $('#btnReloadApi').button('loading');
            get_shipping_cost();
        });

        $('select[name="courierId"]').change(function () {
            
            if ($(this).val()) {
                $.getJSON("<?php echo site_url('templates/References/getCourierPackageByCourier') ?>/" + $(this).val(), '', function (data) {
                    $('#courierPackageId option').remove();
                    $('<option value="">-Silahkan Pilih-</option>').appendTo($('#ProvinceId'));
                    $.each(data, function (i, o) {
                        $('<option value="' + o.id + '">' + o.name + '</option>').appendTo($('#courierPackageId'));
                    });

                });
            } else {
                $('#courierPackageId option').remove()
            }

            $('#btnReloadApi').button('loading');
            get_shipping_cost();

        });

        var source = jQuery.parseJSON('<?php echo $bankSource ?>');

        $( "#accountBankName" ).autocomplete({
            source: source,
            appendTo: '#autocomplete-wrapper'
        });


    });

</script>

<script type="text/javascript">
    $(function () {
        
        var source = jQuery.parseJSON('<?php echo $bankSource ?>');
//        var source = '<?php //echo $bankSource ?>//';

        $( "#bank" ).autocomplete({
            source: source,
            appendTo: '#autocomplete-wrapper'
        });

        $('#checkout-process').on('submit',function (e) {

            e.preventDefault();

            $('#checkout-loading').fadeIn();

            var form = $(this);

            form.find('#checkout-finish').button('loading');

            url = "<?php echo base_url('Cart/Checkout/checkoutFinish'); ?>";
            voucherId = $('#voucherId').val();

            var data = {
                customerId : $('#customerId').val(),
                customerName : $('#customerName').val(),
                recipientName : $('#recipientName').val(),
                recipientPhone : $('#recipientPhone').val(),
                shippingAddressId : $('#shippingAddressId').val(),
                courierPackageId : $('#courierPackageId').val(),
                courierId : $('#courierId').val(),
                voucherCode : $('#voucherCode').val(),
                isGift : false,
                notes : $('#notes').val(),
                addressType : $('#addressType').val(),
                address_default : $('#address_default').val(),
                countryId : $('#countryId').val(),
                provinceId : $('#provinceId').val(),
                cityId : $('#cityId').val(),
                districtId : $('#districtId').val()
            };
            //console.log(data);

            $.ajax({
                url : url,
                type: "POST",
                data: $.param(data),
                dataType: "JSON",
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                processData: false,
                success: function (data) {

                    var statusCode = data.statusCode;

                    form.find('#checkout-finish').button('reset');

                    if(statusCode == 400){

                        $('#checkout-loading').fadeOut();

                        if(data.code === 'PRODUCT_STOCK_EMPTY') {
                            $('#msg_error').html('Stok Habis !');
                            $('#message_error').html('<div class="alert alert-danger">Mohon maaf, barang yang anda pesan stoknya belum tersedia saat ini</div>');
                            $('#customer-modal').modal('show');
                        }else{
                            $('#msg_error').html(data.name);
                            $('#message_error').html('<div class="alert alert-danger">'+data.message+'</div>');
                            $('#customer-modal').modal('show');
                        }

                    }else{
                        form.find('#checkout-finish').button('loading');
                        form.find('#checkout-finish').button('reset');
                        window.location = '<?php echo base_url('Cart/Checkout/payment_method?invoiceId='); ?>'+data.invoiceId+'&vcid='+voucherId+'';
                        console.log(data);

                    }

                    console.log(data);
                },
                error: function(data) {
                    console.log(data);
                }
            });

        });

    });

</script>

<!-- breadcrumb start -->
<div class="breadcrumb-area">
    <div class="container">
        <?php echo $breadcrumbs ?>
    </div>
</div>
<!-- breadcrumb end -->

<?php
    $user = $this->templates_model->getUserDetail();
    $alamat = $this->templates_model->getDefaultAddress();
    $address = $this->templates_model->getCustomerAddress();
    $bank = $this->templates_model->getDefaultBank();
    $listBank = $this->templates_model->getListBank();
//    print_r($alamat);

$kimart = 'no';
$nonkimart = 'no';
foreach ($allCartByMerchant->result->carts as $merc) {
    if($merc->merchant->type == 2) {
        $kimart = 'yes';
    }
    if($merc->merchant->type == 1) {
        $nonkimart = 'yes';
    }
}
//exit;
?>
<!-- cart-main-area start -->

<div id="checkout-loading">
    <div class="loading-content">
        <img src="<?php echo base_url('assets/img/logo_2.png') ?>" alt="Logo Pasar Selon">
        <br><br>
        <i class="fa fa-cog fa-spin fa-3x fa-fw"></i>
        <br><br>
        <span class="">Pesanan Anda sedang di proses, silahkan menunggu beberapa saat . . .</span>
    </div>
</div>

<div id="customer-modal" class="modal fade bs-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 id="msg_error">Success</h3>
            </div>

            <p id="message_error"></p>

        </div>
    </div>
</div>


<div class="checkout-area">
    <div class="container container-custom">
        <div class="row">
            <form id="checkout-process" method="post">
                <table class="table table-striped" border="0" style="font-size:11.5px">
                    <tr>
                        <td colspan="5" style="padding-top:15px"><h2><i class="fa fa-user"></i> <?php echo ucwords($this->session->userdata('user')->customerName) ?></h2></td>
                    </tr>
                    <tr>
                        <td style="padding-top:15px;width:180px">*Nama Customer</td>
                        <td style="padding-top:15px" colspan="3"><?php echo $this->session->userdata('user')->customerName ?></td>
<!--                        <td rowspan="6" width="300px">-->
<!--                            <table class="table" style="font-size:11.5px;width:100%">-->
<!--                                <tr>-->
<!--                                    <td align="center" style="padding-top:15px;background-color:red;">-->
<!--                                        <b><h4 style="color:white"> <i class="fa fa-gift"></i> Masukan Kode Voucher </h4></b>-->
<!--                                    </td>-->
<!--                                </tr>-->
<!--                                <tr>-->
<!--                                    <td align="center">-->
<!--                                        <input class="form-control" type="text" name="kodeVouceher" id="voucherCode" style="height:50px;width:350px; font-size:30px; text-align:center;">-->
<!---->
<!--                                        <a href="#" id="validateVouceher" class="btn btn-primary" style="margin-top:5px"><i class="fa fa-eye"></i> Validate Voucher</a>-->
<!---->
<!--                                        <div id="failed" class="alert alert-danger left" style="margin-top:5px;margin-bottom:-2px;display:none">-->
<!--                                            <i class="fa fa-times-circle"></i> <b>Peringatan! </b> Validasi Voucher gagal dilakukan.-->
<!--                                        </div>-->
<!---->
<!--                                        <div class="success_validate" style="display:none">-->
<!--                                            <div class="alert alert-success left" style="margin-top:5px;margin-bottom:-2px">-->
<!--                                                <i class="fa fa-check-circle"></i><b> Berhasil!</b> Validasi Voucher berhasil dilakukan.-->
<!--                                            </div>-->
<!--                                            <br>-->
<!--                                            <div>-->
<!--                                                Selamat anda mendapat potongan harga senilai <br>-->
<!--                                                <h2 id="potongan_voucher"></h2>-->
<!--                                            </div>-->
<!--                                        </div>-->
<!---->
<!--                                    </td>-->
<!--                                </tr>-->
<!--                                <tr class="success_validate" style="display:none">-->
<!--                                    <td align="left">-->
<!--                                        <div style="width:15px; margin-top:-18px !important;float:left">-->
<!--                                            <input  type="checkbox" name="voucherId" id="voucherId" checked>-->
<!--                                        </div>-->
<!--                                        <div>-->
<!--                                            &nbsp;&nbsp; Gunakan voucher ini untuk mendapatkan potongan harga.-->
<!--                                        </div>-->
<!--                                    </td>-->
<!--                                </tr>-->
<!---->
<!--                                <tr>-->
<!--                                    <td align="left">-->
<!--                                        <b>Keterangan :</b> <br>-->
<!--                                        Silahkan masukan <b>KODE VOUCHER</b> anda lalu klik tombol <b>VALIDATE VOUCHER</b> untuk memvalidasi voucher anda.-->
<!--                                    </td>-->
<!--                                </tr>-->
<!---->
<!---->
<!--                            </table>-->
<!--                        </td>-->
                    </tr>
                    <tr>
                        <td style="padding-top:15px;width:180px">* Email</td>
                        <td style="padding-top:15px" colspan="3">
                            <?php echo $user->email; ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            * No Telp : <?php echo $user->phoneNo; ?>
                        </td>
                    </tr>
                    <!-- pilih alamnat pengiriman -->
                    <?php if($nonkimart == 'no' && $kimart == 'yes') { ?>

                    <?php }else{ ?>
                        <tr>
                            <td style="padding-top:15px;width:180px">* Alamat Pengiriman</td>
                            <td colspan="3">
                                <select name="addressDefaultId" class="form-control" id="addressDefaultId" required style="width:400px;float:left;">
                                    <option value="">- Silahkan Pilih -</option>
                                    <?php foreach ($listAddress->result as $address) { ?>
                                        <option value="<?php echo $address->id ?>"><?php echo ucwords($address->name).' - '.substr($address->address, 0, 30); ?></option>
                                    <?php } ?>
                                </select>
                                <?php echo form_error('District') ?>
                                <a style="margin-left:10px;margin-top:2px" title="Buat alamat baru" href="#" class="btn btn-sm btn-info" onclick="add_address()"><i class="fa fa-plus"></i></a>
                                <a style="margin-top:2px" href="#" onclick="reload_address()" title="Referesh Alamat" class="btn btn-sm btn-success"><i class="fa fa-refresh"></i></a>

                            </td>
                        </tr>
                        <!-- detail alamat pengiriman -->
                        <tr id="textAddressDiv" style="display:none">
                            <td style="padding-top:15px;width:180px">&nbsp;</td>
                            <td colspan="3">
                                <input id="shippingAddressId" type="hidden" name="shippingAddressId" value="">
                                <div class="col-sm-8">
                                    <div id="textAddress"><i class="fa fa-map-marker"></i> Kantor Pusat Telkomsel, Komplek Telkom Landmark Tower 1, Lantai 11 Jalan Jendra Gatot Subroto Kav 52 Jakarta 12710</div>
                                </div>
                            </td>
                        </tr>

                        <!-- kurir pemgiriman -->
                        <tr>
                            <td style="padding-top:15px;width:180px">*Kurir Pengiriman</td>
                            <td style="width:250px" >
<!--                                --><?php //echo '<pre>'; print_r($kurir); ?>
                                <select name="courierId" id="courierId" class="form-control" style="width:200px">
                                    <option value="0" selected> - Silahkan Pilih - </option>
                                    <?php foreach ($kurir->result as $kur) { ?>
                                        <option id="courier-<?php echo $kur->id; ?>" value="<?php echo $kur->id; ?>"><?php echo $kur->name; ?></option>
                                    <?php } ?>
                                </select>
                            </td>
                            <td style="padding-top:15px;width:180px">*Paket Pengiriman</td>
                            <td>
                                <?php echo $this->master->get_change($params = array('table' => 'CourierPackage', 'id' => 'id', 'name' => 'name', 'where' => array()), '', 'courierPackageId', 'courierPackageId', 'form-control', 'required style="width:200px;float:left" ', '') ?>
                                <?php echo form_error('CourierPackage') ?>

                            </td>

                        </tr>
                        <tr>
                            <td style="padding-top:15px;width:180px">&nbsp;</td>
                            <td>
                                <a href="#" id="btnReloadApi" data-loading-text="<i class='fa fa-spinner fa-refresh bigger-120 fa-fw'></i> Cek Ongkir" class="btn btn-sm btn-warning" style="margin-top:0px;float:left"> <i class="fa fa-refresh bigger-120" ></i> Cek Ongkir</a>
                            </td>
                            <td colspan="2" align="right">
                                <div class="totalShippingCostdiv"></div>
                            </td>
                        </tr>
                    <?php } ?>
                    <tr>
                        <td style="padding-top:15px;width:180px">Keterangan</td>
                        <td style="padding-top:15px" colspan="3">
                            <textarea id="notes" class="form-control" name="notes" cols="10"
                                          rows="3"><?php echo set_value('notes') ?></textarea>
                                <small>Anda bisa mengisikan keterangan barang yang anda pesan seperti warna, ukuran, dll.</small><?php echo form_error('notes') ?>
                        </td>
                    </tr>


                </table>

                <div class="row">
                    <div class="col-sm-12">

                        <!-- form hidden for customer -->
                        <input type="hidden" name="customerId" id="customerId" value="<?php echo $customerId ?>">
                        <input type="hidden" name="customerName" id="customerName"
                               value="<?php echo $this->session->userdata('user')->customerName ?>">

                        <!-- <div class="form-group">
                            <label class="col-sm-2" for="voucher">Kode Voucher</label>
                            <div class="col-sm-2">
                                <input id="voucherCode" class="form-control" name="voucherCode" type="text">
                            </div>
                        </div> -->

                        <hr>

                        <div class="panel-body">
                            <div class="row">
                                <div class="panel-body">
                                    <div class="table-content table-responsive">
                                        
                                        <table class="table" style="font-size:11.5px">
                                            
                                            <?php
                                            foreach ($allCartByMerchant->result->carts as $merc) {
                                                $priceNett = $merc->totalPrice - $merc->totalDiscount + $merc->totalMargin;
                                                $sum_total[] = $priceNett;
                                                ?>
                                                <!-- form hidden for merchant -->
                                                <input type="hidden" name="merchantId[]"
                                                       value="<?php echo $merc->merchantId; ?>">

                                                <tr id="merchant-<?php echo $merc->merchantId; ?>">
                                                    <td colspan="7" align="left">
                                                        <h4 style="padding-top:13px"><b><img width="20" class="produk-met" src="<?php echo base_url().'assets/img/store.png'?>" style="margin-top:-5px">  <?php echo strtoupper($merc->merchant->name); ?> </b>
                                                        <br>
                                                        </h4>
                                                        <p class="ongkir-message" style="color: red"></p>
                                                    </td>
                                                    <td class="product-thumbnail" align="right"><h4 style="padding-top:13px">
                                                            <b><?php echo "Rp." . '&nbsp;' . number_format($priceNett) ?>
                                                        </h4></b>
                                                        <input type="hidden" name="totalPriceMerchant[]"
                                                               value="<?php echo $merc->totalPrice ?>">
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <th style="width:10px">No</th>
                                                    <th>Image</th>
                                                    <th>Nama Product</th>
                                                    <th>Harga Satuan</th>
                                                    <th>Quantity</th>
                                                    <th>Berat</th>
                                                    <th>Notes</th>
                                                    <th>Harga Total</th>
                                                </tr>


                                                <?php
                                                //echo '<pre>';print_r($merc->items);
                                                $i = 1;
                                                foreach ($merc->items as $prod) {
                                                    $img = $prod->product->images;
                                                    $path_img = (count($img) > 0) ? IMG_PRODUCT.$img[0]->thumbnail : '' ;
                                                    $discount = ($prod->product->discount > 0) ? ($prod->subtotalPrice * $prod->product->discount) / 100 : 0;
                                                    $total_price_after_discount = $prod->subtotalPrice - $discount;
                                                    $sum_total_weight[] = round($prod->subtotalWeight /1000, 2) ;
                                                    ?>



                                                    <tr>
                                                        <td class="product-thumbnail"><?php echo $i; ?></td>
                                                        <td class="product-thumbnail">
                                                            <input type="hidden"
                                                                   name="cartId<?php echo $merc->merchantId ?>[]"
                                                                   value="<?php echo $prod->id ?>">
                                                            <input type="hidden"
                                                                   name="ProductId<?php echo $merc->merchantId ?>[]"
                                                                   value="<?php echo $prod->product->id ?>">
                                                            <a href="<?php echo base_url() ?>Product/detail/<?php echo url_title($prod->product->name); ?>?id=<?php echo $prod->product->id; ?>">

                                                            <img src="<?php echo $path_img; ?>"
                                                                    alt="" width="50px"/></a>
                                                        </td>
                                                        <td class="product-name" align="left">
                                                            <a href="<?php echo base_url() ?>Product/detail/<?php echo url_title($prod->product->name); ?>?id=<?php echo $prod->product->id; ?>"
                                                               style="color:green;margin-left:-2px"><?php echo $prod->product->name; ?></a><br>
                                                            SKU : <?php echo ($prod->product->sku) ? $prod->product->sku : '-'; ?><br>
                                                            Kategori : <?php echo $prod->product->productCategoryName; ?><br>
                                                            Kondisi : <?php echo $prod->product->condition; ?><br>
                                                        </td>

                                                        <td class="product-price">
                                                        
                                                            <span
                                                                class="amount"><?php 
                                                            $nett = $prod->subtotalPrice - $prod->subtotalDiscount + $prod->subtotalMargin;
                                                             echo 'Rp '.number_format($prod->product->netTotal);
                                                        ?></span><br>
                                                            <?php if($prod->product->discount > 0) { ?>
                                                                <span style="color: red;"><?php echo 'Disc Rp '.number_format($prod->product->price * ($prod->product->discount/100)).' ('.$prod->product->discount.'%)' ?></span>
                                                            <?php } ?>
                                                        </td>

                                                        <td class="product-quantity">
                                                            <input type="hidden"
                                                                   name="qtyProduct<?php echo $merc->merchantId ?>[]"
                                                                   value="<?php echo $prod->quantity; ?>"/>
                                                            <?php echo $prod->quantity; ?>
                                                        </td>
                                                        <td class="product-quantity">
                                                            <input type="hidden"
                                                                   name="totalWeightProduct<?php echo $merc->merchantId ?>[]"
                                                                   value="<?php echo round($prod->subtotalWeight /1000, 2) ; ?>">
                                                            <?php echo round($prod->subtotalWeight /1000, 2) ; ?> KG
                                                        </td>

                                                        <td class="product-name" align="left"><?php echo $prod->notes; ?>
                                                        </td>

                                                        <td class="product-price" align="right"><?php echo 'Rp. ' . number_format($nett) ?>
                                                            <input type="hidden"
                                                                   name="totalPriceProduct<?php echo $merc->merchantId ?>[]"
                                                                   value="<?php echo $nett; ?>">
                                                        </td>
                                                    </tr>
                                                    <?php $i++;
                                                } ?>
                                                <?php if($merc->merchant->type == 2) { ?>
                                                    <tr>
                                                        <td colspan="8">
                                                            <div class="panel panel-warning">
                                                                <div class="panel-heading">
                                                                    <h3 class="panel-title" style="color: #333 !important;">Alamat Pengiriman</h3>
                                                                </div>
                                                                <div class="panel-body">
                                                                    <p><i class="fa fa-map-marker"></i> KiMart Telkomsel Smart Office, Jl. Jend. Gatot Subroto Kav. 52, RT. 6 / RW. 1, Kuningan Barat , Mampang Prapatan , Jakarta Selatan , Dki Jakarta</p>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                            <?php } ?>
                                            <tr>
                                                <td colspan="7" align="right">Total Pembelian</td>
                                                <td align="right"><h4><b><?php $totalPay = array_sum($sum_total); echo 'Rp. ' . number_format($totalPay) ?></b></h4>
                                                    <input id="totalPrice" type="hidden" name="totalPrice"
                                                           value="<?php echo $allCartByMerchant->result->netTotal ?>">
                                                    <input type="hidden" name="totalWeight"
                                                           value="<?php echo $allCartByMerchant->result->totalWeight ?>"></td>
                                            </tr>
                                            <tr>
                                                <td colspan="7" align="right">Biaya Pengiriman</td>
                                                <td align="right"><h4><div class="totalShippingCostdiv2"><?php echo 'Rp. ' . number_format($allCartByMerchant->result->totalShippingCost) ?></div></h4>
                                                    <input type="hidden" name="totalShippingCost" id="totalShippingCost" value="<?php echo $allCartByMerchant->result->totalShippingCost ?>"></td>
                                            </tr>
<!--                                            <tr>-->
<!--                                                <td colspan="7" align="right">Potongan Voucher</td>-->
<!--                                                <td align="right"><h4><div id="totalVoucher">0</div></h4>-->
<!--                                                    <input type="hidden" name="totalVoucher" id="totalVoucherInput" value="0"></td>-->
<!--                                            </tr>-->
                                            <!-- <tr>
                                                <td colspan="8" align="right">Total Diskon</td>
                                                <td align="right"><h4><?php echo 'Rp. ' . number_format($allCartByMerchant->result->totalDiscount) ?></h4>
                                                    <input type="hidden" name="totalDiscount" value="<?php echo $allCartByMerchant->result->totalDiscount ?>"></td>
                                            </tr> -->
                                            <tr>
                                                <td colspan="7" align="right">Total Pembayaran</td>
                                                <td align="right"><h4 class="total-pembayaran">
                                                        <?php
                                                            echo '<div id="totalPayDiv">Rp. '.number_format($totalPay).'</div>'
                                                        ?>
                                                    </h4>
                                                    <input class="totalPembayaran" id="totalPay" type="hidden" name="totalPembayaran"
                                                           value="<?php echo $totalPay ?>">
                                                           <input type="hidden" name="totalMargin" value="<?php echo $allCartByMerchant->result->totalMargin; ?>">
                                                           <input type="hidden" name="totalVoucher" value="<?php echo $allCartByMerchant->result->totalVoucher ?>">
                                                </td>
                                            </tr>
                                        </table>


                                    </div>
                                </div>
                            </div>
                            <div class="cart-total-wrapper" style="margin-top:-55px">
                                <table>
                                    <tr>
                                        <td>
                                            <div class="buttons-cart pull-left">
                                                <a href="<?php echo base_url('Cart/Checkout') ?>"><i
                                                        class="fa fa-chevron-left"></i> &nbsp;&nbsp; Back</a>
                                            </div>
                                            <div class="wc-proceed-to-checkout pull-right">
                                                <button type="submit" id="checkout-finish">Proceed to Finish &nbsp;&nbsp;<i
                                                        class="fa fa-chevron-right"></i></button>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>




<!-- Bootstrap modal-->
<div class="modal fade" id="modal_form_add_address" role="dialog">
<div class="modal-dialog">
<div class="modal-content">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h3 class="modal-title">Alamat</h3>
  </div>
  <div class="modal-body form">
    <form action="#" id="form_address" class="form-horizontal" method="post">
      <input type="hidden" value="" name="id"/> 
      <div class="form-body">
        <div class="form-group">
            <label class="col-sm-3" for="City">* Nama Alamat</label>
            <div class="col-sm-8">
                <input type="text" class="form-control" name="name" id="name" value="<?php echo set_value('name') ?>"><?php echo form_error('name') ?>
                <input type="hidden" class="form-control" name="addressId" id="addressId" value="<?php echo set_value('addressId') ?>"><?php echo form_error('addressId') ?>
                <small><i>Simpan alamat sebagai contoh : Rumah Pacar, Rumah Orang Tua, Dll</i></small>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3" for="City">Nama Penerima</label>
            <div class="col-sm-8">
                <input type="text" class="form-control" name="recipientName" id="recipientName" value="<?php echo set_value('recipientName') ?>"><?php echo form_error('recipientName') ?>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3" for="City">No Telp Penerima</label>
            <div class="col-sm-5">
                <input type="number" class="form-control" name="recipientPhone" id="recipientPhone" value="<?php echo set_value('recipientPhone') ?>"><?php echo form_error('recipientPhone') ?>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-3" for="Country">* Negara</label>
            <div class="col-sm-6">
                <?php echo $this->master->get_custom($params = array('table' => 'Country', 'id' => 'id', 'name' => 'name', 'where' => array()), '', 'countryId', 'countryId', 'form-control', 'required', '') ?>
                <?php echo form_error('Country') ?>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-3" for="Province">* Provinsi</label>

            <div class="col-sm-6">
                <?php echo $this->master->get_change($params = array('table' => 'Province', 'id' => 'id', 'name' => 'name', 'where' => array()), '' , 'provinceId', 'provinceId', 'form-control', 'required', '') ?>
                <?php echo form_error('Province') ?>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-3" for="City">* Kota/Kab</label>

            <div class="col-sm-6">
                <?php echo $this->master->get_change_city($params = array('table' => 'City', 'id' => 'id', 'name' => 'name', 'where' => array()), '' , 'cityId', 'cityId', 'form-control', 'required', '') ?>
                <?php echo form_error('City') ?>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-3" for="District">* Kecamatan</label>

            <div class="col-sm-6">
                <?php echo $this->master->get_change($params = array('table' => 'District', 'id' => 'id', 'name' => 'name', 'where' => array()), '' , 'districtId', 'districtId', 'form-control', 'required', '') ?>
                <?php echo form_error('District') ?>
            </div>
        </div>

        <div class="form-group">
              <label class="col-sm-3" for="District">* Kode Pos</label>

              <div class="col-sm-6">
                  <input type="text" class="form-control" id="kodePos" name="kodePos" required><?php echo form_error('kodePos') ?>
              </div>
          </div>

        <div class="form-group">
            <label class="col-sm-3" for="City">* Alamat</label>

            <div class="col-sm-9">
                <textarea class="form-control" name="address" id="address" cols="10"
                  rows="3"><?php echo set_value('address') ?></textarea><?php echo form_error('address') ?>
            </div>
        </div>
      </div>
    </form>
      </div>
      <div class="modal-footer">
        <button type="button" id="btnSave" onclick="save()" class="btn btn-primary">Save</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<div class="modal fade" id="modal_success" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h3 class="modal-title">Success</h3>
      </div>
      <div class="modal-body form"> 
        Success process
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<div class="modal fade" id="modal_error" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h3 class="modal-title">Error</h3>
      </div>
      <div class="modal-body form"> 
        <div id="error-content">Error Process</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->