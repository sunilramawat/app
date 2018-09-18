
<?php

use Cake\Core\Configure;

echo $this->Html->css("admin/signin.css");
echo $this->Html->script("//www.google.com/recaptcha/api.js");
?>
<nav class="navbar navbar-inverse" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <a class="navbar-brand" href=""><?php echo Configure::read('Site.title'); ?></a>
        </div>
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse navbar-ex1-collapse">
            <ul class="nav navbar-nav navbar-right">
                <li class="">                     
                </li>
            </ul>
        </div><!-- /.navbar-collapse -->
    </div> <!-- /.container -->
</nav>
<div class="account-container stacked">
    <div class="content clearfix">  
        <?php echo $this->Form->Create($login, array('role' => 'Form','validate' => true,'onsubmit' => 'return rememberme()')); ?>
        <h1>Sign In</h1>        
        <div class="login-fields">
            <p>Sign in using your registered account:</p>
            <div class="field">
                <label for="username">Email:</label>
                <?php
                echo $this->Form->input('email', array(
                    'class' => 'form-control input-lg username-field',
                    'id' => 'UserEmail',
                    'placeholder' => 'E-mail',
                    'type' => 'email',
                    'label' => FALSE,
                    'div' => FALSE
                ));
                ?>
            </div> <!-- /field -->
            <div class="field">
                <label for="password">Password:</label>
                <?php
                echo $this->Form->input('password', array(
                    'class' => 'form-control input-lg password-field',
                    'placeholder' => 'Password',
                    'id' => 'UserUPassword',
                    'type' => 'password',
                    'label' => FALSE,
                    'div' => FALSE
                ));
                ?>
            </div> <!-- /password -->
            <div class="field">
                <div class="g-recaptcha" data-sitekey="<?php echo Configure::read('gc-sitekey'); ?>"></div>
                <?php
                if ($this->Form->isFieldError('g-recaptcha-response')) {
                    echo $this->Form->error('g-recaptcha-response');
                }
                ?>
            </div> <!-- /password -->
        </div> <!-- /login-fields -->
        <div class="login-actions">
            <span class="login-checkbox">
                <div class="checkbox icheck">
                    <label>
                        <?php
                        echo $this->Form->input('checkbox', array(
                            'type' => 'checkbox',
                            'label' => 'Remember Me',
                            'required' => false,
                            'class' => '',
                            'div' => false,
                            'id' => 'remember')
                        );
                        ?>
                    </label>
                </div> 
            </span>
            <?php
            echo $this->Form->submit('Sign In', array(
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
    I forgot my <?php echo $this->Html->link('Password', ['controller' => 'users', 'action' => 'forgotPassword', '_full' => true, 'prefix' => 'admin']); ?>
</div> <!-- /login-extra -->
<script>
    $(document).ready(function () {
        checkcookies();
    });
</script>