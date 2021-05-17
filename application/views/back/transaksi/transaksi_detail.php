<?php $this->load->view('back/meta') ?>
  <div class="wrapper">
    <?php $this->load->view('back/navbar') ?>
    <?php $this->load->view('back/sidebar') ?>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <h1>INVOICE #<?php echo $cart_finished_row->id_invoice ?></h1>
        <ol class="breadcrumb">
          <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
          <li><a href="#"><?php echo $module ?></a></li>
          <li class="active"><?php echo $title ?></li>
        </ol>
      </section>
      <div class="pad margin no-print">
        <div class="callout callout-info" style="margin-bottom: 0!important;">
          <h4><i class="fa fa-info"></i> Note:</h4>
          Halaman ini bisa langsung diprint dengan cara menekan tombol ctrl + p di keyboard
        </div>
      </div>
      <!-- Main content -->
      <section class="invoice">
        <!-- title row -->
        <div class="row">
          <div class="col-xs-12">
            <h2 class="page-header">
              <i class="fa fa-file-text-o"></i> Invoice: <?php echo $cart_finished_row->id_invoice ?>
            </h2>
          </div><!-- /.col -->
        </div>
        <!-- info row -->
        <div class="row invoice-info">
          <div class="col-sm-4 invoice-col">
            Dari
            <address>
              <strong><?php echo $company_data->company_name ?></strong><br>
              <?php echo $company_data->company_address ?>
            </address>
          </div><!-- /.col -->
          <div class="col-sm-4 invoice-col">
            Kepada
            <address>
              <strong><?php echo $cart_finished_row->name ?></strong><br>
              <?php echo $cart_finished_row->address.', '.$cart_finished_row->nama_kota.', '.$cart_finished_row->nama_provinsi ?>
            </address>
          </div><!-- /.col -->
          <div class="col-sm-4 invoice-col">
            Tanggal Pemesanan: <b><?php echo tgl_indo($cart_finished_row->created_date) ?></b><br/>
            Status: <b>
              <?php if($cart_finished_row->status == '0'){echo "BELUM CHECKOUT";}
                    elseif($cart_finished_row->status == '1'){echo "BELUM LUNAS";}
                    elseif($cart_finished_row->status == '2'){echo "SUDAH LUNAS";}
                    elseif($cart_finished_row->status == '3'){echo "EXPIRED";}
              ?></b><br/>
          </div><!-- /.col -->
        </div><!-- /.row -->
        <!-- Table row -->
        <div class="row">
          <div class="col-xs-12">
            <div class="table-responsive">
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th style="text-align: center">No.</th>
                    <th style="text-align: center">Nama Lapangan</th>
                    <th style="text-align: center">Harga</th>
                    <th style="text-align: center">Tanggal</th>
                    <th style="text-align: center">Jumlah Mulai</th>
                    <th style="text-align: center">Durasi</th>
                    <th style="text-align: center">Jam Selesai</th>
                    <th style="text-align: center">Total</th>
                  </tr>
                </thead>
                <tbody>
                <?php $no=1; foreach ($cart_finished as $cart){ ?>
                  <tr>
                    <td style="text-align:center"><?php echo $no++ ?></td>
                    <td style="text-align:left"><?php echo $cart->nama_lapangan ?></td>
                    <td style="text-align:center"><?php echo number_format($cart->harga_jual) ?></td>
                    <td style="text-align:center"><?php echo $cart->tanggal ?></td>
                    <td style="text-align:center"><?php echo $cart->jam_mulai ?></td>
                    <td style="text-align:center"><?php echo $cart->durasi ?></td>
                    <td style="text-align:center"><?php echo $cart->jam_selesai ?></td>
                    <td style="text-align:right"><?php echo number_format($cart->subtotal) ?></td>
                  </tr>
                <?php } ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-xs-12">
            <div class="table-responsive">
              <table class="table">
                <tr>
    							<th>SubTotal</th>
                  <td align="right">Rp</td>
    							<td colspan="2" align="right"><?php echo number_format($cart_finished_row->subtotal) ?></td>
    						</tr>
    				    <tr>
    							<th>Diskon</th>
    							<td align="right">Rp</td>
    							<td align="right"><?php echo number_format($cart_finished_row->diskon) ?></td>
    						</tr>
    						<tr>
    				      <th>Grand Total</th>
                  <td align="right">Rp</td>
    				      <td align="right"><?php echo number_format($cart_finished_row->grand_total) ?></td>
    				    </tr>
              </table>
            </div>
            <b>Catatan:</b>
            <?php if($cart_finished_row->catatan != NULL){ echo $cart_finished_row->catatan; } else{ echo "-"; } ?>
            <br><br>
            <p><b>Hormat kami,</b>
              <br>Operator
            </p>
            <br><br><br><br>
            (<?php echo $this->session->userdata('username') ?>)
            <hr>
          </div><!-- /.col -->
        </div><!-- /.row -->

        <!-- this row will not appear when printing -->
        <div class="row no-print">
          <div class="col-xs-12">
            <a href="javascript:window.print()" target="_blank" class="btn btn-default"><i class="fa fa-print"></i> Print</a>
          </div>
        </div>
      </section><!-- /.content -->
      <div class="clearfix"></div>
    </div><!-- /.content-wrapper -->
    <?php $this->load->view('back/footer') ?>
  </div>
  <?php $this->load->view('back/js') ?>
</body>
</html>
