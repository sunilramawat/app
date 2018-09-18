<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Post Comment'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Posts'), ['controller' => 'Posts', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Post'), ['controller' => 'Posts', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="postComments index large-9 medium-8 columns content">
    <h3><?= __('Post Comments') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('pc_id') ?></th>
                <th><?= $this->Paginator->sort('pc_p_id') ?></th>
                <th><?= $this->Paginator->sort('pc_u_id') ?></th>
                <th><?= $this->Paginator->sort('added_date') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($postComments as $postComment): ?>
            <tr>
                <td><?= $this->Number->format($postComment->pc_id) ?></td>
                <td><?= $postComment->has('post') ? $this->Html->link($postComment->post->p_id, ['controller' => 'Posts', 'action' => 'view', $postComment->post->p_id]) : '' ?></td>
                <td><?= $postComment->has('user') ? $this->Html->link($postComment->user->id, ['controller' => 'Users', 'action' => 'view', $postComment->user->id]) : '' ?></td>
                <td><?= h($postComment->added_date) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $postComment->pc_id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $postComment->pc_id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $postComment->pc_id], ['confirm' => __('Are you sure you want to delete # {0}?', $postComment->pc_id)]) ?>
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
