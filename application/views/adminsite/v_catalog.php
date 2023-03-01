<link href="https://fonts.googleapis.com/css?family=Open+Sans&display=swap" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/10.6.2/css/bootstrap-slider.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/adminsite/custom/css/catalog2.css').'?'.md5(date('c'));?>">
<script type="text/javascript" src="<?php echo base_url('assets/adminsite/bower_components/print/printThis.js');?>"></script>
<style type="text/css">
  .wrapper-list-product{
    margin-top: 10px;
  }
</style>
<div class="content-wrapper">
 <!-- Content Header (Page header) -->
 <section class="content-header">
  <h1>
   KATALOG & TAG
   <!-- <small>Version 2.0</small> -->
 </h1>
</section>

<!-- Main content -->
<section class="content">
  <div class="row">
   <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="box box-solid">
     <div class="box-body">

      <form id="form-katalog" class="form-inline" style="margin-bottom: 10px;" action="" method="POST">
       <div class="form-group" style="width: 100%;">
        <label style="width: 100%;">Huruf</label>
        <input type="hidden" id="huruf-exist" value="<?php echo htmlspecialchars(json_encode($rangekode['huruf']));?>">
<!--                 <select name="huruf" class="select2-init form-control">
                  <option value="0">Select..</option>
                  <?php if(!empty($rangekode)){
                    foreach($rangekode as $index => $value){
                      foreach($value as $key => $row){
                        echo '<option value="'.$row.'">'.$row.'</option>';
                      }
                    }
                  }?>
                </select> -->
              </div>
              <ul class="nav nav-tabs">
                <li class="active"><a data-toggle="tab" href="#home">By Range Angka</a></li>
                <li><a data-toggle="tab" href="#menu1">By Product</a></li>
              </ul>

              <div class="tab-content" style="margin-top: 5px;">
                <div id="home" class="tab-pane fade in active">
                  <div class="form-group" style="width: 100%;">
                   <input type="text" class="form-control" name="huruf">
                   <button class="search-huruf btn btn-primary" data-by="range">Cari</button>
                 </div>
                 <div class="form-group" style="width: 100%;">
                   <label style="display:block;">Range Angka <i>(Pilih huruf terlebih dahulu)</i></label>
                   <input type="text" disabled class="form-control range-angka" style="width: 100px;" name="angka_first"/>
                   <span style="display:inline-block;">s/d</span>
                   <input type="text" disabled class="form-control range-angka" style="width: 100px;" name="angka_last"/>
                 </div>
               </div>
               <div id="menu1" class="tab-pane fade">
                <div class="form-group" style="width: 100%;">
                 <input type="text" class="form-control" name="product_huruf">
                 <button class="search-huruf btn btn-primary" data-by="product">Cari</button>
                 <label style="display:block;"><i>(Pilih huruf terlebih dahulu)</i></label>
               </div>
               <div id="list-product"></div>
               <!-- <select name="get_product[]" multiple class="select-catalog-product select2-init form-control"></select> -->
             </div>
           </div>

           <div class="form-group" style="width: 100%;">
            <div class="divider" style="border:none;border-top:1px dotted grey;height:1px;"></div>
          </div>
          <div class="form-group" style="width: 100%;">
            <label style="display:block;">Option tag</label>
            <div class="checkbox">
              <label><input type="checkbox" value="1" name="qty">&nbsp;1 set per size</label>
            </div>
          </div>
<!--               <div class="form-group">
                <div class="checkbox">
                  <label><input type="checkbox" value="1">Option 1</label>
                </div>
              </div> -->
              <!-- <div class="form-group" style="width: 100%;">
                <label>Choose Product</label>
                <select multiple name="product[]" class="select2-init form-control">
                  <option value="0">Select..</option>
                  <?php if(!empty($product)){
                    foreach($product as $index => $value){
                      echo '<option value="'.$value['product_id'].'">'.$value['product_nama'].' - '.$value['product_kode'].'</option>';
                    }
                  }?>
                </select>
              </div> -->
              <div class="form-group" style="margin-top: 25px;">
                <button type="submit" data-print="katalog" class="btn-print btn-flat btn-primary btn">Print Katalog</button>
                <button type="submit" data-print="tag" class="btn-print btn-flat btn-primary btn">Print Tag</button>
                <a id="reset-form-katalog" type="button" class="btn-flat btn-primary btn">Reset All</a>
              </div>

              <div class="wrapper-template" style="display:none;">
                <div id="template"></div>
              </div>

            </form>
            <!-- <div class="container container-list"></div> -->
            <!-- <?php
            $no=1;
            foreach($get as $i => $val){ ?>
              <div class="container container-list">
                <div class="row">
                  <?php 
                  foreach($val as $index => $value){ 
                    $product_slug = str_replace('/','',$value['product_slug']).'.png';
                    $image        = $value['image'];
                    if(!file_exists('assets/qr/'.$product_slug)){
                      $product_slug = 'no-thumbnail.png';
                    } 
                    if(!file_exists($value['image'])){
                      $image      = 'assets/images/no-thumbnail.png';
                    }
                    ?>

                    <div class="container-box-list col-xs-5">
                      <div class="wraper-box-list col-xs-5">
                        <div class="main-catalog">
                          <div class="main-header-catalog">
                            <div class="content">
                              <div class="title"><?php echo $value['product_nama'];?></div>
                              <div class="item-content">Kode: <b><?php echo $value['product_kode'];?></b></div>
                              <div class="item-content">Harga Sewa: <b><?php echo number_format($value['product_hargasewa']);?></b></div>
                              <div class="item-content">Jaminan: <b><?php echo number_format($value['product_deposit']);?></b></div>
                            </div>

                            <div class="qr">
                              <img src="<?php echo base_url('assets/qr').'/'.$product_slug;?>" class="img-responsive">
                            </div>
                          </div>

                          <div class="main-body-catalog">
                            <div class="content">
                              <div class="title">Ukuran:</div>
                              <div class="item-content">
                                <?php if(isset($value['sizestock']) && !empty($value['sizestock'][0])){
                                  echo '<ul>';
                                  foreach($value['sizestock'] as $key => $row){
                                    echo '<li>'.$row['product_size'].' <br>('.$row['product_stock'].' Set)</li>';
                                  }
                                  echo '</ul>';
                                }
                                ?>
                              </div>
                            </div>
                          </div>

                          <div class="main-body-catalog">
                            <div class="title">Isi Paket:</div>
                            <div class="item-content">
                              <?php
                              $product_isipaket = explode("\n",$value['product_isipaket']);
                              if(is_array($product_isipaket)){
                                echo '<ul>';
                                foreach($product_isipaket as $k => $r){ 
                                  echo '<li>'.$r.'</li>';
                                }
                                echo '</ul>';
                              }
                              ?>
                            </div>
                          </div>

                          <div class="main-footer-catalog">
                            <img src="<?php echo base_url($image);?>" class="img-responsive">
                          </div>

                        </div>
                      </div>
                    </div>
                    <?php
                  }
                  ?>
                </div>
              </div>
          <?php 
          if($no%2==0){
            echo '<div class="break-container"></div>';
          } 
          $no++;
        }
        ?> -->

        <?php
           /* $no=1;
            foreach($tag as $i => $val){ ?>
              <div class="container container-list">
                <div class="row">
                  <?php 
                  foreach($val as $index => $value){ 
                    $product_slug = str_replace('/','',$value['product_slug']).'.png';
                    $image        = $value['image'];
                    if(!file_exists('assets/qr/'.$product_slug)){
                      $product_slug = 'no-thumbnail.png';
                    } 
                    if(!file_exists($value['image'])){
                      $image      = 'assets/images/no-thumbnail.png';
                    }
                    ?>

                    <div class="container-box-list tag-box-list col-xs-5">
                      <div class="wraper-box-list tag col-xs-5">
                        <div class="main-catalog">
                          <div class="main-footer-catalog tag-image">
                            <img src="<?php echo base_url($image);?>" class="img-responsive">
                          </div>
                        </div>
                      </div>
                      <div class="wraper-box-list tag col-xs-5">
                        <div class="main-catalog">
                          <div class="main-header-catalog">
                            <div class="content">
                              <div class="title"><b><?php echo $value['product_nama'];?></b></div>
                              <div class="item-content">Kode: <b><?php echo $value['product_kode'];?></b></div>
                              <div class="item-content">Ukuran: <b><?php echo (isset($value['current'])) ? $value['current']['product_size'].' ('.$value['current']['product_stock'].' Set)' : '';?></b></div>
                              <div class="item-content">Harga Sewa: <b><?php echo number_format($value['product_hargasewa']);?></b></div>
                              <div class="item-content">Jaminan: <b><?php echo number_format($value['product_deposit']);?></b></div>
                            </div>

                            <div class="qr">
                              <img src="<?php echo base_url($value['qr']);?>" class="img-responsive">
                            </div>
                          </div>

                          <div class="main-body-catalog">
                            <div class="subtitle"><b>Isi Paket:</b></div>
                            <div class="item-content">
                              <?php
                              $product_isipaket = explode("\n",$value['product_isipaket']);
                              if(is_array($product_isipaket)){
                                echo '<ul>';
                                foreach($product_isipaket as $k => $r){ 
                                  echo '<li>'.$r.'</li>';
                                }
                                echo '</ul>';
                              }
                              ?>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <?php
                  }
                  ?>
                </div>
              </div>
          <?php 
          if($no%2==0){
            echo '<div class="break-container"></div>';
          } 
          $no++;
        }*/
        ?>
      </div>
      <div class="overlay">
        <i class="fa fa-refresh fa-spin"></i>
      </div>
    </div>
  </div>
</div>
</section>
</div>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/10.6.2/bootstrap-slider.min.js"></script>
<script type="text/javascript">
 $(function(){

  jQuery.fn.preventDoubleSubmission = function() {
    $(this).on('submit',function(e){
      var $form = $(this);
      if ($form.data('submitted') === true) {
        e.preventDefault();
      } else {
        $form.data('submitted', true);
      }
    });
    return this;
  };

  var form_katalog            = $('#form-katalog');
  var base_url                = $('base').attr('href');
  var loading_main            = $(document).find('.overlay');
  var select_catalog_product  = $('.select-catalog-product');

  var waitForEl = function(selector, callback) {
    if (jQuery(selector).length) {
      callback();
    } else {
      setTimeout(function() {
       waitForEl(selector, callback);
     }, 100);
    }
  };

  $(".range-angka").inputmask("9{4}", {
   placeholder: "0",
   numericInput: true
 });

  $('#form-katalog').on('#reset-form-katalog',function(ev){
   ev.preventDefault();
   alert('tes');
 });

  $('#reset-form-katalog').on('click',function(){
    $('input[name="angka_first"]').val(0);
    $('input[name="angka_last"]').val(0);
    $('input[name="angka_first"]').attr('disabled',true);
    $('input[name="angka_last"]').attr('disabled',true);
    $(".select2-init").select2("val", 0);
    $("input[name=type][value='all']").prop("checked",true);
    $('input[name="huruf"]').val("");
    $('input[name="product_huruf"]').val("");
    $('#template').empty();
    var selected = $('input[name="qty"]');
    $(selected).iCheck('uncheck');

    var listProduct   = $('.wrapper-list-product');
    if(listProduct.length > 0){
      $.each(listProduct,function(i,v){
        $(this).find('select').select2("destroy");
        $(this).remove();
      });
    }
  });

  var thisRangeKode = $("#range-kode").slider();

  function removeListProduct(kode){
    var id            = '#'+kode;
    var thisID        = $(document).find(id);
    $(thisID).fadeOut('slow', function(){
      $(thisID).find('select').select2("destroy");
      $(thisID).remove();
    });
  }

  function appendListProduct(kode,data){
    var parent   = $('#list-product');
    var id       = '#'+kode;
    var existID  = $(document).find(id);
    var template = '';

    if(data.length > 0 && kode !== undefined){
      if(existID.length == 0){
        template = '<div id="'+kode+'" class="wrapper-list-product">'
        template += '<label style="display:block;">'+kode+'</label>';
        template += '<select multiple name="product[]" class="form-control">'

        $.each(data,function(i,v){
          template += '<option value="'+kode+v+'">'+kode+v+'</option>'
        });

        template += '</select>'
        template += '<a href="javascript:void(0);" class="remove-list-product" data-kode="'+kode+'" style="display:inline-block;margin-top: 5px; text-decoration:underline;">Remove</a>'
        template += '</div>';

          setTimeout(function(){
            $(parent).append(template);
          });

          setTimeout(function(){
            $(id).find('select').select2({placeholder: 'Select'});
            $(id).find('select').trigger('change'); 
          },100);
          
      } else {
        alert('range kode already exists.');
      }
    }

    return template;
  }

  function getKodeByAngka(selector,form_data,flag){
   $.ajax({
    url: base_url + 'adminsite/catalog/getKodeByAngka',
    type: 'POST',
    dataType: 'json',
    data: form_data,
    beforeSend: function(data){
     $(loading_main).show();

     if(flag == 'range'){
      $('input[name="angka_first"]').val(0);
      $('input[name="angka_last"]').val(0);
      $('input[name="angka_first"]').attr('disabled',true);
      $('input[name="angka_last"]').attr('disabled',true);
    }
  },
  complete: function(){
   $(loading_main).hide();
 },
 success: function(result){

   if(result.length > 0){
    var firstAngka = result[0];
    var lastAngka  = result[result.length-1];

    if(flag == 'range'){
      $('input[name="angka_first"]').val(firstAngka);
      $('input[name="angka_last"]').val(lastAngka);
      $('input[name="angka_first"]').attr('disabled',false);
      $('input[name="angka_last"]').attr('disabled',false);
    } else if(flag == 'product'){
      $('input[name="product_huruf"]').val('');
      appendListProduct(form_data[0].value,result);
    }

        //console.log(result);
        //console.log(form_data);
        //$(select_catalog_product).empty();
        /*setTimeout(function() {
          if(!$.trim($(select_catalog_product).html()).length) {
            var thisCatalogProduct = '';
            $.each(result,function(i,v){
              thisCatalogProduct += '<option value="'+form_data[0].value+v+'">'+form_data[0].value+v+'</option>';
            });
            $(select_catalog_product).append(thisCatalogProduct);
            $(select_catalog_product).trigger('change'); 
          };
        });*/

      }
    },
    fail: function(){
     $(loading_main).hide();
   },
   error: function(xhr, ajaxOptions, thrownError) {
     $(loading_main).hide();
   }
 });
 }
    $('#list-product').on('click','.remove-list-product',function(e){
        var kode = $(this).data('kode');
        $(this).attr('disabled',false);
        removeListProduct(kode);
    });
    /*$('.btn-print').on('click',function(e){
     e.preventDefault();
     var form_print = $('#form-katalog');
     var flag       = $(this).data('print');
     $(form_print).trigger('submit');
     $(form_print).preventDoubleSubmission();
   });*/

   $('.search-huruf').on('click',function(e){
     e.preventDefault();
     var this_selector     = $(this);
     var this_flag         = $(this).data('by');
     var this_huruf;

     if(this_flag == 'product'){
        this_huruf         = $('input[name="product_huruf"]').val();
     } else {
        this_huruf         = $('input[name="huruf"]').val();
     }

     var this_huruf_exist  = JSON.parse($('#huruf-exist').val());
     var this_check        = $.inArray(this_huruf.toUpperCase(),this_huruf_exist);

     console.log(this_check);
     if(this_check > -1){
        //alert('range kode is not found.');
        //exist
        var form_data = [];
        form_data.push({'name':'huruf','value':this_huruf.toUpperCase()});
        getKodeByAngka(this_selector,form_data,this_flag);
      }
    });

        //function getCatalog(form,flag){

          //console.log('getCatalog');
          $('#form-katalog').submit(function(ev){
            ev.preventDefault();
            var flag      = $(document.activeElement).attr('data-print');
            var form_data = $(this).serializeArray();
            form_data.push({'name':'type','value':flag});
            console.log(form_data);
            $.ajax({
             url: base_url + 'adminsite/catalog/formprint',
             type: 'POST',
             dataType: 'json',
             data: form_data,
             beforeSend: function(data){
              console.log(data);
              $(loading_main).show();
            },
            complete: function(){
              $(loading_main).hide();
            },
            success: function(result){
              console.log(result);
              $('#template').empty();

              if(result.callback){
                if(!$.trim($('#template').html()).length) {
                 $(result.template).appendTo('#template');
                 waitForEl($("#template").find(".container-list"), function() {
                  setTimeout(function(){
                   if(flag == 'tag'){
                     $(document).find("#template").printThis({printDelay: 1000,debug: false, importCSS: true ,loadCSS: '<?php echo base_url('assets/adminsite/custom/css/catalog-tag.css').'?'.md5(date('c'));?>'});
                   }

                   if(flag == 'katalog'){
                      $(document).find("#template").printThis({printDelay: 1000,debug: false, importCSS: true ,loadCSS: '<?php echo base_url('assets/adminsite/custom/css/catalog2.css').'?'.md5(date('c'));?>'});
                   }
                 });
                });
               }
              } else {
                alert('product is not found.');
              }
           },
           fail: function(){
            $(loading_main).hide();

          },
          error: function(xhr, ajaxOptions, thrownError) {
            $(loading_main).hide();
          }

        });
          });


    //}

    $('input[name="huruf"]').on('keyup', function(e) {
      if(e.keyCode == 13){
        e.preventDefault();
        var this_search = $('#home').find('.search-huruf').trigger('click');
      }
    });

    $('input[name="product_huruf"]').on('keyup', function(e) {
      if(e.keyCode == 13){
        e.preventDefault();
        console.log('a');
        var this_search = $('#menu1').find('.search-huruf').trigger('click');
      }
    });

  });
</script>