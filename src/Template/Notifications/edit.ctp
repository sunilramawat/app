<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $notification->n_id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $notification->n_id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Notifications'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="notifications form large-9 medium-8 columns content">
    <?= $this->Form->create($notification) ?>
    <fieldset>
        <legend><?= __('Edit Notification') ?></legend>
        <?php
            echo $this->Form->input('n_u_id', ['options' => $users]);
            echo $this->Form->input('n_que');
            echo $this->Form->input('n_ans');
            echo $this->Form->input('n_date');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
