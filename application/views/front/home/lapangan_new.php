<hr><h3 align="center"><b>LAPANGAN KAMI</b></h3><hr>
<div class="row">
  <?php foreach($lapangan_new as $lapangan){ ?>
    <div class="col-lg-4">
      <div class="thumbnail">
        <?php
        if(empty($lapangan->foto)) {echo "<img class='card-img-top' src='".base_url()."assets/images/no_image_thumb.png'>";}
        else { echo "<img src='".base_url()."assets/images/lapangan/".$lapangan->foto."'> ";}
        ?>
        <div class="caption">
          <p class="card-text"><b><?php echo $lapangan->nama_lapangan ?></b></p>
          <hr>
          <a href="<?php echo base_url('cart/buy/').$lapangan->id_lapangan ?>">
            <button class="btn btn-sm btn-primary"><i class="fa fa-shopping-cart"></i> Booking Sekarang!</button>
          </a>
        </div>
      </div>
    </div>
  <?php } ?>
</div>
