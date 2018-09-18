<?php 
    use Cake\Core\Configure;
    //echo $this->Html->css(array('admin/cropper_main','admin/cropper.min')); 

    echo $this->Html->script(array('angular')); 
?>
<div class="content">
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="box box-primary">
                <div class="panel-body">
                  <div class="col-md-9">
                  <?php echo $this->Form->create('',array('name'=>'user','id'=>'user')); ?>
                       <?php //echo $this->Form->create($user);
                       echo $this->Form->input('image',array(
                                  'type'  => 'hidden',
                                  'class' => 'form-control class_hidden_image',
                       )) ?>
                    <div class="row">
                        <div class="col-md-12">
                            
                            <?php
                            if ($this->Form->isFieldError('password')) {
                                $class_error = 'has-error';
                            } else {
                                $class_error = "";
                            }
                            ?>
                            <div class="form-group <?php echo $class_error; ?>">
                                <label><?php echo __('Password');?></label>
                                <?php
                                echo $this->Form->input('password', array(
                                    'type'        => 'password',
                                    'class'       => 'form-control',
                                    "placeholder" => "Password",
                                    'div'         => false,
                                    'label'       => false,
                                    'required'    => false
                                ));
                                ?>
                            </div>
                            <?php
                            if ($this->Form->isFieldError('confirm_password')) {
                                $class_error = 'has-error';
                            } else {
                                $class_error = "";
                            }
                            ?>
                            <div class="form-group <?php echo $class_error; ?>">
                                <label><?php echo __('Confirm Password');?></label>
                                <?php
                                echo $this->Form->input('confirm_password', array(
                                    'type' => 'password',
                                    'class' => 'form-control',
                                    "placeholder" => __("Confirm Password"),
                                    'div' => false,
                                    'label' => false,
                                    'required' => false
                                ));
                                ?>
                            </div>
                        </div>
                    </div>

                    <div class="row">           
                        <div class="col-lg-8">

                            <div class="form-group">
                                <?php $url = (['controller' => 'users', 'action' => 'dashboard', 'prefix'=>'admin']); ?>
                                <?php
                                echo $this->Form->button('Cancel', array(
                                    'onclick' => "location.href='" . $this->Url->build($url) . "'",
                                    'label' => false,
                                    'div' => false,
                                    'class' => 'btn btn-white',
                                    'type' => 'reset'
                                ));
                                ?>
                              <?php echo $this->Form->button('Submit', array('type'=>'button', 'id'=>'registerbtn', 'class'=>'btn logingbtn registerbtn','label'=>false,'div'=>false)); ?>
                                <?php
                                /*echo $this->Form->button('Submit', array(
                                    'type' => 'button',
                                    'class' => 'btn btn-primary',
                                    'label' => false,
                                    'div' => false,
                                    'id'=>'changepass', 
                                ));*/
                                ?>
                            </div>
                        </div>
                    </div>
                    <!-- /.row (nested) --> 
                    <?php echo $this->Form->end(); ?> 
                  </div>
                   
   
                </div><!-- ./panel-body-->
            </div><!--/.box -->
            

        </div>
    </div>
</div>
<script>
$("#registerbtn").on("click", function()
{
   if($.trim($('#password').val()) == '')
   {
         alert('Please enter password');
         return false;
   }
   if($.trim($('#confirm-password').val()) == '')
   {
         alert('Please enter confirm password');
         return false;
   }
  if($.trim($('#password').val()) != $.trim($('#confirm-password').val()))
   {
         alert('Password do not match.');
         return false;
   }
  var form = $("#user");
        if(form.length) {}else{ form = $("#user");}
        var formData = form.serialize();
          $.ajax({
              type: "POST",
              url:$(this).attr('action'),
              dataType: 'json',
              data: form.serialize(),
              success: function (response) {
                  if(response.err==1){
                    $("#errorMessage").html(response.msg);
                   // alert(response.msg);
                  }else{
                    
                    window.location.href = "dashboard";
                  }
              },
              error: function() {
                  alert('fail');
              }
          });
 // alert('sunil');
  
  //   return ValidateFileUpload('company_logo_id');
    //submitregister();  
});

</script>