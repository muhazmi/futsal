<?php $this->load->view('back/head'); ?>
<?php $this->load->view('back/header'); ?>
<?php $this->load->view('back/leftbar'); ?>

<div class="content-wrapper">
  <section class="content-header">
    <h1><?php echo $title ?></h1>
    <ol class="breadcrumb">
      <li><a href="<?php echo base_url('admin/dashboard') ?>"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><?php echo $module ?></li>
      <li class="active"><a href="<?php echo current_url() ?>"><?php echo $title ?></a></li>
    </ol>
  </section>
  <section class='content'>
		<div class="col-lg-12">
			<div class="row">
			<div class="box box-primary">
				<div class="box-body table-responsive padding">
					<?php echo $this->session->userdata('message') <> '' ? $this->session->userdata('message') : ''; ?>
          <a href="<?php echo base_url('admin/company/create') ?>">
            <button class="btn btn btn-success"><i class="fa fa-plus"></i> Tambah Data</button>
          </a>
	        <button class="btn btn-grey" onclick="reload_table()">
	          <i class="glyphicon glyphicon-refresh"></i> Refresh
	        </button>
	        <hr/>
					<table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
						<thead>
							<tr>
								<th style="text-align: center">No.</th>
								<th style="text-align: center">Judul Company</th>
                <th style="text-align: center">Description</th>
								<th style="text-align: center">Address</th>
								<th style="text-align: center">Phone</th>
								<th style="text-align: center">Fax</th>
								<th style="text-align: center">Email</th>
								<th style="text-align: center">Created</th>
								<th style="text-align: center">Aksi</th>
							</tr>
						</thead>
						<tbody></tbody>
					</table>
				</div>
			</div>
		</div><!-- /.row -->
		</div>
  </section>
</div>

<?php $this->load->view('back/footer'); ?>
<!-- DATA TABLES SCRIPT -->
<script src="<?php echo base_url('assets/plugins/datatables/jquery.dataTables.min.js') ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/plugins/datatables/dataTables.bootstrap.min.js') ?>" type="text/javascript"></script>
<script type="text/javascript">
var table;
$(document).ready(function() {
  table = $('#table').DataTable({
    "processing": true, //Feature control the processing indicator.
    "serverSide": true, //Feature control DataTables' server-side processing mode.
    "bPaginate": true,
    "bLengthChange": true,
    "bFilter": true,
    "bSort": true,
    "bInfo": true,
    "bAutoWidth": false,
    // "aaSorting": [[0,'ASC']],
    "lengthMenu": [[10, 25, 50, 100, 500, 1000, -1], [10, 25, 50, 100, 500, 1000, "Semua"]],

    // Load data for the table's content from an Ajax source
    "ajax": {
        "url": "<?php echo site_url('admin/company/ajax_list')?>",
        "type": "POST"
    },

    //Set column definition initialisation properties.
    "columnDefs": [
    {
      "targets": [ -1 ], //last column
    },
    ],
  });
});

function reload_table(){
  table.ajax.reload(null,false); //reload datatable ajax
}
</script>
