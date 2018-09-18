<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List My Cuisines'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="myCuisines form large-9 medium-8 columns content">
    <?= $this->Form->create($myCuisine) ?>
    <fieldset>
        <legend><?= __('Add My Cuisine') ?></legend>
        <?php
            echo $this->Form->input('mc_name');
            echo $this->Form->input('mc_photo');
            echo $this->Form->input('timming');
            echo $this->Form->input('calories');
            echo $this->Form->input('description');
            echo $this->Form->input('ingredients');
            echo $this->Form->input('u_id', ['options' => $users]);
            echo $this->Form->input('added_date');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
