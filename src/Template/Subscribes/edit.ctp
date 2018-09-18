<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $subscribe->s_id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $subscribe->s_id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Subscribes'), ['action' => 'index']) ?></li>
    </ul>
</nav>
<div class="subscribes form large-9 medium-8 columns content">
    <?= $this->Form->create($subscribe) ?>
    <fieldset>
        <legend><?= __('Edit Subscribe') ?></legend>
        <?php
            echo $this->Form->input('s_type');
            echo $this->Form->input('s_name');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
