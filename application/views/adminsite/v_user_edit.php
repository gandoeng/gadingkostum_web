 <div class="content-wrapper">

  <section class="content-header">

    <h1>EDIT USER</h1>

  </section>

  <section class="content">

    <div class="row">

      <div class="col-lg-9 col-md-12 col-sm-12 col-xs-12">

        <div class="box box-solid main-box">

          <div class="box-body">

            <?php

            if($this->session->flashdata('error') != NULL){

              echo '<div class="callout callout-danger">'.$this->session->flashdata('error').'</div>';

            }

            if(validation_errors()){

              echo '<div class="callout callout-danger">'.validation_errors().'</div>';

            } ?>

            <?php if(!empty($get)){ ?>
            <form action="<?php echo base_url('adminsite/user/edit/').$id;?>" method="post">

              <div class="panel panel-default">

                <div class="panel-body">

                  <div class="form-group">
                   <label><span class="required">*</span> Username <i> (Only contain alpha-numeric characters, underscores, and dashes)</i></label>
                   <input autocomplete="new-password" type="text" name="username" class="form-control" value="<?php echo $get[0]['username'];?>">
                 </div>

                 <label style="display:block;">Password</label>

                 <div class="input-group">
                  <input autocomplete="new-password" id="password" type="password" class="form-control" placeholder="password" name="password" value="<?php echo set_value('password')?>">
                  <span class="input-group-btn">
                    <button class="password btn btn-secondary" type="button">
                      <span class="glyphicon-password glyphicon glyphicon-eye-open"></span>
                    </button>
                  </span>
                </div>

                <label style="display:block; margin-top: 15px;">Re-type Password</label>
                <div class="input-group">
                  <input autocomplete="new-password" id="re_password" type="password" class="form-control" placeholder="password" name="re_password" value="<?php echo set_value('re_password')?>">
                  <span class="input-group-btn">
                    <button class="re_password btn btn-secondary" type="button">
                      <span class="glyphicon-repassword glyphicon glyphicon-eye-open"></span>
                    </button>
                  </span>
                </div>

                <div class="form-group">
                  <label style="display:block; margin-top: 15px;">Role</label>
                  <div class="radio">
                  <label><input <?php echo ($get[0]['role'] == 'admin') ? 'checked' : '' ?> type="radio" name="role" value="admin">Admin</label>
                  </div>
                  <div class="radio">
                    <label><input <?php echo ($get[0]['role'] == 'kasir') ? 'checked' : '' ?> type="radio" name="role" value="kasir">Kasir</label>
                  </div>
                </div>

                <div class="form-group">
                 <label style="display:block; margin-top: 5px;"><span class="required">*</span> Email</label>
                 <input autocomplete="new-password" type="text" name="email" class="form-control" value="<?php echo $get[0]['email'];?>">
               </div>

               <div class="form-group">

                <div class="checkbox">
                  <?php

                  $checked = ''; 
                  if($get[0]['status'] == 2){

                    $checked = 'checked';
                  }
                  ?>
                  <label><input type="checkbox" name="status" <?php echo $checked;?> value="2">Active</label>

                </div>

              </div>
            </div>

            <div class="panel-footer">

              <div class="btn-group">

                <button type="submit" class="btn btn-primary btn-flat">Save</button>

              </div>

              <div class="btn-group">

                <a href="<?php echo base_url('adminsite/user');?>" class="btn btn-primary btn-flat">Back</a>

              </div>

            </div>

          </div>

        </form>
        <?php } else { ?>

        <div class="panel panel-default">

          <div class="panel-footer">

            <div class="btn-group">

              <a href="<?php echo base_url('adminsite/user');?>" class="btn btn-primary btn-flat">Back</a>

            </div>

          </div>

        </div>

        <?php } ?>
      </div>

      <div class="overlay">
        <i class="fa fa-refresh fa-spin"></i>
      </div>

    </div>

  </div>

</div>

</section>
</div>

<script type="text/javascript">
  $(function(){
    $(".password").on('click',function(e){

      e.preventDefault();
      var data = $("#password").attr('type');
      if(data == 'password'){
        $("#password").attr("type", "text");
        $(".glyphicon-password").removeClass("glyphicon-eye-open").addClass("glyphicon-eye-close");
      }

      if(data == 'text'){
        $("#password").attr("type", "password");
        $(".glyphicon-password").removeClass("glyphicon-eye-close").addClass("glyphicon-eye-open");
      }

    });
    $(".re_password").on('click',function(e){

      e.preventDefault();
      var data = $("#re_password").attr('type');
      if(data == 'password'){
        $("#re_password").attr("type", "text");
        $(".glyphicon-repassword").removeClass("glyphicon-eye-open").addClass("glyphicon-eye-close");
      }

      if(data == 'text'){
        $("#re_password").attr("type", "password");
        $(".glyphicon-repassword").removeClass("glyphicon-eye-close").addClass("glyphicon-eye-open");
      }
    });
  });
</script>