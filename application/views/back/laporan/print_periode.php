<?php
$tgl_awal   = tgl_indo($this->input->post('tgl_awal'));
$tgl_akhir  = tgl_indo($this->input->post('tgl_akhir'));

header("Content-Type: application/force-download");
header("Cache-Control: no-cache, must-revalidate");
header("content-disposition: attachment;filename=Laporan_Penjualan_Periode_".$tgl_awal."-".$tgl_akhir.".xls");
?>

<div align="center"><h3><?php echo $company_data->company_name ?></h3></div>
<div align="center"><?php echo $company_data->company_address ?></div>
<hr>
<hr>
<center>LAPORAN PENJUALAN PERIODE <?php echo "$tgl_awal - $tgl_akhir" ?></center>

<br>

<table border="1">
  <thead>
    <tr>
      <td style="text-align: center; background: #ddd; width: 100px"><b>No.Urut</b></td>
      <td style="text-align: center; background: #ddd; width: 100px"><b>No. Transaksi</b></td>
      <td style="text-align: center; background: #ddd; width: 100px"><b>Oleh</b></td>
      <td style="text-align: center; background: #ddd; width: 100px"><b>Status</b></td>
      <td style="text-align: center; background: #ddd; width: 100px"><b>Pengiriman</b></td>
      <td style="text-align: center; background: #ddd; width: 100px"><b>Resi</b></td>
      <td style="text-align: center; background: #ddd; width: 100px"><b>Tanggal</b></td>
    </tr>
  </thead>
  <tbody>
    <?php
    $no = 1;
    foreach ($get_periode as $export)
    {
    ?>
    <tr>
      <td style="text-align: center;vertical-align: top"><?php echo $no++ ?></td>
      <td style="text-align: center;vertical-align: top"><?php echo $export->id_trans; ?></td>
      <td style="text-align: center;vertical-align: top"><?php echo $export->name; ?></td>
      <td style="text-align: center;vertical-align: top"><?php echo $export->status; ?></td>
      <td style="text-align: center;vertical-align: top"><?php echo $export->kurir.' '.$export->service; ?></td>
      <td style="text-align: center;vertical-align: top"><?php echo $export->resi; ?></td>
      <td style="text-align: center;vertical-align: top"><?php echo $export->created; ?></td>
    </tr>
  <?php
  $no++;
  }
  ?>
  </tbody>
</table>

</table>
