<script src="<?php echo base_url() ?>assets/js/vendor/jquery-1.12.0.min.js"></script>
<script src="<?php echo base_url() ?>assets/js/vendor/typeahead.js-master/dist/typeahead.jquery.min.js"></script>
<!-- jquery-ui.min.js -->
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<!-- bootstrap.min.js -->
<script src="<?php echo base_url() ?>assets/js/bootstrap.min.js"></script>
<script src="<?php echo base_url() ?>assets/js/bootstrap-switch.min.js"></script>
<!-- Jquery Zoomple -->
<script src="<?php echo base_url() ?>assets/js/vendor/Zoomple-master/zoomple.js"></script>
<!-- nivo.slider.js -->
<script src="<?php echo base_url() ?>assets/js/jquery.nivo.slider.pack.js"></script>
<!-- jquery.magnific-popup.min.js -->
<script src="<?php echo base_url() ?>assets/js/jquery.magnific-popup.min.js"></script>
<!-- jquery.meanmenu.min.js -->
<script src="<?php echo base_url() ?>assets/js/jquery.meanmenu.js"></script>
<!-- dropzone.js -->
<script src="<?php echo base_url('assets/js/dropzone.js') ?>"></script>
<!-- jquery.scrollup.min.js-->
<script src="<?php echo base_url() ?>assets/js/jquery.scrollup.min.js"></script>
<!-- owl.carousel.min.js -->
<script src="<?php echo base_url() ?>assets/js/owl.carousel.min.js"></script>
<!-- plugins.js -->
<script src="<?php echo base_url() ?>assets/js/plugins.js"></script>
<script src="<?php echo base_url() ?>assets/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/masonry.pkgd.min.js') ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/imagesloaded.pkgd.min.js') ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/jquery.rateyo.min.js') ?>"></script>

<!-- DATATABLES PLUGIN -->
<script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js') ?>"></script>
<script src="<?=base_url('assets/js/fnReloadAjax.js');?>"></script>
<script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js') ?>"></script>
<!-- END OF DATATABLES PLUGIN -->

<script type="text/javascript" src="<?php echo base_url('assets/js/jquery-scrolltofixed-min.js') ?>"></script>
<script type="text/javascript" src="<?=base_url('assets/js/jstz.min.js');?>"></script>

<script type="text/javascript">
    $(function() {
        $(window).on('load', function() {
            $('.product-sidebar').scrollToFixed({
                marginTop: 139,
                limit: $('.footer-top-area').offset().top - $('.product-sidebar').outerHeight() - 50,
                minWidth: 992
            });

            var timezone = jstz.determine().name();
            //            console.log('Your timezone is: ' + timezone);

            $.ajax({
                type: 'POST',
                url: '<?php echo base_url('TimezoneApi/setTimezone'); ?>',
                data: {
                    timezone: timezone
                },
                dataType: 'json',
                success: function(data) {
                    // console.log(data);
                }
            })
        })
    });
</script>
<!-- main.js -->
<script src="<?php echo base_url() ?>assets/js/main.js"></script>

<!-- js custom -->
<!--<script src="--><?php //echo base_url()
                    ?>
<!--assets/js/custom/checkout.js"></script>-->

<script type="text/javascript">
    // init Masonry
    //    var $grid = $('.grid-container').masonry({
    //        itemSelector: '.grid'
    ////        percentPosition: true
    //    });
    //    // layout Masonry after each image loads
    //    $grid.imagesLoaded().progress( function() {
    //        $grid.masonry({
    //            itemSelector: '.grid'
    //        });
    //    });

    // Navigation fixed to top
    $(window).scroll(function() {
        if ($(window).scrollTop() > 1) {
            $('#header').addClass('header-fixed');
        }
        if ($(window).scrollTop() < 1) {
            $('#header').removeClass('header-fixed');
        }
    });


    Number.prototype.format = function(n, x, s, c) {
        var re = '\\d(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\D' : '$') + ')',
            num = this.toFixed(Math.max(0, ~~n));

        return (c ? num.replace('.', c) : num).replace(new RegExp(re, 'g'), '$&' + (s || ','));
    };

    $(function() {
        // ADD TO CART AJAX SCRIPT
        $('#addToCart').on('submit', function(e) {
            e.preventDefault();

            var form = $(this);

            form.find('.compare').button('loading');

            $.ajax({
                type: 'POST',
                url: '<?php echo base_url('Cart/addToCart'); ?>',
                data: {
                    productId: $('#productIdHidden').val(),
                    qty: $('#qty').val()
                },
                dataType: 'json',
                success: function(data) {
                    if (data.session === 'yes') {
                        var result = data.result_api.result;

                        form.find('.compare').button('reset');
                        //                        var pricePerProduct = parseFloat(data.priceTotal.format());
                        if ($('#cart-list_' + result.product.id).length) {
                            document.getElementById('qty_' + result.product.id).value = result.quantity;
                            document.getElementById('qty-nya_' + result.product.id).innerHTML = result.quantity;

                            document.getElementById('price_' + result.product.id).value = result.netTotal;
                            document.getElementById('price-nya_' + result.product.id).innerHTML = 'Rp ' + parseFloat(result.netTotal).format();
                        } else {
                            $html = "<li class='cart-list-li' id='cart-list_" + result.product.id + "'>" +
                                "<a class='product-image' href='Product/" + slugify(result.product.name) + "/id=" + result.product.id + "'>" +
                                "<img alt='' src='<?php echo IMG_PRODUCT ?>" + result.product.images[0].thumbnail + "'></a>" +
                                "<div class='product-details'>" +
                                "<p class='cartproduct-name'><a href=''>" + result.product.name + "</a></p>" +
                                "<input type='hidden' class='qty-per-product' id='qty_" + result.product.id + "' value='" + result.quantity + "'>" +
                                "<strong class='qty'>Qty : <span id='qty-nya_" + result.product.id + "'>" + result.quantity + "</span></strong>" +
                                "<input class='price-per-product' type='hidden' id='price_" + result.product.id + "' value='" + parseFloat(result.netTotal) + "'><span id='price-nya_" + result.product.id + "' class='sig-price'>Rp " + parseFloat(result.netTotal).format() + "</span></div><div class='pro-action'><a class='btn delete-cart' data-session='yes' data-id='" + result.product.id + "' data-toggle='tooltip' title='Hapus!'><i class='fa fa-times'></i></a></div></li>";
                            $('.cart-list').append($html);
                        }

                        var totalItem = $('.cart-list .cart-list-li').length;

                        var totalPrice = 0;
                        $('.cart-list .price-per-product').each(function() {
                            totalPrice += parseFloat(this.value);
                        });

                        document.getElementById('sub-total-price').innerHTML = 'Rp ' + totalPrice.format();
                        document.getElementById('total-price').innerHTML = 'Rp ' + totalPrice.format();
                        document.getElementById('total-item').innerHTML = totalItem + ' item(s)';
                        $('[data-toggle="tooltip"]').tooltip();
                        /*$('<li>New cart</li>').appendTo($('#cart-list'));*/
                        //                        alert("Barang berhasil ditambahkan ke keranjang");
                        $('#modal-success-add-cart').modal('show');
                    } else if (data.session === 'no') {
                        form.find('.compare').button('reset');
                        //                        var pricePerProduct = parseFloat(data.priceTotal.format());
                        if ($('#cart-list_' + data.productId).length) {
                            document.getElementById('qty_' + data.productId).value = data.quantity;
                            document.getElementById('qty-nya_' + data.productId).innerHTML = data.quantity;

                            document.getElementById('price_' + data.productId).value = data.priceTotal * data.quantity;
                            document.getElementById('price-nya_' + data.productId).innerHTML = 'Rp ' + parseFloat(data.priceTotal * data.quantity).format();

                        } else {
                            $html = "<li class='cart-list-li' id='cart-list_" + data.productId + "'>" +
                                "<a class='product-image' href='" + data.url + "'>" +
                                "<img alt='' src=" + data.image + "></a>" +
                                "<div class='product-details'>" +
                                "<p class='cartproduct-name'><a href=''>" + data.name + "</a></p>" +
                                "<input type='hidden' class='qty-per-product' id='qty_" + data.productId + "' value='" + data.quantity + "'>" +
                                "<strong class='qty'>Qty : <span id='qty-nya_" + data.productId + "'>" + data.quantity + "</span></strong>" +
                                "<input class='price-per-product' type='hidden' id='price_" + data.productId + "' value='" + parseFloat(data.priceTotal * data.quantity) + "'><span id='price-nya_" + data.productId + "' class='sig-price'>Rp " + parseFloat(data.priceTotal * data.quantity).format() + "</span></div><div class='pro-action'><a class='btn delete-cart' data-session='no' data-id='" + data.productId + "' data-toggle='tooltip' title='Hapus!'><i class='fa fa-times'></i></a></div></li>";
                            $('.cart-list').append($html);
                        }

                        var totalItem = $('.cart-list .cart-list-li').length;

                        var totalPrice = 0;
                        $('.cart-list .price-per-product').each(function() {
                            totalPrice += parseFloat(this.value);
                        });

                        $('.li-belum-ada').hide();
                        document.getElementById('sub-total-price').innerHTML = 'Rp ' + totalPrice.format();
                        document.getElementById('total-price').innerHTML = 'Rp ' + totalPrice.format();
                        document.getElementById('total-item').innerHTML = totalItem + ' item(s)';
                        $('[data-toggle="tooltip"]').tooltip();
                        /*$('<li>New cart</li>').appendTo($('#cart-list'));*/
                        //                        alert("Barang berhasil ditambahkan ke keranjang");
                        $('#modal-success-add-cart').modal('show');
                    } else if (data.status === 'overload') {
                        alert('Pesanan anda melebihi batas');
                    } else {
                        alert('Gagal menambah produk');
                    }
                    form.find('.compare').button('reset');
                    console.log(data);
                    return true;
                }
            });
            return true;
        });
        // END OF ADD TO CART AJAX SCRIPT

        $('.cart-list').on('click', '.delete-cart', function(e) {
            e.preventDefault();
            id = $(this).attr('data-id');
            var url = '';
            if ($(this).attr('data-session') === 'yes') {
                url = '<?php echo base_url('Cart/delete/'); ?>' + id;
            } else {
                url = '<?php echo base_url('Cart/delete_sess/'); ?>' + id;
            }

            $.ajax({
                type: 'GET',
                url: url,
                dataType: 'json',
                success: function(data) {
                    if (data.message === 'sukses') {
                        $('#cart-list_' + id).remove();
                        var totalItem = $('#cart-list .cart-list-li').length;

                        var totalPrice = 0;
                        $('.price-per-product').each(function() {
                            totalPrice += parseFloat(this.value);
                        });

                        document.getElementById('sub-total-price').innerHTML = 'Rp ' + totalPrice.format();
                        document.getElementById('total-price').innerHTML = 'Rp ' + totalPrice.format();
                        document.getElementById('total-item').innerHTML = totalItem + ' item(s)';
                    } else {
                        alert('gagal menghapus barang');
                    }
                }
            });

            return false;
        });

        //        function deleteCart(id) {
        //            $.ajax({
        //                type: 'GET',
        //                url: '<?php //echo base_url('Cart/delete/'); 
                                ?>//'+id,
        //                dataType: 'json',
        //                success: function (data) {
        //                    if(data.message === 'sukses') {
        //                        $('#cart-list_'+id).remove();
        //                    }
        //                }
        //            })
        //        }

        // Rating plugin init RateYo
        $(".rateYo").rateYo({
            starWidth: "20px",
            halfStar: true,
            rating: 1
        });

        $('[data-toggle="tooltip"]').tooltip();
        $('.datepicker').datepicker({
            dateFormat: 'yyyy-mm-dd'
        });

        $('select[name="escrowBankAccountId"]').change(function() {
            if ($(this).val()) {
                $.getJSON("<?php echo site_url('templates/References/getEscrowBankAccount') ?>/" + $(this).val(), '', function(data) {
                    $('#bankName').val(data.bankName);
                    $('#location').val(data.location);
                    $('#accountNo').val(data.accountNo);
                });
            } else {
                $('#bankName').val('');
                $('#location').val('');
                $('#accountNo').val('');
            }
        });

        $('select[name="countryId"]').change(function() {
            if ($(this).val()) {
                $.getJSON("<?php echo site_url('templates/References/getProvinceByCountry') ?>/" + $(this).val(), '', function(data) {
                    $('#provinceId option').remove();
                    $('<option value="">-Silahkan Pilih-</option>').appendTo($('#provinceId'));
                    $.each(data, function(i, o) {
                        $('<option value="' + o.id + '">' + o.name + '</option>').appendTo($('#provinceId'));
                    });

                });
            } else {
                $('#provinceId option').remove()
            }
        });

        $('select[name="provinceId"]').change(function() {
            if ($(this).val()) {
                $.getJSON("<?php echo site_url('templates/References/getCityByProvince') ?>/" + $(this).val() + "/" + $('select[name="countryId"]').val(), '', function(data) {
                    $('#cityId option').remove();
                    $('<option value="">-Silahkan Pilih-</option>').appendTo($('#cityId'));
                    $.each(data, function(i, o) {
                        $('<option value="' + o.id + '">' + o.name + ' - ' + o.type + '</option>').appendTo($('#cityId'));
                    });

                });
            } else {
                $('#cityId option').remove()
            }
        });

        $('select[name="cityId"]').change(function() {
            if ($(this).val()) {
                $.getJSON("<?php echo site_url('templates/References/getDistrictByCity') ?>/" + $(this).val() + "/" + $('select[name="provinceId"]').val(), '', function(data) {
                    $('#districtId option').remove();
                    $('<option value="">-Silahkan Pilih-</option>').appendTo($('#districtId'));
                    $.each(data, function(i, o) {
                        $('<option value="' + o.id + '">' + o.name + '</option>').appendTo($('#districtId'));
                    });

                });
            } else {
                $('#districtId option').remove()
            }
        });

        $('select[name="districtId"]').change(function() {
            if ($(this).val()) {
                $.getJSON("<?php echo site_url('templates/References/getMarketByDistrict') ?>/" + $(this).val() + "/" + $('select[name="cityId"]').val() + "/" + $('select[name="provinceId"]').val(), '', function(data) {
                    $('#marketId option').remove();
                    $('<option value="">-Silahkan Pilih-</option>').appendTo($('#marketId'));
                    $.each(data, function(i, o) {
                        $('<option value="' + o.id + '">' + o.name + '</option>').appendTo($('#marketId'));
                    });

                });
            } else {
                $('#marketId option').remove()
            }
        });
        // Select your input element.

        //var number = document.getElementById('qty');

        // Listen for input event on numInput.
        /*qty.onkeydown = function(e) {
            if (!((e.keyCode > 95 && e.keyCode < 106)
                || (e.keyCode > 47 && e.keyCode < 58)
                || e.keyCode == 8)) {
                return false;
            }
        };*/

        //        var number = document.getElementById('qty');

        // Listen for input event on numInput.
        //        qty.onkeydown = function(e) {
        //            if (!((e.keyCode > 95 && e.keyCode < 106)
        //                || (e.keyCode > 47 && e.keyCode < 58)
        //                || e.keyCode == 8)) {
        //                return false;
        //            }
        //        };


        /*$('#sort').change(function() {

            var currentUrl = window.location.search;
//

            if(currentUrl.indexOf('?')) {

            }
            if(this.value) {
                if(currentUrl.indexOf('?')) {
                    var url = currentUrl + '?sortBy=' + this.value;
                } else {
                    var url = currentUrl + '&sortBy=' + this.value;
//                    alert(url.indexOf('?' + field + '=') != -1);
                }
                window.location.href = url;
            }
        });*/
        //
        //        $('#search').on('click', function() {
        //            var currentUrl = window.location.search;
        //
        //                if(window.location.search == '') {
        //                    var url = currentUrl + '?';
        //                } else {
        //                    var url = currentUrl + '&';
        //                }
        //                window.location.href = url;
        //
        //                $('#form').submit();
        //        });

        /*-------------------------
         faq toggle function
         --------------------------*/
        $('.coupon-accordion').accordion({
            heightStyle: "content"
        });
        $('.order-detail-courpon').accordion({
            collapsible: true
        });

        //        var url = "http://dashboard.kisel.nusantarabetastudio.com/api/v1/faqs";
        //
        //        $.ajax({
        //            type: 'GET',
        //            url: url,
        //            dataType: 'json',
        //            success: function (data) {
        //                for(i = 0; i<data.length; i++) {
        //                    alert(data[i].id);
        //                }
        //            },
        //            error: function() {
        //                alert("gagal mendapatkan json");
        //            }
        //        });
        //        $.getJSON(url, function (data) {
        //            format: "json"
        //        }).done(function(data) {
        //
        //        });

        $('.btn-pilih-kurir').on('click', function() {
            $(this).toggleClass("selected");
        });
        //            $(".table-kurir tbody tr").hide();
        $("#pilihKurir").change(function() {
            if ($(this).val() == 'all') {
                $(".table-kurir tbody tr").show();
            } else {
                var kurir = '.' + $(this).val();
                $(".table-kurir tbody tr").hide();
                $(kurir).show();
            }
        });

        // boostrap-switch plugin initial
        $(".switch-radio2").bootstrapSwitch();



        // get cookies name
        function getCookie(name) {
            var value = "; " + document.cookie;
            var parts = value.split("; " + name + "=");
            if (parts.length == 2) return parts.pop().split(";").shift();
        }

        var d = new Date();
        d.setTime(d.getTime() + (24 * 60 * 60 * 1000));
        var expires = "expires=" + d.toUTCString();

        // pop up modal when first visiting site
        if (getCookie('isshow') == null) {
            document.cookie = "isshow=0; expires=" + expires;
        }
        if (getCookie('isshow') == 0) {
            document.cookie = "isshow=1; expires=" + expires;
            $('#popupf').modal('show');
        }

    });
    //        function kurirtable() {
    //                var pilihkurir = document.getElementById("pilihKurir").value;
    //            console.log(pilihkurir);
    //            if($(".table-kurir tbody tr").hasClass(pilihkurir)) {
    //                $(".table-kurir tbody tr").addClass("show-kurir");
    //            }else{
    ////                $(".table-kurir tbody tr").css({"display":"none"});
    //                $(".table-kurir tbody tr").addClass("hide-kurir");
    //            }
    //        }
</script>

<?php $bannerPopUp = $this->templates_model->getBannerByCategory(9);
if(is_object($bannerPopUp)):
if ($bannerPopUp->code == 200 && count($bannerPopUp->data) != 0) { ?>
    <div class="modal modal-lg fade" id="popupf">
        <div class="modal-header">
            <a class="close" data-dismiss="modal">×</a>
        </div>
        <div class="modal-content">
            <div class="modal-body">
                <?php
                $bannerPopUp = $this->templates_model->getBannerByCategory(9);
                if ($bannerPopUp->code == 200 && count($bannerPopUp->data) != 0) {
                    echo "<a href='" . $bannerPopUp->data[0]->urlLink . "'><img src='" . $bannerPopUp->data[0]->name . "' width='100%'></a>";
                } elseif (count($bannerPopUp->data) == 0) {
                    echo "banner tidak ada";
                }
                ?>
            </div>
        </div>
    </div>
<?php }
endif; ?>

<script src="<?php echo base_url() ?>assets/yosemodal/js/gsap.js"></script>
<script>
    <?php if ($this->session->userdata('logged_in')) { ?>

        function showConfirm(orderId, merchantId) {
            $.ajax({
                type: 'POST',
                url: '<?php echo API_DATA . 'Customers/' . $this->session->userdata('user')->id . '/order/delivered?access_token=' . $this->session->userdata('token') . '&orderId='; ?>' + orderId,
                dataType: 'json',
                success: function(data) {
                    if (data.result === 'Delivered') {
                        alert(data.result);
                        $('#modal_konfirm').modal('show');
                        $('#konfirmasi-frame').load("<?php echo base_url() ?>Cart/openconfirm?order=" + orderId + "&merchant=" + merchantId);
                    } else {
                        alert('Gagal Menerima Barang');
                    }
                    console.log(data);
                }
            });
        }

        function showConfirmSuccess(orderId, merchantId) {
            $('#modal_konfirm').modal('show');
            $('#konfirmasi-frame').load("<?php echo base_url() ?>Cart/openconfirm?order=" + orderId + "&merchant=" + merchantId);
        }
    <?php } ?>

    function slugify(string) {
        return string
            .toString()
            .trim()
            .toLowerCase()
            .replace(/\s+/g, "-")
            .replace(/[^\w\-]+/g, "")
            .replace(/\-\-+/g, "-")
            .replace(/^-+/, "")
            .replace(/-+$/, "");
    }
</script>
<div class="modal fade modal-lg" id="modal_konfirm_success" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Konfirmasi Penerimaan Barang</h3>
            </div>
            <div class="modal-body form">
                <div id="konfirmasi-frame">
                    <img src="<?php echo base_url('assets/img/logo_2.png') ?>" alt="Logo Pasarselon">
                    <h3 align="center">Terima kasih sudah berbelanja di Halal Shopping, kami tunggu ulasan barangnya..</h3>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    <?php
    $sess_user_id = ($this->session->userdata('logged_in')) ? $this->session->userdata('user')->id : 0;
    $sess_token = ($this->session->userdata('logged_in')) ? $this->session->userdata('token') : 'NULL';
    ?>

    function barangDiterimaSukses($idOrder, elem) {
        $.ajax({
            type: 'POST',
            url: '<?php echo API_DATA . 'Customers/' . $sess_user_id . '/order/success?access_token=' . $sess_token . '&orderId='; ?>' + $idOrder,
            dataType: "json",
            success: function(data) {
                if (data.result == 'Success') {
                    $('#modal_konfirm_success').modal('show');
                    //                    setTimeout(function () {
                    //                        window.location.href = "<?php //echo base_url('Pesan/ulasan'); 
                                                                        ?>//#"+$idOrder;
                    //                    }, 2000);
                } else {
                    alert("Gagal menerima barang dengan baik");
                }
            }
        });
    }
</script>

<!-- SCRIPT FOR LOGIN SUCCESS -->
<?php if ($this->session->flashdata('loginProcess') == 'success') { ?>
    <script type="text/javascript">
        $(function() {
            $('#successLoginModal').modal('show');

            setTimeout(function() {
                $('#successLoginModal').modal('hide');
            }, 4000)
        })
    </script>
<?php } ?>
<!-- END OF SCRIPT FOR LOGIN SUCCESS -->
