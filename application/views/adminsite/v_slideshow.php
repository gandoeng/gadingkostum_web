 <div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Slideshow
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
                    <option value="<?php echo base_url('adminsite/slideshow/publish');?>">Publish</option>
                    <option value="<?php echo base_url('adminsite/slideshow/unpublish');?>">Unpublish</option>
                    <option value="<?php echo base_url('adminsite/slideshow/multiple_delete');?>">Delete</option>
                  </select>
                </div>
                <button class="btn btn-primary btn-flat" type="submit">Apply</button>
              </div>
            </form>

            <table id="<?php echo $table_data;?>" class="display text-center table table-hover table-bordered table-striped table-align-middle" cellspacing="0" width="100%">
              <thead>
                <tr>
                  <th><div class="icheckbox"><input id="check_action" type="checkbox"></div></th>
                  <th>Image</th>
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
            <form id="form-side-right-action" class="form-horizontal" action="<?php echo base_url('adminsite/slideshow/form');?>">
              <input type="hidden" name="slideshow_id" value="">
              <label style="width: 100%; display:block;"><span class="required">*</span> Image</label>
              <div class="input-group">
                <input id="image" type="text" class="form-control" name="slideshow_image">
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
                  <label style="width: 100%; display:block;">Order</label>
                  <input type="text" class="form-control input-number" name="slideshow_order">
                  <p class="input-info italic">*urutan semakin besar angka berarti urutan lebih awal</p>
                </div>
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