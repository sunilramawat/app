<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Group'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="groups index large-9 medium-8 columns content">
    <h3><?= __('Groups') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('g_id') ?></th>
                <th><?= $this->Paginator->sort('g_name') ?></th>
                <th><?= $this->Paginator->sort('g_u_id') ?></th>
                <th><?= $this->Paginator->sort('g_image') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($groups as $group): ?>
            <tr>
                <td><?= $this->Number->format($group->g_id) ?></td>
                <td><?= h($group->g_name) ?></td>
                <td><?= $group->has('user') ? $this->Html->link($group->user->id, ['controller' => 'Users', 'action' => 'view', $group->user->id]) : '' ?></td>
                <td><?= h($group->g_image) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $group->g_id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $group->g_id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $group->g_id], ['confirm' => __('Are you sure you want to delete # {0}?', $group->g_id)]) ?>
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
