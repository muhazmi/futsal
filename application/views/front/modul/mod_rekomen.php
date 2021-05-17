<?php foreach($featured_data as $featured){ ?>
  <div class="row">
    <div class="col-xs-4">
      <a href="<?php echo base_url('produk/read/').$featured->slug_produk ?>">
        <img src="<?php echo base_url('assets/images/produk/').$featured->foto.$featured->foto_type ?>" class="img-responsive">
    </div>
    <div class="col-xs-8">
        <h5><?php echo character_limiter($featured->judul_produk,'25') ?></h5>
      </a>
      Rp <?php echo number_format($featured->harga) ?>
    </div>
  </div><br>
<?php } ?>
