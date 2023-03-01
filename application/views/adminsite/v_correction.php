 <div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <style type="text/css">
    .list-correction{
      margin-bottom: 10px;
    }
    .list-group-item{
      padding: 0px 0 5px 0;
      border: 0;
    }

    .nav-tabs{
      border: 0;
    }
  </style>
  <section class="content-header">
    <h1>
      Correction
      <!-- <small>Version 2.0</small> -->
    </h1>
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="row">

      <div class="col-lg-8 col-md-8 col-sm-8 col-xs-7">
        <?php if(!empty($correction)){ ?>
        <form class="form-horizontal" method="POST" action="<?php echo base_url('adminsite/correction');?>">
          <div class="box box-solid">
            <div class="box-body">
              <div class="form-group">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                  <button class="btn btn-primary btn-flat pull-right" type="submit">UPDATE</button>
                </div>
              </div>

              <div id="correction" class="form-group">

                <?php 
                $no = 1;

                echo '<div class="list-correction col-lg-6 col-md-6 col-sm-6 col-xs-6">';
                echo '<label>Right</label>';
                echo '<ul class="nav nav-tabs">';

                foreach($correction as $index => $value){
                  $decode_wrong = json_decode($value['wrong']);
                  if(is_array($decode_wrong)){
                    $decode_wrong = implode("\n",$decode_wrong);
                  }

                  echo '<li class="list-correction item-more-'.$no.'">
                  <div class="input-group" style="margin-bottom: 5px;">
                    <input type="hidden" name="id[]" value="'.$value['Id'].'">
                    <input type="text" name="right[]" class="form-control" value="'.$value['right'].'">
                    <div class="input-group-btn">
                      <a class="btn btn-primary" data-action="scrollTop" data-toggle="tab" href="#correction-'.$no.'"><i class="fa fa-eye"></i>
                      </a>
                      <button class="btn btn-danger remove-correction" id="item-more-'.$no.'"><i class="fa fa-times"></i>
                      </button>
                    </div>
                  </div>';
                  $no++;
                }
                echo '</ul>';
                echo '</div>';

                $no = 1;
                echo '<div class="list-correction col-lg-6 col-md-6 col-sm-6 col-xs-6">';
                echo '<div class="tab-content">';
                foreach($correction as $index => $value){
                  $decode_wrong = json_decode($value['wrong']);
                  if(is_array($decode_wrong)){
                    $decode_wrong = implode("\n",$decode_wrong);
                  }

                  echo '<div id="correction-'.$no.'" class="tab-pane fade item-more-'.$no.'">';

                  echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                  <label>Wrong keywords for '.$value['right'].'</label>
                  <textarea style="height: 300px;" type="text" name="wrong[]" class="form-control">'.$decode_wrong.'</textarea>
                </div>';

                echo '</div>';
                $no++;
              }
              echo '</div>';
              echo '</div>';
              ?>

            </div>

            <!-- <div class="form-group">
              <div class="col-lg-12">
                <a class="btn-add-more-correction btn btn-primary btn-flat" id="<?php echo $no;?>"><i class="fa fa-plus"></i> Add more</a>
              </div>
            </div> -->

          </div>

          <div class="box-footer clearfix no-border">
            <button class="btn btn-primary btn-flat pull-right" type="submit">UPDATE</button>
          </div>

          <div class="overlay">
            <i class="fa fa-refresh fa-spin"></i>
          </div>

        </div>
      </form>
      <?php } else {
        echo '<p>No data correction available</p>';
      } ?>
    </div>

    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-5">
      <form class="form-horizontal" method="POST" action="<?php echo base_url('adminsite/correction/add');?>">
        <div class="box box-solid">
          <div class="box-body">
            <label>Right</label>
            <input type="text" name="right" class="form-control">

            <label>Wrong</label>
            <textarea type="text" name="wrong" class="form-control" style="height: 200px;"></textarea>
          </div>

          <div class="box-footer clearfix no-border">
            <button class="btn btn-primary btn-flat pull-right" type="submit">SAVE</button>
          </div>

        </div>
      </form>
    </div>

  </div>
</section>
</div>