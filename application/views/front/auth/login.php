<?php $this->load->view('front/header'); ?>
<?php $this->load->view('front/navbar'); ?>
<?php echo $script_captcha; // javascript recaptcha ?>

<div class="container">
	<div class="row">
    <div class="col-sm-12 col-lg-12">
			<nav aria-label="breadcrumb">
			  <ol class="breadcrumb">
			    <li class="breadcrumb-item"><a href="<?php echo base_url() ?>"><i class="fa fa-home"></i> Home</a></li>
					<li class="breadcrumb-item active">Login</li>
			  </ol>
			</nav>
    </div>
    <div class="col-lg-12"><h1>LOGIN</h1>
			<hr>Belum punya akun? Silahkan Register <a href="<?php echo base_url('auth/register') ?>">disini</a><hr>
			<div class="row">
			  <div class="col-lg-12">
					<?php echo $message;?>
					<?php echo form_open("auth/login");?>
						<div class="form-group has-feedback"><label>Email</label>
							<?php echo form_input($identity) ?>
							<span class="glyphicon glyphicon-user form-control-feedback"></span>
						</div>
						<div class="form-group has-feedback"><label>Password</label>
							<?php echo form_password($password); ?>
							<span class="glyphicon glyphicon-lock form-control-feedback"></span>
						</div>
						<p><?php echo $captcha ?></p>
						<?php echo lang('login_remember_label', 'remember');?>
						<?php echo form_checkbox('remember', '1', FALSE, 'id="remember"');?> | <button type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target="#pswreset">Lupa Password?</button>
						<hr>
						<div class="form-group">
							<button type="submit" name="submit" class="btn btn-primary">Login</button>
							<button type="reset" name="reset" class="btn btn-danger">Reset</button>
						</div>
					<?php echo form_close();?>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="pswreset" role="dialog"  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Reset Password</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
			<?php echo form_open("auth/forgot_password");?>
      <div class="modal-body">
				<div class="form-group"><label>Silahkan Masukkan Username Anda</label>
					<input class="form-control" name="identity" type="text" id="identity" />
				</div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Kirim</button>
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
			<?php echo form_close() ?>
    </div>
  </div>
</div>


<?php $this->load->view('front/footer'); ?>
