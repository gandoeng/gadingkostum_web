 <style type="text/css">
  h2{
    margin-top: 2px;
    margin-bottom: 2px;
  }

  .wrapper-report{
    margin-top: 15px;
    margin-bottom: 15px;
  }
  .wrapper-report .callout{
    margin-bottom: 0;
    border-left: 0;
    border: 1px solid #ddd;
  }

  .wrapper-report .callout.blue{
    border-right: 5px solid #3c8dbc;
  }

  .wrapper-report .callout.purple{
    border-right: 5px solid #804f9f;
  }

  .wrapper-report .callout.pink{
    border-right: 5px solid #f6a881;
  }

  .wrapper-report .callout.green{
    border-right: 5px solid #87c340;
  }

  .form-inline{
    padding: 5px;
  }

  .form-inline input.form-control{
    width: 110px;
  }

  .nav-tabs li{
    padding: 5px 0;
  }

  .nav-tabs li.active .form-inline{
    color: #555;
    background-color: #fff;
    border: 1px solid #ddd;
    border-bottom-color: transparent;
    border-radius: 4px 4px 0 0;
  }
</style>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Report
      <!-- <small>Version 2.0</small> -->
    </h1>
    <ol class="breadcrumb">
      <li <?php echo (isset($_GET['sales_by']) && $_GET['sales_by'] == 'date') ? 'class="active"' : '' ?>>
        <a href="<?php echo base_url('adminsite/report?sales_by=date&filter_by=year');?>">Sales by date</a>
      </li>
      <li <?php echo (isset($_GET['sales_by']) && $_GET['sales_by'] == 'product') ? 'class="active"' : '' ?>>
        <a href="<?php echo base_url('adminsite/report?sales_by=product&filter_by=year');?>">Sales by product</a>
      </li>
    </ol>
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="row">
      <div class="col-lg-12">
        <div class="box box-solid">
          <div class="box-body">

            <?php if(isset($_GET['sales_by']) && $_GET['sales_by'] == 'date'){ ?>
            <ul class="nav nav-tabs">
              <li <?php echo (isset($_GET['filter_by']) && $_GET['filter_by'] == 'year') ? 'class="active"' : '' ?> ><a href="<?php echo base_url('adminsite/report?sales_by=date&filter_by=year');?>">Year</a></li>
              <li <?php echo (isset($_GET['filter_by']) && $_GET['filter_by'] == 'lastmonth') ? 'class="active"' : '' ?>><a href="<?php echo base_url('adminsite/report?sales_by=date&filter_by=lastmonth');?>">Last month</a></li>
              <li <?php echo (isset($_GET['filter_by']) && $_GET['filter_by'] == 'thismonth') ? 'class="active"' : '' ?>><a href="<?php echo base_url('adminsite/report?sales_by=date&filter_by=thismonth');?>">This month</a></li>
              <li <?php echo (isset($_GET['filter_by']) && $_GET['filter_by'] == 'lastdays') ? 'class="active"' : '' ?>><a href="<?php echo base_url('adminsite/report?sales_by=date&filter_by=lastdays');?>">Last 7 days</a></li>
              <li <?php echo (isset($_GET['filter_by']) && $_GET['filter_by'] == 'custom') ? 'class="active"' : '' ?>>
                <form class="form-inline" action="<?php echo base_url('adminsite/report');?>" method="GET">
                  <?php echo (isset($_GET['sales_by'])) ? '<input type="hidden" name="sales_by" value="'.$_GET['sales_by'].'">' : ''; ?>
                  <input type="hidden" name="filter_by" value="custom">
                  <div class="form-group">
                    <label for="start" style="margin-right: 5px;">Custom</label>
                    <input <?php echo (isset($_GET['start'])) ? 'value="'.$_GET['start'].'"' : '' ?> readonly type="text" class="form-datepicker form-control" id="start" name="start">
                  </div>
                  <div class="form-group">
                    <input <?php echo (isset($_GET['end'])) ? 'value="'.$_GET['end'].'"' : '' ?>  readonly type="text" class="form-datepicker form-control" id="end" name="end">
                  </div>
                  <button type="submit" class="btn btn-primary btn-flat">Go</button>
                </form>
              </li>
            </ul>
            <?php 
            echo '<div class="col-lg-12 wrapper-report" style="padding-right: 0; padding-left: 0;">';

            if($display){

              echo '<div class="col-lg-3" style="padding-right: 0; padding-left: 0;">';

              echo '<div class="callout blue">
              <h2>Rp. '.$prices.'</h2>
              <p>Sales in this period</p>
            </div>';

            echo '<div class="callout purple">
            <h2>Rp. '.$average.'</h2>
            <p>Average daily sales</p>
          </div>';

          echo '<div class="callout pink">
          <h2>'.$orders.'</h2>
          <p>Orders placed</p>
        </div>';

        echo '<div class="callout green">
        <h2>'.$items.'</h2>
        <p>Items Purchased</p>
      </div>';

      echo '</div>';
      if(!empty($grafik)){
        echo '<div class="col-lg-9" style="padding-right: 0; padding-left: 0;">';
        echo '<div id="chart" style="height: 300px; width: 100%;"></div>';
        echo '</div>';
      }

    } else {
      echo '<p class="text-center"><i>No Data Available</i></p>';
    }
    echo '</div>';

  } elseif(isset($_GET['sales_by']) && $_GET['sales_by'] == 'product'){ 

    $prod_filter = '';

    (isset($_GET['prod_id'])) ? $prod_filter = '&prod_id='.$_GET['prod_id'] : '';

    ?>
    <ul class="nav nav-tabs">
      <li <?php echo (isset($_GET['filter_by']) && $_GET['filter_by'] == 'year') ? 'class="active"' : '' ?> ><a href="<?php echo base_url('adminsite/report?sales_by=product&filter_by=year'.$prod_filter);?>">Year</a></li>
      <li <?php echo (isset($_GET['filter_by']) && $_GET['filter_by'] == 'lastmonth') ? 'class="active"' : '' ?>><a href="<?php echo base_url('adminsite/report?sales_by=product&filter_by=lastmonth'.$prod_filter);?>">Last month</a></li>
      <li <?php echo (isset($_GET['filter_by']) && $_GET['filter_by'] == 'thismonth') ? 'class="active"' : '' ?>><a href="<?php echo base_url('adminsite/report?sales_by=product&filter_by=thismonth'.$prod_filter);?>">This month</a></li>
      <li <?php echo (isset($_GET['filter_by']) && $_GET['filter_by'] == 'lastdays') ? 'class="active"' : '' ?>><a href="<?php echo base_url('adminsite/report?sales_by=product&filter_by=lastdays'.$prod_filter);?>">Last 7 days</a></li>
      <li <?php echo (isset($_GET['filter_by']) && $_GET['filter_by'] == 'custom') ? 'class="active"' : '' ?>>
        <form class="form-inline" action="<?php echo base_url('adminsite/report');?>" method="GET">
          <?php echo (isset($_GET['sales_by'])) ? '<input type="hidden" name="sales_by" value="'.$_GET['sales_by'].'">' : ''; ?>
          <input type="hidden" name="filter_by" value="custom">
          <?php echo (isset($_GET['prod_id'])) ? '<input type="hidden" name="prod_id" value="'.$_GET['prod_id'].'">' : '';?>
          <div class="form-group">
            <label for="start" style="margin-right: 5px;">Custom</label>
            <input <?php echo (isset($_GET['start'])) ? 'value="'.$_GET['start'].'"' : '' ?> readonly type="text" class="form-datepicker form-control" id="start" name="start">
          </div>
          <div class="form-group">
            <input <?php echo (isset($_GET['end'])) ? 'value="'.$_GET['end'].'"' : '' ?>  readonly type="text" class="form-datepicker form-control" id="end" name="end">
          </div>
          <button type="submit" class="btn btn-primary btn-flat">Go</button>
        </form>
      </li>
    </ul>
    <?php 
    echo '<div class="col-lg-12 wrapper-report" style="padding-right: 0; padding-left: 0;">';

  if(!empty($display)){

    echo '<div class="col-lg-3" style="padding-right: 0; padding-left: 0;">';

    echo '<div class="callout blue" style="padding: 10px;">
    <form style="padding: 0;" class="form-inline" action="'.base_url('adminsite/report').'" method="GET">';

      echo (isset($_GET['sales_by'])) ? '<input type="hidden" name="sales_by" value="'.$_GET['sales_by'].'">' : '';
      echo (isset($_GET['filter_by'])) ? '<input type="hidden" name="filter_by" value="'.$_GET['filter_by'].'">' : '';
      echo (isset($_GET['start'])) ? '<input type="hidden" name="start" value="'.$_GET['start'].'">' : '';
      echo (isset($_GET['end'])) ? '<input type="hidden" name="end" value="'.$_GET['end'].'">' : '';

      echo '<div class="form-group" style="margin-bottom: 15px; width: 100%;">
      <select id="product-category" name="prod_id" class="select2-init form-control">';
        if(!empty($product)){
          foreach($product->result_array() as $index => $value){
            $checked = '';
            (isset($_GET['prod_id']) && $_GET['prod_id'] == $value['product_id']) ? $checked = 'selected' : '';
            echo '<option '.$checked.' value="'.$value['product_id'].'">'.$value['product_nama'].' '.$value['product_kode'].'</option>';

          }
        } 
        echo '</select>

      </div>
      <div class="form-group" style="display:block;">
        <button style="min-height: 32px; padding: 6px 20px;" class="btn btn-primary" type="submit">Go</button>
      </div>
    </form>
  </div>';

  if(!empty($check_product)){
    echo '<div class="callout blue">
    <h2>Rp. '.$prices.'</h2>
    <p>Sales for the selected items</p>
  </div>';

  echo '<div class="callout purple">
  <h2>'.$items.'</h2>
  <p>Purchases for the selected items</p></div>';

  echo '<div class="callout blue">
    <h4 style="margin-top: 0;margin-bottom: 5px;">'.$check_product[0]['product_nama'].'</h4>
    <h4 style="margin-top: 0;margin-bottom: 5px;">'.$check_product[0]['product_kode'].'</h4>
  </div>';

}

echo '</div>';
if(!empty($grafik) && !empty($check_product)){
  echo '<div class="col-lg-9" style="padding-right: 0; padding-left: 0;">';
  echo '<div id="chart" style="height: 300px; width: 100%;"></div>';
  echo '</div>';
} else {
  echo '<div class="col-lg-9" style="padding-right: 0;">';
  echo '<div class="callout callout-danger" style="margin-bottom: 10px;">
      <p class="text-center">Produk belum dipilih atau data report pada produk tidak ada.</p>
    </div>';
    echo '</div>';
}

} else {
  echo '<p class="text-center"><i>No Data Available</i></p>';
}
echo '</div>';

} else {
  echo '<p class="text-center"><i>No Data Available</i></p>';
}
?>

</div>
<div class="overlay">
  <i class="fa fa-refresh fa-spin"></i>
</div>
</div>
</div>
</div>
</section>
</div>

<script type="text/javascript">

  <?php if(!empty($grafik)){ ?>

    var data_sales  = [<?php echo implode(', ',$grafik['sales']) ?>];
    var data_labels = [<?php echo implode(', ',$grafik['labels']) ?>];
    var data_kostum = [<?php echo implode(', ',$grafik['kostum']) ?>];
    var data_order  = [<?php echo implode(', ',$grafik['order']) ?>];

    console.log(data_sales);
    console.log(data_labels);

    var labelFormatter = function(value) {
      var val = Math.abs(value);
      if (val >= 1000000) {
        val = (val / 1000000).toFixed(1) + " M";
      }
      return val;
    };

    function numberWithCommas(number) {
      var parts = number.toString().split(".");
      parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
      return parts.join(".");
    }

    var options = {
      grid: {
        row: {
          colors: ['#e5e5e5', 'transparent'],
          opacity: 0.5
        }, 
        column: {
          colors: ['#f8f8f8', 'transparent'],
        }, 
        grid: {
          row: {
            colors: ['#e5e5e5', 'transparent'],
            opacity: 0.5
          }, 
          column: {
            colors: ['#f8f8f8', 'transparent'],
          }
        },
        xaxis: {
          lines: {
            show: true
          }
        }
      },
      chart: {
        height: 350,
        type: 'line',
        zoom: {
          enabled: false
        }
      },
      dataLabels: {
        enabled: false
      },
      stroke: {
        curve: "straight"
      },
      colors: ["#247BA0", '#66C7F4',"#FF1654"],
      series: [{
        name: 'Sales',
        type: 'column',
        data: data_sales
      }, {
        name: 'Kostum',
        type: 'line',
        data: data_kostum
      }, {
        name: 'Order',
        type: 'line',
        data: data_order
      }],
      stroke: {
        width: [0, 4]
      },
      title: {
        text: 'Report'
      },
      labels: data_labels,
      xaxis: {
        labels: {
          datetimeFormatter: {
            year: 'yyyy',
            month: 'MMM \'yy',
            day: 'dd MMM',
            hour: 'HH:mm'
          }
        }
      },
      yaxis: [{
        labels: {
          formatter: function(val, index) {
            var price = index;
            return numberWithCommas(val);
          },
          seriesName: {
            seriesName: 'Sales',
            logarithmic: true,
            offsetX: 10
          }
        },

      }, {
        opposite: true,
        seriesName: {
          text: 'Kostum'
        }
      }, {
        opposite: true,
        seriesName: {
          text: 'Order'
        }
      }]

    }

    var chart = new ApexCharts(
      document.querySelector("#chart"),
      options
      );

    chart.render();

    <?php } ?>

  </script>