<?php $this->load->view('front/header'); ?>
<?php $this->load->view('front/navbar'); ?>

<div class="container">
	<div class="row">
    <div class="col-lg-12">
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="<?php echo base_url() ?>"><i class="fa fa-home"></i> Home</a></li>
					<li class="breadcrumb-item active">Profil Saya</li>
				</ol>
			</nav>
    </div>

		<div class="col-lg-12"><h1>PROFIL SAYA</h1><hr>
			<div class="row">
				<div class="col-sm-6"><label>Nama</label><br>
		      <?php echo $profil->name ?><br><br>
		    </div>
		    <div class="col-sm-6"><label>Username</label><br>
		      <?php echo $profil->username ?><br><br>
		    </div>
			</div>
			<div class="row">
		    <div class="col-sm-6"><label>No. HP</label><br>
		      <?php echo $profil->phone ?><br><br>
		    </div>
		    <div class="col-sm-6"><label>Email</label><br>
		      <?php echo $profil->email ?><br><br>
		    </div>
			</div>
	    <div class="form-group"><label>Alamat</label><br>
	      <?php echo $profil->address.', '.$profil->nama_kota.', '.$profil->nama_provinsi ?>
	    </div>
		</div>
	</div>
</div>

<?php $this->load->view('front/footer'); ?>
