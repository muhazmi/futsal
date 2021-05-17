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
          <div class="col-lg-6">
						<div class="box box-primary">
              <div class="box-header with-border">
                <h4 class="box-title"><b>Keseluruhan</b></h4>
              </div>
              <div class="box-body">
								<?php echo $this->session->userdata('message') <> '' ? $this->session->userdata('message') : ''; ?>
                <?php echo form_open('admin/laporan/export_all') ?>
                  <button type="submit" name="submit" class="btn btn-success">Download</button>
                <?php echo form_close() ?>
							</div>
						</div>
          </div><!-- ./col -->
          <div class="col-lg-6">
            <div class="box box-primary">
              <!-- form start -->
              <div class="box-header with-border">
                <h4 class="box-title"><b>Per Periode</b></h4>
              </div>
              <?php echo form_open('admin/laporan/export_periode') ?>
                <div class="box-body">
                  <div class="panel-body">
                  <?php echo $this->session->userdata('message') <> '' ? $this->session->userdata('message') : ''; ?>
                    <div class="form-group">
                      <input type="text" class="form-control" name="tgl_awal" id="tgl_awal" placeholder="Isi tanggal mulai" class="form-control" >
                    </div>
                    <div class="form-group">
                      <input type="text" class="form-control" name="tgl_akhir" id="tgl_akhir" placeholder="Isi tanggal akhir" class="form-control" >
                    </div>
                  </div>
                </div><!-- /.box-body -->
                <div class="box-footer">
                  <button type="submit" name="submit" class="btn btn-success">Download</button>
                  <button type="reset" name="reset" class="btn btn-primary">Reset</button>
                </div>
              <?php echo form_close() ?>
            </div><!-- /.box -->
          </div>
        </div><!-- /.row -->
      </section><!-- /.content -->
    </div><!-- /.content-wrapper -->
    <?php $this->load->view('back/footer') ?>
  </div><!-- ./wrapper -->
  <?php $this->load->view('back/js') ?>
  <link href="<?php echo base_url('assets/plugins/')?>datepicker/css/bootstrap-datepicker.css" rel="stylesheet">
  <script src="<?php echo base_url('assets/plugins/')?>datepicker/js/bootstrap-datepicker.js"></script>
  <script type="text/javascript">
  $(function()
  {
    $('#tgl_awal').datepicker({autoclose: true,todayHighlight: true,format: 'yyyy-mm-dd'}),
    $('#tgl_akhir').datepicker({autoclose: true,todayHighlight: true,format: 'yyyy-mm-dd'})
  });
  </script>
</body>
</html>
