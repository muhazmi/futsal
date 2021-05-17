<?php $this->load->view('front/header'); ?>
<?php $this->load->view('front/navbar'); ?>

<div class="container">
	<ol class="breadcrumb">
	  <li><a href="<?php echo base_url() ?>">Home</a></li>
	  <li><a href="#">Event</a></li>
	  <li class="active"><?php echo $event_detail->nama_event ?></li>
	</ol>

	<div class="row">
		<div class="col-md-8"><h1><?php echo strtoupper($event_detail->nama_event) ?></h1>
			<a href="<?php echo base_url('assets/images/event/').$event_detail->foto.$event_detail->foto_type ?>" title="<?php echo $event_detail->nama_event ?>">
				<img src="<?php echo base_url('assets/images/event/').$event_detail->foto.'_thumb'.$event_detail->foto_type ?>" alt="<?php echo $event_detail->nama_event ?>" class="img-responsive">
			</a>

	    <i class="fa fa-user"></i> <?php echo $event_detail->created_by ?></a> | <i class="fa fa-calendar"></i> <?php echo date("j F Y", strtotime($event_detail->created_at)); ?>

			<p><?php echo $event_detail->deskripsi ?></p>

			<p>
				<div class="sharethis-inline-share-buttons"></div>
				<script type="text/javascript" src="//platform-api.sharethis.com/js/sharethis.js#property=5ae2ee03de20620011e03337&product=inline-share-buttons"></script>
			</p>

			<?php $this->load->view('front/modul/mod_komen'); ?>
		</div>
		<?php $this->load->view('front/sidebar'); ?>
	</div>
</div>
<?php $this->load->view('front/footer'); ?>
