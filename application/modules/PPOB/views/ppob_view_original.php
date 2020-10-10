<div class="banner-ppob">
    <img src="<?php echo base_url(); ?>assets/img/banner-ppob.png" alt="PPOB" width="100%">
</div>
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="ppob-kisel mt-70 mb-80">
                <!-- Nav tabs -->
                <ul class="nav ppob-kisel__menu-list" role="tablist">
                    <?php foreach ($category->result as $key => $row_cat_ppob) : $active = ($key == 0) ? 'active' : ''; ?>
                        <li role="presentation" class="<?php echo $active ?>"
                            onclick="clearForm()">
                            <a href="#tab-<?php echo $row_cat_ppob->id ?>"
                               aria-controls="<?php echo urlencode($row_cat_ppob->name) ?>" role="tab"
                               data-toggle="tab"><?php echo $row_cat_ppob->name ?></a>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <div class="tab-content ppob-kisel__content">
                    <?php foreach ($category->result as $key => $row_cat_ppob) : $active = ($key == 0) ? 'active' : '' ?>
                        <div class="tab-pane <?php echo $active ?>" id="tab-<?php echo $row_cat_ppob->id ?>"
                             role="tabpanel">
                            <?php if ($row_cat_ppob->id == '6371781566154276865' || $row_cat_ppob->id == '6429546220208914432' || $row_cat_ppob->id == '6371781566162665472') { ?>
                                <h4 id="form-title">
                                    <?php if($row_cat_ppob->id == '6371781566154276865' || $row_cat_ppob->id == '6429546220208914432') { ?>
                                        Beli <?php echo $row_cat_ppob->name ?>
                                    <?php } elseif ($row_cat_ppob->id == '6371781566162665472') { ?>
                                        Pembayaran Token Listrik
                                    <?php } ?>
                                </h4>
                                <form class="ppob_category_form" action="<?php echo base_url('PPOB/checkout') ?>"
                                      method="post">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <span class="ppob-kisel__input-title">
                                                    <?php echo $row_cat_ppob->id == 'Nomor Handphone' ? 'Nomor ID Pelanggan' : 'Nomor Handphone'; ?>
                                                </span>
                                                <div class="input-group">
                                                    <input type="tel" id="no_hp" name="no_hp"
                                                           class="form-control ppob-form-input"
                                                           aria-label="No telepon anda"
                                                           placeholder="contoh 081234567890" required
                                                           oninput="getProvidersProduct(this, '<?php echo $row_cat_ppob->id ?>', '')">
                                                    <span class="input-group-addon"><img class="operator-logo"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group nominal-container">
                                                <span class="ppob-kisel__input-title">Nominal</span>
                                                <div class="radio-input-container product-container <?php echo ($row_cat_ppob->id == '6429546220208914432' || $row_cat_ppob->id == '6371781566162665472') ? '' : 'd-grid' ?>"></div>
                                                <input type="number" name="total" class="totalPrice" required hidden>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-20">
                                        <div class="col-sm-6">
                                            <div class="form-group sig-price">
                                                Harga : <b class="show_detail_nominal"></b>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <!-- hidden form -->
                                                <button type="submit" id="btn_beli_pulsa" class="ppob-kisel__btn-buy">
                                                    Beli
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            <?php } elseif ($row_cat_ppob->id == '11937975655745122160') { ?>
                                <ul id="go-pay-providers-tab" class="nav ppob-kisel__menu-list" role="tablist"></ul>
                                <div id="gopay-content" class="tab-content ppob-kisel__content"></div>
                            <?php } else { ?>
                                <h4 align="center">Layanan belum tersedia</h4>
                            <?php } ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- about-us-area-start -->
<div class="about-us-area">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="about-us-page">
                    <div class="about-content">
                        <h4 align="center">Mengapa Beli Pulsa di Pasarselon?</h4>
                        <p align="center">Untuk dapat berbelanja, Seloners dapat menggunakan media web ataupun mobile
                            apps yang dapat di unduh melalui Google Apps Store.</p>
                        <p align="center">Nikmati pengalaman berbelanja yang mudah, cepat dengan jaminan kualitas barang
                            Original (Bukan KW ataupun KW Super).</p>
                        <img class="center-block img1200"
                             src="https://image.pasarselon.com/cms/9fed2140-fcbf-4601-a810-a8c0c8eeff21.png"
                             alt="Panduan Belanja">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<br><br><br>
<!-- about-us-area-end -->

<script type="text/javascript">
    function clearForm() {
        $('.nominal-container').hide();
        $('.product-container').html('');
        $('.ppob-form-input').val('');
        $('.show_detail_nominal').html('(Belum pilih produk)');
        const operatorLogo = document.getElementsByClassName('operator-logo');
        for (let i = 0; i < operatorLogo.length; i++) {
            operatorLogo[i].src = '';
        }
    }

    function getProvidersProduct(e, categoryId, providerId) {
        let phoneCarrier = '';
        // let providerId = '';
        const phoneNumber = e.value;
        let carrierList = '';
        const operatorLogo = document.getElementsByClassName('operator-logo');

        $('.nominal-container').hide();
        $('.product-container').html('');

        // FLOW HIT API FOR GO PAY, PULSA, AND PAKET DATA
        if (categoryId === '6371781566154276865' || categoryId === '6429546220208914432' || categoryId === '11937975655745122160') {

            $.ajax({
                url: '<?php echo base_url('PPOB/getPhoneCarrier/') ?>' + phoneNumber,
                method: 'GET'
            }).done(function (data) {
                if (data) {
                    const json = JSON.parse(data);

                    if (json.status) {
                        switch (json.result) {
                            case "Telkomsel":
                                phoneCarrier = 'Telkomsel';
                                for (let i = 0; i < operatorLogo.length; i++) {
                                    operatorLogo[i].src = '<?php echo base_url('assets/img/telkomsel.svg') ?>';
                                }
                                break;
                            case "IM3":
                            case "Indosat":
                                phoneCarrier = 'Indosat';
                                for (let i = 0; i < operatorLogo.length; i++) {
                                    operatorLogo[i].src = '<?php echo base_url('assets/img/indosat.svg') ?>';
                                }
                                break;
                            case "3":
                            case "Hutchison":
                                phoneCarrier = 'Three';
                                for (let i = 0; i < operatorLogo.length; i++) {
                                    operatorLogo[i].src = '<?php echo base_url('assets/img/three.svg') ?>';
                                }
                                break;
                            case "Smartfren":
                                phoneCarrier = 'Smartfren';
                                for (let i = 0; i < operatorLogo.length; i++) {
                                    operatorLogo[i].src = '<?php echo base_url('assets/img/smartfren.svg') ?>';
                                }
                                break;
                            case "XL":
                                phoneCarrier = 'XL';
                                for (let i = 0; i < operatorLogo.length; i++) {
                                    operatorLogo[i].src = '<?php echo base_url('assets/img/xl.svg') ?>';
                                }
                                break;
                            default:
                                phoneCarrier = '';
                                for (let i = 0; i < operatorLogo.length; i++) {
                                    operatorLogo[i].src = '';
                                }
                                break;
                        }

                        if (phoneCarrier === '') {
                            $('.nominal-container').show();
                            $('.product-container').html('<p class="text-danger">Tidak ada produk yang tersedia</p>')
                        } else {

                            // GET PRODUCTS FOR GOPAY PPOB
                            if (providerId === '') {

                                $.ajax({
                                    url: '<?php echo base_url('PPOB/getProviders/') ?>' + categoryId,
                                    method: 'GET'
                                }).done(function (data) {
                                    carrierList = '';
                                    const json = JSON.parse(data);
                                    carrierList = json.result;

                                    carrierList.forEach(function (item) {
                                        if (item.name === phoneCarrier) {
                                            providerId = item.id;

                                            $.ajax({
                                                url: '<?php echo base_url('PPOB/getProducts/') ?>' + categoryId + '/' + providerId,
                                                method: 'GET'
                                            }).done(function (data) {
                                                const json = JSON.parse(data);
                                                const products = json.result;

                                                $('.nominal-container').show();
                                                $('.product-container').html('');

                                                let counter = 0;

                                                if (categoryId === '6429546220208914432') {
                                                    let html = '<ul>';
                                                    products.forEach(function (item) {
                                                        html += '<li><label for="radio-' + counter + '" class="d-block paket-data-product clearfix"><div class="radio-container"><input type="radio" name="productId" value="' + item.id + '" id="radio-' + counter + '" data-displayprice="' + item.displayPrice + '" required></div><div class="product-content-container"><h5>' + item.name + '</h5><p>' + item.description + '</p></div><div class="price-container"><span class="sig-price">Rp ' + item.displayPrice.format() + '</span></div></label></li>';
                                                        counter++;
                                                    });
                                                    html += '</ul>';
                                                    $('.product-container').append(html);
                                                } else {
                                                    products.forEach(function (item) {
                                                        $('.product-container').append('<span class="custom-radio"><label class="label-input" for="radio-' + counter + '">' + item.name + '</label><input id="radio-' + counter + '" type="radio" name="productId" value="' + item.id + '" class="radio-i" data-displayprice="' + item.displayPrice + '" required></span>');
                                                        counter++;
                                                    })
                                                }

                                            }).error(function (err) {
                                                console.log(err);
                                            });
                                        } else {
                                            $('.nominal-container').show();
                                            $('.product-container').html('<p class="text-danger">Tidak ada produk yang tersedia</p>')
                                        }
                                    })
                                }).error(function (err) {
                                    console.log(err);
                                });

                            } else {

                                $.ajax({
                                    url: '<?php echo base_url('PPOB/getProducts/') ?>' + categoryId + '/' + providerId,
                                    method: 'GET'
                                }).done(function (data) {
                                    const json = JSON.parse(data);
                                    const products = json.result;

                                    $('.nominal-container').show();
                                    $('.product-container').html('');

                                    let counter = 0;

                                    if (categoryId === '6429546220208914432') {
                                        let html = '<ul>';
                                        products.forEach(function (item) {
                                            html += '<li><label for="radio-' + counter + '" class="d-block paket-data-product clearfix"><div class="radio-container"><input type="radio" name="productId" value="' + item.id + '" id="radio-' + counter + '" data-displayprice="' + item.displayPrice + '" required></div><div class="product-content-container"><h5>' + item.name + '</h5><p>' + item.description + '</p></div><div class="price-container"><span class="sig-price">Rp ' + item.displayPrice.format() + '</span></div></label></li>';
                                            counter++;
                                        });
                                        html += '</ul>';
                                        $('.product-container').append(html);
                                    } else {
                                        products.forEach(function (item) {
                                            $('.product-container').append('<span class="custom-radio"><label class="label-input" for="radio-' + counter + '">' + item.name + '</label><input id="radio-' + counter + '" type="radio" name="productId" value="' + item.id + '" class="radio-i" data-displayprice="' + item.displayPrice + '" required></span>');
                                            counter++;
                                        })
                                    }

                                }).error(function (err) {
                                    console.log(err);
                                });

                            }
                        }
                    } else {
                        $('.nominal-container').show();
                        $('.product-container').html('<p class="text-danger">Nomor handphone tidak valid</p>')
                    }

                }
            }).error(function (err) {
                console.log(err);
            });

        } else { // FLOW API FOR BPJS & TOKEN PLN

            $.ajax({
                url: '<?php echo base_url('PPOB/getProviders/') ?>' + categoryId,
                method: 'GET'
            }).done(function (data) {
                carrierList = '';
                const json = JSON.parse(data);

                if (json.length > 0) {
                    $.ajax({
                        url: '<?php echo base_url('PPOB/getProducts/') ?>' + categoryId + '/' + json.id,
                        method: 'GET'
                    }).done(function (data) {
                        const json = JSON.parse(data);
                        const products = json.result[0];

                        $('.nominal-container').show();
                        $('.product-container').html('');

                        let counter = 0;

                        let html = '<ul>';
                        products.forEach(function (item) {
                            html += '<li><label for="radio-' + counter + '" class="d-block paket-data-product clearfix"><div class="radio-container"><input type="radio" name="productId" value="' + item.id + '" id="radio-' + counter + '" data-displayprice="' + item.displayPrice + '" required></div><div class="product-content-container"><h5>' + item.name + '</h5><p>' + item.description + '</p></div><div class="price-container"><span class="sig-price">Rp ' + item.displayPrice.format() + '</span></div></label></li>';
                            counter++;
                        });
                        html += '</ul>';
                        $('.product-container').append(html);
                    })
                } else {
                    $('.nominal-container').show();
                    $('.product-container').html('<p class="text-danger">Produk kosong</p>')
                }

            }).error(function (err) {
                console.log(err);
            });
        }
    }

    $(function () {
        $('.nominal-container').hide();
        $('.show_detail_nominal').html('(Belum pilih produk)');
        $('.product-container').on('change', 'input:radio[name=productId]:checked', function () {
            const totalPrice = parseInt($(this).data('displayprice'));
            $('.totalPrice').val(totalPrice);
            $('.show_detail_nominal').html('Rp ' + totalPrice.format());
        }).on('click', '.custom-radio .label-input', function () {
            $('.label-input').removeClass('label-i-active');
            $(this).addClass('label-i-active');
        }).on('click', '.paket-data-product', function () {
            $(this).find('input:radio[name=productId]').attr('checked', 'checked');
        });
        $('#gopay').on('click', '.custom-radio .label-input', function () {
            $('.label-input').removeClass('label-i-active');
            $(this).addClass('label-i-active');
        });


        // GOPAY TAB CONTENT INITIALIZATION
        let categoryId = '';

        $('#go-pay-providers-tab').html('');
        $('#gopay-content').html('');
        categoryId = '11937975655745122160';

        $.ajax({
            url: '<?php echo base_url('PPOB/getProviders/') ?>' + categoryId,
            method: 'GET'
        }).done(function (data) {
            carrierList = '';
            const json = JSON.parse(data);
            carrierList = json.result;

            let counter = 0;
            let tabList = '';
            let tabContent = '';
            carrierList.forEach(function (item) {
                tabList += '<li role="presentation" class="' + (counter === 0 ? 'active' : '') + '"><a href="#gopay-' + counter + '" aria-controls="gopay-' + counter + '" onclick="clearForm()" role="tab" data-toggle="tab">' + item.name + '</a></li>';

                tabContent += '<div role="tabpanel" class="tab-pane ' + (counter === 0 ? 'active' : '') + '" id="gopay-' + counter + '">\n' +
                    '                    <h4 id="form-title">Top Up ' + item.name + '</h4>\n' +
                    '                    <form class="ppob_category_form" action="<?php echo base_url('PPOB/checkout') ?>" method="post">\n' +
                    '                        <div class="row">\n' +
                    '                            <div class="col-sm-12">\n' +
                    '                                <div class="form-group">\n' +
                    '                                    <span class="ppob-kisel__input-title">Nomor Handphone</span>\n' +
                    '                                    <div class="input-group">\n' +
                    '                                        <input type="tel" id="no_hp" name="no_hp" class="form-control ppob-form-input" aria-label="No telepon anda" placeholder="contoh 081234567890" required oninput="getProvidersProduct(this, \'' + categoryId + '\', \'' + item.id + '\')">\n' +
                    '                                        <span class="input-group-addon"><img class="operator-logo"></span>\n' +
                    '                                    </div>\n' +
                    '                                </div>\n' +
                    '                            </div>\n' +
                    '                            <div class="col-sm-12">\n' +
                    '                                <div class="form-group nominal-container">\n' +
                    '                                    <span class="ppob-kisel__input-title">Nominal</span>\n' +
                    '                                    <div class="radio-input-container d-grid product-container"></div>\n' +
                    '                                    <input type="number" name="total" class="totalPrice" required hidden>\n' +
                    '                                </div>\n' +
                    '                            </div>\n' +
                    '                        </div>\n' +
                    '                        <div class="row mt-20">\n' +
                    '                            <div class="col-sm-6">\n' +
                    '                                <div class="form-group sig-price">\n' +
                    '                                    Harga : <b class="show_detail_nominal"></b>\n' +
                    '                                </div>\n' +
                    '                            </div>\n' +
                    '                            <div class="col-sm-6">\n' +
                    '                                <div class="form-group">\n' +
                    '                                    <!-- hidden form -->\n' +
                    '                                    <button type="submit" id="btn_beli_pulsa" class="ppob-kisel__btn-buy">Beli</button>\n' +
                    '                                </div>\n' +
                    '                            </div>\n' +
                    '                        </div>\n' +
                    '                    </form>\n' +
                    '                </div>';
                counter++;
            });

            $('#go-pay-providers-tab').append(tabList);

            $('#gopay-content').append(tabContent);
            $('.nominal-container').hide();
        }).error(function (err) {
            console.log(err);
        });
    });
</script>

<script type="text/javascript">

    // AMIN'S CODE HERE
    //document.addEventListener('DOMContentLoaded', function () {
    //
    //    $('select[name="select_cate_prov"]').change(function () {
    //        var text = $("#select_cate_prov option:selected").text();
    //        var nominal = $("#select_cate_prov option:selected").attr('data');
    //        var redaksional = $("#select_cate_prov option:selected").attr('title');
    //        var format = format2(parseInt(nominal),'Rp ');
    //        var icon = $("#select_cate_prov option:selected").attr('icon');
    //
    //        var show_description = (text==redaksional)?'':redaksional;
    //
    //        icon_img = '';
    //
    //        var html = '<span class="ppob-kisel__input-title">Harga</span><div class="">'+icon_img+'&nbsp;<b>'+format+' ('+text+')</b><br><span style="border-weight:0;margin-left:30px"><i>'+show_description+'</i></span></div>';
    //        $('#show_detail_nominal').show('fast');
    //        $('#show_detail_nominal').html(html);
    //        $('#nominal_text').val(nominal);
    //        $('#description_text').val(text);
    //        $('#redaksional_text').val(redaksional);
    //        $('#productId').val($('select[name="select_cate_prov"]').val());
    //
    //    });
    //
    //
    //    function format2(n, currency) {
    //      return currency + n.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,');
    //    }
    //
    //
    //});
    //
    //function clear_form(category, name) {
    //    document.getElementById("form-ppob").reset();
    //    $.getJSON("<?php //echo site_url('templates/References/getPPOBProvider') ?>///" + category, '', function (data) {
    //        $('#form-title').text(name);
    //        $('#select_provider option').remove();
    //        $('<option value="">-Pilih Provider-</option>').appendTo($('#select_provider'));
    //        countData = data.length;
    //        console.log(countData);
    //        if( countData > 0 ){
    //            $('#form-provider').show('fast');
    //            $.each(data, function (i, o) {
    //                $('<option value="' + o.id + '">' + o.name + '</option>').appendTo($('#select_provider'));
    //            });
    //        }else{
    //            $('#form-provider').hide('fast');
    //            $('#form-category-provider').hide('fast');
    //        }
    //
    //    });
    //
    //    document.getElementById("show_detail_nominal").style.display = 'none';
    //}
    //
    //$('select[name="select_provider"]').change(function () {
    //
    //    /*current day*/
    //
    //    if ( $(this).val() ) {
    //        $('#form-category-provider').show('fast');
    //        $.getJSON("templates/References/getCategoryProductPPOB/" + $(this).val(), '', function (data) {
    //            $('#select_cate_prov option').remove();
    //            $('<option value="">-Silahkan Pilih-</option>').appendTo($('#select_cate_prov'));
    //            $.each(data, function (i, o) {
    //                $('<option value="' + o.id + '" data="'+o.displayPrice+'" icon="'+o.providerId+'" title="'+o.description+'">' + o.name + '</option>').appendTo($('#select_cate_prov'));
    //            });
    //        });
    //    }else{
    //        $('#form-category-provider').hide('fast');
    //    }
    //
    //});

</script>