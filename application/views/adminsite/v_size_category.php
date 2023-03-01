 <div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Size Category
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
                    <option value="<?php echo base_url('adminsite/size_category/publish');?>">Publish</option>
                    <option value="<?php echo base_url('adminsite/size_category/unpublish');?>">Unpublish</option>
                    <option value="<?php echo base_url('adminsite/size_category/multiple_delete');?>">Delete</option>
                  </select>
                </div>
                <button class="btn btn-primary btn-flat" type="submit">Apply</button>
              </div>
            </form>

            <table id="<?php echo $table_data;?>" class="display text-center table table-hover table-bordered table-striped" cellspacing="0" width="100%">
              <thead>
                <tr>
                  <th><div class="icheckbox"><input id="check_action" type="checkbox"></div></th>
                  <th>Size Category</th>
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
            <form id="form-side-right-action" class="form-horizontal" action="<?php echo base_url('adminsite/size_category/form');?>">
              <div class="form-group">
                <div class="col-lg-12">
                  <input type="hidden" name="category_id" value="">
                  <label>Size Category</label>
                  <input type="text" name="category_name" class="form-control">
                </div>
              </div>
              <div class="form-group">
                <div class="col-lg-12">
                  <label>Size Measurement</label>
                  <select name="category_sizestock[]" multiple="multiple" class="select2-init form-control">
                      <?php if(!empty($size_category)){
                        foreach($size_category as $index => $value){
                          echo '<option value="'.trim($value['product_size']).'">'.trim($value['product_size']).'</option>';
                        }
                        } ?>
                  </select>
                </div>
              </div>
              
              <label>URL</label>
              <div style="margin-bottom: 15px;" class="input-group">
                <span class="input-group-addon">?size=</span>
                <input type="text" class="form-control" name="category_slug">
              </div>

              <div class="form-group">
                <div class="col-lg-12">
                  <label style="width: 100%; display:block;">Publish</label>
                  <label class="radio-inline"><input type="radio" name="status" value="1"> Yes</label>
                  <label class="radio-inline"><input type="radio" name="status" value="0"> No</label>
                </div>
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