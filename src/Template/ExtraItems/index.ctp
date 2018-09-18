<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Extra Item'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List My Cuisines'), ['controller' => 'MyCuisines', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New My Cuisine'), ['controller' => 'MyCuisines', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="extraItems index large-9 medium-8 columns content">
    <h3><?= __('Extra Items') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('ei_id') ?></th>
                <th><?= $this->Paginator->sort('ei_name') ?></th>
                <th><?= $this->Paginator->sort('ei_mc_id') ?></th>
                <th><?= $this->Paginator->sort('ei_u_id') ?></th>
                <th><?= $this->Paginator->sort('ei_status') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($extraItems as $extraItem): ?>
            <tr>
                <td><?= $this->Number->format($extraItem->ei_id) ?></td>
                <td><?= h($extraItem->ei_name) ?></td>
                <td><?= $extraItem->has('my_cuisine') ? $this->Html->link($extraItem->my_cuisine->mc_id, ['controller' => 'MyCuisines', 'action' => 'view', $extraItem->my_cuisine->mc_id]) : '' ?></td>
                <td><?= $extraItem->has('user') ? $this->Html->link($extraItem->user->id, ['controller' => 'Users', 'action' => 'view', $extraItem->user->id]) : '' ?></td>
                <td><?= $this->Number->format($extraItem->ei_status) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $extraItem->ei_id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $extraItem->ei_id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $extraItem->ei_id], ['confirm' => __('Are you sure you want to delete # {0}?', $extraItem->ei_id)]) ?>
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
