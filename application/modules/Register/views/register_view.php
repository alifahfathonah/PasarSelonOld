<!-- SCRIPT FOR SAME CONFIRMATION PASSWORD -->
<script type="text/javascript">
    $(function() {
        $('.not-same-p').hide();
        $('.not-same-p-merchant').hide();
        $('input[type=password]').on('keyup', function() {
            if ($('#password').val() === $('#confirmPassword').val()) {
                $('.not-same-p-merchant').hide();
                $('#btn-submit-merchant').attr('disabled', false);
            } else {
                $('.not-same-p-merchant').show();
                $('#btn-submit-merchant').attr('disabled', true);
            }

            if ($('#password_user').val() === $('#conf_password_user').val()) {
                $('.not-same-p').hide();
                $('#btn-register-customer').attr('disabled', false);
            } else {
                $('.not-same-p').show();
                $('#btn-register-customer').attr('disabled', true);
            }
        });
        /*
        const ktpPembeli = document.getElementById('berkas-ktp-customer'),
                ktpPedagang = document.getElementById('berkas-ktp-merchant'),
                berkasSelfiePedagang = document.getElementById('berkas-selfie-merchant');
        ktpPembeli.addEventListener("change", handleFiles, false);
        ktpPedagang.addEventListener("change", handleFiles, false);
        berkasSelfiePedagang.addEventListener("change", handleFiles, false);
        
        function handleFiles(event){
            const fileList = this.files,
                  allowedExtensions = /(\.jpeg|\.JPEG|\.gif|\.GIF|\.png|\.PNG|\.jpg|\.JPG)$/;
            if(!fileList.length){
                fileList.innerHTML = "<p>Tidak ada berkas yang dipilih!</p>";
            } else {
                const fileName = fileList[0].name;
                if(!allowedExtensions.exec(fileName)){
                    console.log(fileList[0]);
                    //alert("Maaf hanya gambar saja yang diperbolehkan");
                    swal("Kesalahan jenis berkas", "Maaf hanya gambar saja yang diperbolehkan!");
                    $(event.target).val("");
                    return false;
                }
            }
        }
        */
        const ktpPembeli = document.getElementById('berkas-ktp-customer'),
                ktpPedagang = document.getElementById('berkas-ktp-merchant'),
                berkasSelfiePedagang = document.getElementById('berkas-selfie-merchant');
        ktpPembeli.addEventListener("change", validFileType, false);
        ktpPedagang.addEventListener("change", validFileType, false);
        berkasSelfiePedagang.addEventListener("change", validFileType, false);

        const fileTypes = ["image/apng","image/bmp", "image/gif", "image/jpeg", "image/pjpeg", "image/png", "image/svg+xml", "image/tiff", "image/webp","image/x-icon"];

        function validFileType(event){
            if(fileTypes.includes(event.target.files.item(0).type) === false) {
                swal("Kesalahan jenis berkas", "Maaf hanya gambar saja yang diperbolehkan!","error");
                $(event.target).val("");
                return false;
            }
            return true;
        }
    });

</script>
<!-- END OF SCRIPT FOR SAME CONFIRMATION PASSWORD -->

<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function() {

        $('#form-merchant').on('submit', function(e) {
            e.preventDefault();
            //            $('#form-merchant')[0].reset();

            if ($('#password').val() === $('#confirmPassword').val()) {
                $('.not-same-p').hide();
                $('#btn-submit-merchant').attr('disabled', false);

                //    LOGIC REGISTER MERCHANT API
                if (confirm("Apakah anda yakin mendaftar sebagai merchant?")) {
                    url = "<?php echo API_DATA . 'Merchants/register' ?>";
                    var data = {
                        email: $('#email').val(),
                        name: $('#name').val(),
                        username: $('#username').val(),
                        password: $('#password').val(),
                        firstName: $('#firstName').val(),
                        lastName: $('#lastName').val(),
                        phoneNo: $('#phoneNo').val(),
                        sex: $('input[name=sex]:checked').val(),
                        birthday: $('#birthday').val(),
                        address: $('#address').val(),
                        countryId: $('#countryId').val(),
                        provinceId: $('#provinceId').val(),
                        cityId: $('#cityId').val(),
                        districtId: $('#districtId').val(),
                        zipCode: $('#zipCode').val(),
                        reference: $('#reference').val()
                    };

                    $.ajax({
                        url: url,
                        type: "POST",
                        data: $.param(data),
                        dataType: "JSON",
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        processData: false,
                        success: function(data, textStatus, jqXHR, responseText) {
                            $('#form-merchant')[0].reset();
                            $("#merchant-modal").modal("show");
                        },
                        error: function(xhr, textStatus, errorThrown) {
                            var err = eval("(" + xhr.responseText + ")");
                            alert(err.error.message);
                        }
                    });

                    /*url_send_mail = "Register/sendmail";
                     $.ajax({
                     url : url,
                     type: "POST",
                     data: $.param(data),
                     dataType: "JSON",
                     headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                     processData: false,
                     success: function(data, textStatus, jqXHR, responseText)
                     {
                     $('#form-merchant')[0].reset();
                     $("#download_modal").modal("show");
                     },
                     error: function (jqXHR, textStatus, errorThrown)
                     {
                     alert('error');
                     }
                     });*/
                }
                //    END OF LOGIC REGISTER MERCHANT API
            } else {
                $('.not-same-p').show();
                $('#btn-submit-merchant').attr('disabled', true);
            }
        });

        $('#form-register-user').on('submit', function(e) {
            e.preventDefault();

            if ($('#password_user').val() === $('#conf_password_user').val()) {
                $('.not-same-p').hide();
                $('#btn-register-customer').attr('disabled', false);

                //    LOGIC REGISTER USER API
                if (confirm("Apakah anda yakin mendaftar sebagai customer?")) {
                    url = "<?php echo API_DATA ?>Customers/register";
                    var data = {
                        email: $('#email_user').val(),
                        username: $('#username_user').val(),
                        password: $('#password_user').val(),
                        firstName: $('#firstName_user').val(),
                        lastName: $('#lastName_user').val(),
                        phoneNo: $('#phoneNo_user').val(),
                        sex: $('input[name=sex_user]:checked').val(),
                        birthday: $('#birthday_user').val(),
                        phoneNoTsel: $('#phoneNoTsel_user').val()
                    };

                    $.ajax({
                        url: url,
                        type: "POST",
                        data: $.param(data),
                        dataType: "JSON",
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        processData: false,
                        success: function(data, textStatus, jqXHR, responseText) {
                            $('#form-register-user')[0].reset();
                            $("#customer-modal").modal("show");
                            //                      window.location.href="";
                        },
                        error: function(xhr, textStatus, errorThrown) {
                            var err = eval("(" + xhr.responseText + ")");
                            alert(err.error.message);
                        }
                    });
                }
                //   END OF LOGIC REGISTER USER API
            } else {
                $('.not-same-p').show();
                $('#btn-register-customer').attr('disabled', true);
            }
        });

    });
</script>

<div align="center" id="customer-modal" class="modal fade bs-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content" id="dialog-download">
            <img style="margin:15px 0;" src="<?php echo base_url('assets/img/logo_2.png') ?>" alt="">
            <h2>Terima Kasih</h2>
            <hr />
            <p style="margin-top: -10px; padding: 15px; font-size: 11px;">Terima kasih telah mendaftar sebagai customer kami. Silahkan cek email untuk konfirmasi</p>
        </div>
    </div>
</div>

<div align="center" id="merchant-modal" class="modal fade bs-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content" id="dialog-download">
            <img style="margin:15px 0;" src="<?php echo base_url('assets/img/logo_2.png') ?>" alt="">
            <h2>Terima Kasih</h2>
            <hr />
            <p style="margin-top: -10px; padding: 15px; font-size: 11px;">Terima kasih telah mendaftar sebagai merchant kami. Silahkan cek email anda untuk konfirmasi dan tunggu konfirmasi tim kami.</p>
        </div>
    </div>
</div>

<!-- breadcrumb start -->
<div class="breadcrumb-area">
    <div class="container">
        <!-- <ol class="breadcrumb">
            <li><a href="index.html"><i class="fa fa-home"></i> Home</a></li>
            <li class="active">Login</li>
        </ol> -->
        <?php echo $breadcrumbs ?>
    </div>
</div>
<!-- breadcrumb end -->
<!-- register area start -->
<div class="account-area pt-30 pb-30 log">
    <div class="container">
        <div class="row pro-info-box register-wrapper">
            <div class="col-sm-6 col-sm-offset-3">
                <ul class="nav nav-tabs registrasi-tabs" id="myTabs" role="tablist">
                    <li class="active"><a data-toggle="tab" href="#user">Customer</a></li>
                    <li><a data-toggle="tab" href="#merchant">Merchant</a></li>
                </ul>
                <div class="tab-content" id="register-content">
                    <div class="tab-pane fade" id="merchant">
                        <div class="account-info pb-30">
                            <div class="panel panel-default panel-login">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Daftarkan menjadi Merchant</h3>
                                </div>
                                <div class="panel-body">
                                    <p align="center" style="margin-top: 15px;">Halaman pendaftaran merchant halalshopping-mykisel.com isi data anda dan kami akan proses pengajuan anda.</p>
                                    <form id="form-merchant" class="clearfix" method="post">
                                        <br>
                                        <div class="form-fields">
                                            <div class="form-group">
                                                <label for="email">
                                                    Alamat Email
                                                    <span class="required">*</span>
                                                </label>
                                                <input id="email" type="email" required="required" name="email" placeholder="fulan@example.com">
                                            </div>
                                            <div class="form-group">
                                                <label for="username">
                                                    Nama Pengguna
                                                    <span class="required">*</span>
                                                </label>
                                                <input id="username" pattern="^[a-z0-9_]*[a-z0-9/S]$" title="Nama pengguna hanya huruf kecil alphanumeric, underscore '_', dan tidak boleh ada spasi" type="text" required="required" name="username" placeholder="satya_bima">
                                            </div>
                                            <div class="form-group">
                                                <label for="firstname">
                                                    Nama Depan
                                                    <span class="required">*</span>
                                                </label>
                                                <input id="firstName" type="text" required="required" name="firstName" placeholder="fulan">
                                            </div>
                                            <div class="form-group">
                                                <label for="lastname">
                                                    Nama Belakang
                                                    <span class="required">*</span>
                                                </label>
                                                <input id="lastName" type="text" required="required" name="lastName" placeholder="bin fulan">
                                            </div>
                                            <div class="form-group">
                                                <label for="phoneNo">
                                                    Nomor Handphone 
                                                    <span class="required">*</span>
                                                </label>
                                                <input id="phoneNo" type="number" required="required" name="phone" placeholder="0812xxxxxxxx">
                                            </div>
                                            <div class="form-group">
                                                <label for="birthday">
                                                    Tanggal Lahir
                                                    <span class="required">*</span>
                                                </label>
                                                <input class="datepicker" id="birthday" type="text" required="required" name="birthday" placeholder="klik untuk menampilkan kalender">
                                            </div>
                                                <label class="radio-inline">
                                                    <input type="radio" name="sex" value="F">
													Wanita
												</label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="sex" value="M">
													Pria
												</label>
                                            <div class="form-group">
                                                <label for="name">
                                                    Nama Toko
                                                    <span class="required">*</span>
                                                </label>
                                                <input id="name" type="text" required="required" name="name" placeholder="toko jaya makmur">
                                            </div>
                                            <div class="form-group">
                                                <label for="Country">* Negara</label>
												<?php echo $this->master->get_custom($params = array('table' => 'Country', 'id' => 'id', 'name' => 'name', 'where' => array()), '', 'countryId', 'countryId', '', 'required', '') ?>
                                            </div>
                                            <div class="form-group">
                                                <label for="Province">
                                                    Propinsi
                                                    <span class="required">*</span>
                                                </label>
                                                <?php echo $this->master->get_change($params = array('table' => 'Province', 'id' => 'id', 'name' => 'name', 'where' => array()), '', 'provinceId', 'provinceId', '', 'required', '') ?>
                                            </div>
                                            <div class="form-group">
                                                <label for="City">
                                                    Kota/Kabupaten
                                                    <span class="required">*</span>
                                                </label>
                                                <?php echo $this->master->get_change($params = array('table' => 'City', 'id' => 'id', 'name' => 'name', 'where' => array()), '', 'cityId', 'cityId', '', 'required', '') ?>
                                            </div>
                                            <div class="form-group">
                                                <label for="District">
                                                    Kecamatan
                                                    <span class="required">*</span>
                                                </label>
                                                <?php echo $this->master->get_change($params = array('table' => 'District', 'id' => 'id', 'name' => 'name', 'where' => array()), '', 'districtId', 'districtId', '', 'required', '') ?>
                                            </div>
                                            <div class="form-group">
                                                <label for="marketId">
                                                    Nama Pasar
                                                    <!--<span class="required">*</span>-->
                                                </label>
                                                <?php echo $this->master->get_change($params = array('table' => 'Market', 'id' => 'id', 'name' => 'name', 'where' => array()), '', 'marketId', 'marketId', '', '', '') ?>
                                            </div>
											<div class="form-group">
												<label for="berkas-ktp">Berkas Kartu Tanda Penduduk</label>
												<input type="file" id="berkas-ktp-merchant" name="berkas-ktp" accept="image/*;capture=camera">
												<p class="help-block">Silakan Ungguh berkas Kartu Tanda Penduduk</p>
											</div>
											<div class="form-group">
												<label for="berkas-selfie">Berkas Foto Selfie</label>
												<input type="file" id="berkas-selfie-merchant" accept="image/*;capture=camera" name="berkas-selfie">
												<p class="help-block">Silahkan ambil gambar diri Anda.</p>
											</div>
                                            <div class="form-group">
                                                <label for="address">
                                                    Alamat
                                                    <span class="required">*</span>
                                                </label>
                                                <textarea class="" name="address" id="address" cols="10" rows="3" placeholder="jl. Cendrawasih 2 No 20 RT 002/03"></textarea>
                                            </div>
                                            <div class="form-group">
                                                <label for="password">
                                                    Kode Pos
                                                    <span class="required">*</span>
                                                </label>
                                                <input id="zipCode" type="text" required="required" name="zipCode">
                                            </div>
                                            <div class="form-group">
                                                <label for="password">
                                                    Kata Sandi
                                                    <span class="required">*</span>
                                                </label>
                                                <input id="password" type="password" required="required" name="password">
                                            </div>
                                            <div class="form-group">
                                                <label for="password">
                                                    Konfirmasi Kata Sandi
                                                    <span class="required">*</span>
                                                </label>
                                                <input id="confirmPassword" type="password" required="required" name="konfirmasi_password">
                                                <p class="mt-10 text-danger not-same-p-merchant">Password tidak sama</p>
                                            </div>
                                            <div class="form-group">
                                                <label for="password">
                                                    Referensi
                                                    <span class="required"></span>
                                                </label>
                                                <input id="reference" type="text" name="referensi">
                                            </div>
                                        </div>
                                        <div class="form-action">
                                            <button type="submit" href="#" class="btn pull-right btn-login" id="btn-submit-merchant">Daftarkan Saya sekarang</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane active fade in" id="user">
                        <div class="account-info pb-30">
                            <div class="panel panel-default panel-login">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Daftar menjadi Customer</h3>
                                </div>
                                <div class="panel-body">
                                    <p align="center" style="margin-top: 15px;">Halaman pendaftaran customer, silahkan masukkan data diri Anda yang sesuai.</p>
                                    <form id="form-register-user" class="clearfix" method="post">
                                        <br>
                                        <div class="form-fields">
                                            <div class="form-group">
                                                <label for="email">
                                                    Alamat Email 
                                                    <span class="required">*</span>
                                                </label>
                                                <input id="email_user" type="email" required="required" name="emailUser" placeholder="fulan.bin.fulan@example.com">
                                            </div>
                                            <div class="form-group">
                                                <label for="firstname">
                                                    Nama Depan
                                                    <span class="required">*</span>
                                                </label>
                                                <input id="firstName_user" type="text" required="required" name="firstNameUser" placeholder="fulan">
                                            </div>
                                            <div class="form-group">
                                                <label for="lastname">
                                                    Nama Belakang
                                                    <span class="required">*</span>
                                                </label>
                                                <input id="lastName_user" type="text" required="required" name="lastNameUser" placeholder="bin fulan">
                                            </div>
                                            <div class="form-group">
                                                <label for="phoneNo">
                                                    Nomor Handphone 
                                                    <span class="required">*</span>
                                                </label>
                                                <input id="phoneNo_user" type="number" required="required" name="phoneUser" placeholder="0812xxxxxxxx">
                                            </div>
                                            <div class="form-group">
                                                <label for="birthday">
                                                    Tanggal Lahir
                                                    <span class="required">*</span>
                                                </label>
                                                <input class="datepicker" id="birthday_user" type="text" required="required" name="birthday" placeholder="klik untuk menampilkan kalender">
                                            </div>
													<label class="radio-inline">
														<input type="radio" name="sex_user" value="F">
														Wanita
													</label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="sex_user" value="M">
													Pria
												</label>
                                            <div class="form-group">
                                                <label for="username">
                                                    Nama Pengguna
                                                    <span class="required">*</span>
                                                </label>
                                                <input id="username_user" pattern="^[a-z0-9_]*[a-z0-9/S]$" title="Username hanya huruf kecil alphanumeric, underscore '_', dan tidak boleh ada spasi" type="text" required="required" name="usernameUser" placeholder="satyaBima">
                                            </div>
                                            <div class="form-group">
                                                <label for="password">
                                                    Kata Sandi
                                                    <span class="required">*</span>
                                                </label>
                                                <input id="password_user" type="password" required="required" name="passwordUser">
                                            </div>
                                            <div class="form-group">
                                                <label for="password">
                                                    Konfirmasi Kata Sandi
                                                    <span class="required">*</span>
                                                </label>
                                                <input id="conf_password_user" type="password" required="required" name="konfirmasi_passwordUser">
                                                <p class="mt-10 text-danger not-same-p">Kata Sandi tidak sama</p>
                                            </div>
											<div class="form-group">
												<label for="berkas-ktp">
													Unggah Berkas Kartu Tanda Penduduk
												</label>
												<input type="file" name="berkas-ktp" accept="image/*;capture=camera" id="berkas-ktp-customer">
												<p class="help-block">Silahkan ambil gambar diri Anda.</p>
											</div>
                                        </div>
                                        <div class="form-action">
                                            <!--<button 
                                               type="submit" 
                                               href="#" 
                                               class="g-recaptcha btn pull-right btn-login" 
                                               id="btn-register-customer"
                                               data-sitekey="6LdtPK0ZAAAAAECa_o1QGpldrCxBD_QzPdfBr5a4"
                                               data-callback="onSubmitRegisterUser"
                                               data-action="submit" 
                                               >
                                               Daftarkan Saya Sekarang
                                            </button>
                                            -->
                                            <button type="submit" href="#" id="btn-register-customer" class="btn pull-right btn-login"> Daftarkan Saya Sekarang </button>
                                        </div>
                                        <!--<script src="https://www.google.com/recaptcha/api.js"></script>-->
                                        <!--<script>
                                        /*
                                        function onSubmitRegisterUser(){
                                            document.getElementById('#form-register-user').submit();
                                        }
                                        */
                                        </script>-->
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<!-- register area end -->
<br><br>
