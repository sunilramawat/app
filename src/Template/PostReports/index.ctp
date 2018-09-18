<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Post Report'), ['action' => 'add']) ?></li>
    </ul>
</nav>
<div class="postReports index large-9 medium-8 columns content">
    <h3><?= __('Post Reports') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('pr_id') ?></th>
                <th><?= $this->Paginator->sort('p_id') ?></th>
                <th><?= $this->Paginator->sort('u_id') ?></th>
                <th><?= $this->Paginator->sort('status') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($postReports as $postReport): ?>
            <tr>
                <td><?= $this->Number->format($postReport->pr_id) ?></td>
                <td><?= $this->Number->format($postReport->p_id) ?></td>
                <td><?= $this->Number->format($postReport->u_id) ?></td>
                <td><?= $this->Number->format($postReport->status) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $postReport->pr_id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $postReport->pr_id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $postReport->pr_id], ['confirm' => __('Are you sure you want to delete # {0}?', $postReport->pr_id)]) ?>
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
