<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Notification'), ['action' => 'edit', $notification->n_id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Notification'), ['action' => 'delete', $notification->n_id], ['confirm' => __('Are you sure you want to delete # {0}?', $notification->n_id)]) ?> </li>
        <li><?= $this->Html->link(__('List Notifications'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Notification'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="notifications view large-9 medium-8 columns content">
    <h3><?= h($notification->n_id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('User') ?></th>
            <td><?= $notification->has('user') ? $this->Html->link($notification->user->id, ['controller' => 'Users', 'action' => 'view', $notification->user->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('N Id') ?></th>
            <td><?= $this->Number->format($notification->n_id) ?></td>
        </tr>
        <tr>
            <th><?= __('N Date') ?></th>
            <td><?= h($notification->n_date) ?></td>
        </tr>
    </table>
    <div class="row">
        <h4><?= __('N Que') ?></h4>
        <?= $this->Text->autoParagraph(h($notification->n_que)); ?>
    </div>
    <div class="row">
        <h4><?= __('N Ans') ?></h4>
        <?= $this->Text->autoParagraph(h($notification->n_ans)); ?>
    </div>
</div>
