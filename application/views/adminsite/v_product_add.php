 <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/10.6.2/css/bootstrap-slider.min.css">
 <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/adminsite/custom/css/catalog2.css').'?'.md5(date('c'));?>">
 <style type="text/css">
  .input-group-btn{
    display:inline-block;
  }

  .select2-container{
    width: 270px !important;
  }

  .accordion{
    margin: 15px 0;
    border: 1px solid #ccc;
    padding: 5px;
  }
  .group{
    background:gray;
    color:#ffffff;
    margin:3px 0;
    cursor:move;
    width:100%;
    padding: 0;
    border-radius:5px;
  }

  .group.no-data-group{
    background: white;
    color: black;
    font-style: italic;
    text-align: center;
    cursor: auto;
  }

  .group .content{
    height: auto !important;
    min-height: auto !important;
        padding: 4px 10px;
  }
  
  .group:hover{
    background:#9C3;
  }

  .group.no-data-group:hover{
    background: white;
  }

  .group .remove-list:hover{
    cursor: pointer;
  }
  #table-size-stock tbody tr td:nth-child(1):hover{
    cursor:move;
  }

  .slider.slider-horizontal{
        margin-left: 10px;
    margin-bottom: 10px;
    margin-top: 10px;
  }
  .container-box-list.tag-box-list{
        top: -35px;
    height: 202px !important;
    width: 130px !important;
    -ms-transform: rotate(270deg);
    -webkit-transform: rotate(270deg);
    transform: rotate(270deg);
        left: 50px;
            overflow: visible !important;
  }
  .tag{
    height: 202px !important;
    width: 130px !important;
  }
</style>
<script type="text/javascript" src="<?php echo base_url('assets/adminsite/bower_components/jquery-ui-1.12.1/jquery-ui.min.js');?>"></script>
 <div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Add New Product
      <!-- <small>Version 2.0</small> -->
    </h1>
  </section>

  <!-- Main content -->
  <form class="form-horizontal" method="POST" action="<?php echo base_url('adminsite/product/add');?>">
    <section class="content">
      <div class="row">

        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
          <div class="box box-solid">
            <div class="box-body">
              <div class="form-group">
                <div class="col-lg-12">
                  <label>Nama Produk</label>
                  <input type="text" name="product_nama" class="form-control">
                </div>
              </div>
              <div class="form-group">
                <div class="col-lg-12">
                  <label>Kode</label>
                  <input type="text" name="product_kode" class="form-control">
                </div>
              </div>
              <!-- PICTURE HERE -->
              <div class="form-group">
                <div class="col-lg-12">
                  <label>Deskripsi</label>
                  <textarea wrap="hard" name="product_deskripsi" class="form-control textarea-backend"></textarea>
                </div>
              </div>
              <div class="form-group">
                <div class="col-lg-12">
                  <label>Isi Paket</label>
                  <textarea wrap="hard" name="product_isipaket" class="form-control textarea-backend"></textarea>
                </div>
              </div>
              <label>Harga Sewa</label>
              <div class="input-group">
                <span class="input-group-addon">Rp.</span>
                <input type="text" class="form-control pricemask" maxlength="15" name="product_hargasewa">
              </div>
              <br>
              <label>Deposit</label>
              <div class="input-group">
                <span class="input-group-addon">Rp.</span>
                <input type="text" class="form-control pricemask" maxlength="15" name="product_deposit">
              </div>
              <br>
              <label style="width: 100%; display:block; margin-top: 15px;">Picture</label>
              <div class="input-group">
                <input type="hidden" name="product_image_id[]">
                <input id="image" type="text" class="form-control picture" name="product_image[]" data-more="image">
                <a class="input-group-addon btn btn-default btn-flat input-group-addon" type="button" onclick="openKCFinder('image')">Browse</a>
              </div>
              <br>
              <div class="form-group">
                <div class="col-lg-12">
                  <img id="preview-image" class="image img-responsive img-thumbnail preview-more" src="<?php echo base_url('assets/images/no-image.png');?>">
                </div>
              </div>
              <div class="more" data-more="image"></div>
              <div class="form-group">
                <div class="col-lg-12">
                  <a class="btn-add-more btn btn-primary btn-flat" data-more="image" id="1"><i class="fa fa-plus"></i> Add more picture</a>
                </div>
              </div>
              
              <label style="width: 100%; display:block;">Size + Stock</label>
              <div class="form-group">
                <div class="col-lg-12">
                  <table id="table-size-stock" class="text-center table table-hover table-bordered table-striped table-align-middle">
                    <thead>
                      <tr>
                        <th></th>
                        <th>Size</th>
                        <th>Stock</th>
                        <th>Estimasi Ukuran</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody></tbody>
                  </table>
                </div>
              </div>
              <div class="form-group">
                <div class="col-lg-12">
                  <a class="btn-add-more btn btn-primary btn-flat" data-more="table-size-stock" id="1"><i class="fa fa-plus"></i> Add more size</a>
                </div>
              </div>
            </div>

            <div class="box-footer clearfix no-border">
              <a class="btn btn-primary btn-flat pull-left" type="button" href="<?php echo base_url('adminsite/product');?>"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp; CANCEL</a>
              <button class="btn btn-primary btn-flat pull-right" type="submit">SAVE PRODUCT</button>
            </div>

            <div class="overlay">
              <i class="fa fa-refresh fa-spin"></i>
            </div>
          </div>
        </div>

        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
          <div class="box box-solid">
            <div class="box-body">
              <div class="form-group">
                <div class="col-lg-12">
                  <label class="control-label">Product Category</label>
                  <div class="input-group">
                    <select id="product-category" class="select2-init form-control">
                      <?php 
                      if(!empty($product)){
                        foreach($product->result_array() as $index => $value){
                          echo '<option value="'.$value['category_id'].'">'.$value['category_name'].'</option>';
                        }
                      } 
                      ?>
                    </select>
                    <span class="input-group-btn"><button data-list="product" style="min-height: 32px; padding: 0 10px;" class="add-category-list btn btn-default" type="button">Add</button></span>
                  </div>
                  <div class="accordion" data-list="product">
                    <div class="group no-data-group">
                      <div class="content">No data available</div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <div class="col-lg-12">
                  <label class="control-label">Gender Category</label>
                  <div class="input-group">
                    <select id="gender-category" class="select2-init form-control">
                      <?php 
                      if(!empty($gender)){
                        foreach($gender->result_array() as $index => $value){
                          echo '<option value="'.$value['category_id'].'">'.$value['category_name'].'</option>';
                        }
                      } 
                      ?>
                    </select>
                    <span class="input-group-btn"><button data-list="gender" style="min-height: 32px; padding: 0 10px;" class="add-category-list btn btn-default" type="button">Add</button></span>
                  </div>
                  <div class="accordion" data-list="gender">
                    <div class="group no-data-group">
                      <div class="content">No data available</div>
                    </div>
                  </div>
                </div>
              </div>
              
              <div class="form-group">
                <div class="col-lg-12">
                  <label class="control-label">Size Category</label>
                  <div class="input-group">
                    <select id="size-category" class="select2-init form-control">
                      <?php 
                      if(!empty($size)){
                        foreach($size->result_array() as $index => $value){
                          echo '<option value="'.$value['category_id'].'">'.$value['category_name'].'</option>';
                        }
                      } 
                      ?>
                    </select>
                    <span class="input-group-btn"><button data-list="size" style="min-height: 32px; padding: 0 10px;" class="add-category-list btn btn-default" type="button">Add</button></span>
                  </div>
                  <div class="accordion" data-list="size">
                    <div class="group no-data-group">
                      <div class="content">No data available</div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <div class="col-lg-12">
                  <label>Suggested Product</label>
                  <select name="product_related[]" multiple="multiple" class="select2-init form-control">
                    <?php 
                    if(!empty($product_related)){
                      foreach($product_related as $index => $value){
                        echo '<option value="'.$value['product_id'].'">'.$value['product_nama'].'</option>';
                      }
                    } 
                    ?>
                  </select>
                </div>
              </div>

              <div class="form-group">
                <div class="col-lg-12">
                  <label class="control-label">Store Location</label>
                  <div class="input-group">
                  <select id="store-category" class="select2-init form-control">
                    <?php 
                    if(!empty($store_location)){
                      foreach($store_location->result_array() as $index => $value){
                        echo '<option value="'.$value['category_id'].'">'.$value['category_name'].'</option>';
                      }
                    } 
                    ?>
                  </select>
                  <span class="input-group-btn"><button data-list="store" style="min-height: 32px; padding: 0 10px;" class="add-category-list btn btn-default" type="button">Add</button></span>
                  </div>
                  <div class="accordion" data-list="store">
                    <div class="group no-data-group">
                      <div class="content">No data available</div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <div class="col-lg-12">
                  <label>Meta Title</label>
                  <input type="text" name="product_metatitle" class="form-control">
                </div>
              </div>

              <div class="form-group">
                <div class="col-lg-12">
                <label>Meta Keyword</label>
                  <input type="text" name="product_metakeyword" class="form-control">
                </div>
              </div>

              <div class="form-group">
                <div class="col-lg-12">
                <label>Meta Description</label>
                  <input type="text" name="product_metadescription" class="form-control">
                </div>
              </div>

              <label>URL</label>
              <div class="input-group">
                <span class="input-group-addon">../product/</span>
                <input type="text" class="form-control" name="product_slug">
              </div>
              <br>

              <div class="form-group">
                <div class="col-lg-12">
                  <label>Featured Product</label>
                  <div class="checkbox" style="display: inline-block; margin-left: 5px;">
                    <label><input type="checkbox" name="product_featured" value="1"> Yes</label>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <div class="col-lg-12">
                  <label style="display:block;">Preview Tag</label>
                  <input id="ex23" type="text" data-slider-step="0.01" data-slider-value="1.5" name="product_scale"/>
                  <a id="reset-scale" href="javascript:void(0)" style="display:block;text-decoration:underline;">Reset</a>
                </div>
              </div>

              <div class="form-group" style="max-height: 135px; overflow: hidden;">
                <div class="col-xs-12 container-box-list tag-box-list" style="margin-left: 15px; bottom: 0;">
                  <div class="wraper-box-list tag col-xs-12">
                    <div class="main-catalog">
                      <div class="main-footer-catalog tag-image">
                        <img id="preview-scale" class="img-responsive" src="<?php echo base_url('assets/images/no-image.png');?>">
                      </div>
                    </div>
                  </div>
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
              <button class="btn btn-primary btn-flat pull-right" type="submit">SAVE PRODUCT</button>
            </div>

          </div>
        </div>

      </div>

    </section>
  </form>
</div>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/10.6.2/bootstrap-slider.min.js"></script>
<script type="text/javascript">

  function openKCFinderr(div) {
  window.KCFinder = {
    callBack: function(url) {

      var hostname                 = document.location.origin;
      var image                  = document.getElementById('preview-'+div);
      var flag                 = div;
      image.src                  = document.location.origin+url;
      div                    = document.getElementById(div);
      div.value                  = document.location.origin+url;
      var getScale                 = document.getElementsByName("product_scale")[0].value;

      console.log(getScale);
      if(flag == 'image'){
        var scale                = document.getElementById('preview-scale');
        scale.src                = document.location.origin+url;
        scale.style.transform          = "scale("+getScale+")";
      }
      window.KCFinder = null;
    }
  };
  window.open('/assets/adminsite/bower_components/kcfinder/browse.php?type=images&dir=images/public',
    'kcfinder_image', 'status=0, toolbar=0, location=0, menubar=0, ' +
    'directories=0, resizable=1, scrollbars=0, width=800, height=600'
    );
}

  $(function(){ 
    $("#ex23").bootstrapSlider({
      ticks: [1, 2, 3],
      formatter: function(value) {
        var scale                  = document.getElementById('preview-scale');
        scale.style.transform      = "scale("+value+")";
      },
      ticks_tooltip: true,
      step: 0.01
    });

    $('#reset-scale').on('click',function(){
      $("#ex23").bootstrapSlider('setValue', 1.5);
    });
  });
</script>