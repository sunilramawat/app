<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $review->r_id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $review->r_id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Reviews'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List My Cuisines'), ['controller' => 'MyCuisines', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New My Cuisine'), ['controller' => 'MyCuisines', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="reviews form large-9 medium-8 columns content">
    <?= $this->Form->create($review) ?>
    <fieldset>
        <legend><?= __('Edit Review') ?></legend>
        <?php
            echo $this->Form->input('r_by');
            echo $this->Form->input('r_to');
            echo $this->Form->input('r_ratting');
            echo $this->Form->input('r_mc_id', ['options' => $myCuisines]);
            echo $this->Form->input('r_comment');
            echo $this->Form->input('added_date');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
