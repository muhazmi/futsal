<!doctype html>
<html lang="en">
<head>
  <title>Reset Password | <?php echo $company_data->company_name;?></title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<!-- core ui -->
	<link href="<?php echo base_url()?>assets/template/frontend/css/theme/simplex.css" rel="stylesheet" type="text/css" />
  <!-- FontAwesome 4.3.0 -->
  <link href="<?php echo base_url()?>assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
  <!-- Favicon -->
  <link rel="shortcut icon" href="<?php echo base_url() ?>assets/images/fav.png" />
</head>
<body>
  <br>
  <div class="container">
    <div class="row justify-content-md-center">
      <div class="col-lg-6">
        <div class="card text-white bg-dark mb-3" style="max-width: 20rem;">

          <h4 class="card-header">Reset Password</h4>
          <div class="card-body">
            <?php echo $this->session->userdata('message') <> '' ? $this->session->userdata('message') : ''; ?>
            <?php echo $message;?>
            <?php echo form_open('auth/reset_password/' . $code);?>
              <div class="form-group has-feedback"><label>Password Baru</label>
                <?php echo form_input($new_password);?>
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
              </div>
              <div class="form-group has-feedback"><label>Konfirmasi Password Baru</label>
                <?php echo form_input($new_password_confirm);?>
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
              </div>
      				<?php echo form_input($user_id);?>
      				<?php echo form_hidden($csrf); ?>
              <button type="submit" name="submit" class="btn btn-primary btn-block btn-flat">Reset</button>
            <?php echo form_close();?>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
