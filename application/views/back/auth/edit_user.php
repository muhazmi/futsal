<?php $this->load->view('back/meta') ?>
<?php $this->load->view('back/navbar') ?>
<?php $this->load->view('back/sidebar') ?>

    <div class="wrapper">
      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1><?php echo $title ?> </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#"><?php echo $module ?></a></li>
  					<li class="active"><?php echo $title ?></li>
          </ol>
        </section>

        <!-- Main content -->
        <section class='content'>
          <div class='row'>
              <div class="col-lg-12">
                <div class="box box-primary">
                  <div class="box-body">
                    <div class="card">
                      <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active"><a href="#umum" aria-controls="umum" role="tab" data-toggle="tab"><label>UMUM</label></a></li>
                        <li role="presentation"><a href="#login" aria-controls="login" role="tab" data-toggle="tab"><label>LOGIN</label></a></li>
                      </ul>
                      <?php echo form_open_multipart(uri_string());?>
                      <?php echo validation_errors() ?>
                      <?php if($this->session->flashdata('message')){echo $this->session->flashdata('message');} ?>
                        <div class="tab-content">
                          <div role="tabpanel" class="tab-pane active" id="umum"><br>
                            <div class="form-group"><label>Nama Lengkap</label>
                              <?php echo form_input($name);?>
                            </div>
                            <div class="row">
                              <div class="col-xs-6"><label>Email</label>
                                <?php echo form_input($email);?>
                              </div>
                              <div class="col-xs-6"><label>No. HP</label>
                                <?php echo form_input($phone);?>
                              </div>
                            </div><br>
                            <div class="form-group"><label>Alamat</label>
                              <?php echo form_textarea($address);?>
                            </div>
                            <div class="form-group"><label>Foto Lama</label>
                              <div class="form-group">
                                <img src="<?php echo base_url('assets/images/user/').$user->photo.$user->photo_type ?>" width="200px">
                              </div>
                            </div>
                            <div class="form-group"><label>Foto Baru</label>
                              <input type="file" class="form-control" name="photo" id="photo" onchange="tampilkanPreview(this,'preview')"/>
                              <br><p><b>Preview</b><br>
                              <img id="preview" src="" alt="" width="350px"/>
                            </div>
                            <hr>
                            <button type="submit" name="submit" class="btn btn-success">Submit</button>
                            <button type="reset" name="reset" class="btn btn-danger">Reset</button>
                          </div>

                          <div role="tabpanel" class="tab-pane" id="login"><br>
                            <div class="form-group"><label>Username</label>
                              <?php echo form_input($username);?>
                            </div>
                            <?php if ($this->ion_auth->is_superadmin()): ?>
                            <div class="form-group"><label>Tipe User</label>
                              <?php echo form_dropdown('',$get_all_users_group,$user->usertype,$usertype);?>
                            </div>
                            <?php endif ?>
                            <div class="row">
                              <div class="col-xs-6"><label>Password</label>
                                <?php echo form_password($password);?>
                              </div>
                              <div class="col-xs-6"><label>Ulangi Password</label>
                                <?php echo form_password($password_confirm);?>
                              </div>
                            </div>
                            <?php echo form_hidden('id', $user->id);?>
                            <?php echo form_hidden($csrf); ?>
                            <hr>
                            <button type="submit" name="submit" class="btn btn-success">Submit</button>
                            <button type="reset" name="reset" class="btn btn-danger">Reset</button>
                          </div>
                        </div>
                      <?php echo form_close(); ?>
                    </div>
                  </div>
                </div>
              </div>
          </div>
        </section>
      </div><!-- /.content-wrapper -->
      <?php $this->load->view('back/footer') ?>
    </div><!-- ./wrapper -->
    <?php $this->load->view('back/js') ?>
    <script type="text/javascript">
    function tampilkanPreview(photo,idpreview)
    { //membuat objek gambar
      var gb = photo.files;
      //loop untuk merender gambar
      for (var i = 0; i < gb.length; i++)
      { //bikin variabel
        var gbPreview = gb[i];
        var imageType = /image.*/;
        var preview=document.getElementById(idpreview);
        var reader = new FileReader();
        if (gbPreview.type.match(imageType))
        { //jika tipe data sesuai
          preview.file = gbPreview;
          reader.onload = (function(element)
          {
            return function(e)
            {
              element.src = e.target.result;
            };
          })(preview);
          //membaca data URL gambar
          reader.readAsDataURL(gbPreview);
        }
          else
          { //jika tipe data tidak sesuai
            alert("Tipe file tidak sesuai. Gambar harus bertipe .png, .gif atau .jpg.");
          }
      }
    }
    </script>
  </body>
</html>
