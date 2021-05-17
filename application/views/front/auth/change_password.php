<?php $this->load->view('head'); ?>      
<?php $this->load->view('header'); ?>
<?php $this->load->view('leftbar'); ?>      

<div class="content-wrapper">
  <section class='content'>
    <div class='row'>    
      <div id="infoMessage"></div>
      <?php echo form_open("auth/change_password");?>
        <!-- left column -->
        <div class="col-md-6">
          <!-- general form elements -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h4 class="box-title"><b>Form Ubah Password</b></h4>
            </div><!-- /.box-header -->
            <div class="box-body">
              <?php echo $message;?>
              <div class="form-group"><label>Password Lama</label>
                <?php echo form_input($old_password);?>
              </div>
              <div class="form-group"><label>Password Baru</label>
                <?php echo form_input($new_password);?>
              </div>
              <div class="form-group"><label>Konfirmasi Password Baru</label>
                <?php echo form_input($new_password_confirm);?>
              </div>
            </div><!-- /.box-body -->
            <?php echo form_input($user_id);?>
            <div class="box-footer">
              <button type="submit" name="submit" class="btn btn-success">Submit</button>
              <button type="reset" name="reset" class="btn btn-danger">Reset</button>
            </div>
          </div><!-- /.box -->
          <!-- left column -->
        </div>
      <?php echo form_close(); ?>
    </div>
  </section>
</div>

<?php $this->load->view('footer'); ?>      