<div class="ppob-kisel">
    <ul class="nav ppob-kisel__menu-list" role="tablist">
        <li role="presentation" class="active"><a href="#pulsa" aria-controls="pulsa" role="tab" data-toggle="tab" onclick="clearForm()">Pulsa</a></li>
        <li role="presentation"><a href="#paketdata" aria-controls="paketdata" role="tab" data-toggle="tab" onclick="clearForm()">Paket Data</a></li>
        <li role="presentation"><a href="#gopay" aria-controls="gopay" role="tab" data-toggle="tab" onclick="clearForm()">Go Pay</a></li>
        <li role="presentation">
            <a href="<?php echo base_url('PPOB') ?>">Lainya</a>
        </li>
    </ul>
    <div class="tab-content ppob-kisel__content">
        <!-- PULSA -->
        <div role="tabpanel" class="tab-pane active" id="pulsa">
            <h4 id="form-title">Beli Pulsa</h4>
            <form class="ppob_category_form" action="<?php echo base_url('PPOB/checkout') ?>" method="post">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <span class="ppob-kisel__input-title">Nomor Handphone</span>
                            <div class="input-group">
                                <input type="tel" id="no_hp" name="no_hp" class="form-control ppob-form-input" aria-label="No telepon anda" placeholder="contoh 081234567890" required oninput="getProvidersProduct(this, '6371781566154276865', '')">
                                <span class="input-group-addon"><img class="operator-logo"></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group nominal-container">
                            <span class="ppob-kisel__input-title">Nominal</span>
                            <div class="radio-input-container d-grid product-container"></div>
                            <input type="number" name="total" class="totalPrice" required hidden>
                        </div>
                    </div>
                </div>
                <div class="row mt-20">
                    <div class="col-sm-6">
                        <div class="form-group sig-price">
                            Harga : <b class="show_detail_nominal"></b>
                             <input type="hidden" name="description_text" class="description_text">
                             <input type="hidden" name="nominal_text" class="nominal_text">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <!-- hidden form -->
                            <button type="submit" id="btn_beli_pulsa" class="ppob-kisel__btn-buy">Selanjutnya</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <!-- END PULSA -->
        <!-- PAKET DATA -->
        <div role="tabpanel" class="tab-pane" id="paketdata">
            <h4 id="form-title">Paket Data</h4>
            <form class="ppob_category_form" action="<?php echo base_url('PPOB/checkout') ?>" method="post">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <span class="ppob-kisel__input-title">Nomor Handphone</span>
                            <div class="input-group">
                                <input type="tel" id="no_hp" name="no_hp" class="form-control ppob-form-input" aria-label="No telepon anda" placeholder="contoh 081234567890" required oninput="getProvidersProduct(this, '6429546220208914432', '')">
                                <span class="input-group-addon"><img class="operator-logo"></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group nominal-container">
                            <span class="ppob-kisel__input-title">Nominal</span>
                            <div class="radio-input-container product-container"></div>
                            <input type="number" name="total" class="totalPrice" required hidden>
                        </div>
                    </div>
                </div>
                <div class="row mt-20">
                    <div class="col-sm-6">
                        <div class="form-group sig-price">
                            Harga : <b class="show_detail_nominal"></b>
                            <input type="hidden" name="description_text" class="description_text">
                            <input type="hidden" name="nominal_text" class="nominal_text">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <!-- hidden form -->
                            <button type="submit" id="btn_beli_pulsa" class="ppob-kisel__btn-buy">Selanjutnya</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <!-- END PAKET DATA -->
        <!-- GO PAY -->
        <div role="tabpanel" class="tab-pane gopay-tab-pane" id="gopay">
            <ul id="go-pay-providers-tab" class="nav ppob-kisel__menu-list" role="tablist"></ul>
            <form class="ppob_category_form" action="<?php echo base_url('PPOB/checkout') ?>" method="post">
                <div id="gopay-content" class="tab-content ppob-kisel__content"></div>
            </form>
        </div>
        <!-- END GO PAY -->
    </div>
</div>

<script type="text/javascript">
    function clearForm() {
        $('.nominal-container').hide();
        $('.product-container').html('');
        $('.ppob-form-input').val('');
        $('.show_detail_nominal').html('(Belum pilih produk)');
        $('.description_text').val('');
        $('.nominal_text').val('');
        const operatorLogo = document.getElementsByClassName('operator-logo');
        for (let i = 0; i < operatorLogo.length; i++) {
            operatorLogo[i].src = '';
        }
    }

    function getProvidersProduct(e, categoryId, providerId) {
        let phoneCarrier = '';
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
                        // LOGO FOR PROVIDER
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

                                                if (categoryId === '6429546220208914432') { // PRODUCT DATA HERE
                                                    let html = '<ul>';
                                                    products.forEach(function (item) {
                                                        html += '<li><label for="radio-' + counter + '" class="d-block paket-data-product clearfix"><div class="radio-container"><input type="radio" name="productId" value="' + item.id + '" id="radio-' + counter + '" title="'+item.name+'" data-displayprice="' + item.displayPrice + '" required></div><div class="product-content-container"><h5>' + item.name + '</h5><p>' + item.description + '</p></div><div class="price-container"><span class="sig-price">Rp ' + item.displayPrice.format() + '</span></div></label></li>';
                                                        counter++;
                                                    });
                                                    html += '</ul>';
                                                    $('.product-container').append(html);
                                                } else {
                                                    products.forEach(function (item) {
                                                        $('.product-container').append('<span class="custom-radio"><label class="label-input" for="radio-' + counter + '">' + item.name + '</label><input id="radio-' + counter + '" type="radio" name="productId" value="' + item.id + '" class="radio-i" title="'+item.name+'" data-displayprice="' + item.displayPrice + '"></span>');
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
                                            html += '<li><label for="radio-' + counter + '" class="d-block paket-data-product clearfix"><div class="radio-container"><input type="radio" name="productId" value="' + item.id + '" id="radio-' + counter + '" data-displayprice="' + item.displayPrice + '" title="' + item.name + '" required></div><div class="product-content-container"><h5>' + item.name + '</h5><p>' + item.description + '</p></div><div class="price-container"><span class="sig-price">Rp ' + item.displayPrice.format() + '</span></div></label></li>';
                                            counter++;
                                        });
                                        html += '</ul>';
                                        $('.product-container').append(html);
                                    } else {
                                        products.forEach(function (item) {
                                            $('.product-container').append('<span class="custom-radio"><label class="label-input" for="radio-' + counter + '">' + item.name + '</label><input id="radio-' + counter + '" type="radio" name="productId" value="' + item.id + '" class="radio-i" title="'+item.name+'" data-displayprice="' + item.displayPrice + '"></span>');
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
                url: '<?php echo base_url('PPOB/getProducts/') ?>' + categoryId,
                method: 'GET'
            }).done(function (data) {
                console.log('BPJS and TOKEN PLN');
                // console.log(data)
            })
        }
    }

    function setActive(e) {
        $('.label-input').removeClass('list-i-active').removeClass('label-i-active');
        e.classList.add('list-i-active');
    }

    $(function () {
        $('.nominal-container').hide();
        $('.show_detail_nominal').html('(Belum pilih produk)');
        $('.product-container').on('change', 'input:radio[name=productId]:checked', function() {
            const totalPrice = parseInt($(this).data('displayprice'));
            const productId = $(this).val();
            // alert(productId);
            const desc_text = $(this).attr('title');
            /*form hidden*/
            $('.description_text').val(desc_text);
            $('.nominal_text').val(totalPrice);
            $('.totalPrice').val(totalPrice);
            $('.show_detail_nominal').html('Rp ' + totalPrice.format());
        }).on('click', '.custom-radio .label-input', function () {
            $('.label-input').removeClass('label-i-active');
            $(this).addClass('label-i-active');
        }).on('click', '.paket-data-product', function() {
            $(this).find('input:radio[name=productId]').attr('checked', 'checked');
        });
        $('#gopay-content').on('click', '.custom-radio .label-input', function () {
            $('.label-input').removeClass('label-i-active');
            $(this).addClass('label-i-active');
            // console.log(event);
            // event.target.classList.add('label-i-active');
            // event.addClass('label-i-active');
            const productId = $(this).next('input:radio[name=productId]').val();
            $('input:text[name=productId]').val(productId);
            // const productId = event.find('input:radio[name=productId]').val();
        });


        // GOPAY TAB CONTENT INITIALIZATION
        let categoryId = '11937975655745122160';

        $('#go-pay-providers-tab').html('');
        $('#gopay-content').html('');

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

                tabContent += '<div role="tabpanel" class="tab-pane ' + (counter === 0 ? 'active' : '') + '" id="gopay-' + counter + '"><form class="ppob_category_form" action="<?php echo base_url('PPOB/checkout') ?>" method="post">'+
'                <input type="text" name="productId" hidden>' +
                    '                    <h4 id="form-title">Top Up ' + item.name + '</h4>\n' +
                    '                        <div class="row">\n' +
                    '                            <div class="col-sm-12">\n' +
                    '                                <div class="form-group">\n' +
                    '                                    <span class="ppob-kisel__input-title">Nomor Handphone</span>\n' +
                    '                                    <div class="input-group">\n' +
                    '                                        <input type="tel" name="no_hp" class="form-control ppob-form-input" aria-label="No telepon anda" placeholder="contoh 081234567890" required oninput="getProvidersProduct(this, \'' + categoryId + '\', \'' + item.id + '\')">\n' +
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
                    '                                    Harga : <b class="show_detail_nominal"></b>\n<input type="hidden" name="description_text" class="description_text">\n' +
                    '                                                <input type="hidden" name="nominal_text" class="nominal_text">' +
                    '                                </div>\n' +
                    '                            </div>\n' +
                    '                            <div class="col-sm-6">\n' +
                    '                                <div class="form-group">\n' +
                    '                                    <!-- hidden form -->\n' +
                    '                                    <button type="submit" class="ppob-kisel__btn-buy">Selanjutnya</button>\n' +
                    '                                </div>\n' +
                    '                            </div>\n' +
                    '                        </div>\n' +
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