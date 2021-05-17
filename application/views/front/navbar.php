<nav class="navbar navbar-default navbar-fixed-top">
  <div class="container">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="<?php echo base_url() ?>">
        <img src="<?php echo base_url('assets/images/company/').$company_data->foto.$company_data->foto_type ?>" alt="<?php echo $company_data->company_name ?>" width="100px">
      </a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li class="<?php if($this->uri->segment(1) == ""){echo "active";} ?>">
          <a href="<?php echo base_url() ?>">Home </a>
        </li>
        <li class="<?php if($this->uri->segment(1) == "event"){echo "active";} ?>">
          <a href="<?php echo base_url('event') ?>"> Event</a>
        </li>
        <li class="<?php if($this->uri->segment(1) == "gallery"){echo "active";} ?>">
          <a href="<?php echo base_url('gallery/album') ?>"> Foto</a>
        </li>
        <li class="dropdown <?php if($this->uri->segment(1) == "about" or $this->uri->segment(1) == "contact"){echo "active";} ?>">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Profil <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li class="<?php if($this->uri->segment(1) == "about"){echo "active";} ?>">
              <a href="<?php echo base_url('about') ?>"> Tentang Kami</a>
            </li>
            <li class="<?php if($this->uri->segment(1) == "contact"){echo "active";} ?>">
              <a href="<?php echo base_url('contact') ?>"> Hubungi Kami</a>
            </li>
          </ul>
        </li>
        <li class="<?php if($this->uri->segment(1) == "cart" && $this->uri->segment(2) == ""){echo "active";} ?>">
          <a href="<?php echo base_url('cart') ?>"> Keranjang</a>
        </li>
      </ul>

      <?php if($this->session->userdata('usertype') != NULL){ ?>
        <ul class="nav navbar-nav navbar-right">
          <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Hi, <?php echo $this->session->userdata('username') ?> <span class="caret"></span></a>
            <ul class="dropdown-menu">
              <li><a href="<?php echo base_url('cart/history') ?>">Riwayat Booking</a></li>
              <li><a href="<?php echo base_url('auth/edit_profil/').$this->session->userdata('user_id') ?>">Edit Profil</a></li>
              <li><a href="<?php echo base_url('auth/profil') ?>">Profil Saya</a></li>
              <li role="separator" class="divider"></li>
              <li><a href="<?php echo base_url('auth/logout') ?>">Logout</a></li>
            </ul>
          </li>
        </ul>
      <?php }else{ ?>
        <ul class="nav navbar-nav navbar-right">
          <li><a href="<?php echo base_url('auth/register') ?>">Register</a></li>
          <li><a href="<?php echo base_url('auth/login') ?>">Login</a></li>
        </ul>
      <?php } ?>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>
