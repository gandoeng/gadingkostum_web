 <div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Product
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
              <a href="<?php echo base_url('adminsite/product/add');?>" class="btn btn-primary btn-flat"><i class="fa fa-plus"></i> ADD NEW PRODUCT</a>
            </div>

            <form id="form-action" class="form-horizontal">
              <div class="form-group">
                <div class="col-lg-2">
                  <select class="form-control" id="action">
                    <option hidden selected value="">Action</option>
                    <option value="<?php echo base_url('adminsite/product/publish');?>">Publish</option>
                    <option value="<?php echo base_url('adminsite/product/unpublish');?>">Unpublish</option>
                    <?php 
                    if($this->uri->segment(3) == 'view_trash'){
                      echo '<option value="'.base_url('adminsite/product/multiple_restore').'">Restore</option>';
                    }
                    ?>
                    <option value="<?php echo base_url('adminsite/product/multiple_delete');?>">Delete Permanently</option>
                    <option value="<?php echo base_url('adminsite/product/multiple_trash');?>">Trash</option>
                  </select>
                </div>
                <button class="btn btn-primary btn-flat" type="submit">Apply</button>
              </div>
            </form>

            <ol class="breadcrumb">
              <li <?php echo ($this->uri->segment(3) == '') ? 'class="active"' : '' ?>><a href="<?php echo base_url('adminsite/product');?>">Product</a></li>
              <li <?php echo ($this->uri->segment(3) == 'view_trash') ? 'class="active"' : '' ?>><a href="<?php echo base_url('adminsite/product/view_trash');?>">Trash</a></li>
            </ol>

            <form class="form-inline" style="margin-bottom: 10px;" action="<?php echo ($this->uri->segment(3) == 'view_trash') ? base_url('adminsite/product/view_trash') : base_url('adminsite/product');?>" method="GET">
              <div class="form-group">
                <label style="display:block;">Search</label>
                <?php
                $getSearch = '';
                (isset($_GET['search']) && !empty($_GET['search'])) ? $getSearch = $_GET['search'] : '';
                ?>
                <input type="text" name="search" class="form-control" value="<?php echo $getSearch;?>" placeholder="Search">
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
                <a href="<?php echo ($this->uri->segment(3) == 'view_trash') ? base_url('adminsite/product/view_trash') : base_url('adminsite/product');?>" type="button" class="btn-flat btn-primary btn">Reset</a>
              </div>
            </form>
            
            <table id="<?php echo $table_data;?>" class="display text-center table table-hover table-bordered table-striped table-align-middle" cellspacing="0" width="100%">
              <thead>
                <tr>
                  <th><div class="icheckbox"><input id="check_action" type="checkbox"></div></th>
                  <th>Picture</th>
                  <th>Product Name</th>
                  <th>SKU</th>
                  <th>Last Modified</th>
                  <th>Publish</th>
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