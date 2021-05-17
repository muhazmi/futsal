<?php $this->load->view('front/header'); ?>
<?php $this->load->view('front/navbar'); ?>
  <div class="container">
  	<div class="row">
      <div class="col-sm-12 col-lg-12">
  			<nav aria-label="breadcrumb">
  			  <ol class="breadcrumb">
  			    <li class="breadcrumb-item"><a href="<?php echo base_url() ?>"><i class="fa fa-home"></i> Home</a></li>
  					<li class="breadcrumb-item active">Register</li>
  			  </ol>
  			</nav>
      </div>

      <?php echo $this->session->userdata('message') <> '' ? $this->session->userdata('message') : ''; ?>

      <div class="col-lg-12"><h1>Daftar Pelanggan Baru</h1>
  			<hr>Sudah punya akun? Silahkan Login <a href="<?php echo base_url('auth/login') ?>">disini</a><hr>
  			<div class="row">
  			  <div class="col-lg-12">
            <?php echo form_open("auth/register");?>
            <div class="box-body">
  						<div class="form-row">
  	            <div class="form-group col-md-6"><label>Atas Nama</label>
  	              <?php echo $this->session->userdata('name') ?>
  	            </div>
  						</div>
  						<div class="form-row">
  							<div class="form-group col-md-6"><label>No. Hp</label>
  								<?php echo form_input($phone);?>
  							</div>
  							<div class="form-group col-md-6"><label>Email</label>
  								<?php echo form_input($email);?>
  							</div>
  						</div>
  						<div class="form-group"><label>Alamat</label>
  							<?php echo form_textarea($alamat);?>
  						</div>
              <div class="form-row">
                <div class="form-group col-md-6"><label>Provinsi</label>
                  <?php echo form_dropdown('', $ambil_provinsi, '', $provinsi_id); ?>
                </div>
                <div class="form-group col-md-6"><label>Kabupaten/ Kota</label>
                  <?php echo form_dropdown('', array(''=>'- Pilih Kota -'), '', $kota_id); ?>
                </div>
              </div>
              <div class="form-group">
                <button type="submit" class="btn btn-primary">Submit</button>
                <button type="reset" class="btn btn-warning">Cancel</button>
              </div>
            </div>
            <?php echo form_close(); ?>
  			  </div>
  			</div>
  		</div>
  	</div>
  </div>
	<?php $this->load->view('front/footer'); ?>
