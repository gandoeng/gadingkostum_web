<head>
	<meta charset="utf-8">
	<base id="url" href="<?php echo base_url()?>">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
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

	<?php $this->load->view('v_meta');?>

	<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/front/plugins/semantic-2.3.1/dist/semantic.min.css');?>">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/front/plugins/normalize-v2.css');?>">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/front/plugins/bootstrap/css/bootstrap.min.css');?>">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/front/plugins/jquery-ui/jquery-ui.min.css');?>">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/global/plugins/fontawesome/css/font-awesome.min.css');?>">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/front/plugins/slick/slick/slick.css');?>">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/front/plugins/slick/slick/slick-theme.css');?>">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/front/plugins/drawer/dist/css/drawer.min.css');?>">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/front/plugins/wow/css/libs/animate.css');?>">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/front/plugins/select2/dist/css/select2.min.css');?>">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/front/plugins/datepicker/dist/css/bootstrap-datepicker.min.css');?>">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/front/plugins/datepicker/dist/css/bootstrap-datepicker3.min.css');?>">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/front/plugins/icheck/skins/all.css') ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/front/plugins/icheck/skins/flat/blue.css') ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/front/plugins/icheck/skins/minimal/aero.css') ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/front/plugins/lightbox2/dist/css/lightbox.min.css') ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/front/plugins/loader/dist/app.min.css') ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/front/plugins/pagination/simplePagination.css');?>">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/front/plugins/instalink/instalink-2.1.3.min.css');?>">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/front/css/application.css').'?v1';?>">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/front/css/applicationv2.css').'?v1';?>">
	<script type="text/javascript" src="<?php echo base_url('assets/front/plugins/jquery/jquery.min.js');?>"></script>
	
	<style type="text/css">
		#navbar-header .navbar .dropdown-submenu > .dropdown-menu{
			margin-top: 0;
		}
	</style>
	<?php if($this->uri->segment(1) != 'demo_search' && $this->uri->segment(1) != 'demo_product'){ ?>
	<script type="text/javascript">

		// (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		// 	(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		// 	m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		// })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

		// ga('create', 'UA-89577779-1', 'auto');
		// ga('send', 'pageview');

	</script>
	<?php } ?>
</head>
