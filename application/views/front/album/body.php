<?php $this->load->view('front/header'); ?>
<?php $this->load->view('front/navbar'); ?>

<div class="container">
	<ol class="breadcrumb">
		<li><a href="<?php echo base_url() ?>">Home</a></li>
		<li><a href="<?php echo base_url('gallery/album') ?>">Album Foto</a></li>
		<li class="active"><?php echo $title ?></li>
	</ol>

	<div class="row">
		<div class="col-md-8"><h1><?php echo strtoupper($title) ?></h1><hr>
			<?php echo $this->session->userdata('message') <> '' ? $this->session->userdata('message') : ''; ?>
			<div class="row">
				<?php foreach($album_all as $album){ ?>
					<div class="col-lg-6">
						<h4><a href="#"><?php echo $album->nama_album ?></a></h4>
						<a href="<?php echo base_url("gallery/read/$album->slug_album") ?>">
							<?php
							if(empty($album->foto)) {echo "<img class='img-responsive' src='".base_url()."assets/images/no_image_thumb.png'>";}
							else { echo " <img class='img-responsive' src='".base_url()."assets/images/album/".$album->foto."'> ";}
							?>
						</a><br>
					</div>
				<?php } ?>
			</div>
			<p>
				<div align="center"><?php echo $this->pagination->create_links() ?></div>
			</p>
		</div>
		<?php $this->load->view('front/sidebar'); ?>
	</div>
</div>
<?php $this->load->view('front/footer'); ?>
