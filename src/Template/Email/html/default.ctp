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
?>

<?php
/*$content = explode("\n", $content);

foreach ($content as $line):
    echo '<p> ' . $line . "</p>\n";
endforeach;*/
?>
<?php 
	use Cake\Core\Configure;
	use Cake\Routing\Router;
?>
<div style="width: 800px; margin: 10px auto;">
	<div class="header_email" style="float: left; width: 100%; background: #3c8dbc; height: 62px;min-height: 62px;max-height: 62px;">
        <img style="margin:6px;"src="<?php echo Router::url('/',true).'webroot/images/site-logo.png'?>">
    </div>
	<div class="center_email" style="float: left; width: 798px; border-left: 1px solid #eeeeee; border-right:1px solid #eeeeee; background:#f5f5f5; min-height:225px">
		<div style="float:left; width: 100%;">&nbsp;</div>
		<div style="display: table; width: 740px; margin: 0 auto;word-wrap: break-word;line-height: 25px;">
			<?php echo stripslashes($mailContents); ?>
		</div>
		<div style="float: left; width: 100%;">&nbsp;</div>
	</div>
    <div class="footer_email" style="color: #fff;float: left; width: 100%; background: #47484a;height: 42px;min-height: 42px;max-height: 42px;border-top: 3px solid #3c8dbc;">
        <p style="text-align: center;">
            Copyright ©  <?php echo date('Y'); ?>-<?php echo date('Y')+1; ?>  <?php echo Configure::read('Site.title'); ?> All Right Reserved.
        </p>
    </div>
</div>
