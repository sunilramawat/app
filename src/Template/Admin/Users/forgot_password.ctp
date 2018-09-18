<?php 
    use Cake\Core\Configure;
    use Cake\Routing\Router;
    echo $this->Html->css("admin/signin.css"); 
?>
<nav class="navbar navbar-inverse" role="navigation">
    <div class="container">
        <div class="navbar-header">
          <a class="navbar-brand" href="<?php echo Router::url('/admin'); ?>"><?php echo Configure::read('Site.title');?></a>
        </div>
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse navbar-ex1-collapse">
            <ul class="nav navbar-nav navbar-right">
                <li class=""></li>
            </ul>
        </div><!-- /.navbar-collapse -->
    </div> <!-- /.container -->
</nav>
<div class="account-container stacked">
    <div class="content clearfix">
        <?php echo $this->Form->Create('User', array(
                'role'      => 'Form',
                'validate'  => true,
                'onsubmit'  => 'return rememberme()')); ?>
            <h1>Forgot Password</h1>        
            <div class="login-fields">
                <p>Enter you email:</p>
                <div class="field">
                    <label for="username">Email:</label>
                    <?php
                        echo $this->Form->input('email', array(
                            'class' => 'form-control input-lg username-field',
                            'placeholder' => 'E-mail',
                            'type' => 'email',
                            'label' => FALSE,
                            'div' => FALSE
                        ));
                        ?>
                </div> <!-- /field -->
            </div> <!-- /login-fields -->

            <div class="login-actions">
                <?php
                echo $this->Form->submit('Submit', array(
                    'value' => 'Login',
                    'type' => 'submit',
                    'label' => FALSE,
                    'class' => 'login-action btn btn-primary',
                    'div' => FALSE,
                ));
                ?>
             
            </div> <!-- .actions -->
        <?php echo $this->Form->End(); ?>
    </div> <!-- /content -->
</div> <!-- /account-container -->
<!-- Text Under Box -->

<div class="login-extra">
    <?php 
    echo $this->Html->link(
        'Login',
        ['controller' => 'users', 'action' => 'login', '_full' => true, 'prefix'=>'admin']
    );?>

</div> <!-- /login-extra -->

