<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $extraItem->ei_id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $extraItem->ei_id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Extra Items'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List My Cuisines'), ['controller' => 'MyCuisines', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New My Cuisine'), ['controller' => 'MyCuisines', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="extraItems form large-9 medium-8 columns content">
    <?= $this->Form->create($extraItem) ?>
    <fieldset>
        <legend><?= __('Edit Extra Item') ?></legend>
        <?php
            echo $this->Form->input('ei_name');
            echo $this->Form->input('ei_mc_id', ['options' => $myCuisines]);
            echo $this->Form->input('ei_u_id', ['options' => $users]);
            echo $this->Form->input('ei_status');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
