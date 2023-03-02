 <div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Rental Order
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
              <a href="<?php echo base_url('adminsite/rental_order/add');?>" class="btn btn-primary btn-flat"><i class="fa fa-plus"></i> ADD NEW RENTAL ORDER</a>
            </div>

            <form id="form-action" class="form-horizontal">
              <div class="form-group">
                <div class="col-lg-2">
                  <select class="form-control" id="action">
                    <option hidden selected value="">Action</option>
                    <?php 
                    if($this->uri->segment(3) == 'view_trash'){
                      echo '<option value="'.base_url('adminsite/rental_order/multiple_restore').'">Restore</option>';
                    }
                    ?>
                    <option value="<?php echo base_url('adminsite/rental_order/multiple_delete');?>">Delete Permanently</option>
                    <option value="<?php echo base_url('adminsite/rental_order/multiple_trash');?>">Trash</option>
                  </select>
                </div>
                <button class="btn btn-primary btn-flat" type="submit">Apply</button>
              </div>
            </form>

            <ol class="breadcrumb">
              <li <?php echo ($this->uri->segment(3) == '') ? 'class="active"' : '' ?>><a href="<?php echo base_url('adminsite/rental_order');?>">Order</a></li>
              <li <?php echo ($this->uri->segment(3) == 'view_trash') ? 'class="active"' : '' ?>><a href="<?php echo base_url('adminsite/rental_order/view_trash');?>">Trash</a></li>
            </ol>

            <form class="form-inline" style="margin-bottom: 10px;" action="<?php echo base_url('adminsite/rental_order');?>" method="GET">
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
                  <option value="rental_total_hargasewa" <?php echo (isset($_GET['order']) && !empty($_GET['order']) && $_GET['order'] == 'rental_total_hargasewa') ? 'selected' : ''; ?>>Price</option>
                  <option value="rental_payment_status" <?php echo (isset($_GET['order']) && !empty($_GET['order']) && $_GET['order'] == 'rental_payment_status') ? 'selected' : ''; ?>>Payment</option>
                  <option value="rental_total_deposit" <?php echo (isset($_GET['order']) && !empty($_GET['order']) && $_GET['order'] == 'rental_total_deposit') ? 'selected' : ''; ?>>Deposit</option>
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
                <a href="<?php echo base_url('adminsite/rental_order');?>" type="button" class="btn-flat btn-primary btn">Reset</a>
              </div>
            </form>

            <!-- Ndungg -->
            <form class="form-inline" style="margin-bottom: 10px;" action="" method="GET">
              <div class="form-group">
                <label style="display:block;">Backup Data </label>
                <div class="form-group">
                  <?php $years = range(2021, strftime("%Y", time())); ?>
                  <select name="order_by" class="form-control">
                    <option selected disabled hidden>Select Year</option>
                    <?php foreach($years as $year) : ?>
                      <option value="<?php echo $year; ?>"><?php echo $year; ?></option>
                    <?php endforeach; ?>
                  </select>
                  <button type="submit" class="btn-flat btn-primary btn">Start Backup</button>
                </div>
              </div>
            </form>
            
            <table id="<?php echo $table_data;?>" class="display text-center table table-hover table-bordered table-striped table-align-middle" cellspacing="0" width="100%">
              <thead>
                <tr>
                  <th><div class="icheckbox"><input id="check_action" type="checkbox"></div></th>
                  <th>Inv #</th>
                  <th>Name + Phone</th>
                  <th>Date Order</th>
                  <th>Rental Price</th>
                  <th>Payment</th>
                  <th>Deposit</th>
                  <th>Status</th>
                  <th>Action</th>
                  <!-- Ndung -->
                  <!-- <th>Delivery Options</th> -->
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