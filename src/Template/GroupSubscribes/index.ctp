<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Group Subscribe'), ['action' => 'add']) ?></li>
    </ul>
</nav>
<div class="groupSubscribes index large-9 medium-8 columns content">
    <h3><?= __('Group Subscribes') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('gs_id') ?></th>
                <th><?= $this->Paginator->sort('gs_g_id') ?></th>
                <th><?= $this->Paginator->sort('gs_u_id') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($groupSubscribes as $groupSubscribe): ?>
            <tr>
                <td><?= $this->Number->format($groupSubscribe->gs_id) ?></td>
                <td><?= $this->Number->format($groupSubscribe->gs_g_id) ?></td>
                <td><?= $this->Number->format($groupSubscribe->gs_u_id) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $groupSubscribe->gs_id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $groupSubscribe->gs_id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $groupSubscribe->gs_id], ['confirm' => __('Are you sure you want to delete # {0}?', $groupSubscribe->gs_id)]) ?>
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
