<?php $this->load->view('front/header'); ?>
<?php $this->load->view('front/navbar'); ?>

<div class="container">
	<div class="row">
    <div class="col-lg-12">
			<nav aria-label="breadcrumb">
			  <ol class="breadcrumb">
			    <li class="breadcrumb-item"><a href="<?php echo base_url() ?>"><i class="fa fa-home"></i> Home</a></li>
					<li class="breadcrumb-item active">Register</li>
			  </ol>
			</nav>
    </div>

    <div class="col-lg-12"><h1>REGISTER</h1>
			<hr>Sudah punya akun? Silahkan Login <a href="<?php echo base_url('auth/login') ?>">disini</a><hr>
			<?php echo $message;?>
      <?php echo form_open("auth/register");?>
				<div class="row">
          <div class="col-sm-6"><label>Nama</label>
            <?php echo form_input($name);?><br>
          </div>
					<div class="col-sm-6"><label>Username</label>
						<?php echo form_input($username);?><br>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-6"><label>Password</label>
						<?php echo form_password($password);?><br>
					</div>
					<div class="col-sm-6"><label>Konfirmasi Password</label>
						<?php echo form_password($password_confirm);?><br>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-6"><label>No. Hp</label>
						<?php echo form_input($phone);?><br>
					</div>
					<div class="col-sm-6"><label>Email</label>
						<?php echo form_input($email);?><br>
					</div>
				</div>
				<div class="form-group"><label>Alamat</label>
					<?php echo form_textarea($alamat);?>
				</div>
        <div class="row">
          <div class="col-sm-6"><label>Provinsi</label>
            <?php echo form_dropdown('', $ambil_provinsi, '', $provinsi_id); ?><br>
          </div>
          <div class="col-sm-6"><label>Kabupaten/ Kota</label>
            <?php echo form_dropdown('', array(''=>'- Pilih Kota -'), '', $kota_id); ?>
          </div>
        </div>
        <hr>
        <button type="submit" class="btn btn-primary">Submit</button>
        <button type="reset" class="btn btn-warning">Cancel</button>
      <?php echo form_close(); ?>
		</div>
	</div>
</div>

	<?php $this->load->view('front/footer'); ?>
  <script type="text/javascript">
	function tampilKota()
	{
	  provinsi_id = document.getElementById("provinsi_id").value;
	  $.ajax({
		  url:"<?php echo base_url();?>auth/pilih_kota/"+provinsi_id+"",
		  success: function(response){
		    $("#kota_id").html(response);
		  },
		  dataType:"html"
	  });
	  return false;
	}
	</script>
