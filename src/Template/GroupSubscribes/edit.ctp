<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $groupSubscribe->gs_id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $groupSubscribe->gs_id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Group Subscribes'), ['action' => 'index']) ?></li>
    </ul>
</nav>
<div class="groupSubscribes form large-9 medium-8 columns content">
    <?= $this->Form->create($groupSubscribe) ?>
    <fieldset>
        <legend><?= __('Edit Group Subscribe') ?></legend>
        <?php
            echo $this->Form->input('gs_g_id');
            echo $this->Form->input('gs_u_id');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
