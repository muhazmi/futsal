<?php $this->load->view('front/meta'); ?>
<?php $this->load->view('front/navbar'); ?>
<div class="page-banner no-subtitle">
	<div class="container">
		<div class="row">
			<div class="col-md-6">
				<h2><?php echo strtoupper($this->uri->segment(1)) ?></h2>
			</div>
			<div class="col-md-6">
				<ul class="breadcrumbs">
					<li><a href="#">Home</a></li>
					<li><?php echo ucfirst($this->uri->segment(1)) ?></li>
				</ul>
			</div>
		</div>
	</div>
</div>

<!-- Start Content -->
<div id="content">
	<div class="container">
		<div class="row blog-page">
			<div class="col-md-9 blog-box">
				<?php foreach($kategori_data as $kategori){ ?>
	        <!-- Start Post -->
	        <div class="blog-post image-post">
	          <!-- Post Thumb -->
	          <div class="post-head">
	            <a href="<?php echo base_url("kategori/$kategori->slug_kat ") ?>">
								<?php
								if(empty($kategori->foto)) {echo "<img src='".base_url()."assets/images/no_image_thumb.png'>";}
								else { echo " <img src='".base_url()."assets/images/event/".$kategori->foto.'_thumb'.$kategori->foto_type."'> ";}
								?>
	            </a>
	          </div>
	          <!-- Post Content -->
	          <div class="post-content">
	            <div class="post-type"><i class="fa fa-newspaper-o"></i></div>
	            <h2><a href="#"><?php echo $kategori->judul_kategori ?></a></h2>
	            <ul class="post-meta">
	              <li><i class="fa fa-user"></i> <?php echo $kategori->created_by ?></li>
	              <li><i class="fa fa-calendar"></i> <?php echo date("j F Y", strtotime($kategori->created_at)); ?></li>
	            </ul>
	            <p><?php echo character_limiter($kategori->deskripsi,350) ?></p>
	            <a class="main-button" href="<?php echo base_url("kategori/$kategori->slug_kat ") ?>">Selengkapnya <i class="fa fa-angle-right"></i></a>
	          </div>
	        </div>
	        <!-- End Post -->
				<?php } ?>
				<div align="center"><?php echo $this->pagination->create_links() ?></div>
			</div>
			<?php $this->load->view('front/sidebar'); ?>
		</div>
	</div>
</div>
<!-- End Content -->

<?php $this->load->view('front/footer'); ?>
