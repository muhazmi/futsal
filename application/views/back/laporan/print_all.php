<?php
header("Content-Type: application/vnd.ms-excel");
header("content-disposition: attachment;filename=Laporan_Penjualan_Keseluruhan.xls");
header("Cache-Control: max-age=0");
?>

<div align="center"><h3><?php echo $company_data->company_name ?></h3></div>
<div align="center"><?php echo $company_data->company_address ?></div>
<hr>
<div align="center"><h3>LAPORAN PENJUALAN KESELURUHAN</h3></div>
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
    foreach ($get_all as $export)
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
    <?php } ?>
  </tbody>
</table>
