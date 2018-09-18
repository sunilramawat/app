<?php echo $this->Html->script(array('angular')); ?>

<?php echo $this->Form->create('',array('name'=>'user','id'=>'user')); ?>
<div class="fullwidth-forall">
  <div class="container">
    <div><span  class="login-mainheading">Register </span><br>
    </div>

    <div class="login-boxcover">
       <div id="errorMessage" class="errorMessage"></div>
      <div class="login-boxcoverinner">
      <?php
            echo $this->Form->input('username', array(
                'type' => 'text',
                'class' => 'form-control inputstyle input-fullname',
                'ng-model'=>'username',
                "placeholder" => __("User name"),
                'div' => false,
                'label' => false,
                //'onkeypress' => "return alphaOnly(event)"
            ));
      ?>
      <span ng-show="user.email.$error.email">Not a valid e-mail address</span>
      <?php
            echo $this->Form->input('email', array(
                'type' => 'email',
                'class' => 'form-control inputstyle input-emailbg',
                "placeholder" => __("Email address"),
                'ng-model'=>'text',
                'div' => false,
                'label' => false,
            ));
      ?>
      <?php
              echo $this->Form->input('password', array(
                  'type' => 'password',
                  'class' => 'form-control inputstyle input-passwordbg',
                  'ng-model'=>'password',
                  "placeholder" => __("Password"),
                  'div' => false,
                  'label' => false,
                  //'onkeypress' => "return alphaOnly(event)"
              ));
      ?>
      <?php
              echo $this->Form->input('confirm password', array(
                  'type' => 'password',
                  'class' => 'form-control inputstyle input-passwordbg',
                  'ng-model'=>'confirmpassword',
                  "placeholder" => __("Confirm password"),
                  'div' => false,
                  'label' => false,
                  //'onkeypress' => "return alphaOnly(event)"
              ));
      ?>
    
                                 <?php echo $this->Form->button('Submit', array('type'=>'button', 'id'=>'registerbtn', 'class'=>'btn logingbtn registerbtn','label'=>false,'div'=>false)); ?>
        
         <?php echo $this->Form->end(); ?>
      </div>
    </div>
  </div>
</div>
<script>
$("#registerbtn").on("click", function()
{
   //console.log('hi1');
  if($.trim($('#username').val()) == '')
  {
        alert('Please enter username');
        return false;
  }
 
  /*if($.trim($('#UserEmail').val()) != '')
  {*/

    if($.trim($('#email').val()) == '')
    {
        alert('Please enter email address');
        return false;
    }
    
    if(!$('#email').val().toLowerCase().match(/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/))
    {
        alert('Please enter valid email address');
        return false;
    }
    
  /* }*/
  
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
                alert(response);
                  if(response.err==1){
                    $("#errorMessage").html(response.msg);
                    alert(response.msg);
                  }else{
                    window.location.href = "Index";
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