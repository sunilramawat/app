<?php

/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
//$cakeDescription = 'CakePHP: the rapid development php framework';
use Cake\Core\Configure;
?>

<!DOCTYPE html>
<html>
    <head>
        <?= $this->Html->charset() ?>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>
            <?php echo Configure::read('Site.title') ?> :
            <?php echo $title_for_layout; ?>
        </title>
        <?php echo $this->Html->meta('icon') ?>

        <?php
        echo $this->Html->css(array(
            /* "admin/jqGrid/jquery-ui.theme.css",
              "admin/jqGrid/ui.jqgrid", */ //Commented as we are using datatables now
            "jquery.dataTables.min",
            "admin/bootstrap.min",
            'admin/font-awesome/css/font-awesome.css',
            "admin/ionicons/css/ionicons.min.css",
            "admin/admin_style",
            "admin/_all-skins.min.css",
            "admin/bootstrap-wysihtml5.css",
            "admin/check-box-blue/blue.css",
        ));
        echo $this->Html->script(array(
            "admin/jquery-js",
            /* "admin/jqGrid/i18n/grid.locale-en", //Commented as we are using datatables now
              "admin/jqGrid/jquery.jqGrid.min", */

            /* "admin/jQuery-2.1.3.min", */
            "admin/bootstrap.min",
            "admin/jquery-ui.min.js",
            "admin/app.min.js",
            'admin/bootbox.min',
            "jquery.dataTables.min",
            "table-advanced.js",
            /* For Admin login_cookie  */
            'admin/login_cookie.js',
            'main.js',
            'sware.js',
        ));
        ?>

        <?php echo $this->fetch('meta') ?>
        <?php echo $this->fetch('css') ?>
        <?php echo $this->fetch('script') ?>
        <script type="text/javascript">
        
            $(document).ready(function () {
                $.sware.init({
                    'baseUrl': '<?php echo \Cake\Routing\Router::url('/admin/', TRUE); ?>',
                    'admin': true
                });
                if(typeof PATH_GRID != "undefined" && typeof ID_GRID != "undefined"){
                    if(typeof aa_Sorting == 'undefined'){
                       aa_Sorting = [[ 1, 'desc' ]];
                    } 
                    TableAdvanced.init();
                }
            });
        </script>

    </head>
    <?php if ($this->request->session()->read('Auth.Admin.id')) { ?>
        <body class="skin-blue">
            <div class ="wrapper">
                <?php
                $menuListData = \Cake\ORM\TableRegistry::get('AdminMenu');
                $accessMenuList = $menuListData->find('all')->orderAsc('id');
                $accessMenuList = $accessMenuList->toArray();
                ?>
                <?php echo $this->element('admin/header', ['accessMenuList' => $accessMenuList]); ?>
                <?php echo $this->element('admin/left_menu', ['accessMenuList' => $accessMenuList]); ?>
                <div class="content-wrapper">
                    <?php echo $this->Flash->render(); ?>
                    <?php echo $this->element('admin/header_heading'); ?>
                    <?php //echo $this->element("loading_image"); ?>
                    <?php echo $this->fetch('content'); ?>
                </div>
                <footer class="main-footer">
                    <strong>Copyright &copy; <?php echo date('Y'); ?>-<?php echo date('Y') + 1; ?> <a href=""><?php echo Configure::read('Site.title') ?></a>.</strong> All rights reserved.
                </footer>
            </div>
            
            <div id='ajax-modal' class="modal fade"></div>
            
        </body>
    <?php } else { ?>
        <body class="login-page">
            <?php echo $this->Flash->render(); ?>
            <?php echo $this->fetch('content') ?>
        </body>
    <?php } ?>
</html>