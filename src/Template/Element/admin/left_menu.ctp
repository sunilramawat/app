<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                
            </div><!--pull-left image-->
            <div class="pull-left info">
                <p><?php //echo substr($this->request->session()->read('Auth.Admin.username'), 0, 30); ?></p>
                <i class="fa fa-circle text-success"></i> <?php  echo substr($this->request->session()->read('Auth.Admin.username'), 0, 10); ?>
            </div>
        </div><!--user-panel-->
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu">
            <?php 
            $userType = $this->request->session()->read('Auth.Admin.user_type');
            ?>
            
            <li id="dashboard">
                <a href="<?php echo $this->Url->build(['controller' => 'users', 'action' => 'dashboard']) ?>">
                    <i class="fa fa-dashboard"></i> <span><?php echo __('Dashboard'); ?></span>
                </a>
            </li>
            <?php
            if (($accessMenuList[5]['is_checked'] == 1) || $userType == 0) {
            ?>
            <li id="users_li">
                <a href="<?php echo $this->Url->build(['controller' => 'users', 'action' => 'index']) ?>">
                    <i class="fa fa-envelope"></i> <span><?php echo __('Manage Users'); ?></span>
                </a>
            </li>
            <?php
            }

            if (($accessMenuList[5]['is_checked'] == 1) || $userType == 0) {
            ?>
            <li id="categories">
                <a href="<?php echo $this->Url->build(['controller' => 'categories', 'action' => 'index']) ?>">
                    <i class="fa fa-envelope"></i> <span><?php echo __('Manage Category'); ?></span>
                </a>
            </li>
            <?php
            }

            if (($accessMenuList[6]['is_checked'] == 1) || $userType == 0) {
            ?>
           <!--  <li id="post_comment_reports">
                <a href="<?php echo $this->Url->build(['controller' => 'post_comment_reports', 'action' => 'index']) ?>">
                    <i class="fa fa-envelope"></i> <span><?php echo __('Post Comment Report'); ?></span>
                </a>
            </li> -->
            <?php
            }
            
            if (($accessMenuList[3]['is_checked'] == 3) || $userType == 0) {
                ?>
                <li id="page">
                    <a href="<?php echo $this->Url->build(['controller' => 'pages', 'action' => 'index']) ?>">
                        <i class="fa fa-envelope"></i> <span><?php echo __('CMS Pages'); ?></span>
                    </a>
                </li>
                <?php
            }

            if (($accessMenuList[2]['is_checked'] == 1) || $userType == 0) {
                ?>
                 <li id="email_content">
                    <a href="<?php echo $this->Url->build(['controller' => 'email_contents', 'action' => 'index']) ?>">
                        <i class="fa fa-envelope"></i> <span><?php echo __('Manage Email Contents'); ?></span>
                    </a>
                </li> 
                <?php
            }

             

           
            ?>
        </ul><!--sidebar-menu-->
    </section><!--sidebar-->
</aside>