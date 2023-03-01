 <div class="content-wrapper">

  <section class="content-header">

      <h1>USER</h1>

  </section>

  <section class="content">

    <div class="row">

        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

            <div class="box box-solid main-box">

                <div class="box-body">

                    <?php if($this->session->flashdata('success') != NULL){

                        echo '<div class="callout callout-success">'.$this->session->flashdata('success').'</div>';

                    } ?>

                    <div class="btn-group btn-group-action">
                        <a href="<?php echo base_url('adminsite/user/add');?>" class="btn btn-primary"><i class="fa fa-plus"></i> ADD NEW USER</a>
                    </div>

                    <form id="form-action" class="form-horizontal">
                        <div class="form-group">
                          <div class="col-lg-2">
                            <select class="form-control" id="action">
                                <option hidden selected value="">Action</option>
                                <option value="<?php echo base_url('adminsite/user/publish');?>">Active</option>
                                <option value="<?php echo base_url('adminsite/user/unpublish');?>">Non active</option>
                                <option value="<?php echo base_url('adminsite/user/multiple_delete');?>">Delete</option>
                            </select>
                        </div>
                        <button class="btn btn-primary" type="submit">Apply</button>
                    </div>
                </form>

                <table id="<?php echo $table_data;?>" class="display text-center table table-hover table-bordered table-striped table-align-middle" cellspacing="0" width="100%">
                  <thead>
                    <tr>
                      <th><input id="check_action" type="checkbox"></th>
                      <th>Username</th>
                      <th>Role</th>
                      <th>Status Active</th>
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