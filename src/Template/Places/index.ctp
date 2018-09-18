<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Place'), ['action' => 'add']) ?></li>
    </ul>
</nav>
<div class="places index large-9 medium-8 columns content">
    <h3><?= __('Places') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('p_id') ?></th>
                <th><?= $this->Paginator->sort('p_name') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($places as $place): ?>
            <tr>
                <td><?= $this->Number->format($place->p_id) ?></td>
                <td><?= h($place->p_name) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $place->p_id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $place->p_id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $place->p_id], ['confirm' => __('Are you sure you want to delete # {0}?', $place->p_id)]) ?>
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
