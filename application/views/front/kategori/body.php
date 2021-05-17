<?php $this->load->view('front/header'); ?>
<?php $this->load->view('front/navbar'); ?>

<div class="container">
	<div class="row">
    <div class="col-sm-12 col-lg-12">
      <ol class="breadcrumb">
    	  <li><a href="<?php echo base_url() ?>"><i class="fa fa-home"></i> Home</a></li>
    	  <li class="active">Kategori</li>
    	</ol>
    </div>
		<div class="col-sm-12 col-lg-9">
      <h1>KATEGORI: <?php echo $page ?></h1><hr>
			<div class="row">
				<?php
				if($produk_row == NULL){
				echo "<div class='col-lg-12'>Belum Ada data</div>";
				}
				else
				{
		      foreach ($produk->result() as $produk_new)
		      {
	      ?>
					<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
		        <div class="thumbnail">
							<a href="<?php echo base_url("produk/read/".$produk_new->slug_produk) ?>">
			          <?php
			          if(empty($produk_new->foto)) {echo "<img src='".base_url()."assets/images/no_image_thumb.png'>";}
			          else { echo " <img src='".base_url()."assets/images/produk/".$produk_new->foto.'_thumb'.$produk_new->foto_type."'> ";}
			          ?>
							</a>
		          <div class="caption">
		            <h5><a href="<?php echo base_url("produk/read/".$produk_new->slug_produk) ?>"><?php echo character_limiter($produk_new->judul_produk,40) ?></a></h5>
		            <p>Rp <?php echo number_format($produk_new->harga) ?></p>
		          </div>
		        </div>
		      </div>
	      <?php }} ?>
			</div>
			<div class="row" style="text-align:center"><?php echo $pagination; ?></div>
		</div>
		<hr>
		<?php $this->load->view('front/sidebar'); ?>
  <?php $this->load->view('front/footer'); ?>
