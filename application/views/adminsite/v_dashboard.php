 <div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Dashboard
      <!-- <small>Version 2.0</small> -->
    </h1>
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="row">
      <!-- <div class="col-md-4 col-sm-4 col-xs-12">
        <div class="info-box">
          <span class="info-box-icon bg-green"><i class="ion ion-ios-cart-outline"></i></span>

          <div class="info-box-content">
            <span class="info-box-number"><?php echo $count_all_rental_order;?></span>
            <span class="info-box-text">Sales</span>
            <a class="info-box-link" href="<?php echo base_url('adminsite/rental_order');?>">MORE INFO >></a>
          </div>
        </div>
      </div>

      <div class="clearfix visible-sm-block"></div>

      <div class="col-md-4 col-sm-4 col-xs-12">
        <div class="info-box">
          <span class="info-box-icon bg-blue"><i class="fa fa-tshirt"></i></span>

          <div class="info-box-content">
            <span class="info-box-number"><?php echo $count_all_product;?></span>
            <span class="info-box-text">Total Product</span>
            <a class="info-box-link" href="<?php echo base_url('adminsite/product');?>">MORE INFO >></a>
          </div>
        </div>
      </div>
      <div class="col-md-4 col-sm-4 col-xs-12">
        <div class="info-box">
          <span class="info-box-icon bg-yellow"><i class="fa fa-tshirt"></i><span class="tx-ab">Rent</span></span>
          <div class="info-box-content">
            <span class="info-box-number"><?php echo $count_all_rented;?></span>
            <span class="info-box-text">Product Rented</span>
          </div>
        </div>
      </div> -->

      <div class="col-md-6">
          <div class="box box-solid">
            <div class="box-header with-border">

              <h3 class="box-title">This day : <?php echo date('d-M-Y');?></h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <dl class="dl-horizontal">
                <dt style="text-align:left;">Total Sales</dt>
                <dd><?php echo $today_penjualan_order;?></dd>
                <dt style="text-align:left;">Rented Product</dt>
                <dd><?php echo $today_penjualan_kostum;?></dd>
                <dt style="text-align:left;">Total Sales IDR</dt>
                <dd><?php echo $today_penjualan_rupiah;?></dd>
              </dl>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>

      <div class="col-md-6">
          <div class="box box-solid">
            <div class="box-header with-border">

              <h3 class="box-title">This month : <?php echo date('F');?></h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <dl class="dl-horizontal">
                <dt style="text-align:left;">Total Sales</dt>
                <dd><?php echo $month_penjualan_order;?></dd>
                <dt style="text-align:left;">Rented Product</dt>
                <dd><?php echo $month_penjualan_kostum;?></dd>
                <dt style="text-align:left;">Total Sales IDR</dt>
                <dd><?php echo $month_penjualan_rupiah;?></dd>
              </dl>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        
    </div>

    <div class="row">
      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="box box-solid">
          <div class="box-header">
              <h3 class="box-title">Most Rented Product</h3>
          </div>
          <div class="box-body">
<input type="hidden" id="geturl" value="<?php echo base64_encode(serialize($geturl));?>">
            <form class="form-inline" style="margin-bottom: 10px;" action="<?php echo base_url('adminsite/dashboard');?>" method="GET">
              <div class="form-group">
                <label style="display:block;">Search</label>
                <?php
                $getSearch = '';
                (isset($_GET['search']) && !empty($_GET['search'])) ? $getSearch = $_GET['search'] : '';
                ?>
                <input type="text" name="search" class="form-control" value="<?php echo $getSearch;?>" placeholder="Search">
              </div>
              <div class="form-group">
                <label style="display:block;" for="email">Filter</label>
                <select name="filter" class="form-control">
                  <option selected disabled hidden>Select</option>
                  <option value="date" <?php echo (isset($_GET['filter']) && $_GET['filter'] == 'date') ? 'selected' : '' ?>>By Date</option>
                </select>
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
                <label style="display:block;">Order Field</label>
                <select name="order" class="form-control">
                  <option selected disabled hidden>Select</option>
                  <option value="">-- Empty --</option>
                  <option value="product_nama" <?php echo (isset($_GET['order']) && !empty($_GET['order']) && $_GET['order'] == 'product_nama') ? 'selected' : ''; ?>>Product Name</option>
                  <option value="product_kode" <?php echo (isset($_GET['order']) && !empty($_GET['order']) && $_GET['order'] == 'product_kode') ? 'selected' : ''; ?>>Kode</option>
                </select>
              </div>
              <div class="form-group">
                <label style="display:block;">Order By</label>
                <select name="order_by" class="form-control">
                  <option selected disabled hidden>Select</option>
                  <option value="">-- Empty --</option>
                  <option value="asc" <?php echo (isset($_GET['order_by']) && !empty($_GET['order_by']) && $_GET['order_by'] == 'asc') ? 'selected' : ''; ?>>Ascending (A-Z)</option>
                  <option value="desc" <?php echo (isset($_GET['order_by']) && !empty($_GET['order_by']) && $_GET['order_by'] == 'desc') ? 'selected' : ''; ?>>Descending (Z-A)</option>
                </select>
                <button type="submit" class="btn-flat btn-primary btn">Apply</button>
                <a href="<?php echo base_url('adminsite/dashboard');?>" type="button" class="btn-flat btn-primary btn">Reset</a>
              </div>
            </form>
            <div class="form-group">
            <a class="btn btn-flat btn-primary btn-print-action" href="<?php echo base_url('adminsite/dashboard/print_report/').urlencode(base64_encode(serialize($geturl)));?>">Download PDF</a>
            </div>
            <table id="<?php echo $table_data;?>" class="display text-center table table-hover table-bordered table-striped table-align-middle table-most-rented" cellspacing="0" width="100%">
              <thead>
                <tr>
                  <th>Product Name</th>
                  <th>SKU</th>
                  <th>Total Rented</th>
                </tr>
              </thead>
            </table>

          </div>
          <div class="overlay">
            <i class="fa fa-refresh fa-spin"></i>
          </div>
        </div>
      </div>
    </div>

  </section>
  <!-- /.content -->
</div>