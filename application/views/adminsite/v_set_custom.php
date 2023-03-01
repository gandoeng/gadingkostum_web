<style type="text/css">
  @media (max-width: 1200px){
    #form-action .form-group{
      margin: 0;
    }
    #form-action .form-group .btn{
      margin-top: 5px;
      margin-bottom: 5px;
    }
    #form-action .col-lg-2{
      padding: 0;
    }
  }
  @media (max-width: 767px){
    .datepicker{
      width: 767px;
    }
    .space{
      display: none !important;
    }
  }
</style>
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Set Kostum
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
            <div class="btn-group btn-group-action">
              <a href="<?php echo base_url('adminsite/set_custom/add');?>" class="btn btn-primary btn-flat"><i class="fa fa-plus"></i> ADD NEW</a>
            </div>

            <ol class="breadcrumb">
              <li <?php echo ($this->uri->segment(3) == '') ? 'class="active"' : '' ?>><a href="<?php echo base_url('adminsite/set_custom');?>">Set Custom</a></li>
              <li <?php echo ($this->uri->segment(3) == 'karyawan') ? 'class="active"' : '' ?>><a href="<?php echo base_url('adminsite/set_custom/karyawan');?>">List Karyawan</a></li>
            </ol>

            <form class="form-inline" style="margin-bottom: 10px;" action="<?php echo base_url('adminsite/set_custom');?>" method="GET">
              <div class="form-group">
                <label style="display:block;">Cari Karyawan</label>
                <?php
                $getSearch = '';
                (isset($_GET['search']) && !empty($_GET['search'])) ? $getSearch = $_GET['search'] : '';
                ?>
                <input type="text" name="search" class="form-control" value="<?php echo $getSearch;?>" placeholder="Cari Nama">
              </div>
              <div class="form-group">
                <label style="display:block;">Produk Nama/Kode</label>
                <?php
                $getProd = '';
                (isset($_GET['prod']) && !empty($_GET['prod'])) ? $getProd = $_GET['prod'] : '';
                ?>
                <input type="text" name="prod" class="form-control" value="<?php echo $getProd;?>" placeholder="Cari">
              </div>
              <div class="form-group">
                <label style="display:block;">Date Range</label>
                <div class="input-group datepicker date" data-provide="datepicker">
                  <input style="width: 140px;" readonly name="start" type="text" class="form-control" <?php echo (isset($_GET['start'])) ? 'value="'.$_GET['start'].'"' : '' ?>>
                  <div class="input-group-addon">
                    <span class=" glyphicon glyphicon-calendar"></span>
                  </div>
                </div>
                <div class="input-group datepicker date" data-provide="datepicker">
                  <input style="width: 140px;" readonly name="end" type="text" class="form-control" <?php echo (isset($_GET['end'])) ? 'value="'.$_GET['end'].'"' : '' ?>>
                  <div class="input-group-addon">
                    <span class=" glyphicon glyphicon-calendar"></span>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label class="space" style="display:block;">&nbsp;</label>
                <div class="checkbox">
                  <label>
                    <input type="checkbox" name="note" value="1" <?php echo (isset($_GET['note'])) ? 'checked' : '' ?>>
                    Note Terisi
                  </label>
                </div>
                <button type="submit" class="btn-flat btn-primary btn">Apply</button>
                <a href="<?php echo base_url('adminsite/set_custom');?>" type="button" class="btn-flat btn-primary btn">Reset</a>
                <a class="btn btn-flat btn-primary btn-print-action" href="<?php echo base_url('adminsite/set_custom/print_report/').urlencode(base64_encode(serialize($geturl)));?>">Print</a>
              </div>
            </form>
            
            <form id="form-action" class="form-horizontal">
              <label style="display:block;">Action</label>
              <div class="form-group">
                <div class="col-lg-2">
                  <select class="form-control" id="action">
                    <option hidden selected value="">Action</option>
                    <option value="<?php echo base_url('adminsite/set_custom/multiple_delete');?>">Delete</option>
                  </select>
                </div>
                <button class="btn btn-primary btn-flat" type="submit">Apply</button>
              </div>
            </form>

            <style type="text/css">
              table tbody tr td:nth-child(3), table tbody tr td:nth-child(4), table tbody tr td:nth-child(5){
                text-align: left;
              }
            </style>
            <div class="table-responsive">
              <table id="<?php echo $table_data;?>" class="display text-center table table-hover table-bordered table-striped table-align-middle" cellspacing="0" width="100%">
                <thead>
                  <tr>
                    <th><div class="icheckbox"><input id="check_action" type="checkbox"></div></th>
                    <th>Nama</th>
                    <th>Tanggal</th>
                    <th>Product Nama/Kode</th>
                    <th>Product Size</th>
                    <th>Note</th>
                    <th>Action</th>
                  </tr>
                </thead>
              </table>
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