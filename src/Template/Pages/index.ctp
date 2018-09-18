<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Page'), ['action' => 'add']) ?></li>
    </ul>
</nav>
<div class="pages index large-9 medium-8 columns content">
    <h3><?= __('Pages') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('p_id') ?></th>
                <th><?= $this->Paginator->sort('p_title') ?></th>
                <th><?= $this->Paginator->sort('p_showing_order') ?></th>
                <th><?= $this->Paginator->sort('p_status') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($pages as $page): ?>
            <tr>
                <td><?= $this->Number->format($page->p_id) ?></td>
                <td><?= h($page->p_title) ?></td>
                <td><?= $this->Number->format($page->p_showing_order) ?></td>
                <td><?= $this->Number->format($page->p_status) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $page->p_id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $page->p_id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $page->p_id], ['confirm' => __('Are you sure you want to delete # {0}?', $page->p_id)]) ?>
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
