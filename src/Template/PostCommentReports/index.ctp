<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Post Comment Report'), ['action' => 'add']) ?></li>
    </ul>
</nav>
<div class="postCommentReports index large-9 medium-8 columns content">
    <h3><?= __('Post Comment Reports') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('pcr_id') ?></th>
                <th><?= $this->Paginator->sort('pc_id') ?></th>
                <th><?= $this->Paginator->sort('p_id') ?></th>
                <th><?= $this->Paginator->sort('u_id') ?></th>
                <th><?= $this->Paginator->sort('status') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($postCommentReports as $postCommentReport): ?>
            <tr>
                <td><?= $this->Number->format($postCommentReport->pcr_id) ?></td>
                <td><?= $this->Number->format($postCommentReport->pc_id) ?></td>
                <td><?= $this->Number->format($postCommentReport->p_id) ?></td>
                <td><?= $this->Number->format($postCommentReport->u_id) ?></td>
                <td><?= $this->Number->format($postCommentReport->status) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $postCommentReport->pcr_id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $postCommentReport->pcr_id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $postCommentReport->pcr_id], ['confirm' => __('Are you sure you want to delete # {0}?', $postCommentReport->pcr_id)]) ?>
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
