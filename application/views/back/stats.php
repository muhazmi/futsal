<section class="content">
  <div class="row">
    <div class="col-lg-6">
      <script src="<?php echo base_url('assets/plugins/chartjs/Chart.min.js') ?>"></script>
      <div class="box box-success box-solid">
        <div class="box-header with-border">
          <h3 class="box-title">Grafik Omset Bulanan Tahun <?php echo date("Y") ?></h3>
          <div class="box-tools pull-right">
            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          </div><!-- /.box-tools -->
        </div><!-- /.box-header -->
        <div class="box-body">
          <canvas id="canvas" width="100%" height="100%"></canvas>
          <?php foreach($stats_omset_tahunan as $laporan){
            $json_jual_tahunan[] = $laporan->nama_bulan;
            $json2_jual_tahunan[] = $laporan->subtotal;
          }?>
          <script type="text/javascript">
          new Chart(document.getElementById("canvas"), {
            type: 'bar',
            data: {
              labels: <?php echo json_encode($json_jual_tahunan); ?>,
              datasets: [
                {
                  label: "Omset (Rp)",
                  backgroundColor: ['#e6194b','#3cb44b','#ffe119','#0082c8','#f58231','#911eb4','#46f0f0','#f032e6','#d2f53c','#fabebe','#008080','#aa6e28'],
                  data: <?php echo json_encode($json2_jual_tahunan); ?>
                }
              ]
            },
            options: {
              legend: { display: false },
              scales: {
                yAxes: [{
                  ticks: {
                    beginAtZero: true,
                    callback: function(value, index, values) {
                      if(parseInt(value) >= 1000){
                        return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                      } else {
                        return value;
                      }
                    }
                  }
                }]
              }
            }
          });
          </script>
        </div><!-- /.box-body -->
      </div>
    </div>
    <div class="col-lg-6">
      <script src="<?php echo base_url('assets/plugins/chartjs/Chart.min.js') ?>"></script>
      <div class="box box-warning box-solid">
        <div class="box-header with-border">
          <h3 class="box-title">Grafik Omset Harian Bulan <?php echo date("M-Y") ?></h3>
          <div class="box-tools pull-right">
            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          </div><!-- /.box-tools -->
        </div><!-- /.box-header -->
        <div class="box-body">
          <canvas id="canvas_bulan" width="100%" height="100%"></canvas>
          <?php foreach($stats_omset_bulanan as $laporan_bulan){
            $json_jual_bulan[] = $laporan_bulan->created_date;
            $json2_jual_bulan[] = $laporan_bulan->subtotal;
          }?>
          <script type="text/javascript">
          var nama = <?php echo json_encode($json_jual_bulan); ?>;
          var total = <?php echo json_encode($json2_jual_bulan); ?>;
          new Chart(document.getElementById("canvas_bulan"), {
            type: 'line',
            data: {
              labels: nama,
              datasets: [{
                  data: total,
                  borderColor: "green",
                  fill: true
                }
              ]
            },
            options: {
              scales: {
                yAxes: [{
                    stacked: true,
                    ticks: {
                      beginAtZero: true,
                      callback: function(value, index, values) {
                        if(parseInt(value) >= 1000){
                          return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                        } else {
                          return value;
                        }
                      }
                    }
                }]
            },
              legend: {
                display: false
              },
              tooltips: {
                callbacks: {
                  label: function(tooltipItem) {
                    return tooltipItem.yLabel;
                  }
                }
              }
            }
          });
          </script>

        </div><!-- /.box-body -->
      </div>
    </div>
  </div>
</section>
