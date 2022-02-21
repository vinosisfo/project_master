<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>AdminLTE 3 | Log in</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo base_url('assets/AdminLTE-3.1.0/plugins/fontawesome-free/css/all.min.css')?>">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="<?php echo base_url('assets/AdminLTE-3.1.0/plugins/icheck-bootstrap/icheck-bootstrap.min.css')?>">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo base_url('assets/AdminLTE-3.1.0/dist/css/adminlte.min.css')?>">
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <p>Admin LTE</p>
  </div>
  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body">
      <p class="login-box-msg">Sign in to start your session test edit disini</p>

      <?php if(strlen($pesan) > 3) { ?>
        <p class="login-box-msg" style="color : red;"><?php echo $pesan ?>Username / Password Salah</p>
      <?php } ?>

      <form id="form_login" autocomplete="off">
        <div class="input-group mb-3">
          <input type="text" name="username" id="username" class="form-control" placeholder="Username" maxlength="50">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" name="password" id="password" class="form-control" placeholder="Password" maxlength="50">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <!-- /.col -->
          <div class="col-4">
            <button type="button" class="btn btn-primary btn-block" onclick="akses_login(this)">Sign In</button>
          </div>
          <!-- /.col -->
        </div>
      </form>
    </div>
    <!-- /.login-card-body -->
  </div>
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="<?php echo base_url('assets/AdminLTE-3.1.0/plugins/jquery/jquery.min.js')?>"></script>
<!-- Bootstrap 4 -->
<script src="<?php echo base_url('assets/AdminLTE-3.1.0/plugins/bootstrap/js/bootstrap.bundle.min.js')?>"></script>
<!-- AdminLTE App -->
<script src="<?php echo base_url('assets/AdminLTE-3.1.0/dist/js/adminlte.min.js')?>"></script>
</body>
</html>

<script type="text/javascript">
    function akses_login(){
      username = $("#username").val()
      password = $("#password").val()

      if(username==""){
        alert("Username Tidak Boleh Kosong")
        $("#username").focus()
        return false
      }

      if(password==""){
        alert("Password Tidak Boleh Kosong")
        $("#password").focus()
        return false
      }

      url = '<?php echo base_url('welcome/akses_login')?>'
      $('#form_login').attr('action',url);
      $('#form_login').attr('method','post');
      $('#form_login').submit();
    }
</script>