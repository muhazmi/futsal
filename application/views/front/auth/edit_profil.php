<?php $this->load->view('front/header'); ?>
<?php $this->load->view('front/navbar'); ?>

<div class="container">
	<div class="row">
    <div class="col-lg-12">
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="<?php echo base_url() ?>"><i class="fa fa-home"></i> Home</a></li>
					<li class="breadcrumb-item active">Edit Profil</li>
				</ol>
			</nav>
    </div>

		<div class="col-lg-12"><h1>EDIT PROFIL</h1><hr>
			<?php echo validation_errors() ?>
			<?php echo form_open(uri_string());?>
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
			      <?php echo form_input($password);?><br>
			    </div>
					<div class="col-sm-6"><label>Konfirmasi Password</label>
			      <?php echo form_input($password_confirm);?><br>
			    </div>
				</div>
				<div class="row">
			    <div class="col-sm-6"><label>No. HP</label>
			      <?php echo form_input($phone);?><br>
			    </div>
			    <div class="col-sm-6"><label>Email</label>
			      <?php echo form_input($email);?><br>
			    </div>
				</div>
		    <div class="form-group"><label>Alamat</label>
					<?php echo form_textarea($address);?>
		    </div>
				<div class="row">
					<div class="col-sm-6"><label>Provinsi</label>
						<?php echo form_dropdown('',$ambil_provinsi,$user->provinsi,$provinsi_id);?><br>
					</div>
					<div class="col-sm-6"><label>Kabupaten/ Kota</label>
						<?php echo form_dropdown('',$ambil_kota,$user->kota,$kota_id);?><br>
					</div>
				</div>
				<?php echo form_hidden('id', $user->id);?>
				<button type="submit" name="submit" class="btn btn-primary">Update</button>
				<button type="reset" name="reset" class="btn btn-danger">Reset</button>
			<?php echo form_close() ?>
		</div>
	</div>
</div>
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

<?php $this->load->view('front/footer'); ?>
