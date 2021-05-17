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
								<?php echo validation_errors() ?>
								<?php if($this->session->flashdata('message')){echo $this->session->flashdata('message');} ?>
								<?php echo form_open($action);?>
                  <table id="datatable myTable" class="table order-list">
                    <thead>
                      <tr>
                        <th style="text-align: center">Nama Barang</th>
                        <th style="text-align: center">Stok</th>
                        <th style="text-align: center">Harga Jual</th>
                        <th style="text-align: center">Qty</th>
                        <th style="text-align: center">Total</th>
                        <th style="text-align: center">Keterangan</th>
                        <th style="text-align: center">Aksi</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>
                          <select name="nama_barang[]" id="nama_barang" onchange="changeValue(this.value,'')" class="form-control namaBarang" required>
                            <option value="">-Pilih Barang-</option>
                          </select>
                        </td>
                        <td>
                          <input class="form-control" name="stok[]" id="stok" type="text" readonly value="<?php echo set_value('kd_barang[0]') ?>"/>
                          <input class="form-control" name="kd_barang[]" id="kd_barang" type="hidden" readonly value="<?php echo set_value('kd_barang[0]') ?>"/>
                        </td>
                        <td><input class="form-control" name="harga_jual[]" id="harga_jual" type="text" readonly value="<?php echo set_value('harga_jual[0]') ?>"/></td>
                        <td><input class="form-control qty" name="qty[]" id="qty" type="number" placeholder="Isi angka saja" onkeyup="count('');" onchange="count('');" onclick="count('');" value="<?php echo set_value('qty[0]') ?>" min="1" required/></td>
                        <td><input class="form-control total" name="total[]" id="total" type="text" readonly/></td>
                        <td><input class="form-control" name="catatan[]" id="catatan" type="text" value="<?php echo set_value('catatan[0]') ?>"/></td>
                        <td><button type="button" class="btn btn-primary" id="addrow"/><i class="fa fa-plus"></i></button></td>
                      </tr>
                    </tbody>
                  </table>
									<button type="submit" name="submit" class="btn btn-success"><?php echo $button_submit ?></button>
									<button type="reset" name="reset" class="btn btn-danger"><?php echo $button_reset ?></button>
								<?php echo form_close(); ?>
							</div>
						</div>
          </div><!-- ./col -->
        </div><!-- /.row -->
      </section><!-- /.content -->
    </div><!-- /.content-wrapper -->
    <?php $this->load->view('back/footer') ?>
  </div><!-- ./wrapper -->
  <?php $this->load->view('back/js') ?>
  <link href="<?php echo base_url('assets/plugins/')?>datepicker/css/bootstrap-datepicker.css" rel="stylesheet">
  <script src="<?php echo base_url('assets/plugins/')?>datepicker/js/bootstrap-datepicker.js"></script>
  <script type="text/javascript">
  <?php
    $jsArray = "var prdName = new Array();\n";
    foreach ($get_all as $barang)
    {
      $jsArray .= "prdName['".$barang->id_lapangan."'] =
      {
        stok:'".addslashes($barang->stok)."',
        kd_barang:'".addslashes($barang->kd_barang)."',
        harga_jual:'".addslashes($barang->harga_jual)."',
      };\n";
    }
    echo $jsArray;
  ?>

  const numberWithCommas = (x) => {
    var parts = x.toString().split(".");
    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    return parts.join(".");
  }

  $(function()
  {
    $(document).on("focus", ".tanggal", function(){
      $(this).datepicker({startDate: '0',autoclose: true,todayHighlight: true,format: 'yyyy-mm-dd'});
    });

    $('.tanggal').on('changeDate', function(ev){
      tanggal_el = $(this);
      tanggal_val = $(this).val();
      jam_mulai_el = tanggal_el.parent().parent().find(".jam_mulai");
      durasi_el = tanggal_el.parent().parent().find(".durasi");
      jam_selesai_el = durasi_el.parent().parent().find(".jam_selesai");
      loading_container_el = tanggal_el.parent().parent().find(".loading_container");
      lapangan_id_el = tanggal_el.parent().parent().find(".lapangan_id");

      jam_mulai_el.hide();
      loading_container_el.show();

      $.post( '<?php echo base_url(); ?>Cart/getJamMulai', {tanggal: tanggal_val, lapangan_id: lapangan_id_el.val()}, function(data) {
          jam_mulai_el.show();
          loading_container_el.hide();
          jam_mulai_el.html("");

          jam_mulai_el.append("<option value='' selected='selected'>- Pilih Jam Mulai -</option>");

          count = 0;

          data.forEach(function(item, index){
            // console.log(item);
            jam_mulai_el.append("<option durasi='"+item.durasi+"'>"+item.jam_mulai+"</option>");
            count++;
          });

          durasi_el.val(0);
          jam_selesai_el.html("");

          if(count == 0){
            jam_mulai_el.html("");
            jam_mulai_el.append("<option value='' selected='selected'>- Tidak ada pilihan -</option>");
          }

         },
         'json'
      );
    });

    $(document).on("change", ".jam_mulai", function(){
      jam_mulai_el = $(this);
      durasi_el = jam_mulai_el.parent().parent().find(".durasi");
      durasi_el.val(jam_mulai_el.find(":selected").attr("durasi")).change();
    });

    $(document).on("change keyup", ".durasi", function(){
      durasi_el = $(this);
      durasi = $(this).val();

      if(durasi == ""){
        durasi = 0;
        durasi_el.val(durasi);
      }

      jam_mulai_el = durasi_el.parent().parent().find(".jam_mulai");
      jam_selesai_el = durasi_el.parent().parent().find(".jam_selesai");

      harga_per_jam_el = durasi_el.parent().parent().find(".harga_per_jam");
      subtotal_el = durasi_el.parent().parent().find(".subtotal");

      if(jam_mulai_el.val() != ""){
        jam_selesai = moment("01-01-2018 "+jam_mulai_el.val(), "MM-DD-YYYY HH:mm:ss").add(parseInt(durasi), 'hours').format('HH:mm:ss');
        jam_selesai_el.html(jam_selesai);

        harga_per_jam = harga_per_jam_el.html().replace(/,/g, '');
        harga_per_jam_int = parseInt(harga_per_jam);

        subtotal_el.html(numberWithCommas(harga_per_jam_int*parseInt(durasi)));

        subtotal_bawah = 0;
        $('.subtotal').each(function(i, obj) {
          a_subtotal_html = $(this).html().trim().replace(/,/g, '');
          if(a_subtotal_html == ""){
            a_subtotal_html = "0";
          }

          a_subtotal_html_int = parseInt(a_subtotal_html);
          subtotal_bawah += a_subtotal_html_int;
        });

        <?php if($this->session->userdata('usertype') == '3'){ echo "var disc = '20000';" ;?>
        <?php }else{echo "var disc = '0';";} ?>

        var diskon = $('#diskon').val();

        $("#subtotal_bawah").html(numberWithCommas(subtotal_bawah));
        $("#diskon").html(disc);
        var gtotal = (subtotal_bawah - disc);
        $("#grandtotal").html(numberWithCommas(gtotal));
      }
    });
  });
  </script>
</body>
</html>
