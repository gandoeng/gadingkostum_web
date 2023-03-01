 <div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Setting
      <!-- <small>Version 2.0</small> -->
    </h1>
  </section>
  <?php 
  $late_charge                = 0;
  $meta_title                 = '';
  $meta_keyword               = '';
  $meta_description           = '';
  $footer_address             = '';
  $footer_phone               = '';
  $footer_email               = '';
  $contact_form_email         = '';
  $day_after_return           = 0;
  $latest_product_at_homepage = 0;
  $popular_category           = 0;
  $popular_theme              = 0;
  $invoice_footer             = '';
  if(!empty($setting)){
    foreach($setting as $index => $value){
      if($value['setting_name'] == 'late_charge'){
        $late_charge = $value['setting_value'];
      } elseif($value['setting_name'] == 'meta_title'){
        $meta_title = $value['setting_value'];
      } elseif($value['setting_name'] == 'meta_description'){
        $meta_description = $value['setting_value'];
      } elseif($value['setting_name'] == 'meta_keyword'){
        $meta_keyword = $value['setting_value'];
      } elseif($value['setting_name'] == 'footer_address'){
        $footer_address = $value['setting_value'];
      } elseif($value['setting_name'] == 'footer_email'){
        $footer_email = $value['setting_value'];
      } elseif($value['setting_name'] == 'footer_phone'){
        $footer_phone = $value['setting_value'];
      } elseif($value['setting_name'] == 'contact_form_email'){
        $contact_form_email = $value['setting_value'];
      } elseif($value['setting_name'] == 'latest_product_at_homepage'){
        $latest_product_at_homepage = $value['setting_value'];
      } elseif($value['setting_name'] == 'popular_category'){
        $popular_category = $value['setting_value'];
      } elseif($value['setting_name'] == 'popular_theme'){
        $popular_theme = $value['setting_value'];
      } elseif($value['setting_name'] == 'day_after_return'){
        $day_after_return = $value['setting_value'];
      } elseif($value['setting_name'] == 'header_whatsapp'){
        $header_whatsapp = $value['setting_value'];
      } elseif($value['setting_name'] == 'footer_whatsapp'){
        $footer_whatsapp = $value['setting_value'];
      } elseif($value['setting_name'] == 'invoice_footer'){
        $invoice_footer = $value['setting_value_textarea'];
      }
    }
  } ?>

  <!-- Main content -->
    <section class="content">
      <div class="row">

        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <form class="form-horizontal" method="POST" action="<?php echo base_url('adminsite/setting');?>">
          <div class="box box-solid">
            <div class="box-body">
              <div class="form-group">
                <div class="col-lg-12">
                  <h4>Rental Setting</h4>
                </div>
              </div>

              <label>Late Charge per item per day</label>
              <div class="input-group">
                <span class="input-group-addon">Rp.</span>
                <input type="text" name="late_charge" class="form-control pricemask" maxlength="15" value="<?php echo $late_charge;?>">
              </div>
              <br>
              <label>Day After Return <span style="font-size: 12px;"><i>(Stock availability)</i></span></label>
              <div class="input-group">
                <input type="text" name="day_after_return" class="form-control input-number" maxlength="15" value="<?php echo $day_after_return;?>">
              </div>
              <br>
              <div class="form-group">
                <div class="col-lg-12">
                  <label>Invoice Footer</label>
                  <textarea name="invoice_footer" class="tinymcebasic form-control"><?php echo $invoice_footer;?></textarea>
                </div>
              </div>
              <div class="form-group">
                <div class="col-lg-12">
                  <h4>SEO</h4>
                </div>
              </div>
              <div class="form-group">
                <div class="col-lg-12">
                  <label>Meta Title</label>
                  <input type="hidden" name="setting_name[]" value="meta_title" class="form-control">
                  <input type="text" name="setting_value[]" class="form-control" value="<?php echo $meta_title;?>">
                </div>
              </div>
              <div class="form-group">
                <div class="col-lg-12">
                  <label>Meta Keyword</label>
                  <input type="hidden" name="setting_name[]" value="meta_keyword" class="form-control">
                  <input type="text" name="setting_value[]" class="form-control" value="<?php echo $meta_keyword;?>">
                </div>
              </div>
              <div class="form-group">
                <div class="col-lg-12">
                  <label>Meta Description</label>
                  <input type="hidden" name="setting_name[]" value="meta_description" class="form-control">
                  <input type="text" name="setting_value[]" class="form-control" value="<?php echo $meta_description;?>">
                </div>
              </div>
              <div class="form-group">
                <div class="col-lg-12">
                  <h4>Header</h4>
                </div>
              </div>
              <label>Whatsapp</label>
              <div class="input-group">
                <span class="input-group-addon">(+62)</span>
                <input type="hidden" name="setting_name[]" value="header_whatsapp" class="form-control">
                  <input type="text" name="setting_value[]" class="form-control" value="<?php echo $header_whatsapp;?>">
              </div>
              <br>
              <div class="form-group">
                <div class="col-lg-12">
                  <h4>Footer</h4>
                </div>
              </div>
              <div class="form-group">
                <div class="col-lg-12">
                  <label>Address</label>
                  <input type="hidden" name="setting_name[]" value="footer_address" class="form-control">
                  <textarea style="height: 150px;" name="setting_value[]" class="form-control"><?php echo html_entity_decode($footer_address);?></textarea>
                </div>
              </div>
              <div class="form-group">
                <div class="col-lg-12">
                  <label>Phone</label>
                  <input type="hidden" name="setting_name[]" value="footer_phone" class="form-control">
                  <input type="text" name="setting_value[]" class="form-control" value="<?php echo $footer_phone;?>">
                </div>
              </div>
              <div class="form-group">
                <div class="col-lg-12">
                  <label>Whatsapp</label>
                  <input type="hidden" name="setting_name[]" value="footer_whatsapp" class="form-control">
                  <input type="text" name="setting_value[]" class="form-control" value="<?php echo $footer_whatsapp;?>">
                </div>
              </div>
              <div class="form-group">
                <div class="col-lg-12">
                  <label>Email</label>
                  <input type="hidden" name="setting_name[]" value="footer_email" class="form-control">
                  <input type="text" name="setting_value[]" class="form-control" value="<?php echo $footer_email;?>">
                </div>
              </div>

              <div class="form-group">
                <div class="col-lg-12">
                  <h4>Contact Form</h4>
                </div>
              </div>
              <div class="form-group">
                <div class="col-lg-12">
                  <label>Email</label>
                  <input type="hidden" name="setting_name[]" value="contact_form_email" class="form-control">
                  <input type="text" name="setting_value[]" class="form-control" value="<?php echo $contact_form_email;?>">
                </div>
              </div>

              <div class="form-group">
                <div class="col-lg-12">
                  <h4>Product</h4>
                </div>
              </div>
              <div class="form-group">
                <div class="col-lg-12">
                  <label style="width: 100%; display:block;">Latest Product at Homepage</label>
                  <label class="radio-inline radio-custom"><input type="checkbox" name="latest_product_at_homepage" <?php echo ($latest_product_at_homepage == 1) ? 'checked' : '';?> value="1"> Publish</label>
                </div>
              </div>

              <div class="form-group">
                <div class="col-lg-12">
                  <h4>Homepage</h4>
                </div>
              </div>
              <div class="form-group">
                <div class="col-lg-12">
                  <label>Popular Category</label>
                  <select class="form-control" name="popular_category">
                    <option <?php echo ($popular_category == 0 || $popular_category == '') ? 'selected' : '';?> value="0">0</option>
                    <option <?php echo ($popular_category == 4) ? 'selected' : '';?> value="4">4</option>
                    <option <?php echo ($popular_category == 8) ? 'selected' : '';?> value="8">8</option>
                    <option <?php echo ($popular_category == 12) ? 'selected' : '';?> value="12">12</option>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <div class="col-lg-12">
                  <label>Popular Theme</label>
                  <select class="form-control" name="popular_theme">
                    <option <?php echo ($popular_theme == 0 || $popular_theme == '') ? 'selected' : '';?> value="0">0</option>
                    <option <?php echo ($popular_theme == 4) ? 'selected' : '';?> value="4">4</option>
                    <option <?php echo ($popular_theme == 8) ? 'selected' : '';?> value="8">8</option>
                    <option <?php echo ($popular_theme == 12) ? 'selected' : '';?> value="12">12</option>
                  </select>
                </div>
              </div>
            </div>

            <div class="box-footer clearfix no-border">
              <button class="btn btn-primary btn-flat pull-right" type="submit">UPDATE</button>
            </div>

            <div class="overlay">
              <i class="fa fa-refresh fa-spin"></i>
            </div>
          </div>
          </form>
        </div>


        <!-- <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <form class="form-horizontal" method="POST" action="<?php echo base_url('adminsite/setting/correction');?>">
          <div class="box box-solid">
            <div class="box-body">
              <div class="form-group">
                <div class="col-lg-12">
                  <h4>Correction Search Keywords</h4>
                </div>
              </div>

             <div id="correction" class="form-group">

                <?php 
                $no = 1;
                if(!empty($correction)){

                  foreach($correction as $index => $value){
                    $decode_wrong = json_decode($value['wrong']);
                    if(is_array($decode_wrong)){
                      $decode_wrong = implode("\n",$decode_wrong);
                    }
                    echo '<div class="list-correction col-lg-12 col-md-12 col-sm-12 col-xs-12 item-more-'.$no.'" style="padding-left: 0; padding-right: 0;">
                    <input type="hidden" name="id[]" value="'.$value['Id'].'">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                      <label>Right</label>
                      <input type="text" name="right[]" class="form-control" value="'.$value['right'].'">
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                      <label>Wrong</label>
                      <textarea type="text" name="wrong[]" class="form-control">'.$decode_wrong.'</textarea>
                    </div>

                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding-top: 5px;">
                      <button class="remove-correction circle-button btn btn-danger btn-flat" type="button" id="item-more-'.$no.'"><i class="fa fa-times"></i> Remove </button>
                    </div>

                  </div>';
                  $no++;
                }
              } else {
                  echo '<div class="list-correction col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding-left: 0; padding-right: 0;">
                    <input type="hidden" name="id[]">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                      <label>Right</label>
                      <input type="text" name="right[]" class="form-control">
                    </div>

                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                      <label>Wrong</label>
                      <textarea type="text" name="wrong[]" class="form-control"></textarea>
                    </div>

                  </div>';
              }
              ?>

            </div>

            <div class="form-group">
              <div class="col-lg-12">
                <a class="btn-add-more-correction btn btn-primary btn-flat" id="<?php echo $no;?>"><i class="fa fa-plus"></i> Add more</a>
              </div>
            </div>

          </div>

          <div class="box-footer clearfix no-border">
            <button class="btn btn-primary btn-flat pull-right" type="submit">UPDATE CORRECTION</button>
          </div>

          <div class="overlay">
            <i class="fa fa-refresh fa-spin"></i>
          </div>

        </div>
      </form>
    </div> -->

      </div>

    </section>
</div>