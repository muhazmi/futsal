<?php $this->load->view('front/header'); ?>
<?php $this->load->view('front/navbar'); ?>

<div class="container">
	<?php if($this->session->flashdata('message')){echo $this->session->flashdata('message');} ?>
	<?php $this->load->view('front/home/slider'); ?>
	<?php $this->load->view('front/home/lapangan_new'); ?>
	<?php $this->load->view('front/home/event_new'); ?>
</div>

<?php $this->load->view('front/footer'); ?>
