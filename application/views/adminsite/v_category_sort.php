 <style type="text/css">
   /* line 1, /Users/jonasvonandrian/jquery-sortable/source/css/jquery-sortable.css.sass */
   body.dragging, body.dragging * {
    cursor: move !important; }

    /* line 4, /Users/jonasvonandrian/jquery-sortable/source/css/jquery-sortable.css.sass */
    .dragged {
      position: absolute;
      top: 0;
      opacity: 0.5;
      z-index: 2000; }

      /* line 10, /Users/jonasvonandrian/jquery-sortable/source/css/jquery-sortable.css.sass */
      ol.vertical {
        margin: 0;
    min-height: 10px;
    padding-left: 0; }
        /* line 13, /Users/jonasvonandrian/jquery-sortable/source/css/jquery-sortable.css.sass */
        ol.vertical li {
          display: block;
          margin: 5px;
          padding: 5px;
          border: 1px solid #cccccc;
          color: #0088cc;
          background: #eeeeee; }
          /* line 20, /Users/jonasvonandrian/jquery-sortable/source/css/jquery-sortable.css.sass */
          ol.vertical li.placeholder {
            position: relative;
            margin: 0;
            padding: 0;
            border: none; }
            /* line 25, /Users/jonasvonandrian/jquery-sortable/source/css/jquery-sortable.css.sass */
            ol.vertical li.placeholder:before {
              position: absolute;
              content: "";
              width: 0;
              height: 0;
              margin-top: -5px;
              left: -5px;
              top: -4px;
              border: 5px solid transparent;
              border-left-color: red;
              border-right: none; }

              /* line 32, /Users/jonasvonandrian/jquery-sortable/source/css/application.css.sass */
              ol {
                list-style-type: none; }
                /* line 34, /Users/jonasvonandrian/jquery-sortable/source/css/application.css.sass */
                ol i.icon-move {
                  cursor: pointer; }
                  /* line 36, /Users/jonasvonandrian/jquery-sortable/source/css/application.css.sass */
                  ol li.highlight {
                    background: #333333;
                    color: #999999; }
                    /* line 39, /Users/jonasvonandrian/jquery-sortable/source/css/application.css.sass */
                    ol li.highlight i.icon-move {
                      background-image: url("../img/glyphicons-halflings-white.png"); }

                      /* line 42, /Users/jonasvonandrian/jquery-sortable/source/css/application.css.sass */
                      ol.nested_with_switch, ol.nested_with_switch ol {
                        border: 1px solid #eeeeee; }
                        /* line 44, /Users/jonasvonandrian/jquery-sortable/source/css/application.css.sass */
                        ol.nested_with_switch.active, ol.nested_with_switch ol.active {
                          border: 1px solid #333333; }

                          /* line 48, /Users/jonasvonandrian/jquery-sortable/source/css/application.css.sass */
                          ol.nested_with_switch li, ol.simple_with_animation li, ol.serialization li, ol.default li {
                            cursor: pointer; }

                            /* line 51, /Users/jonasvonandrian/jquery-sortable/source/css/application.css.sass */
                            ol.simple_with_animation {
                              border: 1px solid #999999; }

                              /* line 54, /Users/jonasvonandrian/jquery-sortable/source/css/application.css.sass */
                              .switch-container {
                                display: block;
                                margin-left: auto;
                                margin-right: auto;
                                width: 80px; }

                                /* line 60, /Users/jonasvonandrian/jquery-sortable/source/css/application.css.sass */
                                .navbar-sort-container {
                                  height: 200px; }

                                  /* line 64, /Users/jonasvonandrian/jquery-sortable/source/css/application.css.sass */
                                  ol.nav li, ol.nav li a {
                                    cursor: pointer; }
                                    /* line 66, /Users/jonasvonandrian/jquery-sortable/source/css/application.css.sass */
                                    ol.nav .divider-vertical {
                                      cursor: default; }
                                      /* line 69, /Users/jonasvonandrian/jquery-sortable/source/css/application.css.sass */
                                      ol.nav li.dragged {
                                        background-color: #2c2c2c; }
                                        /* line 71, /Users/jonasvonandrian/jquery-sortable/source/css/application.css.sass */
                                        ol.nav li.placeholder {
                                          position: relative; }
                                          /* line 73, /Users/jonasvonandrian/jquery-sortable/source/css/application.css.sass */
                                          ol.nav li.placeholder:before {
                                            content: "";
                                            position: absolute;
                                            width: 0;
                                            height: 0;
                                            border: 5px solid transparent;
                                            border-top-color: red;
                                            top: -6px;
                                            margin-left: -5px;
                                            border-bottom: none; }
                                            /* line 84, /Users/jonasvonandrian/jquery-sortable/source/css/application.css.sass */
                                            ol.nav ol.dropdown-menu li.placeholder:before {
                                              border: 5px solid transparent;
                                              border-left-color: red;
                                              margin-top: -5px;
                                              margin-left: none;
                                              top: 0;
                                              left: 10px;
                                              border-right: none; }

                                              /* line 94, /Users/jonasvonandrian/jquery-sortable/source/css/application.css.sass */
                                              .sorted_table tr {
                                                cursor: pointer; }
                                                /* line 96, /Users/jonasvonandrian/jquery-sortable/source/css/application.css.sass */
                                                .sorted_table tr.placeholder {
                                                  display: block;
                                                  background: red;
                                                  position: relative;
                                                  margin: 0;
                                                  padding: 0;
                                                  border: none; }
                                                  /* line 103, /Users/jonasvonandrian/jquery-sortable/source/css/application.css.sass */
                                                  .sorted_table tr.placeholder:before {
                                                    content: "";
                                                    position: absolute;
                                                    width: 0;
                                                    height: 0;
                                                    border: 5px solid transparent;
                                                    border-left-color: red;
                                                    margin-top: -5px;
                                                    left: -5px;
                                                    border-right: none; }

                                                    /* line 114, /Users/jonasvonandrian/jquery-sortable/source/css/application.css.sass */
                                                    .sorted_head th {
                                                      cursor: pointer; }
                                                      /* line 116, /Users/jonasvonandrian/jquery-sortable/source/css/application.css.sass */
                                                      .sorted_head th.placeholder {
                                                        display: block;
                                                        background: red;
                                                        position: relative;
                                                        width: 0;
                                                        height: 0;
                                                        margin: 0;
                                                        padding: 0; }
                                                        /* line 124, /Users/jonasvonandrian/jquery-sortable/source/css/application.css.sass */
                                                        .sorted_head th.placeholder:before {
                                                          content: "";
                                                          position: absolute;
                                                          width: 0;
                                                          height: 0;
                                                          border: 5px solid transparent;
                                                          border-top-color: red;
                                                          top: -6px;
                                                          margin-left: -5px;
                                                          border-bottom: none; }
                                                        </style>

                                                        <div class="content-wrapper">
                                                          <!-- Content Header (Page header) -->
                                                          <section class="content-header">
                                                            <h1>
                                                              Category Sort
                                                              <!-- <small>Version 2.0</small> -->
                                                            </h1>
                                                          </section>

                                                          <!-- Main content -->
                                                          <section class="content">
                                                            <div class="row">
                                                              <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                                                <div class="box box-solid">
                                                                  <div class="box-header">
                                                                      <h3 class="box-title">Product Categories</h3>
                                                                  </div>
                                                                  <div class="box-body">
                                                                    <form id="form-action" class="form-horizontal">
                                                                      <?php 
                                                                      if(!empty($get_product)){
                                                                        echo '<ol id="drag-sort" class="serialization vertical">';
                                                                        $no=0;
                                                                        foreach($get_product as $index => $value){
                                                                          echo '<li data-id="'.$no.'" data-name="'.$value['category_id'].'">'.$value['category_name'].'</li>';
                                                                          $no++;
                                                                        }
                                                                        echo '</ol>';
                                                                      }
                                                                      ?>
                                                                    </form>
                                                                  </div>
                                                                  <div class="overlay">
                                                                    <i class="fa fa-refresh fa-spin"></i>
                                                                  </div>
                                                                </div>
                                                              </div>

                                                              <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                                                <div class="box box-solid">
                                                                  <div class="box-header">
                                                                      <h3 class="box-title">Size Category</h3>
                                                                  </div>
                                                                  <div class="box-body">
                                                                    <form id="form-action" class="form-horizontal">
                                                                      <?php 
                                                                      if(!empty($get_size)){
                                                                        echo '<ol id="drag-sort" class="serialization vertical">';
                                                                        $no=0;
                                                                        foreach($get_size as $index => $value){
                                                                          echo '<li data-id="'.$no.'" data-name="'.$value['category_id'].'">'.$value['category_name'].'</li>';
                                                                          $no++;
                                                                        }
                                                                        echo '</ol>';
                                                                      }
                                                                      ?>
                                                                    </form>
                                                                  </div>
                                                                  <div class="overlay">
                                                                    <i class="fa fa-refresh fa-spin"></i>
                                                                  </div>
                                                                </div>
                                                              </div>

                                                              <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                                                <div class="box box-solid">
                                                                  <div class="box-header">
                                                                      <h3 class="box-title">Gender Category</h3>
                                                                  </div>
                                                                  <div class="box-body">
                                                                    <form id="form-action" class="form-horizontal">
                                                                      <?php 
                                                                      if(!empty($get_gender)){
                                                                        echo '<ol id="drag-sort" class="serialization vertical">';
                                                                        $no=0;
                                                                        foreach($get_gender as $index => $value){
                                                                          echo '<li data-id="'.$no.'" data-name="'.$value['category_id'].'">'.$value['category_name'].'</li>';
                                                                          $no++;
                                                                        }
                                                                        echo '</ol>';
                                                                      }
                                                                      ?>
                                                                    </form>
                                                                  </div>
                                                                  <div class="overlay">
                                                                    <i class="fa fa-refresh fa-spin"></i>
                                                                  </div>
                                                                </div>
                                                              </div>

                                                            </div>
                                                          </section>
                                                        </div>