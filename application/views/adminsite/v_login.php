<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?php echo (isset($title)) ? 'Admin - '.$title : 'Admin';?></title>
  <!-- Tell the browser to be responsive to screen width -->
  <base href="<?php echo base_url()?>" <?php echo (empty($table_data)) ? '' : 'data-table="'.$table_data.'"';?>>
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="<?php echo base_url('assets/adminsite/bower_components/bootstrap/dist/css/bootstrap.min.css') ?>">
  <!-- Font Awesome -->
  <!-- <link rel="stylesheet" href="<?php echo base_url('assets/adminsite/bower_components/font-awesome/css/font-awesome.min.css'); ?>"> -->
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/adminsite/bower_components/datatables/datatables.min.css');?>">
  <!-- <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/adminsite/bower_components/datatables/css/jqueryui.css');?>"> -->
  <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/adminsite/bower_components/datatables/css/datatables.jqueryui.min.css');?>">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/adminsite/bower_components/datatables/css/buttons.jqueryui.min.css');?>">
  <!-- <link rel="stylesheet" href="https://cdn.datatables.net/1.10.9/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.0.3/css/buttons.dataTables.min.css"> -->
    
  <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/adminsite/bower_components/datatables/css/rowReorder.bootstrap.min.css');?>">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/adminsite/bower_components/pnotify/pnotify.custom.min.css');?>">
  <!-- Ionicons -->
  <link rel="stylesheet" href="<?php echo base_url('assets/adminsite/bower_components/Ionicons/css/ionicons.min.css') ?>">
  <link rel="stylesheet" href="<?php echo base_url('assets/adminsite/bower_components/icheck/skins/all.css') ?>">
  <link rel="stylesheet" href="<?php echo base_url('assets/adminsite/bower_components/icheck/skins/flat/blue.css') ?>">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/adminsite/bower_components/fancybox/dist/jquery.fancybox.min.css')?>">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/adminsite/bower_components/inputmask/css/inputmask.css')?>">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/adminsite/bower_components/spectrum/spectrum.css')?>">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/adminsite/bower_components/select2/dist/css/select2.min.css')?>">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datepicker/dist/css/bootstrap-datepicker.min.css');?>">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo base_url('assets/adminsite/dist/css/AdminLTE.min.css') ?>">
  <link rel="stylesheet" href="<?php echo base_url('assets/adminsite/dist/css/skins/_all-skins.min.css') ?>">
  <link rel="stylesheet" href="<?php echo base_url('assets/adminsite/custom/css/style.css').'?'.md5(date('c'));?>">
  <?php if(isset($css_init) && is_array($css_init)){
    foreach($css_init as $index => $value){
      echo '<link rel="stylesheet" type="text/css" href="'.$value.'">';
    }
  }?>
  <!-- jQuery 3 -->
  <script src="<?php echo base_url('assets/adminsite/bower_components/jquery/dist/jquery.min.js'); ?>"></script>
  <!-- Bootstrap 3.3.7 -->
  <script src="<?php echo base_url('assets/adminsite/bower_components/bootstrap/dist/js/bootstrap.min.js') ?>"></script>
  <!-- FastClick -->
  <script src="<?php echo base_url('assets/adminsite/bower_components/fastclick/lib/fastclick.js') ?>"></script>
  <!-- AdminLTE App -->
  <script src="<?php echo base_url('assets/adminsite/dist/js/adminlte.min.js') ?>"></script>
  <!-- Sparkline -->
  <script src="<?php echo base_url('assets/adminsite/bower_components/jquery-sparkline/dist/jquery.sparkline.min.js') ?>"></script>
  <!-- jvectormap  -->
<!--<script src="<?php echo base_url('assets/adminsite/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js'); ?>"></script>
  <script src="<?php echo base_url('assets/adminsite/plugins/jvectormap/jquery-jvectormap-world-mill-en.js'); ?>"></script>-->
  <!-- SlimScroll -->
  <script src="<?php echo base_url('assets/adminsite/bower_components/jquery-slimscroll/jquery.slimscroll.min.js') ?>"></script>
  <!-- ChartJS -->
  <script src="<?php echo base_url('assets/adminsite/bower_components/chart.js/Chart.js') ?>"></script>
  <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
  <!-- <script type="text/javascript" src="https://code.jquery.com/jquery-1.11.3.min.js"></script> -->
  <script type="text/javascript" src="<?php echo base_url('assets/adminsite/bower_components/datatables/datatables.min.js')?>"></script>
  <script type="text/javascript" src="<?php echo base_url('assets/adminsite/bower_components/datatables/natural.js')?>"></script>
  <script type="text/javascript" src="<?php echo base_url('assets/adminsite/bower_components/datatables/numeric-comma.js')?>"></script>
  <script type="text/javascript" src="<?php echo base_url('assets/adminsite/bower_components/datatables/num-html.js')?>"></script>
  <script type="text/javascript" src="<?php echo base_url('assets/adminsite/bower_components/datatables/formatted-numbers.js')?>"></script>
  <script type="text/javascript" src="<?php echo base_url('assets/adminsite/bower_components/datatables/any-number.js')?>"></script>
  <script type="text/javascript" src="<?php echo base_url('assets/adminsite/bower_components/datatables/date-de.js')?>"></script>
  <script type="text/javascript" src="<?php echo base_url('assets/adminsite/bower_components/datatables/moment.js')?>"></script>
  <script type="text/javascript" src="<?php echo base_url('assets/adminsite/bower_components/datatables/datetime-locales.js')?>"></script>
<!--   <script type="text/javascript" src="https://cdn.datatables.net/1.10.9/js/jquery.dataTables.min.js"></script> -->
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.0.3/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.0.3/js/buttons.html5.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.0.3/js/buttons.print.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.0.3/js/buttons.flash.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
    <script type="text/javascript" src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js"></script>
    <script type="text/javascript" src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js"></script>

  <!-- <script type="text/javascript" src="<?php echo base_url('assets/adminsite/bower_components/datatables/datatables.button.min.js')?>"></script>
  <script type="text/javascript" src="<?php echo base_url('assets/adminsite/bower_components/datatables/buttons.html5.min.js')?>"></script>
  <script type="text/javascript" src="<?php echo base_url('assets/adminsite/bower_components/datatables/buttons.print.min.js')?>"></script>
  <script type="text/javascript" src="<?php echo base_url('assets/adminsite/bower_components/datatables/buttons.flash.min.js')?>"></script>
  <script type="text/javascript" src="<?php echo base_url('assets/adminsite/bower_components/datatables/jszip.min.js')?>"></script>
  <script type="text/javascript" src="<?php echo base_url('assets/adminsite/bower_components/datatables/pdfmake.min.js')?>"></script>
  <script type="text/javascript" src="<?php echo base_url('assets/adminsite/bower_components/datatables/vfs_fonts.js')?>"></script> -->

  <script type="text/javascript" src="<?php echo base_url('assets/adminsite/bower_components/datatables/js/dataTables.rowReorder.min.js')?>"></script>

  <script type="text/javascript" src="<?php echo base_url('assets/adminsite/bower_components/pnotify/pnotify.custom.min.js');?>"></script>

  <script type="text/javascript" src="<?php echo base_url('assets/adminsite/bower_components/icheck/icheck.min.js');?>"></script>

  <script type="text/javascript" src="<?php echo base_url('assets/adminsite/bower_components/fancybox/dist/jquery.fancybox.min.js')?>"></script>

  <script type="text/javascript" src="<?php echo base_url('assets/adminsite/bower_components/inputmask/dist/inputmask/inputmask.js')?>"></script>
  <script type="text/javascript" src="<?php echo base_url('assets/adminsite/bower_components/inputmask/dist/inputmask/inputmask.extensions.js')?>"></script>
  <script type="text/javascript" src="<?php echo base_url('assets/adminsite/bower_components/inputmask/dist/inputmask/inputmask.numeric.extensions.js')?>"></script>
  <script type="text/javascript" src="<?php echo base_url('assets/adminsite/bower_components/inputmask/dist/inputmask/inputmask.date.extensions.js')?>"></script>
  <script type="text/javascript" src="<?php echo base_url('assets/adminsite/bower_components/inputmask/dist/inputmask/jquery.inputmask.js')?>"></script>
  <script type="text/javascript" src="<?php echo base_url('assets/adminsite/bower_components/spectrum/spectrum.js')?>"></script>
  <script type="text/javascript" src="<?php echo base_url('assets/adminsite/bower_components/select2/dist/js/select2.min.js')?>"></script>
  <script type="text/javascript" src="<?php echo base_url('assets/front/plugins/jquery.matchHeight.min.js')?>"></script>
  <script type="text/javascript" src="<?php echo base_url('assets/global/plugins/datepicker/dist/js/bootstrap-datepicker.js').'?'.md5(date('c'));?>"></script>
  <script type="text/javascript" src="<?php echo base_url('assets/adminsite/bower_components/printpage/jquery.printPage.js').'?'.md5(date('c'));?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/adminsite/bower_components/tinymce/js/tinymce/tinymce.min.js');?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/adminsite/bower_components/jquery-sortable.js');?>"></script>
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet"
  href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
  <style type="text/css">
      .store_address{
        display:block;
        width: 100%;
      }
  </style>
</head>
<body class="hold-transition login-page">
  <div class="login-box">
    <div class="login-logo">
      <a><b>Gading Kostum</b></a>
    </div>
    <div class="login-box-body">
      <!-- <?php if(validation_errors()){
        echo '<div class="alert alert-danger alert-dismissible"><h5>';
        echo '<i class="icon fa fa-ban"></i> Something Happened !</h5><h6>';
        echo validation_errors();
        echo '</h6></div>'; } ?> -->

        <?php if(isset($error_message)){ echo $error_message; } ?>

        <?php if($this->session->flashdata('error_message') != NULL){ echo $this->session->flashdata('error_message'); } ?>

        <form method="post" action="<?php echo base_url('adminsite/auth/login')?>">
          <div class="form-group has-feedback">
            <input type="text" name="username" class="form-control" placeholder="Username">
          </div>
          <div class="form-group has-feedback">
            <input type="password" name="password" class="form-control" placeholder="Password">
          </div>
          <div class="row">
            <div class="col-xs-4">
              <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </body>
</html>