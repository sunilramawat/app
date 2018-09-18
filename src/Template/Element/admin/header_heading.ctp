<div class="content-header">
	<h1>
		<?php echo $title_for_layout; ?>
	</h1>
	<?php 
		$breadclass = '';
		if( isset($breadcrumb) && strlen($breadcrumb) > 400){
			$breadclass = 'breadcurumb-cat-heading';
		} 
	?>
	<ol class="breadcrumb <?php echo $breadclass;?>">
		<li><a href="<?php echo $this->Url->build([
    "controller" => "users",
    "action" => "dashboard"]); ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
	<?php if(isset($breadcrumb) && !empty($breadcrumb)){
			echo $breadcrumb;
		}else{?>
		<li class="active"><?php echo $title_for_layout; ?></li>
		<?php }?>
	</ol>
</div>

	
	

