<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Review'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List My Cuisines'), ['controller' => 'MyCuisines', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New My Cuisine'), ['controller' => 'MyCuisines', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="reviews index large-9 medium-8 columns content">
    <h3><?= __('Reviews') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('r_id') ?></th>
                <th><?= $this->Paginator->sort('r_by') ?></th>
                <th><?= $this->Paginator->sort('r_to') ?></th>
                <th><?= $this->Paginator->sort('r_ratting') ?></th>
                <th><?= $this->Paginator->sort('r_mc_id') ?></th>
                <th><?= $this->Paginator->sort('added_date') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($reviews as $review): ?>
            <tr>
                <td><?= $this->Number->format($review->r_id) ?></td>
                <td><?= $this->Number->format($review->r_by) ?></td>
                <td><?= $this->Number->format($review->r_to) ?></td>
                <td><?= h($review->r_ratting) ?></td>
                <td><?= $review->has('my_cuisine') ? $this->Html->link($review->my_cuisine->mc_id, ['controller' => 'MyCuisines', 'action' => 'view', $review->my_cuisine->mc_id]) : '' ?></td>
                <td><?= h($review->added_date) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $review->r_id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $review->r_id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $review->r_id], ['confirm' => __('Are you sure you want to delete # {0}?', $review->r_id)]) ?>
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
