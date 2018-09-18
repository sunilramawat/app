<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Subscribe'), ['action' => 'add']) ?></li>
    </ul>
</nav>
<div class="subscribes index large-9 medium-8 columns content">
    <h3><?= __('Subscribes') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('s_id') ?></th>
                <th><?= $this->Paginator->sort('s_type') ?></th>
                <th><?= $this->Paginator->sort('s_name') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($subscribes as $subscribe): ?>
            <tr>
                <td><?= $this->Number->format($subscribe->s_id) ?></td>
                <td><?= $this->Number->format($subscribe->s_type) ?></td>
                <td><?= h($subscribe->s_name) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $subscribe->s_id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $subscribe->s_id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $subscribe->s_id], ['confirm' => __('Are you sure you want to delete # {0}?', $subscribe->s_id)]) ?>
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
