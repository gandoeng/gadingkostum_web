 <style type="text/css">
   table.dataTable thead tr th{
    vertical-align: middle;
   }
   table.dataTable thead tr th:after{
    bottom: 35%;
    vertical-align: middle;
    height: 20px;
   }
 </style>
 <div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Booking List
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
            <form class="form-inline" style="margin-bottom: 10px;" action="<?php echo base_url('adminsite/return_list');?>" method="GET">
              <div class="form-group">
                <label style="display:block;">Search</label>
                <?php
                $getSearch = '';
                (isset($_GET['search']) && !empty($_GET['search'])) ? $getSearch = $_GET['search'] : '';
                ?>
                <input type="text" name="search" class="form-control" value="<?php echo $getSearch;?>" placeholder="Search">
              </div>
              <div class="form-group">
                <label style="display:block;">Store</label>
                <select style="width: 120px;" name="store_location" class="form-control">
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
              </div>
              <div class="form-group">
                <label style="display:block;" for="email">Filter</label>
                <select name="filter" class="form-control">
                  <option selected disabled hidden>By Start Date / Return Date</option>
                  <option value="">-- Empty --</option>
                  <option <?php echo (isset($_GET['filter']) && $_GET['filter'] == 'start') ? 'selected' : '' ?> value="start">Start Date</option>
                  <option <?php echo (isset($_GET['filter']) && $_GET['filter'] == 'end') ? 'selected' : '' ?> value="end">Return Date</option>
                </select>
              </div>
              <div class="form-group">
                <label style="display:block;">Date Range</label>
                <div style="width: 190px;" class="input-group datepicker date" data-provide="datepicker">
                  <input <?php echo (isset($_GET['start'])) ? 'value="'.$_GET['start'].'"' : '' ?> readonly name="start" type="text" class="form-control">
                  <div class="input-group-addon">
                    <span class=" glyphicon glyphicon-calendar"></span>
                  </div>
                </div>
                <div style="width: 190px;" class="input-group datepicker date" data-provide="datepicker">
                  <input <?php echo (isset($_GET['end'])) ? 'value="'.$_GET['end'].'"' : '' ?> readonly name="end" type="text" class="form-control">
                  <div class="input-group-addon">
                    <span class=" glyphicon glyphicon-calendar"></span>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label style="display:block;">Status</label>
                <select name="status" class="form-control">
                  <option selected disabled hidden>Select</option>
                  <option value="">-- Empty --</option>
                  <option value="pickup" <?php echo (isset($_GET['status']) && !empty($_GET['status']) && $_GET['status'] == 'pickup') ? 'selected' : ''; ?>> Pickup</option>
                  <option value="booked" <?php echo (isset($_GET['status']) && !empty($_GET['status']) && $_GET['status'] == 'booked') ? 'selected' : ''; ?>> Booked</option>
                  <option value="return" <?php echo (isset($_GET['status']) && !empty($_GET['status']) && $_GET['status'] == 'return') ? 'selected' : ''; ?>> Return</option>
                </select>
              </div>
              <div class="form-group">
                <label style="display:block;">Status due</label>
                <select name="due" class="form-control">
                  <option selected disabled hidden>Select</option>
                  <option value="">-- Empty --</option>
                  <option value="due_pickup" <?php echo (isset($_GET['due']) && !empty($_GET['due']) && $_GET['due'] == 'due_pickup') ? 'selected' : ''; ?>>Due Pickup</option>
                  <option value="due_return" <?php echo (isset($_GET['due']) && !empty($_GET['due']) && $_GET['due'] == 'due_return') ? 'selected' : ''; ?>>Due Return</option>
                </select>
              </div>
              <div class="form-group">
                <label style="display:block;">Order Field</label>
                <select name="order" class="form-control">
                  <option selected disabled hidden>Select</option>
                  <option value="">-- Empty --</option>
                  <option value="rental_invoice" <?php echo (isset($_GET['order']) && !empty($_GET['order']) && $_GET['order'] == 'rental_invoice') ? 'selected' : ''; ?>>Inv#</option>
                  <option value="customer_name" <?php echo (isset($_GET['order']) && !empty($_GET['order']) && $_GET['order'] == 'customer_name') ? 'selected' : ''; ?>>Name + Phone</option>
                  <option value="rental_created" <?php echo (isset($_GET['order']) && !empty($_GET['order']) && $_GET['order'] == 'rental_created') ? 'selected' : ''; ?>>Date Order</option>
                  <option value="rental_start_date" <?php echo (isset($_GET['order']) && !empty($_GET['order']) && $_GET['order'] == 'rental_start_date') ? 'selected' : ''; ?>>Start Date</option>
                  <option value="rental_end_date" <?php echo (isset($_GET['order']) && !empty($_GET['order']) && $_GET['order'] == 'rental_end_date') ? 'selected' : ''; ?>>Return Date</option>
                  <option value="rental_status" <?php echo (isset($_GET['order']) && !empty($_GET['order']) && $_GET['order'] == 'rental_status') ? 'selected' : ''; ?>>Status</option>
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
                <a href="<?php echo base_url('adminsite/return_list');?>" type="button" class="btn-flat btn-primary btn">Reset</a>
              </div>
            </form>
            <div class="form-group">
            <a class="btn btn-flat btn-primary btn-print-action" href="<?php echo base_url('adminsite/return_list/print_report/').urlencode(base64_encode(serialize($geturl)));?>">Download PDF</a>
            </div>
            <table id="<?php echo $table_data;?>" class="display text-center table table-hover table-bordered table-striped table-align-middle table-return-list" cellspacing="0" width="100%">
              <thead>
                <tr>
                  <th style="width: 30px;">Inv #</th>
                  <th>Name + Phone</th>
                  <th>Items</th>
                  <th>Date Order</th>
                  <th>Start Date</th>
                  <th>Return Date</th>
                  <th>Status</th>
                  <th>Action</th>
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