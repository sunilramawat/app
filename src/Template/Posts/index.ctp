<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Post'), ['action' => 'add']) ?></li>
    </ul>
</nav>
<div class="posts index large-9 medium-8 columns content">
    <h3><?= __('Posts') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('p_id') ?></th>
                <th><?= $this->Paginator->sort('photo') ?></th>
                <th><?= $this->Paginator->sort('description') ?></th>
                <th><?= $this->Paginator->sort('type') ?></th>
                <th><?= $this->Paginator->sort('lat') ?></th>
                <th><?= $this->Paginator->sort('lng') ?></th>
                <th><?= $this->Paginator->sort('added_date') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($posts as $post): ?>
            <tr>
                <td><?= $this->Number->format($post->p_id) ?></td>
                <td><?= h($post->photo) ?></td>
                <td><?= h($post->description) ?></td>
                <td><?= $this->Number->format($post->type) ?></td>
                <td><?= h($post->lat) ?></td>
                <td><?= h($post->lng) ?></td>
                <td><?= h($post->added_date) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $post->p_id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $post->p_id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $post->p_id], ['confirm' => __('Are you sure you want to delete # {0}?', $post->p_id)]) ?>
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
