<?php

use Cake\Core\Configure;
?>
<header class="main-header">
    <!--Logo-->
    <a href="<?php
    echo $this->Url->build([
        "controller" => "users",
        "action" => "dashboard"]);
    ?>" class="logo">
       <?php echo Configure::read('Site.title') ?>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top" role="navigation">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        Admin
                        <span class="hidden-xs"><?php echo substr($this->request->session()->read('Auth.Admin.username'), 0, 10) ; ?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header">
                            Admin
                            <p>
                                <?php echo substr($this->request->session()->read('Auth.Admin.username'), 0, 10); ?>
                            </p>
                        </li>
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <?php
                            $userType = $this->request->session()->read('Auth.Admin.user_type');

                            if ((isset($accessMenuList[0]['is_checked']) && $accessMenuList[0]['is_checked'] == 1) || $userType == 0) {
                                ?>
                                <div class="pull-left">
                                    <a href="<?php
                                    echo $this->Url->build([
                                        "controller" => "users",
                                        "action" => "changePassword"]);
                                    ?>" class="btn btn-default btn-flat">
                                        Change Password
                                    </a>
                                </div>
                                <?php
                            }
                            ?>

                            <div class="pull-right">
                                <a href="<?php
                                echo $this->Url->build([
                                    "controller" => "users",
                                    "action" => "logout"]);
                                ?>" class="btn btn-default btn-flat">
                                    Sign out
                                </a>
                            </div>
                        </li>
                    </ul>
                </li><!--dropdown user user-menu-->
            </ul><!--nav navbar-nav-->
        </div><!--navbar-custom-menu-->
    </nav>
</header>