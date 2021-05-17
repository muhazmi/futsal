<div id="infoMessage"><?php echo $message;?></div>

Harap masukkan username Anda

<?php echo form_open("admin/auth/forgot_password");?>

      <p>
      	<?php echo form_input($identity);?>
      </p>

      <p><?php echo form_submit('submit', lang('forgot_password_submit_btn'));?></p>

<?php echo form_close();?>
