 <link rel="stylesheet" href="<?php echo base_url('assets/adminsite/bower_components/font-awesome/css/font-awesome.min.css'); ?>">
 <style type="text/css">
	#canvas {
    width: 100%;
    -webkit-box-shadow: 0 5px 15px rgba(0,0,0,.5);
    box-shadow: 0 5px 15px rgba(0,0,0,.5);
  }

  #output {
    margin-top: 20px;
    background: #eee;
    padding: 10px;
    padding-bottom: 0;
  }

  #output div {
    padding-bottom: 10px;
    word-wrap: break-word;
  }

  #noQRFound {
    text-align: center;
  }
  #modal-scan-camera .modal-body{
    padding: 0;
  }
  #modal-scan-camera .modal-content{
    background: transparent;
    background-color: transparent;
    box-shadow: none;
  }
  #modal-scan-camera .modal-footer{
    border-top: 0;
    margin: 0 auto;
    text-align: center;
  }
  #modal-scan-camera .modal-footer button{
    margin: 0 auto;
    text-align: center;
  }
</style>
 <style>
  .table-invoice .payment{
    position: absolute;
    width: 100%;
    top: 15px;
    left: 0;
    font-size: 9px;
    text-align: center;
  }
  .table-invoice .payment p{
    margin-bottom: 0;
  }
  .table-invoice .payment p:last-child{
    margin-bottom: 5px;
  }
 </style>
 <div id="form-1" class="content-wrapper">
  <section class="content-header">
    <h1>
      View Rental Order
    </h1>
  </section>
  <?php if(isset($rental_order) && !empty($rental_order)){ ?>
  <form id="open-form-2" class="form-horizontal" method="POST" action="<?php echo base_url('adminsite/rental_order/form');?>">
    <input type="hidden" name="rental_order_id" value="<?php echo $rental_order[0]['rental_order_id'];?>">
    <input type="hidden" name="rental_invoice" value="<?php echo $rental_order[0]['rental_invoice'];?>">
    <input type="hidden" name="customer_id" value="<?php echo $rental_order[0]['customer_id'];?>">
    <section id="form-1" class="content">
      <div class="row">

        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
          <div class="box box-solid">
            <div class="box-body">
              <h4>Status</h4>
              <div class="form-group">
                <div class="col-lg-6 col-xs-12">
                  <select name="rental_status" class="form-control">
                    <?php if(!empty($rental_status)){
                      foreach($rental_status as $index => $value){
                        $selected = '';
                        if($value == $rental_order[0]['rental_status']){
                          $selected = 'selected';
                        }
                        echo '<option '.$selected.' value="'.$value.'">'.$value.'</option>';

                      }
                    } ?>
                  </select>
                </div>
                <div class="col-lg-6 col-xs-12">
                  <?php 
                  $current_date = date('Y-m-d');
                  $start_date   = date('Y-m-d',strtotime($rental_order[0]['rental_start_date']));
                  $end_date     = date('Y-m-d',strtotime($rental_order[0]['rental_end_date']));
                  if($current_date >= $start_date && $rental_order[0]['rental_status'] == 'booked'){
                    echo '<span class="required" style="display:inline-block; width: 100%; font-size: 11px; margin-top: 3px;">due pickup</span>';
                  }

                  if($current_date > $end_date && $rental_order[0]['rental_status'] == 'pickup'){
                    echo '<span class="required" style="display:inline-block; width: 100%; font-size: 11px; margin-top: 3px;">due return</span>';
                  }
                  ?>
                </div>
              </div>
              <legend></legend>
              <h4>Personal Information</h4>
              <div class="form-group">
                <div class="col-lg-6">
                  <label>Name</label>
                  <input type="text" name="customer_name" class="form-control" value="<?php echo $rental_order[0]['customer_name'];?>">
                  <label style="margin-top: 5px;"><span class="required">*</span>Phone</label>
                  <input type="text" name="customer_phone" class="form-control" value="<?php echo $rental_order[0]['customer_phone'];?>">
                </div>
                <div class="col-lg-6">
                  <label>Address</label>
                  <textarea style="height: 100px;" name="customer_address" class="form-control"><?php echo html_entity_decode($rental_order[0]['customer_address']);?></textarea>
                </div>
              </div>
              <legend></legend>
              <h4>Order</h4>
              <div class="form-group">
                <div class="col-lg-6">
                  <label><span class="required">*</span>Rental Date</label>
                  <input autocomplete="off" type="text" value="<?php echo date('j F Y',strtotime($rental_order[0]['rental_start_date']));?>" name="start_date" class="datepicker-view-start form-control">
                </div>
                <div class="col-lg-6">
                  <label><span class="required">*</span>Return Date</label>
                  <input autocomplete="off" type="text" name="end_date" data-date-start-date="<?php echo date('j F Y',strtotime("+3 day",strtotime($rental_order[0]['rental_start_date'])));?>" value="<?php echo date('j F Y',strtotime($rental_order[0]['rental_end_date']));?>" class="datepicker-view-end form-control">
                </div>
                <div class="col-lg-6">
                  <label style="margin: 10px 0px; width: 100%;"><span class="required" for="selectDelivery">*</span>Delivery Option
                    <select class="form-control" id="selectDelivery" style="margin: 5px 0px;">
                    <option disabled selected>Pilih</option>
                    <option>Diambil sendiri</option>
                    <option>Gojek</option>
                    <option>JNE</option>
                  </select>
                </div>
              </div>
            </div>

            <div class="box-body">
              <div class="form-group">
                <div class="col-lg-6">
                  <div class="main-product-rental-wrapper">
                    <div class="product-rental-wrapper product-rental-form form">
                      <label><span class="required">*</span> Store Location</label>
                      <select name="store_location_category_id" class="select-store-rental select2-init form-control">
                        <option value="0">Select Store</option>
                        <?php if(!empty($store_location)){
                          foreach($store_location as $index => $value){
                            $selected = '';
                            if($value['category_id'] == $rental_order[0]['store_location_category_id']){
                              $selected = 'selected';
                            }
                            echo '<option '.$selected.' value="'.$value['category_id'].'">'.$value['category_name'].'</option>';
                          }
                        }?>
                      </select>
                      <div class="wrapper-select-product">
                        <div class="main-select-product">
                          <label style="margin-top: 5px;">Nama Produk / Kode</label>
                          <select class="select-product-rental select2-init form-control">
                            <?php if(!empty($exist_product_from_location)){
                              echo '<option value="0">Select Product</option>';
                              foreach($exist_product_from_location as $index => $value){
                                echo '<option value="'.$value['product_id'].'">'.$value['product_nama'].' / '.$value['product_kode'].'</option>';
                              }
                            } ?>
                          </select>
                        </div>
                      </div>
                      <label style="margin-top: 5px;">Size</label>
                      <select class="select-product-size-rental form-control"></select>
                    </div>

                    <div class="product-rental-wrapper product-rental-form image">
                      <a href="assets/images/no-image.png" data-fancybox class="thumbnail-select-product"><img class="image img-responsive img-thumbnail preview-more" src="assets/images/no-image.png"></a>
                    </div>

                    <div class="product-rental-detail"></div>
                    <div class="calendar"></div>
                    <div class="box-footer inside-box-body clearfix no-border">
                      <button class="add-item-rental btn btn-primary btn-flat pull-left" type="button"><i class="fa fa-plus"></i> ADD ITEM</button>

                      <!-- <div id="some-test-element" class="btn btn-primary btn-flat pull-right"><i class="fa fa-qrcode"></i>&nbsp;Scan QR</div> -->
                      <div id="search-set-custom" class="btn btn-primary btn-flat pull-right"><i class="fa fa-qrcode"></i>&nbsp;Scan QR</div>
                      <div class="search">
                         <input id="foo" type="text" class="form-control" value="" autofocus/>
                      </div>
                      <div id="focus-here"></div>
                    </div>
                  </div>
                </div>

                <div class="col-lg-6">
                  <div class="main-product-rental-wrapper">
                    <div class="product-rental-wrapper">
                      <div class="product-rental-list-item">
                        <label><span class="required">*</span>Cost</label>
                        <table class="table-list-item table table-bordered">
                          <thead>
                            <tr>
                              <th>Action</th>
                              <th>Qty</th>
                              <th>Item Name</th>
                              <th>Harga Sewa</th>
                              <th>Deposit</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php
                            $i = 0;
                            $count_product = 1;
                            if(isset($rental_product) && !empty($rental_product)){
                              $count_product = count($rental_product);
                              foreach($rental_product as $index => $value){

                                $count_hargasewa = $value['rental_product_qty'] * $value['rental_product_hargasewa'];
                                $count_deposit   = $value['rental_product_qty'] * $value['rental_product_deposit'];

                                echo '<tr>
                                <td style="text-align: center;">
                                  <input type="hidden" name="rental_product_id[]" value="'.$value['rental_product_id'].'">
                                  <input type="hidden" name="product_id[]" value="'.$value['product_id'].'">
                                  <input type="hidden" name="rental_product_size[]" value="'.$value['rental_product_size'].'">
                                  <input type="hidden" name="rental_product_nama[]" value="'.$value['rental_product_nama'].'">
                                  <input type="hidden" name="rental_product_isipaket[]" value="'.$value['rental_product_isipaket'].'">
                                  <input type="hidden" name="rental_product_kode[]" value="'.$value['rental_product_kode'].'">
                                  <input type="hidden" name="rental_product_sizestock_id[]" value="'.$value['rental_product_sizestock_id'].'">
                                  <button data-hargasewa="'.$value['rental_product_hargasewa'].'" data-deposit="'.$value['rental_product_deposit'].'" class="remove-rental-product btn btn-danger btn-flat btn-xs">Remove</button>
                                </td>
                                <td style="text-align:center;"><input type="hidden" name="rental_product_qty[]" value="'.$value['rental_product_qty'].'">'.$value['rental_product_qty'].'</td>
                                <td style="text-align:left;">
                                  <span>'.$value['rental_product_nama'].'</span><span>'.$value['rental_product_size'].'</span>
                                </td>
                                <td style="text-align: right;">
                                  <input type="hidden" name="rental_product_hargasewa[]" value="'.$value['rental_product_hargasewa'].'">Rp. '.number_format($value['rental_product_hargasewa']).'</td>
                                  <td style="text-align: right;"><input type="hidden" name="rental_product_deposit[]" value="'.$value['rental_product_deposit'].'">Rp. '.number_format($value['rental_product_deposit']).'</td>
                                </tr>';

                              }
                            }
                            ?>
                          </tbody>
                          <tfoot>
                            <tr class="subtotal">
                              <?php 

                              $total_hargasewa  = 0;
                              $total_deposit    = 0;
                              $total            = 0;

                              if(isset($rental_order) && !empty($rental_order)){
                                $total_hargasewa = $rental_order[0]['rental_total_hargasewa'];
                                $total_deposit   = $rental_order[0]['rental_total_deposit'];
                                $total           = $rental_order[0]['rental_total'];
                              }

                              echo '<input type="hidden" name="rental_total_hargasewa" value="'.$total_hargasewa.'">
                              <input type="hidden" name="rental_total_deposit" value="'.$total_deposit.'">
                              <td colspan="3" style="text-align: right;">Subtotal : </td>
                              <td class="pricetotal_hargasewa" style="text-align: right;">Rp. '.number_format($total_hargasewa).'</td>
                              <td class="pricetotal_deposit" style="text-align: right;">Rp. '.number_format($total_deposit).'</td>';

                              ?>
                            </tr>
                            <tr class="total">
                              <input type="hidden" name="rental_total" value="<?php echo $total;?>">
                              <td colspan="3" style="text-align: right;">Total : </td>
                              <td class="all_price" colspan="2" style="text-align: right;"><strong>Rp. <?php echo number_format($total);?></strong></td>
                            </tr>
                          </tfoot>
                        </table>

                        <div class="checkbox" style="display: inline-block; margin-left: 5px;">
                          <label><input <?php echo (isset($rental_order) && !empty($rental_order) && $rental_order[0]['rental_payment_status'] == 'unpaid') ? 'checked' : '' ?> type="checkbox" name="rental_payment_status" value="unpaid"> Unpaid</label>
                        </div>

                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="box-footer clearfix no-border">
              <a href="<?php echo base_url('adminsite/rental_order');?>" class="btn btn-primary btn-flat pull-left"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp; CANCEL</a>
              <a id="print-invpinjam" href="<?php echo base_url('adminsite/rental_order/invpinjam/').$rental_order[0]['rental_order_id'];?>" class="btn btn-primary btn-flat pull-left" style="display:none;"></a>
              <button class="btn btn-primary btn-flat pull-right" type="submit"><i class="fa fa-plus"></i> SAVE + PRINT INVOICE PEMINJAMAN</button>
            </div>

            <div class="overlay">
              <i class="fa fa-refresh fa-spin"></i>
            </div>

          </div>
        </div>

      </div>

    </section>

  </form>

  <?php if($rental_order[0]['rental_status'] == 'pickup' || $rental_order[0]['rental_status'] == 'return'){ ?>
  <form id="open-form-3" class="form-horizontal" method="POST" action="<?php echo base_url('adminsite/rental_order/return_order');?>">
    <section class="content">
      <div class="row">
        <?php

        echo '<input type="hidden" name="customer_id" value="'.$rental_order[0]['customer_id'].'">';
        echo '<input type="hidden" name="customer_name" value="'.$rental_order[0]['customer_name'].'">';
        echo '<input type="hidden" name="customer_phone" value="'.$rental_order[0]['customer_phone'].'">';
        echo '<input type="hidden" name="customer_email" value="'.$rental_order[0]['customer_email'].'">';
        echo '<input type="hidden" name="customer_address" value="'.$rental_order[0]['customer_address'].'">';
        echo '<input type="hidden" name="start_date" value="'.$rental_order[0]['rental_start_date'].'">';
        echo '<input type="hidden" name="end_date" value="'.$rental_order[0]['rental_end_date'].'">';
        echo '<input type="hidden" name="rental_total_hargasewa" value="'.$rental_order[0]['rental_total_hargasewa'].'">';
        echo '<input type="hidden" name="rental_total_deposit" value="'.$rental_order[0]['rental_total_deposit'].'">';
        echo '<input type="hidden" name="rental_total" value="'.$rental_order[0]['rental_total'].'">';
        echo '<input type="hidden" name="rental_payment_status" value="'.$rental_order[0]['rental_payment_status'].'">';
        echo '<input type="hidden" name="rental_order_id" value="'.$rental_order[0]['rental_order_id'].'">';
        echo '<input type="hidden" name="rental_invoice" value="'.$rental_order[0]['rental_invoice'].'">';
        echo '<input type="hidden" name="current_date_now" value="'.date('j F Y').'">';
        echo '<input type="hidden" id="count_product" value="'.$count_product.'">';
        $datenow        = date('j F Y');

        if(!empty($return_order)){
          $return_date          = $return_order[0]['return_date'];
          $return_note          = htmlentities($return_order[0]['return_note']);
          $return_late_charges  = $return_order[0]['return_late_charges'];
          $return_damage_fine   = $return_order[0]['return_damage_fine'];
          $return_deposit       = $return_order[0]['return_deposit'];

          echo '<input type="hidden" name="return_order_id" value="'.$return_order[0]['return_order_id'].'">';
        } else {
          $return_date          = $rental_order[0]['rental_end_date'];
          $return_note          = '';
          $return_late_charges  = 0;
          $return_damage_fine   = 0;
          $return_deposit       = $total_deposit;

          echo '<input type="hidden" name="return_order_id" value="">';
        }

        if(isset($rental_product) && !empty($rental_product)){
          foreach($rental_product as $index => $value){

            $count_hargasewa = $value['rental_product_qty'] * $value['rental_product_hargasewa'];
            $count_deposit   = $value['rental_product_qty'] * $value['rental_product_deposit'];

            echo '<input type="hidden" name="rental_product_id[]" value="'.$value['rental_product_id'].'">
            <input type="hidden" name="product_id[]" value="'.$value['product_id'].'">
            <input type="hidden" name="rental_product_size[]" value="'.$value['rental_product_size'].'">
            <input type="hidden" name="rental_product_nama[]" value="'.$value['rental_product_nama'].'">
            <input type="hidden" name="rental_product_isipaket[]" value="'.$value['rental_product_isipaket'].'">
            <input type="hidden" name="rental_product_kode[]" value="'.$value['rental_product_kode'].'">
            <input type="hidden" name="rental_product_sizestock_id[]" value="'.$value['rental_product_sizestock_id'].'">
            <input type="hidden" name="rental_product_qty[]" value="'.$value['rental_product_qty'].'">
            <input type="hidden" name="rental_product_hargasewa[]" value="'.$value['rental_product_hargasewa'].'">
            <input type="hidden" name="rental_product_deposit[]" value="'.$value['rental_product_deposit'].'">';

          }
        }

        $latecharge = 0;
        if(!empty($return_order[0]['return_late_charges'])) {
          $latecharge = $return_order[0]['return_late_charges'];
        }

        if(!empty($return_order[0]['return_order_id'])){
          if(strtotime($datenow) > strtotime(date('j F Y',strtotime($rental_order[0]['rental_end_date']))) && !empty($return_order)){
            $remaining  = '<p style="color: green;">0</p>';

            $returnorder_returndate    = date('j F Y',strtotime($return_order[0]['return_date']));
            $returndate = date('j F Y',strtotime($rental_order[0]['rental_end_date'])); 
            $timeleft   = strtotime($returnorder_returndate)-strtotime($returndate);
            
            if($timeleft > 0 && empty($late_charge)){
              $latecharge = $late_charge * ($count_product * $timeleft);
            } else{
              $latecharge = $return_order[0]['return_late_charges'];
            }
          } elseif(empty($return_order)) {
            $latecharge = 0;
          }
        } else {
          $startdate  = date('j F Y',strtotime($rental_order[0]['rental_start_date'])); 
          $endate     = date('j F Y',strtotime($rental_order[0]['rental_end_date'])); 
          $strdatenow = strtotime($datenow);
          $strendate  = strtotime($endate);

          $timeleft = 0;

          if($strdatenow > $strendate){
            $timeleft   = $strdatenow-$strendate;
            $timeleft   = round((($timeleft/24)/60)/60);
            //$timeleft   = $endate-$datenow;
          }

          if($timeleft){
            $latecharge = $late_charge * ($count_product * $timeleft);
            $return_deposit = $return_deposit - $latecharge;
          } else{
            $latecharge = 0;
          }
        }
        
        $return_rental_date = $datenow;
        if(!empty($return_order[0]['return_order_id'])){
          $return_rental_date = date('j F Y',strtotime($return_date));
        } 
        echo '<input type="hidden" id="late_charge_value" value="'.$late_charge.'">';

        ?>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
          <div class="box box-solid">
            <div class="product-rental-wrapper no-padding">
              <div class="box-body">
                <h2 class="box-title box-no-padding-top"><strong>Return Date</strong></h2>
                <div class="form-group">
                  <div class="col-lg-5">
                    <input autocomplete="off" type="text" data-date-start-date="<?php echo $return_rental_date;?>" value="<?php echo $return_rental_date;?>" name="return_date" class="datepicker-return-date form-control with-border-grey">
                    <br>
                    <textarea class="form-control return-note note with-border-grey" name="return_note" placeholder="Notes:"><?php echo $rental_order[0]['rental_note'];?></textarea>
                  </div>
                  <div class="col-lg-7">
                    <label>Total Deposit</label>
                    <div class="input-group">
                      <span readonly class="input-group-addon padding-20">Rp.</span>
                      <input autocomplete="off" id="return-deposit" name="return_current_deposit" type="text" readonly class="form-control pricemask with-border-grey" maxlength="15" value="<?php echo number_format($total_deposit);?>">
                    </div>
                    <label>Late Charges</label>
                    <div class="input-group">
                      <span class="input-group-addon">- Rp.</span>
                      <input autocomplete="off" type="text" id="late-charges" name="return_late_charges" class="form-control pricemask late-charges with-border-grey" maxlength="15" value="<?php echo number_format($latecharge);?>">
                    </div>
                    <label>Damage Fine</label>
                    <div class="input-group">
                      <span class="input-group-addon">- Rp.</span>
                      <input autocomplete="off" type="text" id="damage-fine" name="return_damage_fine" class="form-control pricemask damage-fine with-border-grey" maxlength="15" value="<?php echo number_format($return_damage_fine);?>">
                    </div>
                    <label>Return Deposit</label>
                    <div class="input-group">
                      <span class="input-group-addon padding-20">Rp.</span>
                      <input autocomplete="off" type="text" readonly id="return-result-deposit" name="return_deposit" class="form-control pricemask late-charges with-border-grey" maxlength="15" value="<?php echo number_format($return_deposit);?>">
                    </div>
                  </div>

                </div>

                <div class="box-footer inside-box-body clearfix no-border">
                  <a href="<?php echo base_url('adminsite/rental_order');?>" class="btn btn-primary btn-flat pull-left"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp; CANCEL</a>
                  <button class="btn btn-primary btn-flat pull-right" type="submit"><i class="fa fa-plus"></i> SAVE + PRINT INVOICE PENGEMBALIAN</button>
                </div>

              </div>
            </div>
            <div class="overlay">
              <i class="fa fa-refresh fa-spin"></i>
            </div>
          </div>
        </div>
      </div>
    </section>
  </form>
  <?php } } ?>
</div>

<div style="display:none;" id="form-2" class="content-wrapper">
  <form class="form-2 form-horizontal" method="POST" action="<?php echo base_url('adminsite/rental_order/update');?>">
    <!-- Main content -->
    <section class="invoice">
      <!-- title row -->
      <div class="row">
        <div class="col-xs-12">
          <h2 class="page-header">
            RENTAL ORDER INVOICE
          </h2>
        </div>
        <!-- /.col -->
      </div>
      <!-- info row -->
      <div class="row invoice-info">
        <div class="col-sm-6 invoice-col">
          <address>
            <strong>GADING KOSTUM Menyewakan Kostum Anak & Dewasa</strong>
            <div class="store_address"><?php echo $store_location[0]['category_value_textarea'];?></div>
            Website : www.gadingkostum.com, <i class="fa fa-instagram"></i> : gadingkostum<br>
            Telpon : (021) 4584 3087, WA : 0813 53570168<br>
            Cabang: Kelapa Gading
          </address>
        </div>
        <!-- /.col -->
        <div class="col-sm-6 invoice-col text-right">
          <img style="margin-left: auto; max-width: 170px;" class="img-responsive" src="<?php echo base_url('assets/images/logo-245x162.png');?>">
        </div>
      </div>
      <!-- /.row -->
      <div class="row">
        <div class="col-sm-12">
          <h4><strong>Personal Information</strong></h4>
        </div>
        <div class="col-sm-6">
          <div class="form-group">
            <div class="col-lg-12">
              <label>Name</label>
              <input id="customer_id" type="hidden" name="customer_id" value="">
              <input id="customer_name" type="text" name="customer_name" class="form-control">
              <label style="margin-top: 5px;">Phone</label>
              <input id="customer_phone" type="text" name="customer_phone" class="form-control">
              <label style="margin-top: 5px;">Rental Date</label>
              <input readonly id="rental_start_date" type="text" name="rental_start_date" class="form-control">
              <label style="margin-top: 5px;">Return Date</label>
              <input readonly id="rental_end_date" type="text" name="rental_end_date" class="form-control">
            </div>
          </div>
        </div>
        <div class="col-sm-6">
          <div class="form-group">
            <div class="col-lg-12">
              <label>Rental Invoice #</label>
              <input readonly id="rental_invoice" type="text" name="rental_invoice" class="form-control">
              <label>Address</label>
              <textarea id="customer_address" style="height: 103px;" name="customer_address" class="form-control"></textarea>
            </div>
          </div>
        </div>
      </div>
      <!-- Table row -->
      <div class="row">
        <div class="col-sm-12">
          <h4><strong>Order</strong></h4>
        </div>
        <div class="col-xs-12 table-responsive">
          <table id="table-order" class="table table-order table-bordered">
            <thead>
              <tr>
                <th style="background-color: #D3D3D3;"><strong>Nama Produk</strong></th>
                <th style="background-color: #D3D3D3;"><strong>Kode</strong></th>
                <th style="background-color: #D3D3D3;"><strong>Isi Paket</strong></th>
                <th style="background-color: #D3D3D3;"><strong>Size</strong></th>
                <th style="background-color: #D3D3D3;"><strong>Qty</strong></th>
                <th style="background-color: #D3D3D3;"><strong>Harga Sewa</strong></th>
                <th style="background-color: #D3D3D3;"><strong>Harga Deposit</strong></th>
              </tr>
            </thead>
            <tbody id="tbody-order"></tbody>
            <tbody id="extra-payment">
              <?php 
              $total_extrapayment_sewa 	= 0;
              $total_extrapayment_deposit = 0;
              if(!empty($rental_extrapayment)){
                $extrapayment_flag = array('sewa','deposit');
                $action_turn = 'readonly';
                foreach($rental_extrapayment as $index => $value){
                  if(strtotime(date('Y-m-d',strtotime($value['rental_extra_created']))) == strtotime(date('Y-m-d'))){
                    $action_turn = '';
                  }
                  echo '<tr>';
                  echo '<td style="padding: 8px;width: 150px;">';
                  echo '<select '.$action_turn.' class="form-control" name="rental_extrapayment_flag[]">';
                  foreach($extrapayment_flag as $key => $row){
                    ($value['rental_extrapayment_flag'] == $row) ? $checked = 'selected' : $checked = '';
                    echo '<option value="'.$row.'" '.$checked.'>Harga '.ucfirst($row).'</option>';
                  }
                  echo '</select>';
                  echo '</td>';

                  echo '<td colspan="3" style="padding: 8px;">';
                  echo '<textarea '.$action_turn.' placeholder="Notes payment:" wrap="hard" style="padding: 8px; text-align:left;" name="rental_extranote[]" class="form-control">'.html_entity_decode($value['rental_extranote']).'</textarea>';
                  echo '</td>';

                  echo '<td>';
                  echo  '<input type="hidden">';
                  echo '</td>';

                  if($value['rental_extrapayment_flag'] == 'sewa'){
                    $total_extrapayment_sewa+=$value['rental_extrapayment'];
                    echo '<td class="extrapayment-row extrapayment-sewa" style="padding: 8px;">';
                    echo '<input type="hidden" name="rental_extrapayment_id[]" value="'.$value['rental_extrapayment_id'].'">';
                    echo '<input type="text" '.$action_turn.' style="margin: 0 auto; text-align:center;display:inline-block;width:90%;" name="rental_extrapayment[]" class="pricemask pricepayment form-control" value="'.number_format($value['rental_extrapayment']).'">';
                    echo '</td>';
                    echo '<td>';
                    echo '</td>';
                  } elseif($value['rental_extrapayment_flag'] == 'deposit'){
                    $total_extrapayment_deposit+=$value['rental_extrapayment'];
                    echo '<td>';
                    echo '</td>';
                    echo '<td class="extrapayment-row extrapayment-deposit" style="padding: 8px;">';
                    echo '<input type="hidden" name="rental_extrapayment_id[]" value="'.$value['rental_extrapayment_id'].'">';
                    echo '<input type="text" '.$action_turn.' style="margin: 0 auto; text-align:center;display:inline-block;width:90%;" name="rental_extrapayment[]" class="pricemask pricepayment form-control" value="'.number_format($value['rental_extrapayment']).'">';
                    echo '</td>';
                  }
                  echo '<td>';
                  if(strtotime(date('Y-m-d',strtotime($value['rental_extra_created']))) == strtotime(date('Y-m-d'))){
                    echo '<button style="display: inline-block;margin-left: 5px;vertical-align: top;" class="remove-extrapayment circle-button btn btn-danger btn-flat" type="button"><i class="fa fa-times"></i></button>';
                  }
                  echo '</td>';
                  echo '</tr>';
                }
              }
              ?>
            </tbody>
            <tfoot>
              <tr>
                <td id="rentalnoteparent" colspan="5" rowspan="3"><textarea style="text-align:left;" id="rental_note" name="rental_note" placeholder="Notes:" class="note form-control"></textarea></td>
                <td style="text-align:center; background-color: #D3D3D3;" id="rental_total_hargasewa"><strong></strong></td>
                <td style="text-align:center; background-color: #D3D3D3;" id="rental_total_deposit"><strong></strong></td>
              </tr>
              <tr>
                <td colspan="2" rowspan="1" style="text-align:center; background-color: #D3D3D3;" id="rental_total"><strong></strong></td>
              </tr>
          </tfoot>
        </table>

        <div class="form-group">
          <div class="col-lg-12">
            <div class="btn-group">
              <a style="margin-right: 5px;" class="btn-add-more btn btn-primary btn-flat" data-more="table-payment" id="1"><i class="fa fa-plus"></i> Add more cost</a>
            </div>

            <label style="width: 100%; display:block; margin-top: 30px;">Terima Uang</label>
            <div class="btn-group" style="width: 100%; margin-top: 5px;">
              <select class="form-control" id="pilih-terima-uang" style="display: inline-block;width: 180px;float: none;">
                <option value="sewa">Terima Uang Sewa</option>
                <option value="deposit">Terima Uang Deposit</option>'
              </select>

              <a style="display: inline-block;float: none; vertical-align: top;" style="margin-left: 5px;" class="btn-add-more btn btn-primary btn-flat" data-more="table-terima-uang" id="1"><i class="fa fa-plus"></i> Tambah</a>
            </div>
          </div>
        </div>

        <div class="form-group">
          <div class="col-lg-12">
            <table id="table-terima-uang" class="text-center table table-hover table-bordered table-striped table-align-middle">
              <thead>
                <tr>
                  <th>Jenis Transaksi</th>
                  <th>Terima</th>
                  <th>Nominal</th>
                  <th>Note</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php if(!empty($jenis_transaksi_order)){
                  $temp_jenisTransaksi = array('cash','transfer','debit');
                  foreach($jenis_transaksi_order as $index => $value){
                    $active_action       = (strtotime(date('Y-m-d',strtotime($value['jenis_transaksi_created']))) != strtotime(date('Y-m-d'))) ? 'disabled' : '';
                    echo '<tr class="item-terima-uang">';
                    echo '<td>';
                    echo '<input type="hidden" name="jenis_transaksi_id[]" value="'.$value['jenis_transaksi_id'].'">';
                    echo '<input type="hidden" name="jenis_transaksi[]" value="'.$value['jenis_transaksi'].'">';
                    if($active_action == 'disabled'){
                      echo '<input type="hidden" name="jenis_transaksi_flag[]" value="'.$value['jenis_transaksi_flag'].'">';
                      echo '<input type="hidden" name="jenis_transaksi_nominal[]" value="'.number_format($value['jenis_transaksi_nominal']).'">';
                      echo '<input type="hidden" name="jenis_transaksi_note[]" value="'.$value['jenis_transaksi_note'].'">';
                    }
                    echo 'Terima Uang '.ucfirst($value['jenis_transaksi']);
                    echo '</td>';

                    echo '<td style="width: 150px;">';
                    echo '<select '.$active_action.' class="form-control" name="jenis_transaksi_flag[]">';
                    foreach($temp_jenisTransaksi as $key => $row){
                      $selected = ($value['jenis_transaksi_flag'] == $row) ? 'selected' : '';
                      echo '<option value="'.$row.'" '.$selected.'>'.ucfirst($row).'</option>';
                    }
                    echo '</select>';
                    echo '</td>';

                    echo '<td>';
                    echo '<input '.$active_action.' type="text" style="margin: 0 auto; text-align:center;display:inline-block;width:90%;" name="jenis_transaksi_nominal[]" class="pricemask pricepayment form-control" value="'.number_format($value['jenis_transaksi_nominal']).'">';
                    echo '</td>';

                    echo '<td>';
                    echo '<textarea '.$active_action.' placeholder="Notes:" wrap="hard" style="text-align:left;" name="jenis_transaksi_note[]" class="form-control">'.$value['jenis_transaksi_note'].'</textarea>';
                    echo '</td>';

                    echo '<td>';
                    if(strtotime(date('Y-m-d',strtotime($value['jenis_transaksi_created']))) == strtotime(date('Y-m-d'))){
                      echo '<button class="remove-terima-uang circle-button btn btn-danger btn-flat" type="button"><i class="fa fa-times"></i></button>';
                    }
                    echo '</td>';
                    echo '<tr>';
                  }
                } else {
                  echo '<tr class="empty-table">';
                  echo '<td colspan="5"> </td>';
                  echo '</tr>';
                } ?>
              </tbody>
            </table>
          </div>
        </div>

      </div>

      <div class="col-xs-12 table-responsive">
        <table class="table table-invoice table-bordered">
          <thead>
            <tr>
              <th><strong>Konfirmasi Booking</strong></th>
              <th><strong>Terima Uang Sewa</strong></th>
              <th><strong>Terima Kostum</strong></th>
              <th><strong>Terima Uang Deposit</strong></th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td><input readonly style="background-color: white !important;" type="text" class="form-control invoice-signature" name="rental_konfirmasi_booking" placeholder="Penyewa"></td>
              <td style="position:relative;">
                  <input type="text" class="form-control invoice-signature" placeholder="Petugas">
                  <div class="payment">
                    <?php 
                      if(!empty($jenis_transaksi)){
                        foreach($jenis_transaksi as $index => $value){
                          if($value['jenis_transaksi'] == 'sewa'){
                            echo '<p>'.date('d M Y',strtotime($value['jenis_transaksi_created'])).' - '.$value['jenis_transaksi_flag'].' - Rp. '.number_format($value['jenis_transaksi_nominal']).'</p>';
                          }
                        }
                      }
                    ?>
								  </div>
                </td>
                <td><input readonly style="background-color: white !important;" type="text" class="form-control invoice-signature" name="rental_terima_kostum" placeholder="Penyewa"></td>
                <td style="position:relative;">
                  <input type="text" class="form-control invoice-signature" placeholder="Petugas">
                  <div class="payment">
                    <?php 
                      if(!empty($jenis_transaksi)){
                        foreach($jenis_transaksi as $index => $value){
                          if($value['jenis_transaksi'] == 'deposit'){
                            echo '<p>'.date('d M Y',strtotime($value['jenis_transaksi_created'])).' - '.$value['jenis_transaksi_flag'].' - Rp. '.number_format($value['jenis_transaksi_nominal']).'</p>';
                          }
                        }
                      }
                    ?>
								  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

      <div class="row sk">
        <!-- accepted payments column -->
        <div class="col-sm-12">
          <h6><strong>Syarat & Ketentuan:</strong></h6>
          <h6>- Dengan tanda tangan diatas ini, saya menyatakan sudah membaca dan menyetujui syarat & ketentuan peminjaman dibawah ini.</h6>
          <h6>- Lama penyawaan adalah 3 hari (hari Sabtu, Minggu dan hari Libur dihitung).</h6>
          <h6>- Biaya pengiriman dan pengembalian kostum menjadi tanggungan penyewa.</h6>
          <h6>- Denda keterlambatan pengembalian kostum adalah Rp. 20.000,- per kostum per hari.</h6>
          <h6>- Pengembalian deposit setelah kostum diterima dalam keadaan baik.</h6>
          <h6>- Kostum yang disewa tidak perlu dicuci saat dikembalikan. Kami yang akan mencuci kostum tersebut.</h6>
          <h6>- Apabila terjadi kerusakan / kehilangan kostum / aksesoris akan dikenakan biaya sesuai ongkos perbaikan kostum / aksesoris tersebut</h6>
          <h6>- Kostum yang sudah dibooking tidak dapat ditukar atau dibatalkan.</h6>
          <h6>- Uang DP yang sudah dibayarkan tidak bisa dikembalikan (non-refundable).</h6>
        </div>
      </div>
      <!-- /.row -->

      <!-- this row will not appear when printing -->
      <div class="row no-print">
        <div class="col-xs-12">
          <div class="box-footer inside-box-body clearfix no-border">
            <a id="print-invpinjam" style="display:none;"></a>
            <button class="open-form-1 btn btn-primary btn-flat pull-left" type="button"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp; BACK</button>
            <button class="save-order btn btn-primary btn-flat pull-right" type="submit" name="save" value="2">PRINT + SAVE</button>
            <button id="submit-form-2" class="save-order btn btn-primary btn-flat pull-right" type="submit" style="margin-right: 3px;" name="save" value="1">SAVE</button>
          </div>
        </div>
      </div>
    </section>

    <div class="clearfix"></div>

  </form>
</div>

<div id="form-3" class="content-wrapper" style="display:none;">
  <form class="form-3 form-horizontal" method="POST" action="<?php echo base_url('adminsite/rental_order/update_return');?>">
    <!-- Main content -->
    <section class="invoice">
      <!-- title row -->
      <div class="row">
        <div class="col-xs-12">
          <h2 class="page-header">
            RETURN INVOICE
          </h2>
        </div>
        <!-- /.col -->
      </div>
      <!-- info row -->
      <div class="row invoice-info">
        <div class="col-sm-6 invoice-col">
          <address>
            <strong>GADING KOSTUM Menyewakan Kostum Anak & Dewasa</strong>
            <div class="store_address"><?php echo $store_location[0]['category_value_textarea'];?></div>
            gadingkostum<br>
            Telpon / WA: (021) 4584 3087 / 0813 53570168<br>
            Cabang: Kelapa Gading
          </address>
        </div>
        <!-- /.col -->
        <div class="col-sm-6 invoice-col text-right">
          <img style="margin-left: auto; max-width: 170px;" class="img-responsive" src="<?php echo base_url('assets/images/logo-245x162.png');?>">
        </div>
      </div>
      <!-- /.row -->
      <div class="row">
        <div class="col-sm-12">
          <h4><strong>Personal Information</strong></h4>
        </div>
        <div class="col-sm-6">
          <div class="form-group">
            <div class="col-lg-12">
              <label>Name</label>
              <input id="customer_id_return" type="hidden" name="customer_id" value="">
              <input id="customer_name_return" readonly type="text" name="customer_name" class="form-control">
              <label style="margin-top: 5px;">Phone</label>
              <input id="customer_phone_return" readonly type="text" name="customer_phone" class="form-control">
              <!-- <label style="margin-top: 5px;">Email</label>
              <input id="customer_email_return" readonly type="text" name="customer_email" class="form-control"> -->
              <label style="margin-top: 5px;">Rental Date</label>
              <input readonly id="rental_start_date_return" type="text" name="rental_start_date" class="form-control">
              <label style="margin-top: 5px;">Return Date</label>
              <input readonly id="rental_end_date_return" type="text" name="return_date" class="form-control">
            </div>
          </div>
        </div>
        <div class="col-sm-6">
          <div class="form-group">
            <div class="col-lg-12">
              <label>Rental Invoice #</label>
              <input readonly id="rental_invoice_return" type="text" name="rental_invoice" class="form-control">
              <label>Address</label>
              <textarea readonly id="customer_address_return" style="height: 103px;" name="customer_address" class="form-control"></textarea>
            </div>
          </div>
        </div>
      </div>
      <!-- Table row -->
      <div class="row">
        <div class="col-sm-12">
          <h4><strong>Order</strong></h4>
        </div>
        <div class="col-xs-12 table-responsive">
          <table id="table-order-return" class="table table-order table-bordered">
            <thead>
              <tr>
                <th style="background-color: #D3D3D3; text-align:center;"><strong>Item Name</strong></th>
                <th style="background-color: #D3D3D3;"><strong>Isi Paket</strong></th>
                <th style="background-color: #D3D3D3;"><strong>Size</strong></th>
                <th style="background-color: #D3D3D3;"><strong>Qty</strong></th>
                <th style="background-color: #D3D3D3;"><strong>Booking Fee</strong></th>
                <th style="background-color: #D3D3D3;"><strong>Deposit</strong></th>
              </tr>
            </thead>
            <tbody id="tbody-order"></tbody>
            <tbody id="extra-payment">
              <?php 
              $total_extrapayment_sewa 	= 0;
              $total_extrapayment_deposit = 0;
              if(!empty($rental_extrapayment)){
                $extrapayment_flag = array('sewa','deposit');
                $action_turn = 'readonly';
                foreach($rental_extrapayment as $index => $value){
                  echo '<tr>';
                  echo '<td style="padding: 8px;width: 150px;">';
                  echo '<select '.$action_turn.' class="form-control" name="rental_extrapayment_flag[]">';
                  foreach($extrapayment_flag as $key => $row){
                    ($value['rental_extrapayment_flag'] == $row) ? $checked = 'selected' : $checked = '';
                    echo '<option value="'.$row.'" '.$checked.'>Harga '.ucfirst($row).'</option>';
                  }
                  echo '</select>';
                  echo '</td>';

                  echo '<td colspan="3" style="padding: 8px;">';
                  echo '<textarea '.$action_turn.' placeholder="Notes payment:" wrap="hard" style="padding: 8px; text-align:left;" name="rental_extranote[]" class="form-control">'.html_entity_decode($value['rental_extranote']).'</textarea>';
                  echo '</td>';

                  if($value['rental_extrapayment_flag'] == 'sewa'){
                    $total_extrapayment_sewa+=$value['rental_extrapayment'];
                    echo '<td class="extrapayment-row extrapayment-sewa" style="padding: 8px;">';
                    echo '<input type="hidden" name="rental_extrapayment_id[]" value="'.$value['rental_extrapayment_id'].'">';
                    echo '<input type="text" '.$action_turn.' style="margin: 0 auto; text-align:center;display:inline-block;width:90%;" name="rental_extrapayment[]" class="pricemask pricepayment form-control" value="'.number_format($value['rental_extrapayment']).'">';
                    echo '</td>';
                    echo '<td>';
                    echo '</td>';
                  } elseif($value['rental_extrapayment_flag'] == 'deposit'){
                    $total_extrapayment_deposit+=$value['rental_extrapayment'];
                    echo '<td>';
                    echo '</td>';
                    echo '<td class="extrapayment-row extrapayment-deposit" style="padding: 8px;">';
                    echo '<input type="hidden" name="rental_extrapayment_id[]" value="'.$value['rental_extrapayment_id'].'">';
                    echo '<input type="text" '.$action_turn.' style="margin: 0 auto; text-align:center;display:inline-block;width:90%;" name="rental_extrapayment[]" class="pricemask pricepayment form-control" value="'.number_format($value['rental_extrapayment']).'">';
                    echo '</td>';
                  }

                  echo '</tr>';
                }
              }
              ?>
            </tbody>
            <tfoot>
              <tr>
                <td colspan="4" rowspan="20"><textarea id="return_note" style="text-align:left;" name="return_note" placeholder="Notes:" class="note form-control"></textarea></td>
                <td style="text-align:center; background-color: #D3D3D3;" id="rental_total_hargasewa_return"><strong></strong></td>
                <td style="text-align:center; background-color: #D3D3D3;" id="rental_total_deposit_return"><strong></strong></td>
              </tr>
              <tr>
                <td colspan="2" rowspan="1" style="text-align:center; background-color: #D3D3D3;" id="rental_total_return"><strong></strong></td>
              </tr>
              <tr>
                <td style="text-align:center; background-color: #D3D3D3;"><strong>Denda Keterlambatan</strong></td>
                <td style="text-align:center;" id="return_late_charges"><strong></strong></td>
              </tr>
              <tr>
                <td style="text-align:center; background-color: #D3D3D3;"><strong>Denda Kerusakan</strong></td>
                <td style="text-align:center;" id="return_damage_fine"><strong></strong></td>
              </tr>
              <?php
              if(!empty($rental_extradenda)){
                $total_denda = 0;
                foreach($rental_extradenda as $index => $value){
                    $active_action       = (strtotime(date('Y-m-d',strtotime($value['rental_extra_created']))) != strtotime(date('Y-m-d'))) ? 'readonly' : '';
                    echo '<tr class="item-biaya">';
                      echo '<td>';
                        echo '<textarea placeholder="Notes:" wrap="hard" style="text-align:left;" name="rental_dendanote[]" class="form-control"></textarea>';
                      echo '</td>';

                      echo '<td>';
                        echo '<input type="hidden" name="rental_dendapayment_flag[]" value="'.$value['rental_extrapayment_flag'].'">';
                        echo '<input type="hidden" name="rental_dendapayment_id[]" value="'.$value['rental_extrapayment_id'].'">';
                        echo '<input type="text" style="margin: 0 auto; text-align:center;display:inline-block;" name="rental_dendapayment[]" class="pricemask pricepayment form-control" value="'.number_format($value['rental_extrapayment']).'">';
                      echo '</td>';

                      echo '<td style="width: 1px;text-align: center;padding-left: 0;padding-right: 0;margin-right: 0;margin-left: 0;">';
                        echo '<button style="display: inline-block;vertical-align: top;" class="remove-denda circle-button btn btn-danger btn-flat" type="button"><i class="fa fa-times"></i></button>';
                      echo '</td>';
                    echo '<tr>';
                }
              }
              ?>
              <tr id="divider-biaya">
                <td style="text-align:center; background-color: #D3D3D3;"><strong>Uang Kembali</strong></td>
                <td style="text-align:center;" id="return_deposit_"><strong></strong></td>
              </tr>
          </tfoot>
        </table>
        
        <div class="form-group">
          <div class="col-lg-12">
            <div class="btn-group">
              <a style="margin-right: 5px;" class="btn-add-more btn btn-primary btn-flat" data-more="table-biaya" id="1"><i class="fa fa-plus"></i> Add more denda</a>
            </div>
          </div>
        </div>

        <div class="form-group">
          <div class="col-lg-12">

            <?php //if(strtotime(date('Y-m-d',strtotime($rental_order[0]['rental_created']))) == strtotime(date('Y-m-d'))){ ?>

            <label style="width: 100%; display:block; margin-top: 30px;">Kembali Uang</label>
            <div class="btn-group" style="width: 100%; margin-top: 5px;">
              <select class="form-control" id="pilih-kembali-uang" style="display: inline-block;width: 180px;float: none;">
                <option value="return">Kembali Uang Deposit</option>'
              </select>

              <a style="display: inline-block;float: none; vertical-align: top;" style="margin-left: 5px;" class="btn-add-more btn btn-primary btn-flat" data-more="table-kembali-uang" id="1"><i class="fa fa-plus"></i> Tambah</a>
            </div>
            <?php //} ?>
          </div>
        </div>

        <div class="form-group">
          <div class="col-lg-12">
            <table id="table-kembali-uang" class="text-center table table-hover table-bordered table-striped table-align-middle">
              <thead>
                <tr>
                  <th>Jenis Transaksi</th>
                  <th>Terima/Kembali</th>
                  <th>Nominal</th>
                  <th>Note</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php if(!empty($jenis_transaksi)){
                  $temp_jenisTransaksi  = array('cash','transfer','debit');
                  $temp_returnTransaksi = array('cash','transfer');
                  foreach($jenis_transaksi as $index => $value){
                    $active_action       = (strtotime(date('Y-m-d',strtotime($value['jenis_transaksi_created']))) != strtotime(date('Y-m-d'))) ? 'disabled' : '';
                    echo '<tr class="item-kembali-uang">';
                    echo '<td>';
                    if($value['jenis_transaksi'] == 'return'){
                      echo '<input type="hidden" name="jenis_transaksi_id[]" value="'.$value['jenis_transaksi_id'].'">';
                      echo '<input type="hidden" name="jenis_transaksi[]" value="'.$value['jenis_transaksi'].'">';
                      if($active_action == 'disabled'){
                        echo '<input type="hidden" name="jenis_transaksi_flag[]" value="'.$value['jenis_transaksi_flag'].'">';
                        echo '<input type="hidden" name="jenis_transaksi_nominal[]" value="'.number_format($value['jenis_transaksi_nominal']).'">';
                        echo '<input type="hidden" name="jenis_transaksi_note[]" value="'.$value['jenis_transaksi_note'].'">';
                      }
                      echo 'Kembali Uang Deposit';
                    } else {
                      echo '<input type="hidden" name="jenis_transaksi_id[]" value="'.$value['jenis_transaksi_id'].'">';
                      echo '<input type="hidden" name="jenis_transaksi[]" value="'.$value['jenis_transaksi'].'">';
                      echo '<input type="hidden" name="jenis_transaksi_flag[]" value="'.$value['jenis_transaksi_flag'].'">';
                      echo '<input type="hidden" name="jenis_transaksi_nominal[]" value="'.number_format($value['jenis_transaksi_nominal']).'">';
                      echo '<input type="hidden" name="jenis_transaksi_note[]" value="'.$value['jenis_transaksi_note'].'">';
                      echo 'Terima Uang '.ucfirst($value['jenis_transaksi']);
                    }
                    echo '</td>';

                    echo '<td style="width: 150px;">';
                    if($value['jenis_transaksi'] == 'return'){
                      echo '<select '.$active_action.' class="form-control" name="jenis_transaksi_flag[]">';
                      foreach($temp_returnTransaksi as $key => $row){
                        $selected = ($value['jenis_transaksi_flag'] == $row) ? 'selected' : '';
                        echo '<option value="'.$row.'" '.$selected.'>'.ucfirst($row).'</option>';
                      }
                      echo '</select>';
                    } else {
                      echo '<select disabled class="form-control" name="jenis_transaksi_flag[]">';
                      foreach($temp_jenisTransaksi as $key => $row){
                        $selected = ($value['jenis_transaksi_flag'] == $row) ? 'selected' : '';
                        echo '<option value="'.$row.'" '.$selected.'>'.ucfirst($row).'</option>';
                      }
                      echo '</select>';
                    }
                    echo '</td>';

                    echo '<td>';

                    if($value['jenis_transaksi'] == 'return'){
                      echo '<input '.$active_action.' type="text" style="margin: 0 auto; text-align:center;display:inline-block;width:90%;" name="jenis_transaksi_nominal[]" class="pricemask pricepayment form-control" value="'.number_format($value['jenis_transaksi_nominal']).'">';
                    } else {
                      echo '<input disabled type="text" style="margin: 0 auto; text-align:center;display:inline-block;width:90%;" name="jenis_transaksi_nominal[]" class="pricemask pricepayment form-control" value="'.number_format($value['jenis_transaksi_nominal']).'">';
                    }
                    echo '</td>';

                    echo '<td>';
                    if($value['jenis_transaksi'] == 'return'){
                      echo '<textarea '.$active_action.' placeholder="Notes:" wrap="hard" style="text-align:left;" name="jenis_transaksi_note[]" class="form-control">'.$value['jenis_transaksi_note'].'</textarea>';
                    } else {
                      echo '<textarea disabled placeholder="Notes:" wrap="hard" style="text-align:left;" name="jenis_transaksi_note[]" class="form-control">'.$value['jenis_transaksi_note'].'</textarea>';
                    }
                    echo '</td>';

                    echo '<td>';
                    if(strtotime(date('Y-m-d',strtotime($value['jenis_transaksi_created']))) == strtotime(date('Y-m-d')) && $value['jenis_transaksi'] == 'return'){
                      echo '<button class="remove-kembali-uang circle-button btn btn-danger btn-flat" type="button"><i class="fa fa-times"></i></button>';
                    }
                    echo '</td>';
                    echo '<tr>';
                  }
                } else {
                  echo '<tr class="empty-table">';
                  echo '<td colspan="5"> </td>';
                  echo '</tr>';
                } ?>
              </tbody>
            </table>
          </div>
        </div>

      </div>

      <div class="col-xs-12 table-responsive">
        <table class="table table-invoice table-bordered">
          <thead>
            <tr>
              <th><strong>Terima Kostum</strong></th>
              <th><strong>Kembali Uang Deposit</strong></th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td><input readonly style="background-color: white !important;" type="text" class="form-control invoice-signature" name="rental_terima_uangdeposit" placeholder="Petugas"></td>
              <td style="position:relative;">
                  <div class="payment">
                    <?php 
                      if(!empty($jenis_transaksi)){
                        foreach($jenis_transaksi as $index => $value){
                          if($value['jenis_transaksi'] == 'return'){
                            echo '<p>'.date('d M Y',strtotime($value['jenis_transaksi_created'])).' - '.$value['jenis_transaksi_flag'].' - Rp. '.number_format($value['jenis_transaksi_nominal']).'</p>';
                          }
                        }
                      }
                    ?>
								  </div>
                <input type="text" class="form-control invoice-signature" placeholder="Penyewa">
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->

    <div class="row sk">
      <!-- accepted payments column -->
      <div class="col-sm-12">
        <h6><strong>Syarat & Ketentuan:</strong></h6>
        <h6>*dengan tanda tangan diatas ini, saya menyatakan sudah membaca dan menyetujui syarat & ketentuan peminjaman dibalik dokumen ini.</h6>
      </div>
    </div>
    <!-- /.row -->

    <!-- this row will not appear when printing -->
    <div class="row no-print">
      <div class="col-xs-12">
        <div class="box-footer inside-box-body clearfix no-border">
          <a id="print-invkembali" style="display:none;"></a>
          <button class="open-form-1 btn btn-primary btn-flat pull-left" type="button"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp; BACK</button>
          <button class="save-return-order btn btn-primary btn-flat pull-right" type="submit" name="save" value="2">PRINT + SAVE</button>
          <button id="submit-form-3" class="save-return-order btn btn-primary btn-flat pull-right" type="submit" style="margin-right: 3px;" name="save" value="1">SAVE</button>
        </div>
      </div>
    </div>
  </section>

  <div class="clearfix"></div>

</form>
</div>

<div class="modal fade" id="modal-scan-camera">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-body">
        <canvas id="canvas" hidden></canvas>
        <div id="output" hidden>
          <div id="outputMessage">No QR code detected.</div>
          <div hidden><b>Data:</b> <span id="outputData"></span></div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary btn-flat close-modal-scan-camera" data-dismiss="modal">Tutup</button>
      </div>
      <div class="overlay">
        <i class="fa fa-refresh fa-spin"></i>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript" src="<?php echo base_url('assets/adminsite/bower_components/jsqrcode/src/grid.js');?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/adminsite/bower_components/jsqrcode/src/version.js');?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/adminsite/bower_components/jsqrcode/src/detector.js');?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/adminsite/bower_components/jsqrcode/src/formatinf.js');?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/adminsite/bower_components/jsqrcode/src/errorlevel.js');?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/adminsite/bower_components/jsqrcode/src/bitmat.js');?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/adminsite/bower_components/jsqrcode/src/datablock.js');?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/adminsite/bower_components/jsqrcode/src/bmparser.js');?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/adminsite/bower_components/jsqrcode/src/datamask.js');?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/adminsite/bower_components/jsqrcode/src/rsdecoder.js');?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/adminsite/bower_components/jsqrcode/src/gf256poly.js');?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/adminsite/bower_components/jsqrcode/src/gf256.js');?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/adminsite/bower_components/jsqrcode/src/decoder.js');?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/adminsite/bower_components/jsqrcode/src/qrcode.js');?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/adminsite/bower_components/jsqrcode/src/findpat.js');?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/adminsite/bower_components/jsqrcode/src/alignpat.js');?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/adminsite/bower_components/jsqrcode/src/databr.js');?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/adminsite/bower_components/jsqrcode/jsQR.js');?>"></script>
