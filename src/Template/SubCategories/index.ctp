<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Sub Category'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Categories'), ['controller' => 'Categories', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Category'), ['controller' => 'Categories', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="subCategories index large-9 medium-8 columns content">
    <h3><?= __('Sub Categories') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('sc_id') ?></th>
                <th><?= $this->Paginator->sort('sc_name') ?></th>
                <th><?= $this->Paginator->sort('sc_c_id') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($subCategories as $subCategory): ?>
            <tr>
                <td><?= $this->Number->format($subCategory->sc_id) ?></td>
                <td><?= h($subCategory->sc_name) ?></td>
                <td><?= $subCategory->has('category') ? $this->Html->link($subCategory->category->c_id, ['controller' => 'Categories', 'action' => 'view', $subCategory->category->c_id]) : '' ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $subCategory->sc_id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $subCategory->sc_id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $subCategory->sc_id], ['confirm' => __('Are you sure you want to delete # {0}?', $subCategory->sc_id)]) ?>
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
