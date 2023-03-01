 <div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Add New Article
      <!-- <small>Version 2.0</small> -->
    </h1>
  </section>

  <!-- Main content -->
  <form class="form-horizontal" method="POST" action="<?php echo base_url('adminsite/article/add');?>">
    <section class="content">
      <div class="row">

        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
          <div class="box box-solid">
            <div class="box-body">
              <div class="form-group">
                <div class="col-lg-12">
                  <label><span class="required">*</span> Title</label>
                  <input type="text" name="article_title" class="form-control">
                </div>
              </div>
              <!-- PICTURE HERE -->
              <div class="form-group">
                <div class="col-lg-12">
                  <label>Description Thumbnail</label>
                  <textarea name="article_description_thumbnail" class="form-control tinymcebasic"></textarea>
                </div>
              </div>

              <div class="form-group">
                <div class="col-lg-12">
                  <label>Description</label>
                  <textarea name="article_description" class="form-control tinymcefull"></textarea>
                </div>
              </div>
              <br>
              
            </div>

            <div class="box-footer clearfix no-border">
              <a class="btn btn-primary btn-flat pull-left" type="button" href="<?php echo base_url('adminsite/article');?>"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp; CANCEL</a>
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

              <label style="width: 100%; display:block;">Image</label>
              <div class="input-group">
              <input id="image" type="text" class="form-control" name="article_image">
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
                  <label>Meta Title</label>
                  <input type="text" name="article_metatitle" class="form-control">
                </div>
              </div>

              <div class="form-group">
                <div class="col-lg-12">
                  <label>Meta Keyword</label>
                  <input type="text" name="article_metakeyword" class="form-control">
                </div>
              </div>

              <div class="form-group">
                <div class="col-lg-12">
                  <label>Meta Description</label>
                  <input type="text" name="article_metadescription" class="form-control">
                </div>
              </div>

              <div class="form-group">
                <div class="col-lg-12">
                  <label style="width: 100%; display:block;">Publish</label>
                  <label class="radio-inline"><input type="radio" name="status" value="1"> Yes</label>
                  <label class="radio-inline"><input type="radio" name="status" value="0"> No</label>
                </div>
              </div>

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