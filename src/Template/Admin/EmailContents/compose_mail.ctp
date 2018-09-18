<?php 
    echo $this->Html->script("admin/ckeditor/ckeditor.js"); 
    echo $this->Html->css(array('admin/tagit/jquery.tagit'));
    echo $this->Html->script(array('admin/tagit/tag-it.js'));
?>

<div class="content">
	<div class="row">
		<!-- left column -->
		<div class="col-md-12">
			<!-- general form elements -->
			<div class="box box-primary">
				<div class="panel-body">
                <?php echo $this->Form->create('EmailContent', array('id' => 'frm_Mail', 'name' => 'frm_Mail','onsubmit' => 'return validation()')); ?>
                <div class="row">
                    <div class="col-lg-8">
                        <?php if ($this->Form->isFieldError('title')) {
                                $class_error = 'has-error';
                            }
                            else{
                                $class_error ="";
                            }
                        ?>
                        <div class="form-group <?php echo $class_error; ?>">
                            <label>Recipients<sup class="MandatoryFields">*</sup></label>
                            <?php
                            echo $this->Form->input('title', array(
                                'type' => 'text',
                                'class' => 'form-control',
                                "placeholder" => "Reciepients",
                                'div' => false,
                                'label' => false,
                                'id'=>'mails',
                                'required'=> false
                            ));
                            ?>

                        </div>
                        <?php if ($this->Form->isFieldError('subject')) {
                                $class_error = 'has-error';
                            }
                            else{
                                $class_error ="";
                            }
                        ?>
                        <div class="form-group <?php echo $class_error; ?>">
                            <label>Subject<sup class="MandatoryFields">*</sup></label>
                            <?php
                            echo $this->Form->input('subject', array(
                                'type' => 'text',
                                'class' => 'form-control',
                                "placeholder" => "",
                                'div' => false,
                                'label' => false,
                                'required'=> false
                            ));
                            ?>
                        </div>
                        <?php if ($this->Form->isFieldError('message')) {
                                $class_error = 'has-error';
                            }
                            else{
                                $class_error ="";
                            }
                        ?>
                        <div class="form-group <?php echo $class_error; ?>">
                            <label>Content<sup class="MandatoryFields">*</sup></label>
                            <?php
                            echo $this->Form->input('message', array(
                                'id'=>'message',
                                'type' => 'textarea',
                                'class' => 'form-control',
                                "placeholder" => "content",
                                'div' => false,
                                'label' => false,
                                'required'=> false
                            ));
                            ?>
                        </div>
                    </div>
                    
                </div>
                <div class="row">           
                    <div class="col-lg-8">

                        <div class="form-group">
                        <?php $url = (['controller' => 'email_contents', 'action' => 'index', 'prefix'=>'admin']);
                
                        if(isset($cancel_url) && !empty($cancel_url)){
                            $url = $cancel_url;
                        }
                        ?>
                        <?php echo $this->Form->button('Cancel',array(
                            'onclick'   =>  "location.href='".$this->Url->build($url)."'",
                            'label'     => false,
                            'div'       => false,
                            'class'     => 'btn btn-white',
                            'type'      => 'reset'
                            ));
                            ?>

                            <?php echo $this->Form->button('Submit',array(
                                'type'          => 'submit',
                                'class'         => 'btn btn-primary',
                                'label'         => false,
                                'div'           => false                                        
                                ));
                                ?>
                        </div>
                    </div>
                </div>
                <!-- /.row (nested) --> 
                <?php echo $this->Form->end(); ?>    
            </div>
			</div><!-- /.box -->
		</div>
	</div>
</div>
<script type="text/javascript">
	$('#email_content').addClass('active');
</script>

<script type="text/javascript">
    function IsEmail(email) {
        var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        return regex.test(email);
    }
    $( document ).ready(function() {
        $("#mails").tagit({
            itemName: 'item',
            fieldName: "reciever_emails[]",
            onTagExists: function(evt, ui) {
                $(".tagit-new").children("input").val('');
            },
            beforeTagAdded: function(evt, ui) {
                if (IsEmail(ui.tagLabel)) {
                    $(".error-message").remove();
                    return true;
                }
                else {
                    $(".tagit-new").children("input").val('');
                    $(".error-message").remove();
                    $("#mails").parent().append('<div class="error-message">Please enter valid email id.</div>');
                    return false;
                }
            },
        });
    });
    var editor = CKEDITOR.replace('message');
    function validation()
    {
        $(".error-message").remove();
        var subject = $('#EmailContentSubject').val();
        if (validate(editor, 'message'))//Editor validation......
        {
            return false;
        }

        if (!$('.tagit-choice').length) {
            $("#mails").parent().append('<div class="error-message">This field is required.</div>');
            return false;
        }
        if(subject.trim() == '')
        {
            $("#EmailContentSubject").parent().append('<div class="error-message">This field is required.</div>');
            $('#EmailContentSubject').html('Please Enter Subject');
            return false;
        }
        
    }
    function validate(obj, MailMessage) //ckeditor validation....
    {
        //$("#errDiv").remove();
        if (validateCKEDITORforBlank($.trim(CKEDITOR.instances.message.getData().replace(/<[^>]*>|\s/g, '')))) {
             $("#message").parent().append('<div class="error-message">This field is required.</div>');
            CKEDITOR.instances.message.setData("");
            return true;
        }
        return false;
    }

    function validateCKEDITORforBlank(field) //ckeditor validation....
    {
        var vArray = new Array();
        vArray = field.split("&nbsp;");
        var vFlag = 0;
        for (var i = 0; i < vArray.length; i++){
            if (vArray[i] == '' || vArray[i] == ""){
                continue;
            }else{
                vFlag = 1;
                break;
            }
        }
        if (vFlag == 0){
            return true;
        }else{
            return false;
        }
    }
</script>

<style type="text/css">
    .tagit{
        border-radius: 0px !important;
        padding:0px !important;
        background:none !important;
    }
    ul.tagit input[type="text"]{
        background: none repeat scroll 0 0 white;
        border:none;
    }
    .bootstrap-tagsinput {
        display: block;
        padding:0px 12px;
    }
    .ui-widget-content {
        border: 1px solid #DDDDDD;
        color: #333333;
    }
</style>