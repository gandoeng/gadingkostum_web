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
<div id="form-1" class="content-wrapper">
  <section class="content-header">
    <h1>
      Add New Rental Order
    </h1>
  </section>

  <form id="open-form-2" class="form-horizontal" method="POST" action="<?php echo base_url('adminsite/rental_order/form');?>">
    <input type="hidden" name="rental_order_id" value="">
    <input type="hidden" name="rental_invoice" value="">
    <input type="hidden" name="rental_status" value="">
    <section id="form-1" class="content">
      <div class="row">

        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
          <div class="box box-solid">
            <div class="box-body">
              <div class="form-group">
                <div class="col-lg-12">
                  <select name="customer_id" class="select2-rental-init select2-customer form-control">
                    <option value="0">Select</option>
                    <?php if(!empty($customer)){
                      foreach($customer as $index => $value){
                        echo '<option value="'.$value['customer_id'].'">'.$value['customer_name'].' / '.$value['customer_phone'].'</option>';
                      }
                    } ?>
                  </select>
                </div>
              </div>
              <legend></legend>
              <h4>Personal Information</h4>
              <div class="form-group">
                <div class="col-lg-6">
                  <label>Name</label>
                  <input type="text" name="customer_name" class="form-control">
                  <label style="margin-top: 5px;"><span class="required">*</span>Phone</label>
                  <input type="text" name="customer_phone" class="form-control">
                </div>
                <div class="col-lg-6">
                  <label>Address</label>
                  <textarea style="height: 100px;" name="customer_address" class="form-control"></textarea>
                </div>
              </div>
              <legend></legend>
              <h4>Order</h4>
              <div class="form-group">
                <div class="col-lg-6">
                  <label><span class="required">*</span>Rental Date</label>
                  <input autocomplete="off" type="text" name="start_date" class="datepicker-current form-control">
                </div>
                <div class="col-lg-6">
                  <label><span class="required">*</span>Return Date</label>
                  <input autocomplete="off" type="text" name="end_date" class="datepicker-return form-control">
                </div>
                <div class="col-lg-6">
                  <label style="margin: 10px 0px; width: 100%;"><span class="required" for="selectDelivery">*</span>Delivery Option
                  <select class="form-control" id="selectDelivery" name="selectDelivery" style="margin: 5px 0px;">
                    <option disabled selected>Pilih</option>
                    <option value="sendiri" >Diambil sendiri</option>
                    <option value="gojek">Gojek</option>
                    <option value="jne">JNE</option>
                  </select>
                </div>
              </div>
            </div>

            <div class="box-body">
              <div class="form-group">
                <div class="col-lg-6 col-md-6">
                  <div class="main-product-rental-wrapper">
                  <div class="product-rental-wrapper product-rental-form form">
                      <label><span class="required">*</span>Store Location</label>
                      <select name="store_location_category_id" class="select-store-rental select2-init form-control">
                        <option value="0">Select Store</option>
                        <?php if(!empty($store_location)){
                          foreach($store_location as $index => $value){
                            $selected = '';
                            (!empty($selected_store) && $selected_store == $value['category_id']) ? $selected = 'selected' : '';
                            echo '<option value="'.$value['category_id'].'" '.$selected.'>'.$value['category_name'].'</option>';
                          }
                        }?>
                      </select>
                      <div class="wrapper-select-product">
                        <div class="main-select-product">
                          <label style="margin-top: 5px;">Nama Produk / Kode</label>
                          <select class="select-product-rental select2-init form-control">
                            <?php if(!empty($product)){
                              echo '<option value="0">Select Produk</option>';
                              foreach($product as $index => $value){
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
                      <!-- <div id="search-set-custom" class="btn btn-primary btn-flat pull-right"><i class="fa fa-qrcode"></i>&nbsp;Scan QR</div> -->
                      <div class="search">
                         <input id="foo" type="text" class="form-control" value="" autofocus/>
                      </div>
                      <div id="focus-here"></div>
                    </div>
                  </div>
                </div>

                <div class="col-lg-6 col-md-6">
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
                          <tbody></tbody>
                          <tfoot>
                            <tr class="subtotal">
                              <input type="hidden" name="rental_total_hargasewa" value="0">
                              <input type="hidden" name="rental_total_deposit" value="0">
                              <td colspan="3" style="text-align: right;">Subtotal : </td>
                              <td class="pricetotal_hargasewa" style="text-align: right;">Rp. 0</td>
                              <td class="pricetotal_deposit" style="text-align: right;">Rp. 0</td>
                            </tr>
                            <tr class="total">
                              <input type="hidden" name="rental_total" value="0">
                              <td colspan="3" style="text-align: right;">Total : </td>
                              <td class="all_price" colspan="2" style="text-align: right;"><strong>Rp. 0</strong></td>
                            </tr>
                          </tfoot>
                        </table>

                        <div class="checkbox" style="display: inline-block; margin-left: 5px;">
                          <label><input type="checkbox" name="rental_payment_status" value="unpaid"> Unpaid</label>
                        </div>

                        <div class="checkbox" style="display: inline-block; margin-left: 5px;">
                          <label><input type="checkbox" name="rental_status" value="pickup"> Pickup</label>
                        </div>

                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="box-footer clearfix no-border">
              <a href="<?php echo base_url('adminsite/rental_order');?>" class="btn btn-primary btn-flat pull-left" type="button"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp; CANCEL</a>
              <button class="btn btn-primary btn-flat pull-right" type="submit">SUBMIT</button>
            </div>

            <div class="overlay">
              <i class="fa fa-refresh fa-spin"></i>
            </div>

          </div>
        </div>

      </div>

    </section>

  </form>
</div>

<div id="form-2" class="content-wrapper" style="display:none;">
  <form class="form-2 form-horizontal" method="POST" action="<?php echo base_url('adminsite/rental_order/save');?>">
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
            <div class="store_address"></div>
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

              <!-- Ndung -->
              <label>Delivery Option</label>
              <input readonly id="delivery_option" type="text" name="selectDelivery" class="form-control">
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
          <table id="table-order" class="table-order table table-bordered">
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
            <tbody id="extra-payment"></tbody>
            <tfoot>
              <tr>
                <td id="rentalnoteparent" colspan="5" rowspan="3"><textarea style="text-align:left;" name="rental_note" placeholder="Notes:" class="note form-control"></textarea></td>
                <td style="text-align:center; background-color: #D3D3D3;" id="rental_total_hargasewa"><strong></strong></td>
                <td style="text-align:center; background-color: #D3D3D3;" id="rental_total_deposit"><strong></strong></td>
              </tr>
              <tr>
                <td colspan="2" rowspan="1" style="text-align:center; background-color: #D3D3D3;" id="rental_total"><strong></strong></td>
              </tr>
              <tr>
                <td colspan="2" id="rentalextrapaymentparent" style="padding: 0;"><table style="width: 100%;"></table></td>
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
                      <tr class="empty-table">
                        <td colspan="5"> </td>
                      </tr>
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
                <td><input type="text" class="form-control invoice-signature" name="rental_konfirmasi_booking" placeholder="Penyewa"></td>
                <td style="position:relative;">
                  <!-- <select style="position: absolute;top: 35px;left: 31%;width: auto;margin-right: 8px;" class="form-control" name="rental_terima_uangsewa">
                    <option selected value="">None</option>
                    <option value="cash">Cash</option>
                    <option value="transfer">Transfer</option>
                    <option value="debit">Debit</option>
                  </select> -->
                  <input type="text" class="form-control invoice-signature" placeholder="Petugas">
                </td>
                <td><input type="text" class="form-control invoice-signature" name="rental_terima_kostum" placeholder="Penyewa"></td>
                <td style="position:relative;">
                  <!-- <select style="position: absolute;top: 35px;left: 31%;width: auto;margin-right: 8px;" class="form-control" name="rental_terima_uangdeposit">
                    <option selected value="">None</option>
                    <option value="cash">Cash</option>
                    <option value="transfer">Transfer</option>
                    <option value="debit">Debit</option>
                  </select> -->
                  <input type="text" class="form-control invoice-signature" placeholder="Petugas">
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
            <button class="open-form-1 btn btn-primary btn-flat pull-left" type="button"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;BACK</button>
            <a id="print-invpinjam" style="display:none;"></a>
            <button id="submit-form-3" class="save-order btn btn-primary btn-flat pull-right" type="submit" name="save" value="2">PRINT + SAVE</button>
            <button id="submit-form-2" class="save-order btn btn-primary btn-flat pull-right" type="submit" style="margin-right: 3px;" name="save" value="1">SAVE</button>
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
