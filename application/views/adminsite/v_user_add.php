<div class="content-wrapper">

  <section class="content-header">

    <h1>ADD USER</h1>

  </section>

  <section class="content">

    <div class="row">

      <div class="col-lg-9 col-md-12 col-sm-12 col-xs-12">

        <div class="box box-solid main-box">

          <div class="box-body">

            <?php

            if(validation_errors()){

              echo '<div class="callout callout-danger">'.validation_errors().'</div>';

            }

            if($this->session->flashdata('success') != NULL){

              echo '<div class="callout callout-success">'.$this->session->flashdata('success').'</div>';

            } ?>

            <form action="<?php echo base_url('adminsite/user/add');?>" method="post" autocomplete="new-password">

              <div class="panel panel-default">

                <div class="panel-body">

                  <div class="form-group">
                   <label><span class="required">*</span> Username <span><i> (Only contain alpha-numeric characters, underscores, and dashes)</i></span></label>
                   <input autocomplete="new-password" type="text" name="username" class="form-control" value="<?php echo set_value('username')?>">
                 </div>

                 <label style="display:block;"><span class="required">*</span> Password</label>

                 <div class="input-group">
                  <input autocomplete="new-password" id="password" type="password" class="form-control" placeholder="password" name="password" value="<?php echo set_value('password')?>">
                  <span class="input-group-btn">
                    <button class="password btn btn-secondary" type="button">
                      <span class="glyphicon-password glyphicon glyphicon-eye-open"></span>
                    </button>
                  </span>
                </div>

                <label style="display:block; margin-top: 15px;"><span class="required">*</span> Re-type Password</label>
                <div class="input-group">
                  <input autocomplete="new-password" id="re_password" type="password" class="form-control" placeholder="password" name="re_password" value="<?php echo set_value('re_password')?>">
                  <span class="input-group-btn">
                    <button class="re_password btn btn-secondary" type="button">
                      <span class="glyphicon-repassword glyphicon glyphicon-eye-open"></span>
                    </button>
                  </span>
                </div>

                <div class="form-group">
                 <label style="display:block; margin-top: 5px;"><span class="required">*</span> Email</label>
                 <input autocomplete="new-password" type="text" name="email" class="form-control" value="<?php echo set_value('email')?>">
               </div>

               <div class="form-group">
                <label>Role</label>
                <div class="radio">
                <label><input checked type="radio" name="role" checked value="admin">Admin</label>
                </div>
                <div class="radio">
                  <label><input type="radio" name="role" checked value="kasir">Kasir</label>
                </div>
              </div>

              <div class="form-group">

                <div class="checkbox">

                  <label><input type="checkbox" name="status" checked value="2">Active</label>

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