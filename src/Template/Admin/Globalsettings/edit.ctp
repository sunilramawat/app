<?php 
    use Cake\Core\Configure;
    /*to make new field in global setting
	* Create new column in global setting table
	* Make Validation in globalsettingtable model
	* create config field in custom_config.php, no change is required in this file
    */
?>
<div class="content">
	<div class="row">
		<!-- left column -->
		<div class="col-md-12">
			<!-- general form elements -->
			<div class="box box-primary">
				<div class="panel-body">
                <?php  
                echo $this->Form->create($global_setting,array("class" => "form"));
				 ?>
	                <div class="row">
	                    <div class="col-lg-8">
		                    <div class="form-group">
	                    	 <?php
                             $globalset = Configure::read('globalsetting.label');
                             if(isset($globalset) && !empty($globalset)){
                             	foreach (Configure::read('globalsetting.label') as $key => $value) { ?>
                            		<div class="form-group">
                            		<label><?php echo $value;?><sup class="MandatoryFields">*</sup></label>
                              <?php if(Configure::read('globalsetting.input_type.'.$key) == 'select'){  ?>
									<?php echo $this->Form->input(Configure::read('globalsetting.field_name')[$key],array(
		                                'type'       => Configure::read('globalsetting.input_type.'.$key),
		                                'empty'      => __('Select Default Country'),
		                                'options'    => $site_country,
		                                'required'   => false,
		                                'div'        => false,
		                                'class'      => 'form-control',
		                                'label'      =>  false));
										echo '</div>';
									 	                 
								}else{  ?>
									<?php echo $this->Form->input(Configure::read('globalsetting.field_name')[$key],array(
										'type'      => Configure::read('globalsetting.input_type.'.$key),
										'div' 		=> false,
										'required' 	=> true,
										'label'		=> false,
										'class'		=> 'form-control',
										'error' => array(
											'attributes' 	=> array('wrap' => 'span', 'class' => 'control-label'))
										));
										echo '</div>';
									?> 	                 
							<?php }?>	 		
                           <?php }
                             }
                                
                                ?>
		                
	                    </div>
	                </div>
	                </div><!--row-->
	                <div class="row">           
	                    <div class="col-lg-8">
	                    	<div class="form-group">
	                         <?php $url = (['controller' => 'users', 'action' => 'dashboard', 'prefix'=>'admin']); ?>
                             <?php echo $this->Form->button('Cancel',array(
							 	'onclick' 	=> "location.href='" . $this->Url->build($url) . "'",
                                'label' 	=> false,
								'div' 		=> false,
								'class'		=> 'btn btn-white',
								'type'		=> 'reset'
								));
								?>

								<?php echo $this->Form->button('Submit',array(
									'type' 			=> 'submit',
									'class'			=> 'btn btn-primary',
									'label' 		=> false,
									'div' 			=> false										
									));
									?>
	                        </div>
	                    </div>
	                </div>
                
           		<?php echo $this->Form->end(); ?>    
           		</div><!--panel-body-->
			</div><!-- /.box -->
		</div><!--col-md-12-->
	</div><!--row-->
</div><!--content-->
<script type="text/javascript">
	$('#pages').addClass('active');
	function validateYoutube(){
		var url = $.trim($('#WebsettingHomepageVideo').val());

		if(url!=''){
			var isyouTubeUrl = /((http|https):\/\/)?(www\.)?(youtube\.com)(\/)?([a-zA-Z0-9\-\.]+)\/?/.test(url);

			if(!isyouTubeUrl || (url.indexOf("watch") == -1 || url.indexOf("embed") == -1) ||  url.indexOf("feature=player_detailpage") != -1 || $.trim(url)==''){

				if(url.indexOf("feature=player_detailpage") != -1){
					alert("<?php echo __('YouTube URL must not contain feature=player_detailpage in URL. Please Copy only Watch URL or remove this string from your URL.');?>");                
				} else {
					alert("<?php echo __('Its not a valid YouTube URL.');?>");
				}

				$('#WebsettingHomepageVideo').val('');

				return false;
			}            
		} 

		return true;        
	}
</script>