<hr><h3 align="center"><b>EVENT TERBARU</b></h3><hr>
<div class="row">
  <?php foreach($event_new as $event){ ?>

      <div class="col-lg-4">
        <div class="thumbnail">
          <a href="<?php echo base_url("event/$event->slug_event ") ?>">
            <?php
            if(empty($event->foto)) {echo "<img src='".base_url()."assets/images/no_image_thumb.png'>";}
            else { echo " <img src='".base_url()."assets/images/event/".$event->foto.'_thumb'.$event->foto_type."'> ";}
            ?>
          </a>
        </div>
      </div>
      <div class="col-lg-8">
        <div class="caption">
          <h4><a href="<?php echo base_url("event/$event->slug_event ") ?>"><?php echo character_limiter($event->nama_event,100) ?></a></h4>
          <i class="fa fa-calendar"></i> <?php echo date("j F Y", strtotime($event->created_at)); ?>
          <br><br>
          <p><?php echo character_limiter($event->deskripsi,400) ?></p>
          <br>
          <p align="right">
            <a href="<?php echo base_url("event/$event->slug_event") ?>">
              <button type="button" name="button" class="btn btn-sm btn-success">Selengkapnya</button>
            </a>
          </p>
        </div>
      </div>
    <br>
  <?php } ?>
</div>
