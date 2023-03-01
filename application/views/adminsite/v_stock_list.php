 <div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Stock List
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
            <form class="form-inline" style="margin-bottom: 10px;" action="<?php echo base_url('adminsite/stock_list');?>" method="GET">
              <div class="form-group">
                <label style="display:block;">Search</label>
                <?php
                $getSearch = '';
                (isset($_GET['search']) && !empty($_GET['search'])) ? $getSearch = $_GET['search'] : '';
                ?>
                <input type="text" name="search" class="form-control" value="<?php echo $getSearch;?>" placeholder="Search">
              </div>
              <div class="form-group">
                <label style="display:block;">Status</label>
                <select name="status" class="form-control">
                  <option selected disabled hidden>Select</option>
                  <option value="">-- Empty --</option>
                  <option value="out_of_stock" <?php echo (isset($_GET['status']) && !empty($_GET['status']) && $_GET['status'] == 'out_of_stock') ? 'selected' : ''; ?>>Out of stock</option>
                  <option value="low" <?php echo (isset($_GET['status']) && !empty($_GET['status']) && $_GET['status'] == 'low') ? 'selected' : ''; ?>>Low</option>
                  <option value="most_stocked" <?php echo (isset($_GET['status']) && !empty($_GET['status']) && $_GET['status'] == 'most_stocked') ? 'selected' : ''; ?>>Most stocked</option>
                </select>
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
              </div>
              <div class="form-group">
                <label style="display:block;">Store</label>
                <select name="store_location" class="form-control">
                  <option selected disabled hidden>Select Store</option>
                  <option value="">-- Empty --</option>
                  <?php if(!empty($store_location)){
                    $get_store_location = '';
                    (isset($_GET['store_location']) && !empty($_GET['store_location'])) ? $get_store_location = $_GET['store_location'] : '';
                    foreach($store_location->result_array() as $index => $value){
                      $selected = '';
                      if($get_store_location == $value['category_id']){
                        $selected = 'selected';
                      }
                      echo '<option '.$selected.' value="'.$value['category_id'].'">'.$value['category_name'].'</option>';
                    }
                  }?>
                </select>
                <button type="submit" class="btn-flat btn-primary btn">Apply</button>
                <a href="<?php echo base_url('adminsite/stock_list');?>" type="button" class="btn-flat btn-primary btn">Reset</a>
              </div>
            </form>
    
            <div class="form-group">
              <a class="btn btn-flat btn-primary btn-print-action" href="<?php echo base_url('adminsite/stock_list/print_report/').urlencode(base64_encode(serialize($geturl)));?>">Download PDF</a>
            </div>
          <table id="<?php echo $table_data;?>" class="display text-center table table-hover table-bordered table-striped table-align-middle table-return-list" cellspacing="0" width="100%">
            <thead>
              <tr>
                <th style="width: 100px;">Product Name</th>
                <th>Kode</th>
                <th style="width: 150px;">Isi Paket</th>
                <th>Estimasi Ukuran</th>
                <th style="width: 130px;">Stock Availability</th>
                <th>Status</th>
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
</div>
<iframe id="printf" name="printf" style="display:none;"></iframe>