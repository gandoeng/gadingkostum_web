<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/adminsite/custom/css/catalog2.css').'?'.md5(date('c'));?>">
<style type="text/css">
  .input-group-btn{
    display:inline-block;
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
  #loadingMessage {
    text-align: center;
    padding: 40px;
    background-color: #eee;
  }

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
  #table-set-custom thead tr th:nth-child(1){
    width: 250px;
  }
  #table-set-custom thead tr th:nth-child(2){
    width: 150px;
  }
  #table-set-custom thead tr th:nth-child(3) textarea{
    width: 244px;
  }
   #table-set-custom tbody tr td:nth-child(3) textarea{
    width: 244px;
  }
  #table-set-custom thead tr th:nth-child(4){
    width: 77px;
  }
  @media (max-width: 1199px){
    .set-custom-produk-box label{
      display: block;
      margin: 0;
      margin-bottom: 10px;
    }
  }
  @media (max-width: 991px){
    #table-set-custom tbody tr td{
      text-align: left;
      padding: 10px !important;
      font-size: 13px;
    }
    #table-set-custom thead tr th:nth-child(1){
    width: auto;
  }
  #table-set-custom thead tr th:nth-child(2){
    width: auto;
  }
  #table-set-custom thead tr th:nth-child(3){
    width: 300px;
  }
   #table-set-custom tbody tr td:nth-child(3) textarea{
    width: 300px;
  }
  #table-set-custom thead tr th:nth-child(4){
    width:auto;
  }
  }
  @media (max-width: 767px){
    .main-header .navbar-custom-menu {
     float: none; 
   }
 }
</style>

<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Add New Set Kostum
      <!-- <small>Version 2.0</small> -->
    </h1>
  </section>

  <!-- Main content -->
  <form class="form-horizontal" method="POST" action="<?php echo base_url('adminsite/set_custom/add');?>">
    <section class="content">
      <div class="row">

        <div class="col-xs-12">
          <div class="box box-solid">
            <div class="box-body">
              <?php
              if($this->session->flashdata('validation')){
                echo '<div class="alert alert-warning" role="alert">';
                echo $this->session->flashdata('validation');
                echo '</div>';
              }
              ?>
              <div class="col-lg-3">
                <div class="form-group">
                  <label>Pilih Karyawan</label>
                  <select class="form-control" name="karyawan_id">
                    <option value="">Pilih</option>';
                    <?php
                    if(!empty($karyawan)){
                      foreach($karyawan as $index => $value){
                        echo '<option value="'.$value['karyawan_id'].'">'.$value['karyawan_nama'].'</option>';
                      }
                    }
                    ?>
                  </select>
                </div>
              </div>

              <div class="col-lg-12" style="padding: 0;">
                <div class="row">
                  <div class="col-lg-12">
                    <button type="button" id="search-set-custom" class="btn btn-primary btn-flat"><i class="fa fa-qrcode"></i>&nbsp;Scan QR</button>
                  </div>
                  <div class="col-lg-3">
                    <div class="set-custom-produk-box">
                      <label style="margin-top: 5px;"><span class="required">*</span> Nama Produk / Kode</label>
                      <select class="select-product-set-custom select2-init form-control" name="get_product_id">
                        <?php if(!empty($product)){
                          echo '<option value="">Select Produk</option>';
                          foreach($product as $index => $value){
                            echo '<option value="'.$value['product_id'].'">'.$value['product_nama'].' / '.$value['product_kode'].'</option>';
                          }
                        } ?>
                      </select>
                    </div>
                  </div>

                  <div class="col-lg-3">
                    <div class="set-custom-produk-box">
                      <label style="margin-top: 5px;"><span class="required">*</span> Size</label>
                      <select class="select-product-size-set-custom select2-init form-control" name="get_product_sizestock_id"></select>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-lg-3">
                    <label style="margin-top: 5px;">Note</label>
                    <textarea class="form-control" name="note" style="height: 150px;"></textarea>
                  </div>

                  <div class="col-sm-12" style="margin-top: 5px;">
                    <button id="add-item-set-custom" class="btn btn-primary btn-flat" type="button"><i class="fa fa-plus"></i> ADD ITEM</button>
                  </div>
                </div>

                <div class="col-lg-12" style="margin-top: 5px;">
                  <div class="form-group">
                    <label style="width: 100%; display:block;">Items</label>
                    <div class="table-responsive">
                      <table id="table-set-custom" class="text-center table table-hover table-bordered table-striped table-align-middle">
                        <thead>
                          <tr>
                            <th>Nama / Kode</th>
                            <th>Size</th>
                            <th>Note</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody></tbody>
                      </table>
                    </div>
                  </div>
                </div>

              </div>

            </div>

            <div class="box-footer clearfix no-border">
              <div class="col-sm-4" style="padding-left: 0; padding-right: 0;">
                <a class="btn btn-primary btn-flat" type="button" href="<?php echo base_url('adminsite/set_custom');?>"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp; CANCEL</a>
                <button class="btn btn-primary btn-flat" type="submit">SAVE</button>
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