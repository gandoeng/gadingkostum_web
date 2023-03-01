    <aside class="main-sidebar">
      <!-- sidebar: style can be found in sidebar.less -->
      <section class="sidebar">
        <!-- Sidebar user panel -->
        <!-- search form -->
        <!-- /.search form -->
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu" data-widget="tree">
          <?php


          if($session_items['username_adm'] == 'fatih'){
              //id_adm = 11;
              //username_adm = fatih;
            /*echo '<pre>';
            print_r($session_items);
            echo '</pre>';*/
          }
          ?>
          <?php if(isset($session_items) && !empty($session_items) && $session_items['role'] == 'kasir' || $session_items['role'] == 'admin'){ ?>
          <li><a href="<?php echo base_url('adminsite/dashboard') ?>"><i class="fas fa-tachometer-alt"></i> <span>Dashboard</span></a></li>
          <li><a href="<?php echo base_url('adminsite/set_custom');?>"><i class="far fa-dot-circle"></i> <span>Set Kostum</span></a></li>
          <li><a href="<?php echo base_url('adminsite/buku_kas');?>"><i class="far fa-dot-circle"></i> <span>Buku Kas</span></a></li>
          <li><a href="<?php echo base_url('adminsite/daily_report');?>"><i class="far fa-dot-circle"></i> <span>Daily Report</span></a></li>
          <li><a href="<?php echo base_url('adminsite/catalog');?>"><i class="far fa-dot-circle"></i> <span>Katalog & Tag</span></a></li>
          <?php } ?>

          <?php if(isset($session_items) && !empty($session_items) && $session_items['role'] == 'admin'){ ?>
          <li><a href="<?php echo base_url('adminsite/report?sales_by=date&filter_by=year');?>"><i class="far fa-dot-circle"></i> <span>Report</span></a></li>
          <?php } ?>

          <?php if(isset($session_items) && !empty($session_items) && $session_items['role'] == 'kasir' || $session_items['role'] == 'admin'){ ?>
          <li><a href="<?php echo base_url('adminsite/rental_order');?>"><i class="fas fa-shopping-cart"></i> <span>Rental Order</span></a></li>
          <li><a href="<?php echo base_url('adminsite/return_list');?>"><i class="far fa-dot-circle"></i> <span>Booking List</span></a></li>
          <li><a href="<?php echo base_url('adminsite/stock_list');?>"><i class="far fa-dot-circle"></i> <span>Stock List</span></a></li>

          <!-- Ndung -->
          <li><a href="<?php echo base_url('adminsite/rental_order_backup');?>"><i class="far fa-dot-circle"></i> <span>Rental Order Backup</span></a></li>

          <?php } ?>


          <?php if(isset($session_items) && !empty($session_items) && $session_items['role'] == 'admin'){ ?>
          <li><a href="<?php echo base_url('adminsite/product');?>"><i class="fa fa-tshirt"></i> Product</a></li>
          <li><a href="<?php echo base_url('adminsite/product_category');?>"><i class="far fa-dot-circle"></i> Product Categories</a></li>
          <li><a href="<?php echo base_url('adminsite/size_category');?>"><i class="far fa-dot-circle"></i> <span>Size Category</span></a></li>
          <li><a href="<?php echo base_url('adminsite/gender_category');?>"><i class="far fa-dot-circle"></i> <span>Gender Category</span></a></li>
          <li><a href="<?php echo base_url('adminsite/store_location');?>"><i class="fas fa-map-marker-alt"></i> <span>Store Location</span></a></li>
          <li><a href="<?php echo base_url('adminsite/slideshow');?>"><i class="far fa-dot-circle"></i> <span>Slideshow</span></a></li>
          <li><a href="<?php echo base_url('adminsite/article');?>"><i class="far fa-dot-circle"></i> <span>Article</span></a></li>
          <li><a href="<?php echo base_url('adminsite/pages');?>"><i class="far fa-file"></i> <span>Pages</span></a></li>
          <li><a href="<?php echo base_url('adminsite/testimonial');?>"><i class="far fa-dot-circle"></i> <span>Testimonial</span></a></li>
          <li><a href="<?php echo base_url('adminsite/correction');?>"><i class="fas fa-cog"></i> <span>Correction</span></a></li>
          <li><a href="<?php echo base_url('adminsite/setting');?>"><i class="fas fa-cog"></i> <span>Setting</span></a></li>
          <li><a href="<?php echo base_url('adminsite/user');?>"><i class="fas fa-cog"></i> <span>User</span></a></li>
          <?php } ?>
        </ul>
      </section>
      <!-- /.sidebar -->
    </aside>