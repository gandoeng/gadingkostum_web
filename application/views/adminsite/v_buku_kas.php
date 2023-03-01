<link href="https://fonts.googleapis.com/css?family=Open+Sans&display=swap" rel="stylesheet">
<style>
  table{
    margin-top: 15px;
    font-family: 'Open Sans', sans-serif;
    margin-bottom: 30px !important;
  }
  table thead tr th{
    vertical-align: middle !important;
  }
  .table-bordered thead tr,.table-bordered tfoot tr,.table-bordered .saldoawal{
    background-color: #ccc !important;
    background: #ccc !important;
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
  .ui-datepicker{ z-index:1151 !important; }
  .datepicker{ z-index:1151 !important; }
</style>
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Buku Kas
      <!-- <small>Version 2.0</small> -->
    </h1>
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="row">
      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="box box-solid">
          <div class="box-body">
            <form class="form-inline" style="margin-bottom: 10px;" action="<?php echo base_url('adminsite/buku_kas');?>" method="GET">
              <div class="form-group" style="margin-top: 25px;">
                <button type="submit" class="btn-flat btn-primary btn">Apply</button>
                <a href="<?php echo base_url('adminsite/buku_kas');?>" type="button" class="btn-flat btn-primary btn">Reset</a>
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
                  <option data-toggle="collapse" data-target="#month" value="month" <?php echo (isset($_GET['show']) && !empty($_GET['show']) && $_GET['show'] == 'month') ? 'selected' : ''; ?>> By month</option>
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
              <div id="month" class="form-group collapse <?php echo (isset($_GET['show']) && $_GET['show'] == 'month') ? 'in' : '' ?>">
                <label style="display:block;">Select Month</label>
                <div style="width: 190px;" class="input-group datepicker-month datepicker date" data-provide="datepicker">
                  <input <?php echo (isset($_GET['month'])) ? 'value="'.$_GET['month'].'"' : '' ?> readonly name="month" type="text" class="form-control">
                  <div class="input-group-addon">
                    <span class=" glyphicon glyphicon-calendar"></span>
                  </div>
                </div>
              </div>
            </form>
            <table class="display text-center table table-hover table-bordered table-striped table-align-middle" cellspacing="0" width="100%">
              <thead>
                <tr>
                  <th style="width:250px;">TRANSAKSI</th>
                  <th style="width:150px;">JENIS TRANSAKSI</th>
                  <th>NAMA & TELP</th>
                  <th style="width:300px;">TANGGAL & JAM</th>
                  <th>DEBIT</th>
                  <th>KREDIT</th>
                  <th>SALDO</th>
                </tr>
              </thead>
              <tbody>
                <?php
                    echo '<tr class="saldoawal">';
                      echo '<td colspan="4"><strong>SALDO AWAL</strong></td>';
                      echo '<td><strong>'.number_format($saldoawal['debit']).'</strong></td>';
                      echo '<td><strong>'.number_format($saldoawal['kredit']).'</strong></td>';
                      echo '<td><strong>'.number_format($saldoawal['total']).'</strong></td>';
                    echo '</tr>';
                    if(!empty($result)){
                        foreach($result as $key => $row){
                          foreach($row as $index => $value){
                            foreach($value as $k => $val){
                              $action = $val['nama'];
                              if(is_string($key) && isset($session_items['role']) && $session_items['role'] == 'admin'){
                                $action = '<div class="inline-group">';
                                $action .= '<button type="button" class="btn btn-primary btn-flat btn-sm" data-action="edit" data-id="'.$val['jenis_transaksi_id'].'" data-flag="'.$val['jenis_transaksi'].'" data-jenis="'.$val['jenis_transaksi_flag'].'" data-debit="'.$val['debit'].'" data-kredit="'.$val['kredit'].'" data-note="'.$val['jenis_transaksi_note'].'" data-tanggal="'.date('d/m/Y',strtotime($val['created'])).'" data-toggle="modal" data-target="#transactionModal">EDIT</button>';
                                $action .= '<a type="button" class="btn btn-remove-transaction btn-danger btn-flat btn-sm" href="'.base_url('adminsite/buku_kas/delete/').$val['jenis_transaksi_id'].'">X</a>';
                              }
                              echo '<tr>';
                                  echo '<td style="text-align:left!important;">'.'<span style="width: 100px;">'.$val['transaksi'].'</span>&nbsp;&nbsp;&nbsp;'.$val['label'].'</td>';
                                  echo '<td>'.strtoupper($val['jenis_transaksi_flag']).'</td>';
                                  echo '<td>'.$action.'</td>';
                                  echo '<td>'.$val['tanggal'].'</td>';
                                  echo '<td>'.number_format($val['debit']).'</td>';
                                  echo '<td>'.number_format($val['kredit']).'</td>';
                                  echo '<td>'.number_format($val['saldo']).'</td>';
                              echo '</tr>';
                            }
                          }
                        }
                    }
                ?>
                <tr>
                    <td colspan="4"></td>
                    <td><strong><?php echo number_format($totaldebit);?></strong></td>
                    <td><strong><?php echo number_format($totalkredit);?></strong></td>
                    <td><strong><?php echo number_format($totalsaldo);?></strong></td>
                </tr>
              <tbody>
            </table>
            <?php if(isset($session_items['role']) && $session_items['role'] == 'admin'){ ?>
              <button type="button" class="btn btn-primary btn-flat" data-action="add" data-toggle="modal" data-target="#transactionModal">ADD NEW TRANSACTION</button>
            <?php } ?>  
          </div>
          <div class="overlay">
            <i class="fa fa-refresh fa-spin"></i>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

<?php if(isset($session_items['role']) && $session_items['role'] == 'admin'){ ?>
<div class="modal fade" id="transactionModal" tabindex="-1" role="dialog" aria-labelledby="transactionModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="transactionModalLabel"></h5>
      </div>
      <form action="<?php echo base_url('adminsite/buku_kas/form');?>" method="POST">
        <input type="hidden" name="current_url" value="<?php echo $current_url;?>">
        <div class="modal-body"></div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">SAVE</button>
        </div>
      </form>
    </div>
  </div>
</div>
<?php } ?>