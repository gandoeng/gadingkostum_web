<link href="https://fonts.googleapis.com/css?family=Open+Sans&display=swap" rel="stylesheet">
<style>
  table{
    margin-top: 15px;
    font-family: 'Open Sans', sans-serif;
    margin-bottom: 50px !important;
  }
  .table-bordered thead tr,.table-bordered tfoot tr,.table-bordered .saldoawal{
    background-color: #ccc !important;
    background: #ccc !important;
  }
  .table-bordered thead tr,.table-bordered tfoot tr,.table-bordered .items td:first-child{
    text-align: left !important;
  }
  .table-bordered>tbody>tr>th, .table-bordered>tfoot>tr>th, .table-bordered>thead>tr>td, .table-bordered>tbody>tr>td, .table-bordered>tfoot>tr>td{
    border: 1px solid #ccc !important;
  }
  .table-bordered>thead>tr>th, .table-bordered>tbody>tr>th, .table-bordered>tfoot>tr>th, .table-bordered>thead>tr>td, .table-bordered>tbody>tr>td, .table-bordered>tfoot>tr>td{
    border: 1px solid #ccc !important;
  }
  .table-striped>tbody>tr:nth-of-type(odd){
    background-color: #ecf0f5;
    background: #ecf0f5;
  }
  .form-inline .form-group.collapse{
    display: none;
  }
  .form-inline .form-group.collapse.in{
    display: inline-block !important;
  }
  .form-inline .form-group .datepicker{
    padding: 0 !important;
  }
</style>
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Daily Report
      <!-- <small>Version 2.0</small> -->
    </h1>
  </section>
  <!-- Main content -->
  <section class="content">
    <div class="row">
      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="box box-solid">
          <div class="box-body">
            <input type="hidden" id="geturl" value="<?php echo base64_encode(serialize($geturl));?>">
            <form class="form-inline" style="margin-bottom: 10px;" action="<?php echo base_url('adminsite/daily_report');?>" method="GET">
              <div class="form-group" style="margin-top: 25px;">
                <button type="submit" class="btn-flat btn-primary btn">Apply</button>
                <a href="<?php echo base_url('adminsite/daily_report');?>" type="button" class="btn-flat btn-primary btn">Reset</a>
              </div>
              <div class="form-group">
                <label style="display:block;">Search</label>
                <?php
                $getSearch = '';
                (isset($_GET['search']) && !empty($_GET['search'])) ? $getSearch = $_GET['search'] : '';
                ?>
                <input type="text" name="search" class="form-control" value="<?php echo $getSearch;?>" placeholder="Search">
              </div>
              <div class="form-group">
                <label style="display:block;">Show</label>
                <select id="show-datepicker" name="show" class="form-control">
                  <option selected disabled hidden>Show</option>
                  <option value="">-- Empty --</option>
                  <option data-toggle="collapse" data-target="#daily" value="daily" <?php echo (isset($_GET['show']) && !empty($_GET['show']) && $_GET['show'] == 'daily') ? 'selected' : ''; ?>> By daily</option>
                  <option data-toggle="collapse" data-target="#date" value="date" <?php echo (isset($_GET['show']) && !empty($_GET['show']) && $_GET['show'] == 'date') ? 'selected' : ''; ?>> By date</option>
                </select>
              </div>
              <div id="date" class="form-group collapse <?php echo (isset($_GET['show']) && $_GET['show'] == 'date') ? 'in' : '' ?>">
                <label style="display:block;">Select Date</label>
                <div class="form-group">
                  <input <?php echo (isset($_GET['start'])) ? 'value="'.$_GET['start'].'"' : '' ?> readonly type="text" class="form-datepicker form-control" id="start" name="start">
                </div>
                <div class="form-group">
                  <input <?php echo (isset($_GET['end'])) ? 'value="'.$_GET['end'].'"' : '' ?>  readonly type="text" class="form-datepicker form-control" id="end" name="end">
                </div>
              </div>
              <div id="daily" class="form-group collapse <?php echo (isset($_GET['show']) && $_GET['show'] == 'daily') ? 'in' : '' ?>">
                <label style="display:block;">Select Daily</label>
                <input <?php echo (isset($_GET['date'])) ? 'value="'.$_GET['date'].'"' : '' ?> readonly type="text" class="form-datepicker form-control" name="date">
              </div>
            </form>
            
            <?php 
                  echo '<table class="display text-center table table-hover table-bordered table-striped table-align-middle" cellspacing="0" width="100%">';
                    echo '<thead>';
                      echo '<tr>';
                        echo '<th colspan="3" style="width: 300px;">'.strtoupper('cash').'</th>';
                        echo '<th>KETERANGAN</th>';
                        echo '<th>DEBIT</th>';
                        echo '<th>KREDIT</th>';
                        echo '<th>SALDO</th>';
                      echo '</tr>';
                    echo '</thead>';
                      echo '<tbody>';
                        echo '<tr class="saldoawal">';
                          echo '<td colspan="3"><strong>SALDO AWAL</strong></td>';
                          echo '<td></td>';
                          echo '<td><strong>'.number_format($saldoawal['cash']['debit']).'</strong></td>';
                          echo '<td><strong>'.number_format($saldoawal['cash']['kredit']).'</strong></td>';
                          echo '<td><strong>'.number_format($saldoawal['cash']['total']).'</strong></td>';
                        echo '</tr>';
                  if(isset($cash) && !empty($cash)){ 
                    foreach($cash as $index => $values){
                      if(is_array($values) && !empty($values)){
                        foreach($values as $k => $r){
                          foreach($r as $i => $val){
                            echo '<tr class="items">';
                              if(is_string($index)){
                                echo '<td colspan="3" style="width: 300px;">'.$val['label'].'</td>';
                                echo '<td></td>';
                              } else {
                                echo '<td colspan="3" style="width: 300px;">'.$val['rental_invoice'].' | '.$val['customer_name'].' '.$val['customer_phone'].' | ('.$val['jenis_transaksi_qty'].') '.$val['jenis_transaksi_product'].'</td>';
                                echo '<td>'.$val['label'].'</td>';
                              }
                                echo '<td>'.number_format($val['debit']).'</td>';
                                echo '<td>'.number_format($val['kredit']).'</td>';
                                echo '<td>'.number_format($val['total']).'</td>';
                            echo '</tr>';
                          }
                        }
                      }
                    }
                  }
                    echo '</tbody>';
                    echo '<tfoot>';
                      echo '<tr>';
                        echo '<td colspan="3"></td>';
                        echo '<td></td>';
                        echo (isset($saldoakhir['cash']['debit'])) ? '<td><strong>'.number_format($saldoakhir['cash']['debit']).'</strong></td>' : '<td></td>';
                        echo (isset($saldoakhir['cash']['kredit'])) ? '<td><strong>'.number_format($saldoakhir['cash']['kredit']).'</strong></td>' : '<td></td>';
                        echo (isset($saldoakhir['cash']['total'])) ? '<td><strong>'.number_format($saldoakhir['cash']['total']).'</strong></td>' : '<td></td>';
                      echo '</tr>';
                    echo '</tfoot>';
                  echo '</table>';
            ?>

            <?php 
                if(isset($debit) && !empty($debit)){ 
                  echo '<table class="display text-center table table-hover table-bordered table-striped table-align-middle" cellspacing="0" width="100%">';
                    echo '<thead>';
                      echo '<tr>';
                        echo '<th colspan="3" style="width: 300px;">'.strtoupper('debit').'</th>';
                        echo '<th>KETERANGAN</th>';
                        echo '<th>DEBIT</th>';
                        echo '<th>KREDIT</th>';
                        echo '<th>SALDO</th>';
                      echo '</tr>';
                    echo '</thead>';
                      echo '<tbody>';
                    foreach($debit as $index => $values){
                      if(is_array($values) && !empty($values)){
                        foreach($values as $k => $r){
                          foreach($r as $i => $val){
                            echo '<tr class="items">';
                              if(is_string($index)){
                                echo '<td colspan="3" style="width: 300px;">'.$val['label'].'</td>';
                                echo '<td></td>';
                              } else {
                                echo '<td colspan="3" style="width: 300px;">'.$val['rental_invoice'].' | '.$val['customer_name'].' '.$val['customer_phone'].' | ('.$val['jenis_transaksi_qty'].') '.$val['jenis_transaksi_product'].'</td>';
                                echo '<td>'.$val['label'].'</td>';
                              }
                                echo '<td>'.number_format($val['debit']).'</td>';
                                echo '<td>'.number_format($val['kredit']).'</td>';
                                echo '<td>'.number_format($val['total']).'</td>';
                            echo '</tr>';
                            }
                        }
                      }
                    }
                    echo '</tbody>';
                    echo '<tfoot>';
                      echo '<tr>';
                        echo '<td colspan="3"></td>';
                        echo '<td></td>';
                        echo (isset($saldoakhir['debit']['debit'])) ? '<td><strong>'.number_format($saldoakhir['debit']['debit']).'</strong></td>' : '<td></td>';
                        echo (isset($saldoakhir['debit']['kredit'])) ? '<td><strong>'.number_format($saldoakhir['debit']['kredit']).'</strong></td>' : '<td></td>';
                        echo (isset($saldoakhir['debit']['total'])) ? '<td><strong>'.number_format($saldoakhir['debit']['total']).'</strong></td>' : '<td></td>';
                      echo '</tr>';
                    echo '</tfoot>';
                  echo '</table>';
                }
            ?>

            <?php
                  if(isset($transfer) && !empty($transfer)){ 
                  echo '<table class="display text-center table table-hover table-bordered table-striped table-align-middle" cellspacing="0" width="100%">';
                    echo '<thead>';
                      echo '<tr>';
                        echo '<th colspan="3" style="width: 300px;">'.strtoupper('transfer').'</th>';
                        echo '<th>KETERANGAN</th>';
                        echo '<th>DEBIT</th>';
                        echo '<th>KREDIT</th>';
                        echo '<th>SALDO</th>';
                      echo '</tr>';
                    echo '</thead>';
                      echo '<tbody>';
                    foreach($transfer as $index => $values){
                      if(is_array($values) && !empty($values)){
                        foreach($values as $k => $r){
                          foreach($r as $i => $val){
                            echo '<tr class="items">';
                              if(is_string($index)){
                                echo '<td colspan="3" style="width: 300px;">'.$val['label'].'</td>';
                                echo '<td></td>';
                              } else {
                                echo '<td colspan="3" style="width: 300px;">'.$val['rental_invoice'].' | '.$val['customer_name'].' '.$val['customer_phone'].' | ('.$val['jenis_transaksi_qty'].') '.$val['jenis_transaksi_product'].'</td>';
                                echo '<td>'.$val['label'].'</td>';
                              }
                                echo '<td>'.number_format($val['debit']).'</td>';
                                echo '<td>'.number_format($val['kredit']).'</td>';
                                echo '<td>'.number_format($val['total']).'</td>';
                            echo '</tr>';
                            }
                        }
                      }
                    }
                    echo '</tbody>';
                    echo '<tfoot>';
                      echo '<tr>';
                        echo '<td colspan="3"></td>';
                        echo '<td></td>';
                        echo (isset($saldoakhir['transfer']['debit'])) ? '<td><strong>'.number_format($saldoakhir['transfer']['debit']).'</strong></td>' : '<td></td>';
                        echo (isset($saldoakhir['transfer']['kredit'])) ? '<td><strong>'.number_format($saldoakhir['transfer']['kredit']).'</strong></td>' : '<td></td>';
                        echo (isset($saldoakhir['transfer']['total'])) ? '<td><strong>'.number_format($saldoakhir['transfer']['total']).'</strong></td>' : '<td></td>';
                      echo '</tr>';
                    echo '</tfoot>';
                  echo '</table>';
                  }
            ?>

              <div class="form-group">
                <a class="btn btn-flat btn-primary btn-print-action" href="<?php echo base_url('adminsite/daily_report/print_report/').urlencode(base64_encode(serialize($geturl)));?>">PRINT PREVIEW</a>
              </div>

          </div>
          <div class="overlay">
            <i class="fa fa-refresh fa-spin"></i>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>
<iframe id="printf" name="printf" style="display:none;"></iframe>
<script>
  $(function(){
    $(document).find(".btn-print-action").printPage();
  });
</script>