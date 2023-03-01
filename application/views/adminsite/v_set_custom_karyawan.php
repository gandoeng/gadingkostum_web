 <div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Set Custom - List Karyawan
      <!-- <small>Version 2.0</small> -->
    </h1>
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="row">
      <div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
        <div class="box box-solid">
          <div class="box-body">
            <input type="hidden" id="geturl" value="<?php echo base64_encode(serialize($geturl));?>">

            <ol class="breadcrumb">
              <li <?php echo ($this->uri->segment(3) == '') ? 'class="active"' : '' ?>><a href="<?php echo base_url('adminsite/set_custom');?>">Set Custom</a></li>
              <li <?php echo ($this->uri->segment(3) == 'karyawan') ? 'class="active"' : '' ?>><a href="<?php echo base_url('adminsite/set_custom/karyawan');?>">List Karyawan</a></li>
            </ol>
            
            <form id="form-action" class="form-horizontal">
              <label style="display:block;">Action</label>
              <div class="form-group">
                <div class="col-lg-2">
                  <select class="form-control" id="action">
                    <option hidden selected value="">Action</option>
                    <option value="<?php echo base_url('adminsite/set_custom/karyawan_multiple_delete');?>">Delete</option>
                  </select>
                </div>
                <button class="btn btn-primary btn-flat" type="submit">Apply</button>
              </div>
            </form>

            <table id="<?php echo $table_data;?>" class="display text-center table table-hover table-bordered table-striped table-align-middle" cellspacing="0" width="100%">
              <thead>
                <tr>
                  <th><div class="icheckbox"><input id="check_action" type="checkbox"></div></th>
                  <th>Nama Karyawan</th>
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
            <form id="form-side-right-action" class="form-horizontal" style="margin-bottom: 10px;" action="<?php echo base_url('adminsite/set_custom/form_karyawan');?>" method="POST">
              <input type="hidden" name="karyawan_id" value="">
              <div class="col-lg-12">
                <div class="form-group">
                  <input type="text" name="karyawan_nama" class="form-control" placeholder="Nama Karyawan">
                </div>
                <div class="form-group">
                  <button type="submit" class="btn-flat btn-primary btn">Submit</button>
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
