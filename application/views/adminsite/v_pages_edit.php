 <div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Edit Page
      <!-- <small>Version 2.0</small> -->
    </h1>
  </section>

  <!-- Main content -->
  <form class="form-horizontal" method="POST" action="<?php echo base_url('adminsite/pages/edit/').$id;?>">
    <section class="content">
      <div class="row">

        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
          <div class="box box-solid">
            <div class="box-body">
              <div class="form-group">
                <div class="col-lg-12">
                  <label>Title</label>
                  <div class=""><?php echo (!empty($page)) ? $page[0]['page_title'] : '';?></div>
                </div>
              </div>
              <div class="form-group">
                <div class="col-lg-12">
                  <label>Content</label>
                  <textarea name="page_description" class="form-control tinymcefull"><?php echo (!empty($page)) ? html_entity_decode($page[0]['page_description']) : '';?></textarea>
                </div>
              </div>
              <br>
              
            </div>

            <div class="box-footer clearfix no-border">
              <a class="btn btn-primary btn-flat pull-left" type="button" href="<?php echo base_url('adminsite/page');?>"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp; CANCEL</a>
              <button class="btn btn-primary btn-flat pull-right" type="submit">SAVE</button>
            </div>

            <div class="overlay">
              <i class="fa fa-refresh fa-spin"></i>
            </div>
          </div>
        </div>

        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
          <div class="box box-solid">
            <div class="box-body">
              
              <!--
              <label style="width: 100%; display:block;">Image</label>
              <div class="input-group">
              <input id="image" type="text" class="form-control" name="page_image" value="<?php echo (!empty($page)) ? $page[0]['page_image'] : base_url('assets/images/no-image.png');?>">
                <a href="<?php echo base_url('assets/adminsite/bower_components/rfm/filemanager/dialog.php?type=1&field_id=image&akey=gadingkostumdcube2k18');?>" class="input-group-addon btn btn-default iframe-btn btn-flat input-group-addo" type="button">Browse</a>
              </div>
              <br>
              <div class="form-group">
                <div class="col-lg-12">
                  <img class="image img-responsive img-thumbnail" src="<?php echo (!empty($page)) ? $page[0]['page_image'] : base_url('assets/images/no-image.png');?>">
                </div>
              </div>
              -->

              <div class="form-group">
                <div class="col-lg-12">
                  <label>Meta Title</label>
                  <input type="text" name="page_metatitle" value="<?php echo (!empty($page)) ? $page[0]['page_metatitle'] : '';?>" class="form-control">
                </div>
              </div>

              <div class="form-group">
                <div class="col-lg-12">
                  <label>Meta Keyword</label>
                  <input type="text" name="page_metakeyword" class="form-control" value="<?php echo (!empty($page)) ? $page[0]['page_metakeyword'] : '';?>">
                </div>
              </div>

              <div class="form-group">
                <div class="col-lg-12">
                  <label>Meta Description</label>
                  <input type="text" name="page_metadescription" value="<?php echo (!empty($page)) ? $page[0]['page_metadescription'] : '';?>" class="form-control">
                </div>
              </div>
              
              <!--
              <div class="form-group">
                <div class="col-lg-12">
                  <label style="width: 100%; display:block;">Publish</label>
                  <label class="radio-inline"><input <?php echo ($page[0]['page_status'] == 1) ? $checked = 'checked' : $checked = ''; ?> type="radio" name="status" value="1"> Yes</label>
                  <label class="radio-inline"><input <?php echo ($page[0]['page_status'] == 0) ? $checked = 'checked' : $checked = ''; ?> type="radio" name="status" value="0"> No</label>
                </div>
              </div>
              -->

            </div>

            <div class="box-footer clearfix no-border">
              <button class="btn btn-primary btn-flat pull-right" type="submit">SAVE</button>
            </div>

          </div>
        </div>

      </div>

    </section>
  </form>
</div>