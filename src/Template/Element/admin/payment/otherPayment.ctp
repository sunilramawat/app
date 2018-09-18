<?php use Cake\Core\Configure; 
$typevalue = isset($countryPaymentGateway->type) ?$countryPaymentGateway->type :$type;
$cID = isset($countryPaymentGateway->country_id) ?$countryPaymentGateway->country_id :$id;
if($typevalue == 1){
    $textlabel =  __("Make Payment Gateway");
}else if($typevalue == 2) {
    $textlabel =  __("Add Funds Gateway");
}else{
    $textlabel =  __("Withdrawal Gateway");
}

?>
 <div class="tab-content clearfix">
      <div id="basic" class="tab-pane active">
        <h3><?php echo $textlabel ;?></h3>
        <?php echo $this->Form->create($countryPaymentGateway) ?>
        <?php  echo $this->Form->hidden('type',array('value'=>$typevalue))?>
        <?php  echo $this->Form->hidden('id')?>
        <?php  echo $this->Form->hidden('country_id',array('value'=>$cID))?>
        <div class="row">
            <div class="col-lg-8">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="form-group" style="margin: 0px;">
                             <label><?php echo __('Select Payment Gateway');?><sup class="MandatoryFields">*</sup></label>
                        </div>
                        <div class="form-group" style="margin: 0px;">
                            <div class="col-md-6">
                                <?php
                                echo $this->Form->input('is_paypal', array(
                                    'label' => 'Paypal',
                                    'type'  => 'checkbox',
                                    'id'    => 'paypal',
                                    'class' => 'form-control-checkbox',
                                    'div'=>true,
                                    'required'    => false  
                                  ));
                                ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-6">
                            <?php
                            echo $this->Form->input('is_commonwealth', array(
                                'label' =>'Commonwealth',
                                'type'  => 'checkbox',
                                'id'    => 'commonwealth',
                                'class'       => 'form-control-checkbox',
                                'div'=>true,
                                'required'    => false  
                              ));
                            ?>
                            </div>
                        </div>    
                    </div>
                </div>
                <div style="clear:both;"></div>

                <?php 
                    $class = 'hideclass';
                    if(isset($countryPaymentGateway['is_paypal']) && $countryPaymentGateway['is_paypal'] == 1){
                        $class = '' ;
                     }
                ?>
                <div id="paypal_div" class="<?php echo $class?>">
                    <div class="form-group">
                        <label><?php echo __('Paypal User Name');?><sup class="MandatoryFields">*</sup></label>
                        <?php
                            echo $this->Form->input('paypal_username', array(
                                'type'        => 'text',
                                'class'       => 'form-control',
                                "placeholder" => __("Paypal User Name"),
                                'div'         => false,
                                'label'       => false,
                                'required'    => false  
                            ));
                            ?>
                    </div>
                    <div class="form-group">
                        <label><?php echo __('Paypal Password');?><sup class="MandatoryFields">*</sup></label>
                        <?php
                            echo $this->Form->input('paypal_password', array(
                                'type'        => 'text',
                                'class'       => 'form-control',
                                "placeholder" => __("Paypal User Password"),
                                'div'         => false,
                                'label'       => false,
                                'required'    => false  
                            ));
                            ?>
                    </div>
                    <div class="form-group">
                        <label><?php echo __('Paypal Signature');?><sup class="MandatoryFields">*</sup></label>
                        <?php
                            echo $this->Form->input('paypal_signature', array(
                                'type'        => 'text',
                                'class'       => 'form-control',
                                "placeholder" => __("Paypal Signature"),
                                'div'         => false,
                                'label'       => false,
                                'required'    => false  
                            ));
                            ?>
                    </div>
                </div>
                <?php 
                    $class = 'hideclass';
                    if(isset($countryPaymentGateway['is_commonwealth']) && $countryPaymentGateway['is_commonwealth'] == 1){
                        $class = '' ;
                     }
                ?>
                <div id="common_web" class="<?php echo $class?>">
                    <div class="form-group">
                        <label><?php echo __('Commonwealth Username');?><sup class="MandatoryFields">*</sup></label>
                        <?php
                            echo $this->Form->input('commonwealth_username', array(
                                'type'        => 'text',
                                'class'       => 'form-control',
                                "placeholder" => __("Commonwealth Username"),
                                'div'         => false,
                                'label'       => false,
                                'required'    => false  
                            ));
                            ?>
                    </div>
                    <div class="form-group">
                        <label><?php echo __('Commonwealth Password');?><sup class="MandatoryFields">*</sup></label>
                        <?php
                            echo $this->Form->input('commonwealth_password', array(
                                'type'        => 'text',
                                'class'       => 'form-control',
                                "placeholder" => __("Commonwealth Password"),
                                'div'         => false,
                                'label'       => false,
                                'required'    => false  
                            ));
                            ?>
                    </div>
                    <div class="form-group">
                        <label><?php echo __('Commonwealth Signature');?><sup class="MandatoryFields">*</sup></label>
                        <?php
                            echo $this->Form->input('commonwealth_signature', array(
                                'type'        => 'text',
                                'class'       => 'form-control',
                                "placeholder" => __("Commonwealth Signature"),
                                'div'         => false,
                                'label'       => false,
                                'required'    => false  
                            ));
                            ?>
                    </div>
                </div>
                 <div class="form-group">
                    <label><?php echo __('Transaction Percentage');?><sup class="MandatoryFields">*</sup></label>
                    <?php
                    echo $this->Form->input('transaction_percentage', array(
                        'type' => 'text',
                        'class' => 'form-control',
                        "placeholder" => __('Transaction Percentage'),
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
                <?php $url = (['controller' => 'countries','action' => 'index']);?>
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
        <?php echo $this->Form->end(); ?>    
    </div>
</div>

<script type="text/javascript">
     $('.form-control-checkbox').change(function() {
            if(this.id == 'commonwealth'){
                if($(this).is(":checked")) {
                    $('#common_web').show();    
                }else{
                   $('#common_web').hide();     
                }
            }else{
                if($(this).is(":checked")) {
                    $('#paypal_div').show();    
                }else{
                   $('#paypal_div').hide();     
                }
            }
    });
</script>