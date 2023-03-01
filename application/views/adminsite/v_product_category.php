 <div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Product Category
      <!-- <small>Version 2.0</small> -->
    </h1>
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="row">
      <div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
        <div class="box box-solid">
          <div class="box-body">
            <form id="form-action" class="form-horizontal">
              <div class="form-group">
                <div class="col-lg-2">
                  <select class="form-control" id="action">
                    <option hidden selected value="">Action</option>
                    <option value="<?php echo base_url('adminsite/product_category/publish');?>">Publish</option>
                    <option value="<?php echo base_url('adminsite/product_category/unpublish');?>">Unpublish</option>
                    <option value="<?php echo base_url('adminsite/product_category/multiple_delete');?>">Delete</option>
                  </select>
                </div>
                <button class="btn btn-primary btn-flat" type="submit">Apply</button>
              </div>
            </form>

            <table id="<?php echo $table_data;?>" class="display text-center table table-hover table-bordered table-striped table-align-middle" cellspacing="0" width="100%">
              <thead>
                <tr>
                  <th><div class="icheckbox"><input id="check_action" type="checkbox"></div></th>
                  <th>Picture</th>
                  <th>Category Name</th>
                  <th>Parent</th>
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

      <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">
        <div class="box box-solid">
          <div class="box-header with-border">
            <h4 class="box-title">Add / Edit</h4>
          </div>
          <div class="box-body">
            <form id="form-side-right-action" class="form-horizontal" action="<?php echo base_url('adminsite/product_category/form');?>">
              <div class="form-group">
                <div class="col-lg-12">
                  <input type="hidden" name="category_id" value="">
                  <input type="hidden" name="product_category_id" value="">
                  <label>Category Name</label>
                  <input type="text" name="category_name" class="form-control">
                </div>
              </div>
              <div class="form-group">
                <div class="col-lg-12">
                  <label>Parent</label>
                  <select class="form-control product_category_parent" name="product_category_parent">
                    <option value="0">Non parent</option>
                    <?php
                    if(!empty($parent)){
                      foreach($parent->result_array() as $index => $value){
                        echo '<option value="'.$value['category_id'].'">'.$value['category_name'].'</option>';
                      }
                    }
                    ?>
                  </select>
                </div>
              </div>
              <label style="width: 100%; display:block;">Picture</label>
              <div class="input-group">
                <input id="image" type="text" class="form-control" name="product_category_picture">
                <a href="<?php echo base_url('assets/adminsite/bower_components/rfm/filemanager/dialog.php?type=1&field_id=image&akey=gadingkostumdcube2k18');?>" class="input-group-addon btn btn-default iframe-btn btn-flat input-group-addo" type="button">Browse</a>
              </div>
              <br>
              <div class="form-group">
              <div class="col-lg-12">
              <img class="image img-responsive img-thumbnail" src="<?php echo base_url('assets/images/no-image.png');?>">
              </div>
              </div>
              <div class="form-group">
                  <div class="col-lg-12">
                      <label style="width: 100%;">Background Color Label</label>
                      <input name="product_category_background_label" type="text" readonly class="form-control colorpicker">
                  </div>
              </div>
              <div class="form-group">
                <div class="col-lg-12">
                  <label style="width: 100%; display:block;">Publish</label>
                  <label class="radio-inline"><input type="radio" name="status" value="1"> Yes</label>
                  <label class="radio-inline"><input type="radio" name="status" value="0"> No</label>
                </div>
              </div>
              <legend></legend>
              <div class="form-group">
                <div class="col-lg-12">
                  <label style="width: 100%; display:block;">Popular Category</label>
                  <div class="checkbox">
                    <label><input type="checkbox" name="popular_category" value="1"> Yes</label>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <div class="col-lg-12">
                  <label style="width: 100%; display:block;">Popular Category Order Number</label>
                  <input type="text" class="form-control input-number" name="popular_category_order">
                  <p class="input-info italic">*urutan semakin besar angka berarti urutan lebih awal</p>
                </div>
              </div>
              <legend></legend>
              <div class="form-group">
                <div class="col-lg-12">
                  <label style="width: 100%; display:block;">Popular Theme</label>
                  <div class="checkbox">
                    <label><input type="checkbox" name="popular_theme" value="1"> Yes</label>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <div class="col-lg-12">
                  <label style="width: 100%; display:block;">Popular Theme Order Number</label>
                  <input type="text" class="form-control input-number" name="popular_theme_order">
                  <p class="input-info italic">*urutan semakin besar angka berarti urutan lebih awal</p>
                </div>
              </div>
              <legend></legend>
              <div class="form-group">
                  <div class="col-lg-12">
                      <label style="width: 100%;">Meta Title</label>
                      <input name="product_meta_title" type="text" class="form-control">
                  </div>
              </div>
              <div class="form-group">
                  <div class="col-lg-12">
                      <label style="width: 100%;">Meta Keyword</label>
                      <input name="product_meta_keyword" type="text" class="form-control">
                  </div>
              </div>
              <div class="form-group">
                  <div class="col-lg-12">
                      <label style="width: 100%;">Meta Description</label>
                      <input name="product_meta_description" type="text" class="form-control">
                  </div>
              </div>

              <legend></legend>
              <label>URL</label>
              <div style="margin-bottom: 15px;" class="input-group">
                <span class="input-group-addon">?product=</span>
                <input type="text" class="form-control" name="category_slug">
              </div>
              
              <div class="form-group">
                <div class="col-lg-12">
                  <button class="btn btn-primary btn-flat" type="submit">Submit</button>
                </div>
              </div>

            </form>
          </div>
          <div class="overlay">
            <i class="fa fa-refresh fa-spin"></i>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>