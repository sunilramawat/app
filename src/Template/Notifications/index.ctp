<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Notification'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="notifications index large-9 medium-8 columns content">
    <h3><?= __('Notifications') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('n_id') ?></th>
                <th><?= $this->Paginator->sort('n_u_id') ?></th>
                <th><?= $this->Paginator->sort('n_date') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($notifications as $notification): ?>
            <tr>
                <td><?= $this->Number->format($notification->n_id) ?></td>
                <td><?= $notification->has('user') ? $this->Html->link($notification->user->id, ['controller' => 'Users', 'action' => 'view', $notification->user->id]) : '' ?></td>
                <td><?= h($notification->n_date) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $notification->n_id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $notification->n_id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $notification->n_id], ['confirm' => __('Are you sure you want to delete # {0}?', $notification->n_id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
        </ul>
        <p><?= $this->Paginator->counter() ?></p>
    </div>
</div>
