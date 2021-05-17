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
                <a href="<?php echo base_url('admin/auth/create_user') ?>">
                  <button class="btn btn-primary"><i class="fa fa-plus"></i> Tambah Data</button>
                </a>
                <hr>
								<?php echo $message;?>
                <div class="table-responsive no-padding">
                  <table id="datatable" class="table table-striped table-bordered">
                    <?php if($this->session->userdata('user_id') == '1'){ ?>
                      <thead>
                        <tr>
                          <th style="text-align: center">No.</th>
                          <th style="text-align: center">Nama</th>
                          <th style="text-align: center">Username</th>
                          <th style="text-align: center">Email</th>
                          <th style="text-align: center">Last Login</th>
                          <th style="text-align: center">Usertype</th>
                          <th style="text-align: center">Status</th>
                          <th style="text-align: center">Aksi</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php $start = 0; foreach ($users as $user):?>
                          <tr>
                            <td style="text-align:center"><?php echo ++$start ?></td>
                            <td style="text-align:left"><?php echo $user->name ?></td>
                            <td style="text-align:center"><?php echo $user->username ?></td>
                            <td style="text-align:center"><?php echo $user->email ?></td>
                            <td style="text-align:center"><?php if(!empty($user->last_login)){echo date("Y-m-d H:i:s", $user->last_login);} ?></td>
                              <td style="text-align:center"><?php echo $user->name_group ?></td>
                              <td style="text-align:center"><?php echo ($user->active) ? anchor("admin/auth/deactivate/".$user->id, 'ACTIVE','title="ACTIVE", class="btn btn-sm btn-primary"', lang('index_active_link')) : anchor("admin/auth/activate/". $user->id, 'INACTIVE','title="INACTIVE", class="btn btn-sm btn-danger"' , lang('index_inactive_link'));?></td>
                              <td style="text-align:center">
                                <?php
                                echo anchor(site_url('admin/auth/edit_user/'.$user->id),'<i class="glyphicon glyphicon-pencil"></i>','title="Edit", class="btn btn-sm btn-warning"'); echo ' ';
                                echo anchor(site_url('admin/auth/delete_user/'.$user->id),'<i class="glyphicon glyphicon-remove"></i>','title="Hapus", class="btn btn-sm btn-danger", onclick="javasciprt: return confirm(\'Apakah Anda yakin ?\')"');
                                ?>
                              </td>
                            </tr>
                          <?php endforeach;?>
                        </tbody>
                      <?php }else{ ?>
                        <thead>
                          <tr>
                            <th style="text-align: center">No.</th>
                            <th style="text-align: center">Nama</th>
                            <th style="text-align: center">Username</th>
                            <th style="text-align: center">Email</th>
                            <th style="text-align: center">Last Login</th>
                            <th style="text-align: center">Usertype</th>
                            <th style="text-align: center">Status</th>
                            <th style="text-align: center">Aksi</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php $start = 0; foreach ($users2 as $user):?>
                            <tr>
                              <td style="text-align:center"><?php echo ++$start ?></td>
                              <td style="text-align:left"><?php echo $user->name ?></td>
                              <td style="text-align:center"><?php echo $user->username ?></td>
                              <td style="text-align:center"><?php echo $user->email ?></td>
                              <td style="text-align:center"><?php echo $user->last_login ?></td>
                              <td style="text-align:center"><?php echo $user->name_group ?></td>
                              <td style="text-align:center"><?php echo ($user->active) ? anchor("admin/auth/deactivate/".$user->id, 'ACTIVE','title="ACTIVE", class="btn btn-sm btn-primary"', lang('index_active_link')) : anchor("admin/auth/activate/". $user->id, 'INACTIVE','title="INACTIVE", class="btn btn-sm btn-danger"' , lang('index_inactive_link'));?></td>
                              <td style="text-align:center">
                                <?php
                                echo anchor(site_url('admin/auth/edit_user/'.$user->id),'<i class="glyphicon glyphicon-pencil"></i>','title="Edit", class="btn btn-sm btn-warning"'); echo ' ';
                                echo anchor(site_url('admin/auth/delete_user/'.$user->id),'<i class="glyphicon glyphicon-trash"></i>','title="Hapus", class="btn btn-sm btn-danger", onclick="javasciprt: return confirm(\'Apakah Anda yakin ?\')"');
                                ?>
                              </td>
                            </tr>
                          <?php endforeach;?>
                        </tbody>
                      <?php } ?>
                    </table>
                </div>
							</div>
						</div>
          </div><!-- ./col -->
        </div><!-- /.row -->
      </section><!-- /.content -->
    </div><!-- /.content-wrapper -->
    <?php $this->load->view('back/footer') ?>
  </div><!-- ./wrapper -->
  <?php $this->load->view('back/js') ?>
	<!-- DATA TABLES-->
  <link href="<?php echo base_url('assets/plugins/') ?>datatables/dataTables.bootstrap.css" rel="stylesheet" type="text/css" />
  <script src="<?php echo base_url('assets/plugins/') ?>datatables/jquery.dataTables.min.js" type="text/javascript"></script>
  <script src="<?php echo base_url('assets/plugins/') ?>datatables/dataTables.bootstrap.min.js" type="text/javascript"></script>
  <script type="text/javascript">
  $(document).ready(function () {
    $("#datatable").dataTable();
  });
  </script>
</body>
</html>
