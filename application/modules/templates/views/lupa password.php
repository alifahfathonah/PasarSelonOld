<html>
  <head>
  <title>Lupa Password - Pasar Selon</title>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://pasarselon.com/assets/jquery-validation/css/screen.css" />
  <script src="https://pasarselon.com/assets/jquery-validation/lib/jquery.js"></script>
  <script src="https://pasarselon.com/assets/jquery-validation/dist/jquery.validate.js"></script>

  <script>
  $.validator.setDefaults({
    submitHandler: function() { 
      url = $('#reset_password_form').attr('action');

       // ajax adding data to database
          $.ajax({
            url : url,
            type: "POST",
            data: $('#reset_password_form').serialize(),
            dataType: "JSON",
            success: function(data)
            { 

              alert('success');
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
              alert('error');
            }
        });
    }
  });

  $().ready(function() {

    // validate signup form on keyup and submit
    $("#reset_password_form").validate({
      rules: {
        
        password: {
          required: true
        },
        confirmation: {
          required: true,
          equalTo: "#password"
        }
      },
      messages: {
        password: {
          required: "Silahkan masukan kata sandi baru !"
        },
        confirmation: {
          required: "Silahkan ulangi kata sandi !",
          equalTo: "Kata sandi anda tidak sesuai !"
        }
      }
    });

  });
  </script>

  <style type="text/css">
  #reset_password_form { width: 670px; }
  #reset_password_form label.error {
    margin-left: 10px;
    width: auto;
    display: inline;
  }
  </style>
</head>

<body style="margin: 0; padding: 0;font-family:sans-serif;" cz-shortcut-listen="true">
  <!-- sub-subject -->
  <div id="cfs_div_2" style="position: relative; width: 100%; border: 0px; padding: 0px; top: 0px;">
    <p style="font-size:0px">Pasar Selon</p>
    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background: #F7F7F7;">
      <!-- margin top-bottom -->
      <tbody>
        <tr>
          <td>
            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin: 10px 0 20px;">
              <tbody>
                <tr>
                  <td></td>
                </tr>
              </tbody>
            </table>
          </td>
        </tr>
        <!-- end of margin -->
        <!-- email body -->
        <tr>
          <td>
            <table align="center" border="0" cellpadding="0" cellspacing="0" width="600" style="border-collapse: collapse;background-color: #FFFFFF; color: #4F4F4F;">
              <!-- header -->
              <tbody>
                <tr>
                  <td style="padding: 15px 0;" bgcolor="#F7F7F7">
                    <table cellspacing="0" cellpadding="0" width="100%" style="border-collapse: collapse;">
                      <tbody>
                        <tr>
                          <td align="center">
                            <a href="https://api.pasarselon.com/reset-password?access_token=BgwPVK5EptNATgH53zk4DBsP3mx7CNzuBxw5zrz0xw6WR759VYFWAJsPQCO390zh#">
                              <img src="./Lupa Password - Pasar Selon_files/logo_2.png" alt="Logo" height="40">
                            </a>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </td>
                </tr>
                <!-- end of header -->
                <!-- navigation -->
                <tr>
                  <td>
                    <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#f39200" style="border-collapse:collapse; font-size:12px;">
                      <tbody>
                        <tr>
                          <td width="25%" height="20" align="center"> </td>
                        </tr>
                      </tbody>
                    </table>
                  </td>
                </tr>
                <!-- end of navigation -->
                <!-- content -->
                <tr>
                  <td>
                    <div style="border-radius:4px; padding:1%">
                      <div style="color:#505050;border-radius:4px; background:#fff; padding:10px 3% 10px 3%">
                        <p style="font-size:14px;line-height:1.6em;">
                          Silahkan Input Password Baru Anda
                        </p>
                        <form action="https://api.pasarselon.com/reset-password?access_token=BgwPVK5EptNATgH53zk4DBsP3mx7CNzuBxw5zrz0xw6WR759VYFWAJsPQCO390zh" class="form-horizontal form-register box box-white box-shadow mb-30" id="reset_password_form" name="form_change_password" method="post">
                            <hr>
                            <br>
                            <div class="control-group">
                              <label class="control-label" for="password" autofocus="autofocus">Kata Sandi Baru</label>
                              <div class="controls">
                                <input type="password" name="password" id="password" autofocus="autofocus">
                              </div>
                            </div>
                            <br>
                            <div class="control-group">
                              <label class="control-label" for="confirmation">Ulangi Kata Sandi</label>
                              <div class="controls">
                                <input type="password" name="confirmation" id="confirmation" class="input-login span12" autocomplete="off">
                              </div>
                            </div>
                            <br>
                            <hr>
                            
                          </div></div></td></tr><tr>
                              <td style="background:#db2a06; border-radius:5px;text-align:center; display: block; width: 40%; margin: 0 auto 40px;" align="center">
                                <input type="submit" value="Ubah Kata Sandi" style="background: transparent; border: none; display:block; width: 100%; padding:15px 10px;text-align:center;color:#fff;font-size:15px;font-weight:bold; text-decoration:none">
                              </td>
                            </tr>
                            <!-- anti-spam -->
                            </form>
                      
                    
                  
                
                <!-- anti-spam -->
                <tr>
                  <td>
                    <table cellspacing="0" cellpadding="0" border="0" width="100%" style="border-collapse:collapse; background-color: #F7F7F7; font-size:13px; color: #999999; border-top: 1px solid #DDDDDD;">
                      <tbody>
                        <tr>
                          <td width="560" align="center" style="padding: 10px 20px 30px;"> ©2017, Pasar Selon</td>
                        </tr>
                      </tbody>
                    </table>
                  </td>
                </tr>
                <!-- end of anti-spam -->
              </tbody>
            </table>
          </td>
        </tr>
        <!-- end of email body -->
      </tbody>
    </table>
  </div>

</body>
</html>