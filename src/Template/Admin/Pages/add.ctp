<?php echo $this->Html->script("admin/ckeditor/ckeditor.js"); ?>
<div class="content">
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="box box-primary">
                <div class="panel-body">
                <?php echo $this->Form->create($page, array('role' => 'form')); ?>
                 <div class="row">
                    <div class="col-lg-8">
                        <?php if ($this->Form->isFieldError('p_title')) {
                                $class_error = 'has-error';
                            }
                            else{
                                $class_error ="";
                            }
                        ?>
                        <div class="form-group <?php echo $class_error; ?>">
                            <label>Title</label>
                            <?php
                            echo $this->Form->input('p_title', array(
                                'type' => 'text',
                                'class' => 'form-control',
                                "placeholder" => "Title",
                                'div' => false,
                                'label' => false,
                            ));
                            ?>

                        </div>
                        
                       
                        <?php if ($this->Form->isFieldError('p_description')) {
                                $class_error = 'has-error';
                            }
                            else{
                                $class_error ="";
                            }
                        ?>
                        <div class="form-group <?php echo $class_error; ?>">
                            <label>Description</label>
                            <?php
                            echo $this->Form->input('p_description', array(
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
                        <?php $url = (['controller' => 'pages', 'action' => 'index', 'prefix'=>'admin']); ?>
                             
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
    $('#page').addClass('active');
    $(function () {
        CKEDITOR.replace('content');
    });
</script>


<!-- <nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Pages'), ['action' => 'index']) ?></li>
    </ul>
</nav>
<div class="pages form large-9 medium-8 columns content">
    <?= $this->Form->create($page) ?>
    <fieldset>
        <legend><?= __('Add Page') ?></legend>
        <?php
            echo $this->Form->input('p_title');
            echo $this->Form->input('p_description');
            echo $this->Form->input('p_showing_order');
            echo $this->Form->input('p_status');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
 -->