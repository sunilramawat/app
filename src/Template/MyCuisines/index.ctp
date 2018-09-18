<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New My Cuisine'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="myCuisines index large-9 medium-8 columns content">
    <h3><?= __('My Cuisines') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('mc_id') ?></th>
                <th><?= $this->Paginator->sort('mc_name') ?></th>
                <th><?= $this->Paginator->sort('mc_photo') ?></th>
                <th><?= $this->Paginator->sort('timming') ?></th>
                <th><?= $this->Paginator->sort('calories') ?></th>
                <th><?= $this->Paginator->sort('u_id') ?></th>
                <th><?= $this->Paginator->sort('added_date') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($myCuisines as $myCuisine): ?>
            <tr>
                <td><?= $this->Number->format($myCuisine->mc_id) ?></td>
                <td><?= h($myCuisine->mc_name) ?></td>
                <td><?= h($myCuisine->mc_photo) ?></td>
                <td><?= h($myCuisine->timming) ?></td>
                <td><?= h($myCuisine->calories) ?></td>
                <td><?= $myCuisine->has('user') ? $this->Html->link($myCuisine->user->id, ['controller' => 'Users', 'action' => 'view', $myCuisine->user->id]) : '' ?></td>
                <td><?= h($myCuisine->added_date) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $myCuisine->mc_id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $myCuisine->mc_id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $myCuisine->mc_id], ['confirm' => __('Are you sure you want to delete # {0}?', $myCuisine->mc_id)]) ?>
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
