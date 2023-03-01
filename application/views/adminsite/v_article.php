 <div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      ARTICLE
      <!-- <small>Version 2.0</small> -->
    </h1>
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="row">
      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="box box-solid">
          <div class="box-body">

            <div class="btn-group btn-group-action">
              <a href="<?php echo base_url('adminsite/article/add');?>" class="btn btn-primary btn-flat"><i class="fa fa-plus"></i> ADD NEW ARTICLE</a>
            </div>

            <form id="form-action" class="form-horizontal">
              <div class="form-group">
                <div class="col-lg-2">
                  <select class="form-control" id="action">
                    <option hidden selected value="">Action</option>
                    <option value="<?php echo base_url('adminsite/article/publish');?>">Publish</option>
                    <option value="<?php echo base_url('adminsite/article/unpublish');?>">Unpublish</option>
                    <option value="<?php echo base_url('adminsite/article/multiple_delete');?>">Delete</option>
                  </select>
                </div>
                <button class="btn btn-primary btn-flat" type="submit">Apply</button>
              </div>
            </form>

            <table id="<?php echo $table_data;?>" class="display text-center table table-hover table-bordered table-striped table-align-middle" cellspacing="0" width="100%">
              <thead>
                <tr>
                  <th><div class="icheckbox"><input id="check_action" type="checkbox"></div></th>
                  <th>Title</th>
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
    </div>
  </section>
</div>