<?php $this->load->view('front/header'); ?>
<?php $this->load->view('front/navbar'); ?>

<div class="container">
	<ol class="breadcrumb">
		<li><a href="<?php echo base_url() ?>">Home</a></li>
		<li><a href="<?php echo base_url('gallery/album') ?>">Album</a></li>
		<li class="active"><?php echo ucfirst($this->uri->segment(3)) ?></li>
	</ol>

	<div class="row">
		<div class="col-md-8"><h1>ALBUM: <?php echo strtoupper(clean2(ucfirst($this->uri->segment(3)))) ?></h1><hr>
			<div class="row">
				<?php foreach($album_detail as $foto){ ?>
	        <div class="col-md-4">
						<h4><a href="#"><?php echo $foto->nama_foto ?></a></h4>
						<?php
						if(empty($foto->foto)) {echo "<img id='zoom-image' class='img-responsive' src='".base_url()."assets/images/no_image_thumb.png'>";}
						else { echo " <img id='zoom-image' class='img-responsive' src='".base_url()."assets/images/foto/".$foto->foto."'> ";}
						?>
					</div>
					<div class="modal fade" id="enlargeImageModal" tabindex="-1" role="dialog" aria-labelledby="enlargeImageModal" aria-hidden="true">
						<div class="modal-dialog modal-lg" role="document">
							<div class="modal-content">
								<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button></div>
								<div class="modal-body"><img class="img-responsive enlargeImageModalSource"></div>
							</div>
						</div>
					</div>
				<?php } ?>
			</div>
		</div>
		<?php $this->load->view('front/sidebar'); ?>
	</div>
</div>
<?php $this->load->view('front/footer'); ?>

<script type="text/javascript">
$(function() {
		$('img').on('click', function() {
		$('.enlargeImageModalSource').attr('src', $(this).attr('src'));
		$('#enlargeImageModal').modal('show');
	});
});
</script>
