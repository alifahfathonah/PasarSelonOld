<!-- breadcrumb start -->
<div class="breadcrumb-area">
    <div class="container">
        <?php echo $breadcrumbs; ?>
    </div>
</div>
<!-- breadcrumb end -->
<div class="shop-area">
    <div class="container">
        <?php echo Modules::run('Section/sidebar') ?>
        <div class="col-sm-9">
            <div class="content-wrap mb-50">
                <h2 class="page-heading mt-40">
                    <span class="cat-name"><i
                            class="fa fa-user-circle"></i> <?php echo $profile->firstName . " " . $profile->lastName; ?></span>
                </h2>
                <ul class="nav nav-tabs" id="myTabs" role="tablist">
                    <li class="active"><a data-toggle="tab" href="#profil">Profil</a></li>
                    <li><a data-toggle="tab" href="#alamat">Alamat</a></li>
                    <li><a data-toggle="tab" href="#rekening-bank">Rekening Bank</a></li>
                </ul>
                <div class="tab-content tab-content-rwy">
                    <div class="tab-pane active fade in" id="profil">
                        <div class="row">
                            <form id="form-profile" method="post" enctype="multipart/form-data">
                                <div class="col-sm-4">
                                    <div class="profil-foto-wrap">
                                        <span class="upload-foto-loading"><i class="fa fa-cog fa-spin fa-3x fa-fw"></i></span>
<!--                                        <input type="file" name="avatarFile" id="upload-photo">-->
                                        <img id="profile-photo" class="profil-foto" src="<?php if(@getimagesize(AVATAR_FILE.$profile->avatarFile)) { echo AVATAR_FILE.$profile->avatarFile;}else{ echo base_url('assets/img/product/1.jpg'); } ?>"
                                             alt="<?php echo $profile->firstName ?>" width="100%">
                                        <a id="upload-photo" class="btn btn-login">Upload Foto</a>
                                    </div>
                                </div>
                                <div class="col-sm-8">
                                    <h5><b>Ubah Profil</b></h5>

                                    <div class="form-group clearfix">
                                        <div class="col-sm-4">
                                            <label for="fullname">Nama</label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" name="fullname"
                                                   value="<?php echo $profile->firstName . " " . $profile->lastName; ?>"
                                                   disabled>
                                        </div>
                                    </div>
                                    <div class="form-group clearfix">
                                        <div class="col-sm-4">
                                            <label for="fullname">Alias</label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" name="alias" value="<?php echo $profile->alias; ?>">
                                        </div>
                                    </div>

                                    <h5><b>Ubah Kontak</b></h5>

                                    <div class="form-group clearfix">
                                        <div class="col-sm-4">
                                            <label>Email</label>
                                        </div>
                                        <div class="col-sm-8">
                                            <span style="color:#F4A137;"><?php echo $profile2->email; ?></span>
                                        </div>
                                    </div>
                                    <div class="form-group clearfix">
                                        <div class="col-sm-4">
                                            <label>Nomor HP</label>
                                        </div>
                                        <div class="col-sm-8">
                                            <span style="color:#F4A137;"><?php echo $profile->phoneNo; ?> <i
                                                    style="color:greenyellow;" class="fa fa-check"></i></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <button data-loading-text="<i class='fa fa-spinner fa-pulse fa-fw'></i> Simpan"
                                                class="btn pull-right btn-login" type="submit">Simpan
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="alamat">
                        <div class="address-list-wrapper">
                            <button class="btn btn-success btn-tambah-alamat"> <i class="fa fa-plus-circle"></i> Tambah Alamat </button>
                            <table id="table-alamat" class="table table-bordered" width="100%">
                                <thead style="background-color:#f4a137;color:white">
                                    <th width="30px">No</th>
                                    <th>Nama Penerima</th>
                                    <th>No Telp</th>
                                    <th>Alamat Lengkap</th>
                                    <th>Keterangan</th>
                                    <th>Aksi</th>
                                </thead>
                            <tbody>
                            </tbody>
                        </table>

                        </div>
                    </div>
                    <div class="tab-pane fade" id="rekening-bank">
                        <div class="address-list-wrapper">
                            <button class="btn btn-success" onclick="add_bank_account()"> <i class="fa fa-plus-circle"></i> Tambah Bank Account </button>
                            <table id="table_bank_account" class="table table-bordered" width="100%">
                                <thead style="background-color:#f4a137;color:white">
                                    <th width="30px">No</th>
                                    <th width="120px">Nama</th>
                                    <th>No Rekening</th>
                                    <th>Nama Bank</th>
                                    <th>Cabang</th>
                                    <th>Aksi</th>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="success-modal" class="white-popup mfp-hide panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">Tambah Alamat</h3>
    </div>
    <div class="panel-body">
    Thank you
    </div>
</div>

<script type="text/javascript">

    // Javascript for preview photo when upload
    function showPreview(objFileInput) {
//            hideUploadOption();
        if (objFileInput.files[0]) {
            var fileReader = new FileReader();
            fileReader.onload = function (e) {
                $(".profil-foto-wrap").html('<img src="'+e.target.result+'" class="profil-foto" width="100%"/>').css('opacity','0.7');
            };
            fileReader.readAsDataURL(objFileInput.files[0]);
        }
    }

    $(function () {
        <?php if($this->session->userdata('logged_in')) { ?>
        //    Dropzone configuration

        var myDropzone = new Dropzone("#upload-photo", {
            paramName: 'avatar',
            maxFilesize: 2,
            dictFileTooBig: "File is too big ({{filesize}}MiB). Max filesize: {{maxFilesize}}MiB.",
            url: "<?php echo API_DATA ?>Customers/<?php if(!is_null($this->session->userdata('user')->id)) {echo $this->session->userdata('user')->id;} ?>/avatar?access_token=<?php if(!is_null($this->session->userdata('token'))) {echo $this->session->userdata('token'); } ?>",
            success: function(file, serverReponse) {
                $('#profile-photo').attr('src','<?php echo AVATAR_FILE ?>'+serverReponse.result);
                $('#sidebar-user-photo').attr('src','<?php echo AVATAR_FILE ?>'+serverReponse.result);
                $('.upload-foto-loading').hide();
            },
            init: function() {
                this.on('addedfile', function() {
                    $('.upload-foto-loading').show();
                    $('.dz-error-message').hide();
                });
                this.on('error', function(e, response) {
                    alert('Maksimal file 2Mb');
                    $('.upload-foto-loading').hide();
                    $('.dz-error-message').show();
                });
            }
        });

        <?php } ?>

        $('.profil-foto').on('click', function () {
            $('.profil-foto-input').click();
        });

        $('#form-profile').on('submit', function(e) {
            e.preventDefault();
            var form = $(this);
            var data = new FormData($(this)[0]);
            form.find('.btn-login').button('loading');

//            console.log(data);

            $.ajax({
                url: '<?php echo base_url('Profile/editProfile') ?>',
                type: 'post',
                data: data,
                success: function(data) {
                    alert('Sukses update profile');
                    console.log(data);
                    form.find('.btn-login').button('reset');
                },
                error: function(data){
                    console.log("error");
                    console.log(data);
                },
                cache:false,
                contentType: false,
                processData: false
            })
        })
    });
    $(document).ready(function() {
        //        DATATABLES SCRIPT
        var tableAlamat = $('#table-alamat').DataTable( {
            "ajax": "<?php echo base_url('Profile/getAddressList'); ?>",
            "columns": [
                { "data": "no" },
                { "data": "nama_penerima" },
                { "data": "phone" },
                { "data": "alamat_lengkap" },
                { "data": "keterangan"},
                { "data": "aksi" }
            ]
        } );

        $('.btn-tambah-alamat').on('click', function() {
            $('#alamat-kategori').val('tambah');
            save_method = 'add';
            $('#form_address')[0].reset(); // reset form on modals
            $('#modal_form').modal('show'); // show bootstrap modal
            $('.modal-title').text('Tambah Alamat'); // Set Title to Bootstrap modal title
        });
        // SAVE ALAMAT
        $('#form_address').on('submit', function (e) {
            e.preventDefault();
            var form = $(this);
            form.find('#btnSave').button('loading');
            var kategori = $('input[name="form-alamat-kategori"]').val();
            if(kategori === 'tambah') {
                save();
            }else{
                simpan();
            }
        });
        // END OF SAVE ALAMAT

        // DELETE ADDRESS
        $('#table-alamat').on('click', '.btn-delete-address', function(){
            var button = $(this);
            if(confirm('Are you sure delete this data?'))
            {
                // ajax delete data to database
                var addressId = $(this).attr('data-id');
                $.ajax({
                    url : "<?php echo base_url().'Profile/DeleteAddress/'?>"+addressId,
                    type: "POST",
                    dataType: "JSON",
                    success: function(data)
                    {
                        //if success reload ajax table
                        $('#modal_form').modal('hide');
                        console.log(data);
                        tableAlamat.row( button.parents('tr') ).remove().draw();
                    },
                    error: function (data)
                    {
                        console.log(data);
                        alert('Error adding / update data');
                    }
                });
                return true;
            }else{
                return false;
            }
        });
        // END OF DELETE ADDRESS

        // SAVE ALAMAT FUNCTION
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
                    
                    tableAlamat.ajax.reload();
                    $('#form_address')[0].reset();
                    $("#modal_form").modal("hide");
                    $("#modal_success").modal("show");
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    $("#modal_error").modal("show");
                    $("#modal_form").modal("hide");
                }
            });
            $('#form_address').find('#btnSave').button('reset');
        }
        // END OF SAVE ALAMAT FUNCTION

        // SIMPAN ALAMAT EDIT FUNCTION
        function simpan() {
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
                url : '<?php echo base_url('Profile/edit_address_simpan') ?>',
                type: "POST",
                data: $.param(data),
                dataType: "JSON",
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                processData: false,
                success: function(data, textStatus, jqXHR, responseText)
                {
                    tableAlamat.ajax.reload();
                    $('#form_address')[0].reset();
                    $("#modal_form").modal("hide");
                    $("#modal_success").modal("show");
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    $("#modal_error").modal("show");
                    $("#modal_form").modal("hide");
                }
            });
            $('#form_address').find('#btnSave').button('reset');
        }
        // END OF SIMPAN ALAMAT FUNCTION

        $('#table-alamat tbody').on('click', '.btn-edit-address', function () {
            $('input[name="form-alamat-kategori"]').val('edit');
            var id = $(this).attr('data-id');
            $.ajax({
                url : "<?php echo site_url('Profile/edit_address')?>/" + id,
                type: "GET",
                dataType: "JSON",
                success: function(response)
                {

                    $('[name="addressId"]').val(response.id);
                    $('[name="name"]').val(response.name);
                    $('[name="recipientName"]').val(response.recipientName);
                    $('[name="recipientPhone"]').val(response.recipientPhone);
                    $('[name="address"]').val(response.address);
                    $('#kodePos').val(response.zipCode);
                    $('select[name="countryId"]').val(response.countryId);
                    $.getJSON("<?php echo site_url('templates/References/getProvinceByCountry') ?>/" + $('select[name="countryId"]').val(), '', function (data) {
                        $('#provinceId option').remove();
                        $('<option value="">-Silahkan Pilih-</option>').appendTo($('#provinceId'));
                        $.each(data, function (i, o) {
                            $('<option value="' + o.id + '">' + o.name + '</option>').appendTo($('#provinceId'));
                        });
                        $('select[name="provinceId"]').val(response.provinceId);

                        $.getJSON("<?php echo site_url('templates/References/getCityByProvince') ?>/" + $('select[name="provinceId"]').val() + "/" + $('select[name="countryId"]').val(), '', function (data) {
                            $('#cityId option').remove();
                            $('<option value="">-Silahkan Pilih-</option>').appendTo($('#cityId'));
                            $.each(data, function (i, o) {
                                $('<option value="' + o.id + '">' + o.name + '</option>').appendTo($('#cityId'));
                            });
                            $('select[name="cityId"]').val(response.cityId);

                            $.getJSON("<?php echo site_url('templates/References/getDistrictByCity') ?>/" + $('select[name="cityId"]').val() + "/" + $('select[name="provinceId"]').val() , '', function (data) {
                                $('#districtId option').remove();
                                $('<option value="">-Silahkan Pilih-</option>').appendTo($('#districtId'));
                                $.each(data, function (i, o) {
                                    $('<option value="' + o.id + '">' + o.name + '</option>').appendTo($('#districtId'));
                                });
                                $('select[name="districtId"]').val(response.districtId);
                            });
                        });
                    });
                    $('#modal_form').modal('show'); // show bootstrap modal when complete loaded
                    $('.modal-title').text('Alamat ' +response.name); // Set title to Bootstrap modal title
//                    console.log(response);
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    alert('Error get data from ajax');
                }
            });

            //alert( 'You clicked on '+data[1]+'\'s row' );
        } );
//        END OF DATATABLES SCRIPT


        // BANK ACCOUNT DATATABLES
        var tableBank = $('#table_bank_account').DataTable( {
            "ajax": "<?php echo base_url('Profile/getBankAccountList'); ?>",
            "columns": [
                { "data": "no" },
                { "data": "account_name" },
                { "data": "account_no" },
                { "data": "bank_name" },
                { "data": "location" },
                { "data": "aksi" }
            ]
        } );

        $('#table_bank_account tbody').on('click', '.btn-edit-bank', function () {
            $('input[name="form-bank-kategori"]').val('edit');
            var bankId = $( this ).attr('data-id');

            $.ajax({
                url : "<?php echo site_url('Profile/edit_bank_account')?>/" + bankId,
                type: "GET",
                dataType: "JSON",
                success: function(response)
                {
                   console.log(response);
                    $('[name="bankAccountId"]').val(response.id);
                    $('[name="customerId"]').val(response.customerId);
                    $('[name="bankId"]').val(response.bankId);
                    $('[name="location"]').val(response.location);
                    $('[name="accountNo"]').val(response.accountNo);
                    $('[name="accountName"]').val(response.accountName);
                    
                    $('#modal_form_bank_account').modal('show'); // show bootstrap modal when complete loaded
                    
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    alert('Error get data from ajax');
                }
            });
        });

        $('#form_bank_account').on('submit', function(e) {
            e.preventDefault();
            var form = $(this);
            form.find('#btnSave').button('loading');

            $.ajax({
                url : '<?php echo base_url().'Profile/ajax_process_bank_account'?>',
                type: "POST",
                data: $('#form_bank_account').serialize(),
                dataType: "JSON",
                processData: false,
                success: function(data, textStatus, jqXHR, responseText)
                {
                    tableBank.ajax.reload();
                    $('#form_bank_account')[0].reset();
                    $("#modal_form_bank_account").modal("hide");
                    $("#modal_success").modal("show");
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    $("#modal_error").modal("show");
                    $("#modal_form_bank_account").modal("hide");
                }
            });
            $('#form_bank_account')[0].reset();
            form.find('#btnSave').button('reset');
        });

        $('#table_bank_account tbody').on('click', '.btn-delete-bank', function() {
            if(confirm('Are you sure to delete this data?'))
            {
                // ajax delete data to database
                var bankAccountId = $(this).attr('data-id');
                $.ajax({
                    url : "<?php echo base_url().'Profile/DeleteBankAccount/'?>"+bankAccountId,
                    type: "POST",
                    dataType: "JSON",
                    success: function(data)
                    {
                        //if success reload ajax table
                        tableBank.ajax.reload();
                        $('#modal_form_bank_account').modal('hide');
                    },
                    error: function (jqXHR, textStatus, errorThrown)
                    {
                        alert('Error adding / update data');
                    }
                });

            }
        })
    });

    function add_bank_account()
    {
      $('input[name="form-bank-kategori"]').val('tambah');
      $('#form_bank_account')[0].reset(); // reset form on modals
      $('#modal_form_bank_account').modal('show'); // show bootstrap modal
      $('.modal-title').text('Tambah Bank Account'); // Set Title to Bootstrap modal title
    }
  </script>

<!-- Bootstrap modal -->
<div class="modal fade" id="modal_form" role="dialog">
<div class="modal-dialog">
<div class="modal-content">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h3 class="modal-title">Alamat</h3>
  </div>
  <div class="modal-body form">
    <form id="form_address" class="form-horizontal" method="post">
      <input type="hidden" value="" name="id"/> 
      <div class="form-body">
          <input type="hidden" id="alamat-kategori" name="form-alamat-kategori" value="tambah">
        <div class="form-group">
            <label class="col-sm-3" for="City">Nama</label>
            <div class="col-sm-8">
                <input type="text" class="form-control" name="name" id="name" value="<?php echo set_value('name') ?>" required><?php echo form_error('name') ?>
                <input type="hidden" class="form-control" name="addressId" id="addressId" value="<?php echo set_value('addressId') ?>"><?php echo form_error('addressId') ?>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3" for="City">Nama Penerima</label>
            <div class="col-sm-8">
                <input type="text" class="form-control" name="recipientName" id="recipientName" value="<?php echo set_value('recipientName') ?>" required><?php echo form_error('recipientName') ?>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3" for="City">No Telp Penerima</label>
            <div class="col-sm-5">
                <input type="number" class="form-control" name="recipientPhone" id="recipientPhone" value="<?php echo set_value('recipientPhone') ?>" required><?php echo form_error('recipientPhone') ?>
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
            <label class="col-sm-3" for="Province">*Provinsi</label>

            <div class="col-sm-6">
                <?php echo $this->master->get_change($params = array('table' => 'Province', 'id' => 'id', 'name' => 'name', 'where' => array()), '' , 'provinceId', 'provinceId', 'form-control', 'required', '') ?>
                <?php echo form_error('Province') ?>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-3" for="City">*Kota/Kab</label>

            <div class="col-sm-6">
                <?php echo $this->master->get_change($params = array('table' => 'City', 'id' => 'id', 'name' => 'name', 'where' => array()), '' , 'cityId', 'cityId', 'form-control', 'required', '') ?>
                <?php echo form_error('City') ?>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-3" for="District">*Kecamatan</label>

            <div class="col-sm-6">
                <?php echo $this->master->get_change($params = array('table' => 'District', 'id' => 'id', 'name' => 'name', 'where' => array()), '' , 'districtId', 'districtId', 'form-control', 'required', '') ?>
                <?php echo form_error('District') ?>
            </div>
        </div>

          <div class="form-group">
              <label class="col-sm-3" for="District">*Kode Pos</label>

              <div class="col-sm-6">
                  <input type="number" class="form-control" id="kodePos" name="kodePos" required><?php echo form_error('kodePos') ?>
              </div>
          </div>

        <div class="form-group">
            <label class="col-sm-3" for="City">* Alamat</label>

            <div class="col-sm-9">
                <textarea class="form-control" name="address" id="address" cols="10"
                  rows="3" required><?php echo set_value('address') ?></textarea><?php echo form_error('address') ?>
            </div>
        </div>
      </div>
      </div>
      <div class="modal-footer">
        <button type="submit" id="btnSave" class="btn btn-primary" data-loading-text="<i class='fa fa-spinner fa-pulse fa-fw'></i> Tambahkan">Tambahkan</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
      </div>
    </form>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<!-- Bootstrap modal -->
<div class="modal fade" id="modal_form_bank_account" role="dialog">
<div class="modal-dialog">
<div class="modal-content">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h3 class="modal-title">Bank Account</h3>
  </div>
  <div class="modal-body form">
    <form id="form_bank_account" class="form-horizontal" method="post">
      <input type="hidden" value="" name="id"/> 
      <div class="form-body">
          <input type="hidden" name="form-bank-kategori" value="tambah">
        <div class="form-group">
            <label class="col-sm-4" for="City">Nama Pemilik Rekening</label>
            <div class="col-sm-8">
                <input required type="text" class="form-control" name="accountName" id="accountName" value=""><?php echo form_error('accountName') ?>
                <input type="hidden" class="form-control" name="customerId" id="customerId" value="<?php echo set_value('customerId') ?>"><?php echo form_error('customerId') ?>
                <input type="hidden" class="form-control" name="bankAccountId" id="bankAccountId" value="<?php echo set_value('bankAccountId') ?>"><?php echo form_error('bankAccountId') ?>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-4" for="City">No Rekening</label>
            <div class="col-sm-8">
                <input required type="number" class="form-control" name="accountNo" id="accountNo" value=""><?php echo form_error('accountNo') ?>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-4" for="bankId">Nama Bank</label>
            <div class="col-sm-4">
                <select class="form-control" name="bankId" id="bankId" required="">
                    <?php foreach($bankList as $blist) { ?>
                        <option value="<?php echo $blist->id ?>"><?php echo $blist->name; ?></option>
                    <?php } ?>
                </select>
                <?php echo form_error('bankId') ?>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-4" for="City">Cabang</label>
            <div class="col-sm-4">
                <input required type="text" class="form-control" name="location" id="location" value=""><?php echo form_error('location') ?>
            </div>
        </div>

      </div>
        <div class="modal-footer">
            <button type="submit" id="btnSave" class="btn btn-primary" data-loading-text="<i class='fa fa-spinner fa-pulse fa-fw'></i> Save">Save</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        </div>
    </form>
    </div>
  </div>
</div>
</div>


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
        Error process
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

  <!-- End Bootstrap modal -->