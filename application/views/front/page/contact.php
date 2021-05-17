<?php $this->load->view('front/header'); ?>
<?php $this->load->view('front/navbar'); ?>

<div class="container">
	<ol class="breadcrumb">
		<li><a href="<?php echo base_url() ?>">Home</a></li>
		<li><a href="#">Profil</a></li>
		<li class="active">Hubungi Kami</li>
	</ol>

	<div class="row">
		<div class="col-md-8"><h1>HUBUNGI KAMI</h1><hr>
			<?php echo validation_errors() ?>
			<?php if($this->session->flashdata('message')){echo $this->session->flashdata('message');} ?>
			<?php echo form_open('send',array('id'=>'contactForm')) ?>
				<div class="form-group">
					<div class="controls">
						<?php echo form_input($name) ?>
					</div>
				</div>
				<div class="form-group">
					<div class="controls">
						<?php echo form_input($email) ?>
					</div>
				</div>
				<div class="form-group">
					<div class="controls">
						<?php echo form_input($subject) ?>
					</div>
				</div>
				<div class="form-group">
					<div class="controls">
						<?php echo form_textarea($message) ?>
					</div>
				</div>
				<button type="submit" id="submit" class="btn btn-sm btn-primary" style="pointer-events: all; cursor: pointer;">Kirim</button>
			<?php echo form_close() ?>
		</div>
		<?php $this->load->view('front/sidebar'); ?>
	</div>
</div>
<?php $this->load->view('front/footer'); ?>
