<?php $this->load->view('back/meta') ?>
  <div class="wrapper">
    <?php $this->load->view('back/navbar') ?>
    <?php $this->load->view('back/sidebar') ?>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <h1><?php echo $title ?></h1>
        <ol class="breadcrumb">
          <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
          <li><a href="#"><?php echo $module ?></a></li>
					<li class="active"><?php echo $title ?></li>
        </ol>
      </section>
      <!-- Main content -->
      <section class="content">
        <!-- Small boxes (Stat box) -->
        <div class="row">
          <div class="col-lg-12">
						<div class="box box-primary">
              <div class="box-body">
                <?php echo validation_errors() ?>
								<?php if($this->session->flashdata('message')){echo $this->session->flashdata('message');} ?>
								<?php echo form_open($action);?>
                  <div class="form-group"><label>Tujuan</label>
                    <?php echo form_dropdown('', $get_all_combo, '', $to); ?><br>
                    <input type="checkbox" name="all" id="all" value="all"/> Pilih semua
                  </div>
                  <div class="form-group"><label>Judul</label>
                    <?php echo form_input($subject);?>
                  </div>
									<div class="form-group"><label>Isi Pesan</label>
										<?php echo form_textarea($message);?>
									</div>
									<button type="submit" name="submit" class="btn btn-success"><?php echo $button_submit ?></button>
									<button type="reset" name="reset" class="btn btn-danger"><?php echo $button_reset ?></button>
								<?php echo form_close(); ?>
							</div>
						</div>
          </div><!-- ./col -->
        </div><!-- /.row -->
      </section><!-- /.content -->
    </div><!-- /.content-wrapper -->
    <?php $this->load->view('back/footer') ?>
  </div><!-- ./wrapper -->
  <?php $this->load->view('back/js') ?>
  <script type="text/javascript" src="<?php echo base_url() ?>assets/plugins/tinymce/js/tinymce/tinymce.min.js"></script>
  <link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/select2-master/dist/css/select2.min.css">
  <script type="text/javascript" src="<?php echo base_url() ?>assets/plugins/select2-master/dist/js/select2.js"></script>
  <script type="text/javascript">
  document.getElementById('all').onchange = function() {
    document.getElementById('to').disabled = this.checked;
};
  $(document).ready(function () {
    $("#to").select2({
        placeholder: "Silahkan Pilih Email"
    });
  });
  tinymce.init({
    selector: "textarea",

    // ===========================================
    // INCLUDE THE PLUGIN
    // ===========================================

    plugins: [
      "advlist autolink lists link image charmap print preview anchor",
      "searchreplace visualblocks code fullscreen",
      "insertdatetime media table contextmenu paste jbimages"
    ],

    // ===========================================
    // PUT PLUGIN'S BUTTON on the toolbar
    // ===========================================

    toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image jbimages",

    // ===========================================
    // SET RELATIVE_URLS to FALSE (This is required for images to display properly)
    // ===========================================

    relative_urls: false,
    remove_script_host : false,
    convert_urls : true,
  });
  </script>
</body>
</html>
