<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php
$ci = new CI_Controller();
$ci =& get_instance();
$ci->load->helper('url');
?>
<!DOCTYPE html>
<html>
<head>
  <title>404 Not Found</title>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- Theme -->
	<link href="<?php echo base_url() ?>assets/template/frontend/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
  <!-- FontAwesome 4.3.0 -->
  <link href="<?php echo base_url() ?>assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
  <!-- Favicon -->
  <link rel="shortcut icon" href="<?php echo base_url() ?>assets/images/fav.png" />
	<link href="<?php echo base_url()?>assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
</head>
<body>
	<div class="container">
		<div class="row" align="center">
			<div class="col-lg-12">
				<p>
					<div class="alert alert-danger">
						<h1><i class="fa fa-frown-o"></i> Ooppss!</h1><hr>
					  <h4>Halaman yang Anda cari tidak ada</h4>
						<p><button class="btn btn-primary" onclick="goBack()"><i class="fa fa-arrow-left"></i> Kembali</button></p>
					</div>
				</p>
			</div>
		</div>
	</div>
	<script>
function goBack() {
    window.history.back()
}
</script>
</body>
</html>
