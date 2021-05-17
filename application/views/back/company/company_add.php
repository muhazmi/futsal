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
    <div class='row'>
      <div class="col-lg-12">
				<?php echo form_open_multipart($action);?>
					<?php echo validation_errors() ?>
					<?php if($this->session->flashdata('message')){echo $this->session->flashdata('message');} ?>
          <div class="box box-primary">
            <div class="box-body">
              <div class="form-group"><label>Nama Perusahaan/ Organisasi</label>
                <?php echo form_input($company_name);?>
              </div>
              <div class="form-group"><label>Deskripsi</label>
                <?php echo form_textarea($company_desc);?>
              </div>
              <div class="row">
                <div class="col-xs-6"><label>No. HP</label>
                  <?php echo form_input($company_phone);?>
                </div>
                <div class="col-xs-6"><label>Telpon</label>
                  <?php echo form_input($company_phone2);?>
                </div>
              </div><br>
							<div class="row">
                <div class="col-xs-6"><label>Email</label>
                  <?php echo form_input($company_email);?>
                </div>
                <div class="col-xs-6"><label>Fax</label>
                  <?php echo form_input($company_fax);?>
                </div>
              </div><br>
              <div class="form-group"><label>Alamat</label>
                <?php echo form_textarea($company_address);?>
              </div>
              <div class="form-group"><label>Logo</label>
                <input type="file" class="form-control" name="logo" id="logo" onchange="tampilkanPreview(this,'preview')"/>
                <br><p><b>Preview</b><br>
                <img id="preview" src="" alt="" width="350px"/>
              </div>
	            <hr>
	            <button type="submit" name="submit" class="btn btn-success">Submit</button>
	            <button type="reset" name="reset" class="btn btn-danger">Reset</button>
						</div>
          </div>
        </div>
      <?php echo form_close(); ?>
    </div>
  </section>
</div>

<?php $this->load->view('back/footer'); ?>

<script type="text/javascript">
$('#myTabs a').click(function (e) {
e.preventDefault()
$(this).tab('show')
})
function tampilkanPreview(userfile,idpreview)
{ //membuat objek gambar
  var gb = userfile.files;
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
