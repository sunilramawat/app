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

$cakeDescription = 'Anamy';
?>
<!DOCTYPE html>
<html ng-app>
<head>

    <?php echo $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?php echo $cakeDescription ?>:
        <?php echo $this->fetch('title') ?>
    </title>
    <?php //echo $this->Html->meta('icon') ?>
    <link rel="shortcut icon" href="images/fav.png">

   <?php echo $this->Html->css(array('bootstrap/css/bootstrap','owl.carousel','ui_layout','ui_responsive_layout'));?>
<?php echo $this->Html->script(array('jquery-1.10.2.min')); ?>
<?php echo $this->Html->script(array('bootstrap/js/bootstrap.min')); ?>
<?php echo $this->Html->script(array('owl.carousel')); ?>


    <?php echo $this->fetch('meta') ?>
    <?php echo $this->fetch('css') ?>
    <?php echo $this->fetch('script') ?>
</head>
<body>
 
       <?php echo $this->element('headerinner'); ?>
       <?php echo $this->Flash->render() ?>
        <?php echo $this->fetch('content') ?>
        
   </html>
        <?php //echo $this->element('footer'); ?>