<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?php echo (isset($title)) ? 'Admin - '.$title : 'Admin';?></title>
  <base href="<?php echo base_url()?>" <?php echo (empty($table_data)) ? '' : 'data-table="'.$table_data.'"';?>>
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="shortcut icon" href="<?php echo base_url('assets/images/favicon/favicon.ico');?>" type="image/x-icon" />
  <link rel="apple-touch-icon" sizes="57x57" href="<?php echo base_url('assets/images/favicon/apple-icon-57x57.png');?>">
  <link rel="apple-touch-icon" sizes="60x60" href="<?php echo base_url('assets/images/favicon/apple-icon-60x60.png');?>">
  <link rel="apple-touch-icon" sizes="72x72" href="<?php echo base_url('assets/images/favicon/apple-icon-72x72.png');?>">
  <link rel="apple-touch-icon" sizes="76x76" href="<?php echo base_url('assets/images/favicon/apple-icon-76x76.png');?>">
  <link rel="apple-touch-icon" sizes="114x114" href="<?php echo base_url('assets/images/favicon/apple-icon-114x114.png');?>">
  <link rel="apple-touch-icon" sizes="120x120" href="<?php echo base_url('assets/images/favicon/apple-icon-120x120.png');?>">
  <link rel="apple-touch-icon" sizes="144x144" href="<?php echo base_url('assets/images/favicon/apple-icon-144x144.png');?>">
  <link rel="apple-touch-icon" sizes="152x152" href="<?php echo base_url('assets/images/favicon/apple-icon-152x152.png');?>">
  <link rel="apple-touch-icon" sizes="180x180" href="<?php echo base_url('assets/images/favicon/apple-icon-180x180.png');?>">
  <link rel="icon" type="image/png" sizes="192x192"  href="<?php echo base_url('assets/images/favicon/android-icon-192x192.png');?>">
  <link rel="icon" type="image/png" sizes="32x32" href="<?php echo base_url('assets/images/favicon/favicon-32x32.png');?>">
  <link rel="icon" type="image/png" sizes="96x96" href="<?php echo base_url('assets/images/favicon/favicon-96x96.png');?>">
  <link rel="icon" type="image/png" sizes="16x16" href="<?php echo base_url('assets/images/favicon/favicon-16x16.png');?>">
  <link rel="manifest" href="<?php echo base_url('assets/images/favicon/manifest.json');?>">
  <meta name="msapplication-TileColor" content="#ffffff">
  <meta name="msapplication-TileImage" content="<?php echo base_url('assets/images/favicon/ms-icon-144x144.png');?>">
  <meta name="theme-color" content="#ffffff">
  
  <link rel="stylesheet" href="<?php echo base_url('assets/adminsite/bower_components/bootstrap/dist/css/bootstrap.min.css') ?>">
  <link rel="stylesheet" href="<?php echo base_url('assets/adminsite/bower_components/fontawesome5.9.0/css/all.min.css') ?>">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/adminsite/bower_components/datatables/datatables.min.css');?>">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/adminsite/bower_components/datatables/css/datatables.jqueryui.min.css');?>">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/adminsite/bower_components/datatables/css/buttons.jqueryui.min.css');?>">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/adminsite/bower_components/datatables/css/rowReorder.bootstrap.min.css');?>">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/adminsite/bower_components/pnotify/pnotify.custom.min.css');?>">
  <!-- Ionicons -->
  <link rel="stylesheet" href="<?php echo base_url('assets/adminsite/bower_components/Ionicons/css/ionicons.min.css') ?>">
  <link rel="stylesheet" href="<?php echo base_url('assets/adminsite/bower_components/icheck/skins/all.css') ?>">
  <link rel="stylesheet" href="<?php echo base_url('assets/adminsite/bower_components/icheck/skins/flat/blue.css') ?>">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/adminsite/bower_components/fancybox/3.5/dist/jquery.fancybox.min.css')?>">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/adminsite/bower_components/inputmask/css/inputmask.css')?>">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/adminsite/bower_components/spectrum/spectrum.css')?>">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/adminsite/bower_components/select2/dist/css/select2.min.css')?>">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/datepicker/dist/css/bootstrap-datepicker.min.css');?>">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo base_url('assets/adminsite/dist/css/AdminLTE.min.css') ?>">
  <link rel="stylesheet" href="<?php echo base_url('assets/adminsite/dist/css/skins/_all-skins.min.css') ?>">
  <link rel="stylesheet" href="<?php echo base_url('assets/adminsite/custom/css/style.css').'?v1.1';?>">
  <?php if(isset($css_init) && is_array($css_init)){
    foreach($css_init as $index => $value){
      echo '<link rel="stylesheet" type="text/css" href="'.$value.'">';
    }
  }?>

  <script src="<?php echo base_url('assets/adminsite/bower_components/jquery/dist/jquery.min.js'); ?>"></script>
  <script src="<?php echo base_url('assets/adminsite/bower_components/bootstrap/dist/js/bootstrap.min.js') ?>"></script>
  <script src="<?php echo base_url('assets/adminsite/bower_components/fastclick/lib/fastclick.js') ?>"></script>
  <script src="<?php echo base_url('assets/adminsite/dist/js/adminlte.min.js') ?>"></script>
  <script src="<?php echo base_url('assets/adminsite/bower_components/jquery-sparkline/dist/jquery.sparkline.min.js') ?>"></script>
  <script src="<?php echo base_url('assets/adminsite/bower_components/jquery-slimscroll/jquery.slimscroll.min.js') ?>"></script>
  <script src="<?php echo base_url('assets/adminsite/bower_components/chart.js/Chart.js') ?>"></script>

  <script type="text/javascript" src="<?php echo base_url('assets/adminsite/bower_components/datatables/datatables.min.js')?>"></script>
  <script src="//cdn.rawgit.com/ashl1/datatables-rowsgroup/v1.0.0/dataTables.rowsGroup.js"></script>
  <script type="text/javascript" src="<?php echo base_url('assets/adminsite/bower_components/datatables/natural.js')?>"></script>
  <script type="text/javascript" src="<?php echo base_url('assets/adminsite/bower_components/datatables/numeric-comma.js')?>"></script>
  <script type="text/javascript" src="<?php echo base_url('assets/adminsite/bower_components/datatables/num-html.js')?>"></script>
  <script type="text/javascript" src="<?php echo base_url('assets/adminsite/bower_components/datatables/formatted-numbers.js')?>"></script>
  <script type="text/javascript" src="<?php echo base_url('assets/adminsite/bower_components/datatables/any-number.js')?>"></script>
  <script type="text/javascript" src="<?php echo base_url('assets/adminsite/bower_components/datatables/date-de.js')?>"></script>
  <script type="text/javascript" src="<?php echo base_url('assets/adminsite/bower_components/datatables/moment.js')?>"></script>
  <script type="text/javascript" src="<?php echo base_url('assets/adminsite/bower_components/datatables/datetime-locales.js')?>"></script>

  <script type="text/javascript" src="<?php echo base_url('assets/adminsite/bower_components/datatables/buttons/1.0.3/js/dataTables.buttons.min.js'); ?>"></script>
  <script type="text/javascript" src="<?php echo base_url('assets/adminsite/bower_components/datatables/buttons/1.0.3/js/buttons.html5.min.js'); ?>"></script>
  <script type="text/javascript" src="<?php echo base_url('assets/adminsite/bower_components/datatables/buttons/1.0.3/js/buttons.print.min.js'); ?>"></script>
  <script type="text/javascript" src="<?php echo base_url('assets/adminsite/bower_components/datatables/buttons/1.0.3/js/buttons.flash.min.js'); ?>"></script>
  <script type="text/javascript" src="<?php echo base_url('assets/adminsite/bower_components/jszip/jszip.min.js'); ?>"></script>
  <script type="text/javascript" src="<?php echo base_url('assets/adminsite/bower_components/pdfmake/pdfmake.min.js').'?v1.0';?>"></script>
  <script type="text/javascript" src="<?php echo base_url('assets/adminsite/bower_components/pdfmake/vfs_fonts.js'); ?>"></script>
  <script type="text/javascript" src="<?php echo base_url('assets/adminsite/bower_components/datatables/js/dataTables.rowReorder.min.js')?>"></script>
  <script type="text/javascript" src="<?php echo base_url('assets/adminsite/bower_components/pnotify/pnotify.custom.min.js').'?v1.0';?>"></script>
  <script type="text/javascript" src="<?php echo base_url('assets/adminsite/bower_components/icheck/icheck.min.js');?>"></script>
  <script type="text/javascript" src="<?php echo base_url('assets/adminsite/bower_components/fancybox/3.5/dist/jquery.fancybox.min.js')?>"></script>
  <script type="text/javascript" src="<?php echo base_url('assets/adminsite/bower_components/inputmask/dist/inputmask/inputmask.js')?>"></script>
  <script type="text/javascript" src="<?php echo base_url('assets/adminsite/bower_components/inputmask/dist/inputmask/inputmask.extensions.js')?>"></script>
  <script type="text/javascript" src="<?php echo base_url('assets/adminsite/bower_components/inputmask/dist/inputmask/inputmask.numeric.extensions.js')?>"></script>
  <script type="text/javascript" src="<?php echo base_url('assets/adminsite/bower_components/inputmask/dist/inputmask/inputmask.date.extensions.js')?>"></script>
  <script type="text/javascript" src="<?php echo base_url('assets/adminsite/bower_components/inputmask/dist/inputmask/jquery.inputmask.js')?>"></script>
  <script type="text/javascript" src="<?php echo base_url('assets/adminsite/bower_components/spectrum/spectrum.js')?>"></script>
  <script type="text/javascript" src="<?php echo base_url('assets/adminsite/bower_components/select2/dist/js/select2.min.js')?>"></script>
  <script type="text/javascript" src="<?php echo base_url('assets/front/plugins/jquery.matchHeight.min.js')?>"></script>
  <script type="text/javascript" src="<?php echo base_url('assets/global/plugins/datepicker/dist/js/bootstrap-datepicker.js').'?v1.0';?>"></script>
  <script type="text/javascript" src="<?php echo base_url('assets/adminsite/bower_components/printpage/jquery.printPage.js').'?v1.0';?>"></script>
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
  <style type="text/css">
  .store_address{
    display:block;
    width: 100%;
  }
  #search {
    position: fixed;
    top: 0px;
    left: 0px;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.7);
    
    -webkit-transition: all 0.5s ease-in-out;
    -moz-transition: all 0.5s ease-in-out;
    -o-transition: all 0.5s ease-in-out;
    -ms-transition: all 0.5s ease-in-out;
    transition: all 0.5s ease-in-out;

    -webkit-transform: translate(0px, -100%) scale(0, 0);
    -moz-transform: translate(0px, -100%) scale(0, 0);
    -o-transform: translate(0px, -100%) scale(0, 0);
    -ms-transform: translate(0px, -100%) scale(0, 0);
    transform: translate(0px, -100%) scale(0, 0);
    
    opacity: 0;
    z-index: 9999;
  }

  #search.open {
    -webkit-transform: translate(0px, 0px) scale(1, 1);
    -moz-transform: translate(0px, 0px) scale(1, 1);
    -o-transform: translate(0px, 0px) scale(1, 1);
    -ms-transform: translate(0px, 0px) scale(1, 1);
    transform: translate(0px, 0px) scale(1, 1); 
    opacity: 1;
  }

  #search input[type="search"] {
    position: absolute;
    top: 50%;
    width: 100%;
    color: rgb(255, 255, 255);
    background: rgba(0, 0, 0, 0);
    font-size: 60px;
    font-weight: 300;
    text-align: center;
    border: 0px;
    margin: 0px auto;
    margin-top: -51px;
    padding-left: 30px;
    padding-right: 30px;
    outline: none;
  }
  #search .btn {
    position: absolute;
    top: 50%;
    left: 50%;
    margin-top: 61px;
    margin-left: -45px;
  }
  #search .close {
    position: fixed;
    top: 15px;
    right: 15px;
    color: #fff;
    background-color: #428bca;
    border-color: #357ebd;
    opacity: 1;
    padding: 10px 17px;
    font-size: 27px;
  }
</style>
</head>
<body class="hold-transition skin-blue fixed">
  <div class="wrapper">
    <header class="main-header">
      <a target="_blank" href="<?php echo base_url();?>" class="logo">
        <span class="logo-lg"><img src="<?php echo base_url('assets/images/logo-gading-small.png') ?>"></span>
      </a>

      <!-- Header Navbar: style can be found in header.less -->
      <nav class="navbar navbar-static-top">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
          <i class="fas fa-bars"></i>
        </a>
        <!-- Navbar Right Menu -->
        <div class="navbar-custom-menu">

          <ul class="nav navbar-nav">

            <!-- User Account: style can be found in dropdown.less -->

            <li class="dropdown user user-menu">

              <a href="<?php echo base_url('adminsite/logout')?>" class="dropdown-toggle">

                <span class="hidden-xs" style="margin-right:10px;">Hi, <?php echo ucfirst($session_items['username_adm']);?></span>

                <i class="fa fa-power-off"></i>

              </a>

            </li>

          </ul>

        </div>

      </nav>
    </header>
    <!-- Left side column. contains the logo and sidebar -->
    <?php $this->load->view('adminsite/section/navigation'); ?>

    <!-- Content Wrapper. Contains page content -->
    <?php $this->load->view($load_view); ?>
    <!-- /.content-wrapper -->

    <footer class="main-footer">
      <div class="pull-right hidden-xs">
        <!-- <b>Version</b> 2.4.0 -->
      </div>
      <strong>Copyright &copy; 2018 </strong> All rights
      reserved.
    </footer>
  </div>

  <div id="search">
    <button type="button" class="close">×</button>
    <input autofocus class="scan-qr scan-input" type="search" value="" placeholder="scan" style="color: transparent;" />
    <input hidden class="scan-qr scan-value"/>
  </div>

  <!-- ./wrapper -->
  <?php if(isset($js_init) && is_array($js_init)){
    foreach($js_init as $index => $value){
     echo '<script type="text/javascript" src="'.$value.'"></script>';
   }
 }?>
 <?php $this->load->view('adminsite/template/datatables_roworder'); ?>

 <script type="text/javascript" src="<?php echo base_url('assets/adminsite/bower_components/scanner-detection/jquery.scannerdetection.compatibility.js');?>"></script>
 <script type="text/javascript" src="<?php echo base_url('assets/adminsite/bower_components/scanner-detection/jquery.scannerdetection.js');?>"></script>
<!--  <script type="text/javascript" src="<?php echo base_url('assets/adminsite/custom/js/application.js').'?cache='.md5(date('c'));?>"></script> -->
 <script type="text/javascript" src="<?php echo base_url('assets/adminsite/custom/js/application.js').'?cache=25_02_2022'.time();?>"></script>
 <script type="text/javascript" src="<?php echo base_url('assets/adminsite/custom/js/custom.js').'?cache=25_03_2022'.time();?>"></script>
 <?php $this->load->view('adminsite/template/notification'); ?>
 <script type="text/javascript">
  $(function(){
    $('.form-datepicker').datepicker({
      format: 'dd/mm/yyyy',
      autoclose: true,
    });
  });
</script>
</body>
</html>
