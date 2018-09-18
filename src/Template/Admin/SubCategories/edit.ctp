<?php //echo '<pre>';print_r($categories); ?>
<?php echo $this->Html->script("admin/ckeditor/ckeditor.js"); ?>
<div class="content">
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="box box-primary">
                <div class="panel-body">
                <?php echo $this->Form->create($subCategory, array('role' => 'form')); ?>
                 <div class="row">
                    <div class="col-lg-8">
                        <?php if ($this->Form->isFieldError('sc_name')) {
                                $class_error = 'has-error';
                            }
                            else{
                                $class_error ="";
                            }
                        ?>
                        <div class="form-group <?php echo $class_error; ?>">
                            <label>Title</label>
                            <?php
                            echo $this->Form->input('sc_name', array(
                                'type' => 'text',
                                'class' => 'form-control',
                                "placeholder" => "Title",
                                'div' => false,
                                'label' => false,
                            ));
                            ?>

                        </div>
                        <div class="form-group <?php echo $class_error; ?>">
                            <label>SubCategory</label>
                             <?php
                                echo $this->Form->input('sc_c_id', [
                                'type' => 'select',
                                'class' => 'form-control',
                                'multiple' => false,
                                'options' => $categories, 
                                'label' =>false,    
                                'empty' => true]);
                                ?>
                            <?php

                            //echo  $this->Form->input('sc_c_id', ['options' => $categories])
                            ?>

                        </div>
                       
                        <?php if ($this->Form->isFieldError('sc_description')) {
                                $class_error = 'has-error';
                            }
                            else{
                                $class_error ="";
                            }
                        ?>
                        <div class="form-group <?php echo $class_error; ?>">
                            <label>Description</label>
                            <?php
                            echo $this->Form->input('sc_description', array(
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
                        <?php $url = (['controller' => 'categories', 'action' => 'index', 'prefix'=>'admin']); ?>
                             
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
    $('#category').addClass('active');
    $(function () {
        CKEDITOR.replace('content');
    });
</script>

