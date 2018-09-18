<?php echo $this->Html->script("admin/ckeditor/ckeditor.js"); ?>
<div class="content">
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="box box-primary">
                <div class="panel-body">
                <?php echo $this->Form->create($emailContent, array('role' => 'form')); ?>
                <?php  echo $this->Form->hidden('ec_id')?>
                <div class="row">
                    <div class="col-lg-8">
                        <?php if ($this->Form->isFieldError('ec_title')) {
                                $class_error = 'has-error';
                            }
                            else{
                                $class_error ="";
                            }
                        ?>
                        <div class="form-group <?php echo $class_error; ?>">
                            <label>Title</label>
                            <?php
                            echo $this->Form->input('ec_title', array(
                                'type' => 'text',
                                'class' => 'form-control',
                                "placeholder" => "Title",
                                'div' => false,
                                'label' => false,
                            ));
                            ?>

                        </div>
                        <?php if ($this->Form->isFieldError('ec_subject')) {
                                $class_error = 'has-error';
                            }
                            else{
                                $class_error ="";
                            }
                        ?>
                        <div class="form-group <?php echo $class_error; ?>">
                            <label>Subject</label>
                            <?php
                            echo $this->Form->input('ec_subject', array(
                                'type' => 'text',
                                'class' => 'form-control',
                                "placeholder" => "Subject",
                                'div' => false,
                                'label' => false,
                            ));
                            ?>
                        </div>
                        <div class="form-group <?php echo $class_error; ?>">
                            <label>Keywords</label>
                            <?php
                            echo $this->Form->input('ec_keywords', array(
                                'type' => 'text',
                                'class' => 'form-control',
                                "placeholder" => "Subject",
                                'div' => false,
                                'label' => false,
                                'readonly'=>true
                            ));
                            ?>
                        </div>
                        <?php if ($this->Form->isFieldError('ec_message')) {
                                $class_error = 'has-error';
                            }
                            else{
                                $class_error ="";
                            }
                        ?>
                        <div class="form-group <?php echo $class_error; ?>">
                            <label>Content</label>
                            <?php
                            echo $this->Form->input('ec_message', array(
                                'id'=>'content',
                                'type' => 'textarea',
                                'class' => 'form-control',
                                "placeholder" => "message",
                                'div' => false,
                                'label' => false,
                            ));
                            ?>
                        </div>
                    </div>
                    
                </div>
                <div class="row">           
                    <div class="col-lg-8">

                        <div class="form-group">
                        <?php $url = (['controller' => 'email_contents', 'action' => 'index', 'prefix'=>'admin']); ?>
                             
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
    $(function () {
        CKEDITOR.replace('content');
    });
</script>