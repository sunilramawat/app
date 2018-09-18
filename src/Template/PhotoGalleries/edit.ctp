<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $photoGallery->pg_id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $photoGallery->pg_id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Photo Galleries'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List My Cuisines'), ['controller' => 'MyCuisines', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New My Cuisine'), ['controller' => 'MyCuisines', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="photoGalleries form large-9 medium-8 columns content">
    <?= $this->Form->create($photoGallery) ?>
    <fieldset>
        <legend><?= __('Edit Photo Gallery') ?></legend>
        <?php
            echo $this->Form->input('pg_mc_id', ['options' => $myCuisines]);
            echo $this->Form->input('ph_photo');
            echo $this->Form->input('pg_u_id', ['options' => $users]);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
