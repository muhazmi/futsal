<html xmlns="http://www.w3.org/1999/xhtml">
<head><meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" /></head>
<body>
  <table align="center">
    <tr>
      <th rowspan="3"></th>
      <td align="center">
        <font style="font-size: 18px"><b><?php echo $company_data->company_name;?></b></font>
        <br><?php echo $company_data->company_address;?>
        <br>No. HP: <?php echo $company_data->company_phone;?> | Telp: <?php echo $company_data->company_phone2;?> | Email: <?php echo $company_data->company_email;?>
      </td>
    </tr>
  </table>
  <hr/>
  <div align="center">
    <b>INVOICE NO. <?php echo $cart_finished_row->id_invoice ?>
      <?php if($cart_finished_row->status == '1'){ ?>
        <p style='color:red'>(BELUM LUNAS)</p>
      <?php }elseif($cart_finished_row->status == '2'){ ?>
        <p style='color:green'>(LUNAS)</p>
      <?php } ?>
    </b>
  </div>

  <?php if($this->session->userdata('user_id') != NULL){ ?>
    <table>
      <thead>
        <tr>
          <th style="text-align: center; background: #ddd; width: 30px">No.</th>
          <th style="text-align: center; background: #ddd; width: 130px">Nama Lapangan</th>
          <th style="text-align: center; background: #ddd; width: 100px">Harga Per Jam</th>
          <th style="text-align: center; background: #ddd; width: 100px">Tanggal</th>
          <th style="text-align: center; background: #ddd; width: 80px">Mulai</th>
          <th style="text-align: center; background: #ddd; width: 50px">Durasi</th>
          <th style="text-align: center; background: #ddd; width: 80px">Selesai</th>
          <th style="text-align: center; background: #ddd; width: 70px">Total</th>
        </tr>
      </thead>
      <tbody>
      <?php $no=1; foreach ($cart_finished as $cart){ ?>
        <tr>
          <td style="text-align:center;width: 30px"><?php echo $no++ ?></td>
          <td style="text-align:left;width: 130px"><?php echo $cart->nama_lapangan ?></td>
          <td style="text-align:right;width: 100px"><?php echo number_format($cart->harga_jual) ?></td>
          <td style="text-align:center;width: 100px"><?php echo tgl_indo($cart->tanggal) ?></td>
          <td style="text-align:center;width: 80px"><?php echo $cart->jam_mulai ?></td>
          <td style="text-align:center;width: 80px"><?php echo $cart->durasi ?></td>
          <td style="text-align:center;width: 80px"><?php echo $cart->jam_selesai ?></td>
          <td style="text-align:right;width: 70px"><?php echo number_format($cart->total) ?></td>
        </tr>
      <?php } ?>
      </tbody>
    </table>
    <table align="right">
      <tbody>
        <tr>
          <th scope="row">SubTotal</th>
          <td align="right">Rp</td>
          <td align="right"><?php echo number_format($cart_finished_row->subtotal) ?></td>
        </tr>
        <tr>
          <th scope="row">Diskon (Member)</th>
          <td align="right">Rp</td>
          <td align="right"><?php echo number_format($cart_finished_row->diskon) ?></td>
        </tr>
        <tr>
          <th scope="row">Grand Total</th>
          <td align="right">Rp</td>
          <td align="right"><b><?php echo number_format($cart_finished_row->grand_total) ?></b></td>
        </tr>
      </tbody>
    </table>
    <b>CATATAN:</b>
    <?php if($cart_finished_row->catatan > 0){?>
      <?php echo $cart_finished_row->catatan ?>
    <?php } else{echo "-";} ?>
    <hr>
		<b>PEMBAYARAN</b><hr>
    Anda dapat melakukan pembayaran melalui nomor rekening kami dibawah ini:
		<table cellspacing='10' align="center">
			<thead>
				<tr>
					<th style="text-align: center">No.</th>
					<th style="text-align: center">Bank</th>
					<th style="text-align: center">Atas Nama</th>
					<th style="text-align: center">No. Rekening</th>
				</tr>
			</thead>
			<tbody>
				<?php $no=1; foreach($data_bank as $bank){ ?>
				<tr>
					<td><?php echo $no++ ?></td>
					<td><?php echo $bank->nama_bank ?></td>
					<td><?php echo $bank->atas_nama ?></td>
					<td><?php echo $bank->norek ?></td>
				</tr>
				<?php } ?>
			</tbody>
		</table>

	  <b>PERHATIAN</b><hr>
		<ul>
      <li>Segera lakukan pembayaran sebelum: <b><?php $time = strtotime($cart_finished_row->deadline); echo date("d F Y | H:i:s",$time); ?> WIB</b>, apabila melewati batas waktu tersebut maka booking dianggap batal.</li>
      <li>Jumlah yang harus Anda bayarkan adalah sebesar: Rp <b><?php echo number_format($cart_finished_row->grand_total) ?></b></li>
      <li>Silahkan melakukan konfirmasi pembayaran ke halaman berikut ini, <a href="<?php echo base_url('contact') ?>">klik disini</a> atau langsung menghubungi kami ke customer service yang telah disediakan dan melampirkan foto bukti bayarnya.</li>
      <li>Kami akan segera memproses pemesanan Anda setelah mendapatkan konfirmasi pembayaran segera mungkin.</li>
		</ul>
		<p align="center"><b>~ Terima Kasih ~</b></p>

  <?php } ?>

</body>
</html><!-- Akhir halaman HTML yang akan di konvert -->
