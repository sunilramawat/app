<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Vote'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Posts'), ['controller' => 'Posts', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Post'), ['controller' => 'Posts', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="votes index large-9 medium-8 columns content">
    <h3><?= __('Votes') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('v_id') ?></th>
                <th><?= $this->Paginator->sort('v_p_id') ?></th>
                <th><?= $this->Paginator->sort('v_u_id') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($votes as $vote): ?>
            <tr>
                <td><?= $this->Number->format($vote->v_id) ?></td>
                <td><?= $vote->has('post') ? $this->Html->link($vote->post->p_id, ['controller' => 'Posts', 'action' => 'view', $vote->post->p_id]) : '' ?></td>
                <td><?= $vote->has('user') ? $this->Html->link($vote->user->id, ['controller' => 'Users', 'action' => 'view', $vote->user->id]) : '' ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $vote->v_id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $vote->v_id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $vote->v_id], ['confirm' => __('Are you sure you want to delete # {0}?', $vote->v_id)]) ?>
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
