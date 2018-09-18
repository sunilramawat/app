
<div class="users form large-9 medium-8 columns content">
    <?= $this->Form->create() ?>
  
        <?php
            echo $this->Form->input('k_query',array('id'=>'k_query'));
            
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
